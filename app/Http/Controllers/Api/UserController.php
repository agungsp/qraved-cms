<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QravedUser;
use App\Helpers\StdResponseHelper;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function get($id)
    {
        try {

            $user = QravedUser::where('qraved_user_mapping_id', $id)->first() ;
            return StdResponseHelper::make(!empty($user),
                                           empty($user) ? 'User not found.' : '',
                                           compact('user'));

        } catch (\Exception $e) {

            return StdResponseHelper::make(false, $e->getMessage());

        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'qraved_id' => ['required'],
            'email' => ['required', 'email'],
            'contact' => ['required'],
            'gender' => ['required', 'max:1'],
            'birth_date' => ['required', 'date'],
            'interest' => ['required'],
            'job' => ['required']
        ]);

        if ($validator->stopOnFirstFailure()->fails()) {
            return StdResponseHelper::make(false, $validator->messages()->first());
        }

        if (!empty($user = QravedUser::where('qraved_user_mapping_id', $request->qraved_id)->first())) {
            return StdResponseHelper::make(false, 'The qraved id has already been taken.', compact('user'));
        }

        try {

            $user = QravedUser::create([
                'qraved_user_mapping_id' => $request->qraved_id,
                'email' => $request->email,
                'contact' => $request->contact,
                'gender' => strtoupper($request->gender),
                'birth_date' => $request->birth_date,
                'interest' => $request->interest,
                'job' => $request->job,
            ]);

            return StdResponseHelper::make(true, '', compact('user'));

        } catch (\Exception $e) {

            return StdResponseHelper::make(false, $e->getMessage());

        }
    }
}
