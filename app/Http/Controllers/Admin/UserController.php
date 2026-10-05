<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('adminUser')
            ->latest()
            ->paginate(15);

        return view(
            'admin.users.index',
            compact('users')
        );
    }

    public function makeAdmin(User $user)
    {
        AdminUser::updateOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'role' => 'admin',
            ]
        );

        return back()->with(
            'success',
            'کاربر با موفقیت به مدیر تبدیل شد.'
        );
    }

    public function removeAdmin(User $user)
    {
        if ((int) $user->id === (int) Auth::id()) {
            return back()->with(
                'error',
                'نمی‌توانید نقش مدیر حساب فعلی خودتان را حذف کنید.'
            );
        }

        AdminUser::where(
            'user_id',
            $user->id
        )->delete();

        return back()->with(
            'success',
            'نقش مدیر از کاربر حذف شد.'
        );
    }
}
