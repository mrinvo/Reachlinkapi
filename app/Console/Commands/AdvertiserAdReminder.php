<?php

namespace App\Console\Commands;

use App\Mail\AdReminder;
use App\Models\Ad;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class AdvertiserAdReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'reminder email for advertisers who have ad the next day';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $AdsInitiatedTommorow = Ad::AdsInitiatedTommorow();
        foreach($AdsInitiatedTommorow as $Ad){

            $advertiser = collect($Ad->advertiser()->first());
            $Advertiser_Email = $advertiser->pluck('email');

            Mail::to($Advertiser_Email)->send(new AdReminder($Ad));
        }
        return Command::SUCCESS;
    }
}
