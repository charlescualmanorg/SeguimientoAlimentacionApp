<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\PostController;

class SendDailyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    /**
     * The console command description.
     *
     * @var string
     */

    /**
     * Execute the console command.
     *
     * @return int
     */
    protected $signature = 'report:daily';
    protected $description = 'Envía el informe diario a los usuarios';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Ejecutar la lógica para enviar correos
        $postController = new PostController();
        $postController->sendDailyReport();

        $this->info('Se enviaron los informes diarios.');
        \Log::info('Se enviaron los informes diarios.');
    }
}

