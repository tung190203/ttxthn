<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Spatie\Activitylog\Models\Activity;
use ZipArchive;
use Carbon\Carbon;

class LogExportService
{
    /**
     * Export logs to a password-protected zip file.
     *
     * @param int|null $months Number of months to export (null for all)
     * @param bool $olderThan If true, export logs older than $months. If false, export logs within the last $months.
     * @param string|null $password
     * @return array|null
     */
    public function exportToZip($months = 3, $olderThan = true, $password = null)
    {
        $query = Activity::with('causer');
        
        if ($months != 0 && $months !== null) {
            $cutoffDate = now()->subMonths($months);
            if ($olderThan) {
                $query->where('created_at', '<', $cutoffDate);
            } else {
                $query->where('created_at', '>=', $cutoffDate);
            }
        }

        $dateStr = now()->format('Y_m_d_His');
        $fileName = "logs_export_{$dateStr}";
        $csvFileName = "{$fileName}.csv";
        $zipFileName = "{$fileName}.zip";
        
        $password = $password ?: env('LOG_ROTATE_PASSWORD', 'Log@2025');

        $storagePath = storage_path('app/logs_archive');
        if (!File::exists($storagePath)) {
            File::makeDirectory($storagePath, 0755, true);
        }

        $csvPath = $storagePath . '/' . $csvFileName;
        $zipPath = $storagePath . '/' . $zipFileName;

        $file = fopen($csvPath, 'w');
        // BOM for UTF-8 Excel support
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($file, ['Thời gian', 'Người thực hiện', 'Hành động', 'Đối tượng', 'Chi tiết thay đổi']);

        $count = 0;
        $query->chunk(1000, function ($logs) use ($file, &$count) {
            foreach ($logs as $log) {
                $performer = $log->causer ? $log->causer->name : 'System';
                $action = ucfirst($log->description);
                $subject = $log->subject_type ? class_basename($log->subject_type) . " (ID: {$log->subject_id})" : '-';
                
                $details = '';
                if ($log->properties && count($log->properties) > 0) {
                    $attributes = $log->properties['attributes'] ?? null;
                    $old = $log->properties['old'] ?? null;
                    
                    if ($attributes) {
                        foreach ($attributes as $key => $value) {
                            if ($key == 'updated_at' || $key == 'created_at') continue;
                            $oldVal = isset($old[$key]) ? (is_array($old[$key]) ? json_encode($old[$key]) : $old[$key]) : '';
                            $newVal = is_array($value) ? json_encode($value) : $value;
                            $details .= "[$key]: " . ($oldVal ? "$oldVal -> " : "") . "$newVal; ";
                        }
                    } else {
                        $otherProps = collect($log->properties)->except(['attributes', 'old']);
                        foreach ($otherProps as $key => $value) {
                            $val = is_array($value) ? json_encode($value) : $value;
                            $details .= "[$key]: $val; ";
                        }
                    }
                }

                fputcsv($file, [
                    $log->created_at->format('H:i d/m/Y'),
                    $performer,
                    $action,
                    $subject,
                    $details ?: '-'
                ]);
                $count++;
            }
        });

        fclose($file);

        if ($count === 0) {
            File::delete($csvPath);
            return null;
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $zip->addFile($csvPath, $csvFileName);
            if (method_exists($zip, 'setEncryptionName')) {
                $zip->setEncryptionName($csvFileName, ZipArchive::EM_AES_256, $password);
            }
            $zip->close();
        }

        File::delete($csvPath);

        return [
            'path' => $zipPath,
            'name' => $zipFileName,
            'count' => $count
        ];
    }
}
