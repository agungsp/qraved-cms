<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\UserLogHelper;
use App\Models\QravedUser;
use App\Helpers\StdResponseHelper;
use Illuminate\Support\Facades\Validator;

class UserLogController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user' => ['required'],
            'action' => ['required'],
        ]);

        if ($validator->stopOnFirstFailure()->fails()) {
            return StdResponseHelper::make(false, $validator->messages()->first());
        }

        try {

            $user = QravedUser::where('qraved_user_mapping_id', $request->user)->first();

            UserLogHelper::create(
                $user->id,
                $request->resto ?? 0,
                $request->action
            );

            return StdResponseHelper::make(true, '');
        } catch (\Exception $e) {
            return StdResponseHelper::make(false, $e->getMessage());
        }
    }
}
