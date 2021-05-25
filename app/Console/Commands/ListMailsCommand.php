<?php

namespace App\Console\Commands;

use App\Models\MailImport;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ListMailsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailwurf:list {--limit=20} {--skip=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will list all E-Mails';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $emails = MailImport::orderBy('created_at', 'DESC')
            ->limit($this->option('limit'))
            ->skip($this->option('skip'))
            ->get()
            ->map(fn($mail) => [
                'from' => $mail->fromShort,
                'to' => $mail->toShort,
                'date' => $mail->date,
                'subject' => Str::substr($mail->subject, 0, 40),
            ])->sortByDesc(function ($item) {
                return $item['date'];
            });

        $this->table([], $emails);

        return 0;
    }
}
