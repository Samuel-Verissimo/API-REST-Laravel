<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Services\OpenFoodService;
use App\Models\Product;
use Carbon\Carbon;

class OpenFoodCron extends Command
{
    protected $signature = 'cron:openfood';
    protected $description = 'Chamar o serviço responsável por importar dados do Open Food Facts';
    protected $openFoodService;

    public function __construct(OpenFoodService $openFoodService)
    {
        parent::__construct();
        $this->openFoodService = $openFoodService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::channel('cron')->info(message: 'Cron iniciado às ' . now());
        cache()->put('cron_last_run', now()->toDateTimeString());

        $files = $this->openFoodService->fetchIndexFile();

        foreach($files as $file) {
            $products = $this->openFoodService->fetchFile(fileUrl: $file);

            foreach($products as $product) {
                $product["status"] = "published";
                $product["imported_t"] = Carbon::now();

                Product::updateOrCreate(["code" => $product["code"]], $product);
            }
        }

        Log::channel('cron')->info(message: 'Cron finalizado com sucesso às ' . now());
    }
}
