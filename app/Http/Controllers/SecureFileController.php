<?php

namespace App\Http\Controllers;

use App\Models\OfficialMail;
use App\Models\PatientDocument;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Serves uploaded files from the private disk so they are only reachable by
 * signed-in users. Files uploaded before the move to the private disk are
 * still read from the public disk as a fallback.
 */
class SecureFileController extends Controller
{
    /** Types that are safe to render inline in the browser. */
    private const INLINE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf'];

    public function viewDocument(PatientDocument $document): StreamedResponse
    {
        return $this->serve($document->file_path, $document->title, inline: true);
    }

    public function downloadDocument(PatientDocument $document): StreamedResponse
    {
        return $this->serve($document->file_path, $document->title, inline: false);
    }

    public function printDocument(PatientDocument $document)
    {
        $this->diskFor($document->file_path);

        return view('print.single-document', compact('document'));
    }

    public function viewMail(OfficialMail $mail): StreamedResponse
    {
        abort_unless($mail->file_path, 404);

        return $this->serve($mail->file_path, 'mail_'.$mail->mail_number, inline: true);
    }

    private function serve(string $path, string $name, bool $inline): StreamedResponse
    {
        $disk = $this->diskFor($path);
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $filename = $name.'.'.$extension;
        $headers = ['X-Content-Type-Options' => 'nosniff'];

        if ($inline && in_array($extension, self::INLINE_EXTENSIONS, true)) {
            return Storage::disk($disk)->response($path, $filename, $headers);
        }

        return Storage::disk($disk)->download($path, $filename, $headers);
    }

    private function diskFor(string $path): string
    {
        foreach (['local', 'public'] as $disk) {
            if (Storage::disk($disk)->exists($path)) {
                return $disk;
            }
        }

        abort(404, 'فایلی بەڵگەنامە نەدۆزرایەوە.');
    }
}
