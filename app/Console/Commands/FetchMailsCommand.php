<?php

namespace App\Console\Commands;

use App\Jobs\FetchMailJob;
use Illuminate\Console\Command;

class FetchMailsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailwurf:fetch {--M|mailbox=*}';

    protected int $statusCode = 0;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will fetch all inbox mails';

    public function handle(): int
    {
        $mailboxes = collect($this->option('mailbox'));
        if (0 == $mailboxes->count()) {
            $mailboxes = collect('default');
        }

        $mailboxes->each(fn($mailbox) => $this->fetchInboxFor($mailbox));

        if (0 == $this->statusCode) {
            $this->info('Successfull');
        }

        return $this->statusCode;
    }

    private function fetchInboxFor(string $mailbox)
    {
        try {
            FetchMailJob::dispatch($mailbox, 'INBOX');
            FetchMailJob::dispatch($mailbox, 'Spam');
        } catch (\Exception $exception) {
            $this->error('Failed fetching mails for: ' . $mailbox);
            $this->statusCode = 1;
        }

        return true;
    }
}
