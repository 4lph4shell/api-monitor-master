<?php

namespace App\Commands;

use App\Channels\TelegramChannel;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use LaravelZero\Framework\Commands\Command;

class CheckEndpointsCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'check';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Check endpoints';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): void
    {
        $endpoints = DB::table('endpoints')->get();
        $output = "";
        $total = 0;
        $good = 0;
        $bad = 0;

        foreach ($endpoints as $endpoint)
        {
            // Increment the total number of endpoints
            $total++;

            try {
                // Send a GET request to the endpoint
                $status = Http::get($endpoint->url)->status();
            } catch (\Exception $exception) {
                // Set the status to 500 if there is an exception
                $status = 500;
            }

            // Check if the status is greater than 200
            if ((int)$status > 204){
                // Set the emoji to "❌" if the status is not good
                $emoji = "❌";
                // Increment the bad count
                $bad++;
            } else {
                // Set the emoji to "✅" if the status is good
                $emoji = "✅";
                // Increment the good count
                $good++;
            }

            // Build the output string
            $output .= "app: ".$endpoint->app . PHP_EOL .
                    "name: ".$endpoint->name . PHP_EOL .
                    "url: ".$endpoint->url . PHP_EOL .
                    "status: ". $status . $emoji . PHP_EOL . PHP_EOL;
        }

        // Build the final output string
        $output = "Total: " . $total . "  " . PHP_EOL .
                $good . " ✅  ". PHP_EOL .
                $bad . " ❌  ". PHP_EOL .
                PHP_EOL . $output;

        // Send the output to telegram
        TelegramChannel::send($output);
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // Run the command every minute
        // $schedule->command(static::class)->everyMinute();
    }

    /**
     * Register the command's schedule.
     */
    public function registerSchedule(): void
    {
        // Register the schedule
        $this->callAfterResolving(function (Schedule $schedule) {
            $schedule->command(static::class)->everyMinute();
        });
    }
}
