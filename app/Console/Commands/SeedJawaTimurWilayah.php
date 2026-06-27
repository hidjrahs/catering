<?php

namespace App\Console\Commands;

use Database\Seeders\JawaTimurWilayahSeeder;
use Illuminate\Console\Command;

class SeedJawaTimurWilayah extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wilayah:seed-jatim 
                            {--force : Paksa jalankan meskipun data sudah ada}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed referensi wilayah Jawa Timur (Kab/Kota, Kecamatan, Kelurahan/Desa) dari laravolt/indonesia ke tabel lokal';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Menjalankan Seeder Wilayah Jawa Timur...');
        $this->newLine();

        $seeder = new JawaTimurWilayahSeeder();
        $seeder->setCommand($this);
        $seeder->run();

        $this->newLine();
        $this->info('✓ Selesai!');

        return Command::SUCCESS;
    }
}
