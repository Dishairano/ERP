<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function showUserName($id)
    {
        $user = User::find($id);

        if ($user) {
            return view('user.show', [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
            ]);
        } else {
            return response('User not found.', 404);
        }
    }
}
