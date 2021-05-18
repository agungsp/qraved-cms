<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\QravedUser;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Exports\QravedUserExport;
use Maatwebsite\Excel\Facades\Excel;

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

    public function qravedGetUsers($lastId, Request $request)
    {
        $filter = $this->parseUrlQuery($request->fullUrl());
        $users = QravedUser::where('id', '>', $lastId)
                           ->when($filter->count() > 0, function ($query) use ($filter) {
                               return $query->where('email', 'like', '%'. $filter['search'] .'%');
                           })
                           ->limit(10)
                           ->get();
        $hasNext = QravedUser::orderBy('id', 'desc')->first()->id;
        return [
            'html' => view('includes.get-users-qraved', compact('users'))->render(),
            'lastId' => $users->last()->id,
            'hasNext' => $hasNext > $users->last()->id,
        ];
    }

    public function qravedGetUser($id)
    {
        return QravedUser::find($id);
    }

    public function qravedDelete(Request $request)
    {
        $success = true;
        $message = '';
        try {

            $user = QravedUser::find($request->id)->delete();
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

    public function exportToCsv()
    {
        return Excel::download(new QravedUserExport, 'qraved_user_' . now()->format('Ymd_His') . '.csv');
    }

    private function parseUrlQuery($url)
    {
        $query = explode('&', parse_url($url, PHP_URL_QUERY));
        $result = collect();
        foreach ($query as $item) {
            if (!empty($item)) {
                $data = explode('=', $item);
                $result->put($data[0], $data[1]);
            }
        }
        return $result;
    }
}
