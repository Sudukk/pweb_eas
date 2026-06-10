<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereIn('role', ['mahasiswa', 'dosen'])->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function unblacklist(User $user)
    {
        $user->update(['is_blacklisted' => false]);
        return back()->with('success', 'Blacklist user dihapus.');
    }
}
