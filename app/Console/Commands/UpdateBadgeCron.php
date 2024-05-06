<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateBadgeCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'badge:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    static public function handle()
    {
        $sql = "ALTER TABLE `admins` CHANGE `fcm_token` `fcm_token` TEXT NULL DEFAULT NULL;";
        $sql2 = "ALTER TABLE `users` CHANGE `fcm_token` `fcm_token` TEXT NULL DEFAULT NULL;";

    }
}
