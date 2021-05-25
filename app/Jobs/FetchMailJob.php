<?php

namespace App\Jobs;

use App\Models\MailImport;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Str;
use Webklex\IMAP\Facades\Client;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Webklex\PHPIMAP\Attribute;
use Webklex\PHPIMAP\Message;
use Webklex\PHPIMAP\Support\MessageCollection;
use Webklex\PHPIMAP\Folder;

class FetchMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $folder;
    protected string $mailbox;

    /**
     * Create a new job instance.
     */
    public function __construct(string $mailbox, string $folder)
    {
        $this->mailbox = $mailbox;
        $this->folder = $folder;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $oClient = Client::account($this->mailbox);
        $oClient->connect();

        /** @var Folder $oFolder */
        $oFolder = $oClient->getFolder($this->folder);

        //Get all messages
        /** @var MessageCollection $aMessage */
        $aMessage = $oFolder->query()->all()->get();

        $aMessage->each(fn($message) => $this->saveMessage($message));
    }

    private function saveMessage(Message $message)
    {
        $to = $this->extractMail($message->getTo());
        $cc = $this->extractMail($message->getCc());
        $bcc = $this->extractMail($message->getBcc());

        $import = new MailImport();
        $import->to = $to;
        $import->cc = $cc;
        $import->bcc = $bcc;
        $import->content = utf8_encode(serialize($message));
        $import->mail_key = Str::random(64);

        $import->save();
        $message->delete();
    }

    protected function extractMail($items)
    {
        if ($items instanceof Attribute) {
            $items = $items->all();
        }
        if (null == $items) {
            $items = [];
        }
        return implode(' ', array_map(function ($i) {
            return $i->mail;
        }, $items));
    }
}
