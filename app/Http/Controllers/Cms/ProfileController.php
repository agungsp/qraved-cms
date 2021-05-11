<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\File;


class ProfileController extends Controller
{
    public function index()
    {
        return view('cms.profile');
    }

    public function store(Request $request)
    {
        $success = true;
        $message = '';

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'email']
        ];

        if (!empty($request->password)) {
            array_push($rules['email'], Rule::unique('users')->ignore(auth()->id()));
            $rules['password'] = [
                'confirmed'
            ];
        }

        $request->validate($rules);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'updated_by' => auth()->id(),
        ];

        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        try {

            if (!empty($request->file('avatar'))) {
                $path = $request->file('avatar')->storeAs(
                    'avatars',
                    md5(now()->toDateTimeString()) . '.' . $request->file('avatar')->extension()
                );

                if (Storage::exists('avatars/' . auth()->user()->avatar_path)) {
                    Storage::delete('avatars/' . auth()->user()->avatar_path);
                }

                $data['avatar_path'] = explode('/', $path)[1];
            }

            $user = User::find(auth()->id())
                        ->update($data);
            $message = 'Your profile has been updated';

        } catch (\Exception $e) {

            $success = false;
            $message = $e->getMessage();

        }

        return [
            'success' => $success,
            'message' => $message,
        ];
    }

    public function avatar($filename)
    {
        $path = storage_path('app/avatars/'. $filename);
        if (!File::exists($path)) abort(404);
        $response = response(File::get($path), 200)
                    ->header("Content-Type", File::mimeType($path));
        return $response;
    }
}
