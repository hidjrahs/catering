<?php

namespace App\Jobs;

use App\Repository\ImportRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateRecipe implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $setData = [];
    public $timeout = 300;
    public function __construct($setData)
    {
        $this->setData = $setData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->setData) {
            $data = $this->setData;
            return $readExcel=ImportRepository::importRecipe($data['id'],$data['user']);
        }
    }
}
