<?php

namespace App\Console\Commands;

use App\Mail\EmailReminder as MailEmailReminder;
use App\Models\SessionBooking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use DateTime;


class EmailReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email-reminder:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To notify the students';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sessions = SessionBooking::select()->where('session_status','pending')->where('email_reminder',"!=",'60')->get();

        foreach ($sessions as $key => $session) {

            $diff_mins = $this->getTimeDiffer($session->date, $session->start_time);

                // 48h = 2880
            if($diff_mins <= 2880 && $session->email_reminder == 0) {

                Mail::to($session->email)->send(new MailEmailReminder($session->toArray(), 48));
                SessionBooking::where('id',$session->id)->update(['email_reminder' => 48]);

                // 24h = 1440
            } else if($diff_mins <= 1440 && $session->email_reminder == 48) {

                Mail::to($session->email)->send(new MailEmailReminder($session->toArray(), 24));
                SessionBooking::where('id',$session->id)->update(['email_reminder' => 24]);

                // 1h  = 60
            } else if($diff_mins <= 60 && $session->email_reminder == 24) {

                Mail::to($session->email)->send(new MailEmailReminder($session->toArray(), 60));
                SessionBooking::where('id',$session->id)->update(['email_reminder' => 60]);
            }
        }

        return Command::SUCCESS;
    }

    public function getTimeDiffer($date, $time)
    {
        $date = date('Y-m-d', strtotime($date))." ".$time;

        $start_date = new DateTime();
        $end_date = new DateTime($date);
        return round(abs($end_date->getTimestamp() - $start_date->getTimestamp()) / 60);
    }
}
