<?php

namespace App\Console\Commands;

use App\Models\MailImport;
use Illuminate\Console\Command;

class ClearMailsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailwurf:clear {--days=7}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will clear all old E-Mails';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $days = (int)$this->option('days');
        if ($days < config('mailwurf.keep_mails.minimum')
            || $days > config('mailwurf.keep_mails.maximum')) {
            $days = config('mailwurf.keep_mails.default');
            $this->warn('Switched to default days: ' . $days);
        }

        MailImport::where('created_at', '<=', now()->subDays($days))->delete();
        $this->info('Successfull');

        return 0;
    }
}
