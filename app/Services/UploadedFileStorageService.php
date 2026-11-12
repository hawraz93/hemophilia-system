<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Stores uploaded documents and optimizes raster images (resize + compress)
 * via GD so high-resolution phone/scanner photos do not bloat server storage.
 */
class UploadedFileStorageService
{
    private const MAX_DIMENSION = 1600;

    private const JPEG_QUALITY = 82;

    private const WEBP_QUALITY = 82;

    private const PNG_COMPRESSION = 9;

    private const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];

    /**
     * Store an uploaded file on the target disk, optimizing raster images when smaller.
     *
     * @return array{path: string, size: int, mime_type: string}
     */
    public function storeOptimized(UploadedFile $file, string $directory, string $disk = 'local'): array
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: '');
        $originalSize = (int) ($file->getSize() ?: 0);
        $mimeType = $file->getMimeType() ?: 'application/octet-stream';

        if (in_array($extension, self::IMAGE_EXTENSIONS, true) && extension_loaded('gd')) {
            $optimized = $this->optimizeToTempFile($file->getRealPath(), $extension);

            if ($optimized !== null) {
                try {
                    $optimizedSize = filesize($optimized);
                    if ($optimizedSize !== false && ($originalSize === 0 || $optimizedSize < $originalSize)) {
                        $filename = Str::random(40).'.'.$extension;
                        $path = trim($directory, '/').'/'.$filename;

                        $stream = fopen($optimized, 'rb');
                        if ($stream !== false) {
                            Storage::disk($disk)->put($path, $stream);
                            fclose($stream);

                            return [
                                'path' => $path,
                                'size' => $optimizedSize,
                                'mime_type' => $mimeType,
                            ];
                        }
                    }
                } finally {
                    @unlink($optimized);
                }
            }
        }

        $path = $file->store($directory, $disk);

        return [
            'path' => $path,
            'size' => $originalSize,
            'mime_type' => $mimeType,
        ];
    }

    /**
     * Optimize an already-stored image in place if doing so reduces its byte size.
     *
     * @return int|null New byte size if replaced, or null if skipped / not smaller.
     */
    public function optimizeStoredImage(string $path, string $disk = 'local', bool $dryRun = false): ?int
    {
        if (! extension_loaded('gd') || ! Storage::disk($disk)->exists($path)) {
            return null;
        }

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (! in_array($extension, self::IMAGE_EXTENSIONS, true)) {
            return null;
        }

        $fullPath = Storage::disk($disk)->path($path);
        $originalSize = @filesize($fullPath);
        if ($originalSize === false || $originalSize === 0) {
            return null;
        }

        $optimized = $this->optimizeToTempFile($fullPath, $extension);
        if ($optimized === null) {
            return null;
        }

        try {
            $optimizedSize = filesize($optimized);
            if ($optimizedSize === false || $optimizedSize >= $originalSize) {
                return null;
            }

            if (! $dryRun) {
                $stream = fopen($optimized, 'rb');
                if ($stream === false) {
                    return null;
                }
                Storage::disk($disk)->put($path, $stream);
                fclose($stream);
            }

            return $optimizedSize;
        } finally {
            @unlink($optimized);
        }
    }

    private function optimizeToTempFile(string $sourcePath, string $extension): ?string
    {
        $image = $this->loadImage($sourcePath, $extension);
        if ($image === null) {
            return null;
        }

        try {
            if (in_array($extension, ['jpg', 'jpeg'], true)) {
                $image = $this->fixJpegOrientation($image, $sourcePath);
            }

            $image = $this->resizeIfNeeded($image, $extension);

            $tempFile = tempnam(sys_get_temp_dir(), 'imgopt_');
            if ($tempFile === false) {
                return null;
            }

            $saved = match ($extension) {
                'jpg', 'jpeg' => @imagejpeg($image, $tempFile, self::JPEG_QUALITY),
                'webp' => @imagewebp($image, $tempFile, self::WEBP_QUALITY),
                'png' => @imagepng($image, $tempFile, self::PNG_COMPRESSION),
                default => false,
            };

            if (! $saved) {
                @unlink($tempFile);

                return null;
            }

            return $tempFile;
        } finally {
            imagedestroy($image);
        }
    }

    private function loadImage(string $path, string $extension): ?\GdImage
    {
        $image = match ($extension) {
            'jpg', 'jpeg' => @imagecreatefromjpeg($path),
            'png' => @imagecreatefrompng($path),
            'webp' => @imagecreatefromwebp($path),
            default => false,
        };

        return $image instanceof \GdImage ? $image : null;
    }

    private function fixJpegOrientation(\GdImage $image, string $path): \GdImage
    {
        if (! function_exists('exif_read_data')) {
            return $image;
        }

        $exif = @exif_read_data($path);
        $orientation = (int) ($exif['Orientation'] ?? 1);

        $rotated = match ($orientation) {
            3 => imagerotate($image, 180, 0),
            6 => imagerotate($image, -90, 0),
            8 => imagerotate($image, 90, 0),
            default => false,
        };

        if ($rotated instanceof \GdImage) {
            imagedestroy($image);

            return $rotated;
        }

        return $image;
    }

    private function resizeIfNeeded(\GdImage $image, string $extension): \GdImage
    {
        $width = imagesx($image);
        $height = imagesy($image);
        $maxSide = max($width, $height);

        if ($maxSide <= self::MAX_DIMENSION) {
            return $image;
        }

        $scale = self::MAX_DIMENSION / $maxSide;
        $newWidth = max(1, (int) round($width * $scale));
        $newHeight = max(1, (int) round($height * $scale));

        $canvas = imagecreatetruecolor($newWidth, $newHeight);
        if (! $canvas instanceof \GdImage) {
            return $image;
        }

        if (in_array($extension, ['png', 'webp'], true)) {
            imagealphablending($canvas, false);
            imagesavealpha($canvas, true);
            $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
            imagefill($canvas, 0, 0, $transparent);
        }

        imagecopyresampled($canvas, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($image);

        return $canvas;
    }
}

