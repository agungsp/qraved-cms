<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return view('cms.users_cms');
    }

    public function getUsers($lastId)
    {
        $users = User::where('id', '>', $lastId)->limit(10)->get();
        return [
            'html' => view('includes.get-users', compact('users'))->render(),
            'hasNext' => !empty($users),
        ];
    }

    public function qravedIndex()
    {
        return view('cms.users_qraved');
    }
}
