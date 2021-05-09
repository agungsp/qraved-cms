<?php

namespace App\Helpers;


use App\Models\QravedUserLog;


class UserLogHelper {

    public static function create($user_id, $resto_id, $action)
    {
        try {
            QravedUserLog::create([
                'qraved_user_id' => $user_id,
                'restaurant_id' => $resto_id,
                'action' => $action
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function createGetQuestionLog($user_id, $resto_id, $question_id, $action)
    {
        try {
            QravedUserLog::create([
                'qraved_user_id' => $user_id,
                'restaurant_id' => $resto_id,
                'question_id' => $question_id,
                'action' => $action
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function createAnswerQuestionLog($user_id, $resto_id, $question_id, $user_answer_id, $action)
    {
        try {
            QravedUserLog::create([
                'qraved_user_id' => $user_id,
                'restaurant_id' => $resto_id,
                'question_id' => $question_id,
                'user_answer_id' => $user_answer_id,
                'action' => $action
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
