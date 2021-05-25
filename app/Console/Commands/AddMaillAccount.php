<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\User;
use Illuminate\Console\Command;

class AddMaillAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailwurf:add-account
    {username : Username `key`}
    {account : The E-Mail that should be added}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user = User::where('username', $this->argument('username'))->first();

        if (null == $user) {
            $this->error('User not found');
            return 1;
        }

        $account = Account::where('mail', $this->argument('account'))->first();

        if (!null == $account) {
            $this->error('Account exists for user ' . $account->user->username);
            return 1;
        }

        $account = new Account();
        $account->mail = $this->argument('account');
        $account->user()->associate($user);
        $account->save();

        $this->info('Successfully created account');

        return 0;
    }
}
