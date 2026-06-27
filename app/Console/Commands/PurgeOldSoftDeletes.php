<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use ReflectionClass;

class PurgeOldSoftDeletes extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'purge:softdeletes';

    /**
     * The console command description.
     */
    protected $description = 'Force delete soft-deleted records older than 1 month for all models using SoftDeletes trait.';

    public function handle()
    {
        $modelPath = app_path('Models');
        $threshold = Carbon::now()->subMonth();
        $totalDeleted = 0;

        $this->info('🧹 Starting purge for soft-deleted records older than 1 month...');
        $this->newLine();
        
        foreach (File::allFiles($modelPath) as $file) {
            $namespace = 'App\\Models\\' . $file->getFilenameWithoutExtension();

            if (!class_exists($namespace)) {
                continue;
            }

            $reflection = new ReflectionClass($namespace);
            if (!in_array(SoftDeletes::class, array_keys($reflection->getTraits()))) {
                continue; // skip model yang tidak pakai SoftDeletes
            }

            $model = app($namespace);
            if (!method_exists($model, 'onlyTrashed')) {
                continue;
            }

            $count = $model::onlyTrashed()
                ->where('deleted_at', '<', $threshold)
                ->forceDelete();

            $totalDeleted += $count;

            $this->line("🗑️  {$namespace}: {$count} records permanently removed.");
        }

        $this->newLine();
        $this->info("✅ Done. Total records purged: {$totalDeleted}");
    }
}
