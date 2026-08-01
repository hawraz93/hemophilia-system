<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\StreamedResponse;

class ExcelExporter
{
    /**
     * Export data array to Native Excel (.xls) file with UTF-8 BOM encoding.
     */
    public static function export(string $filename, array $headers, array $rows): StreamedResponse
    {
        $cleanFilename = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $filename) . '_' . date('Y-m-d') . '.xls';

        return response()->streamDownload(function () use ($headers, $rows) {
            // UTF-8 BOM for Microsoft Excel compatibility
            echo "\xEF\xBB\xBF";
            echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            echo '<head><meta charset="utf-8"/><style>';
            echo 'th { background-color: #dc2626; color: #ffffff; font-weight: bold; border: 1px solid #991b1b; padding: 8px; font-size: 12px; text-align: right; }';
            echo 'td { border: 1px solid #cbd5e1; padding: 6px; font-size: 11px; text-align: right; }';
            echo 'tr:nth-child(even) { background-color: #f8fafc; }';
            echo '</style></head>';
            echo '<body dir="rtl">';
            echo '<table>';
            echo '<thead><tr>';
            foreach ($headers as $h) {
                echo '<th>' . htmlspecialchars($h, ENT_QUOTES, 'UTF-8') . '</th>';
            }
            echo '</tr></thead>';
            echo '<tbody>';
            foreach ($rows as $row) {
                echo '<tr>';
                foreach ($row as $cell) {
                    echo '<td>' . htmlspecialchars((string) $cell, ENT_QUOTES, 'UTF-8') . '</td>';
                }
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
            echo '</body></html>';
        }, $cleanFilename, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $cleanFilename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
