<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Webklex\PHPIMAP\Attribute;
use Webklex\PHPIMAP\Message;

/**
 * Class MailImport
 * @package App\Models
 * @property \Webklex\PHPIMAP\Message $message
 */
class MailImport extends Model
{
    use HasFactory;

    /**
     * @var Message
     */
    protected $message_obj;

    public function getFromDisplayAttribute()
    {
        return $this->getEmailAddressFrom('from');
    }

    public function getFromShortAttribute()
    {
        return $this->getEmailAddressFrom('from', true);
    }

    public function getTextAttribute()
    {
        if ($this->message->hasHTMLBody()) {
            return $this->message->getHTMLBody();
        }
        return $this->message->getTextBody();
    }

    public function getToDisplayAttribute()
    {
        return $this->getEmailAddressFrom('to');
    }

    public function getToShortAttribute()
    {
        return $this->getEmailAddressFrom('to', true);
    }

    public function getCcDisplayAttribute()
    {
        return $this->getEmailAddressFrom('cc');
    }

    public function getBccDisplayAttribute()
    {
        return $this->getEmailAddressFrom('bcc');
    }

    private function getEmailAddressFrom($type, bool $short = false)
    {
        $type = 'get' . ucfirst(strtolower($type));
        /** @var Attribute $mail */
        $mail = $this->message->{$type}();
        if (null != $mail && 0 == $mail->count() && isset($mail->{'0'})) {
            $mail = [$mail->{'0'}];
        } else {
            $mail = $mail ? $mail->all() : [];
        }

        return implode(', ', array_map(function ($obj) use($short) {
            return mb_decode_mimeheader($short ? $obj->mail : $obj->full);
        }, $mail));
    }

    /**
     * @return Message
     */
    public function getMessageAttribute(): Message
    {
        if (null == $this->message_obj) {
            $this->message_obj = unserialize(utf8_decode($this->content));
        }

        return $this->message_obj;
    }

    public function getSubjectAttribute()
    {
        return $this->message->getSubject()->{'0'};
    }

    public function getDateAttribute()
    {
        return $this->message->getDate()->{'0'};
    }

    public function scopeForAccounts(Builder $query, Collection $accounts, $username)
    {
        return $query->where('created_at', '>=', now()->subYear())
            ->where(function ($query) use ($accounts, $username) {
                $query->orWhere('to', 'like', '%' . Str::lower($username) . '%');
                $accounts->each(function ($account) use ($query) {
                    $query->orWhere('to', 'LIKE', "%{$account->mail}%")
                        ->orWhere('cc', 'LIKE', "%{$account->mail}%")
                        ->orWhere('bcc', 'LIKE', "%{$account->mail}%")
                        ->orWhere('content', 'LIKE', "%{$account->mail}%");
                });
            });
    }
}
