<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('cms.users_cms');
    }

    public function getUsers($lastId)
    {
        $users = User::where('id', '>', $lastId)->limit(50)->get();
        $hasNext = User::orderBy('id', 'desc')->first()->id;
        return [
            'html' => view('includes.get-users', compact('users'))->render(),
            'lastId' => $users->last()->id,
            'hasNext' => $hasNext > $users->last()->id,
        ];
    }

    public function getUser($id)
    {
        return User::find($id);
    }

    public function store(Request $request)
    {
        $success = true;
        $message = '';

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'email'],
            'password' => [Rule::requiredIf(empty($request->id)), 'confirmed'],
        ];

        if (empty($request->id)) {
            array_push($rules['email'], 'unique:users');
            array_push($rules['password'], 'string', 'min:8');
        }

        $request->validate($rules);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'updated_by' => auth()->id(),
        ];

        if (empty($request->id)) {
            $data['password'] = Hash::make($request->password);
            $data['created_by'] = auth()->id();
        }

        try {
            if (empty($request->id)) {
                $user = User::create($data);
                $message = 'New user has been created';
            }
            else {
                $user = User::find($request->id)
                            ->update($data);
                $message = 'The user has been updated';
            }

        } catch (\Exception $e) {

            $success = false;
            $message = $e->getMessage();

        }

        return [
            'success' => $success,
            'message' => $message,
        ];
    }

    public function delete(Request $request)
    {
        $success = true;
        $message = '';
        try {

            $user = User::find($request->id)->delete();
            $message = 'The user has been deleted!';

        } catch (\Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        return [
            'success' => $success,
            'message' => $message,
        ];
    }

    public function qravedIndex()
    {
        return view('cms.users_qraved');
    }
}
