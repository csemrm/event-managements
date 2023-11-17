<?php

namespace App\Console\Commands;

use App\Notifications\EventReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class SendEventRemainders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-remainders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends notifications to all Attendees that event stats soon.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $event =  \App\Models\Event::with('attendees.user')
        ->whereBetween('start_time', [now(),now()->addDay()])
        ->get();

        $eventCount = $event->count();

        $eventLabel = Str::plural('events', $eventCount);
        $this->info("Found {$eventCount} {$eventLabel}");

        $event->each(
            fn($event) => $event->attendees->each(
                fn($attendee) => $attendee->user->notify(
                    new EventReminderNotification($event)
                )
                )
            );

        $this->info('this is ack message.');
    }
}
