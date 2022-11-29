<?php

namespace App\Console\Commands;

use App\Http\Controllers\HomeController;
use Illuminate\Console\Command;

class updateRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the URLs requests';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $homeController = new HomeController();
        $homeController->updateAll();
    }
}
