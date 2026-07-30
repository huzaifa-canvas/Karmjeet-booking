<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestSmtp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {email : The email address to send test mail to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test email to verify SMTP configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $to = $this->argument('email');

        $this->info("📧 Sending test email to: {$to}");
        $this->info("📤 Using SMTP Host: " . config('mail.mailers.smtp.host'));
        $this->info("🔑 Auth Mode: " . (config('mail.mailers.smtp.auth_mode') ?? 'default'));
        $this->info("");

        try {
            Mail::raw('This is a test email from your Laravel application. If you received this, your SMTP (Gmail OAuth2) is working perfectly! 🎉', function ($message) use ($to) {
                $message->to($to)
                        ->subject('✅ SMTP Test - ' . config('app.name'));
            });

            $this->info("✅ Email sent successfully to {$to}!");
            $this->info("   Check your inbox (and spam folder).");
        } catch (\Exception $e) {
            $this->error("❌ Failed to send email!");
            $this->error("   Error: " . $e->getMessage());
            $this->newLine();
            $this->warn("Debug info:");
            $this->warn("   MAIL_HOST: " . config('mail.mailers.smtp.host'));
            $this->warn("   MAIL_PORT: " . config('mail.mailers.smtp.port'));
            $this->warn("   MAIL_USERNAME: " . config('mail.mailers.smtp.username'));
            $this->warn("   MAIL_ENCRYPTION: " . config('mail.mailers.smtp.encryption'));
            $this->warn("   AUTH_MODE: " . (config('mail.mailers.smtp.auth_mode') ?? 'not set'));
        }
    }
}
