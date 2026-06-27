<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ClearOldReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear All Reports 7 days later';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $allTempImportExport = [
            'report_order',
            'report_cost_control',
            'report_kitchen',
            'report_purchasing',
            'import_temp',
        ];
        $expiredTime = now()->subDays(7)->timestamp;
        foreach ($allTempImportExport as $directory) {
            if (!Storage::exists($directory)) continue;
            $files = Storage::allFiles($directory);
            foreach ($files as $file) {
                try {
                    if (Storage::lastModified($file) < $expiredTime) {
                        Storage::delete($file);
                    }
                } catch (\Throwable $e) {
                    Log::warning('Failed delete temp file', [
                        'file' => $file,
                        'message' => $e->getMessage()
                    ]);
                }
            }
        }
        
    }
}
