<?php

namespace App\Console\Commands;

use App\Models\AdminUser;
use App\Models\User;
use Illuminate\Console\Command;

class MakeAdmin extends Command
{
    protected $signature = 'app:make-admin {email : Email address of an existing user}';

    protected $description = 'Grant the administrator role to an existing user';

    public function handle(): int
    {
        $user = User::where('email', $this->argument('email'))->first();

        if (!$user) {
            $this->error('کاربری با این ایمیل پیدا نشد. ابتدا از سایت ثبت‌نام کنید.');

            return self::FAILURE;
        }

        AdminUser::updateOrCreate(
            ['user_id' => $user->id],
            ['role' => 'admin']
        );

        $this->info("{$user->email} با موفقیت مدیر شد.");

        return self::SUCCESS;
    }
}
