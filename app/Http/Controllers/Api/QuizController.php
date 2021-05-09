<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuestionBank;
use App\Helpers\StdResponseHelper;
use Illuminate\Support\Facades\Validator;
use App\Helpers\UserLogHelper;
use App\Models\UserAnswer;
use App\Models\QravedUser;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function get(Request $request)
    {
        \parse_str(
            \parse_url($request->fullUrl(), PHP_URL_QUERY),
            $params
        );

        if (!isset($params['user'])) {
            return StdResponseHelper::make(false, '\'user\' param is required');
        }

        if (!isset($params['resto'])) {
            return StdResponseHelper::make(false, '\'resto\' param is required');
        }

        try {

            $question = QuestionBank::inRandomOrder()->first();
            if (!empty($question->question_images)) {
                $question_images = json_decode($question->question_images);
                $images = [];
                foreach ($question_images as $image) {
                    array_push($images, url('images', $image));
                }
                $question->question_images = $images;
            }

            $answers = json_decode($question->answer);
            if ($question->answer_type == 1) {
                for ($i = 0; $i < count($answers); $i++) {
                    if (isset($answers[$i]->image)) {
                        $answers[$i]->image = url('images', $answers[$i]->image);
                    }
                }
            }
            $question->answer = $answers;

            $user = QravedUser::where('qraved_user_mapping_id', $params['user'])->first();
            UserLogHelper::createGetQuestionLog(
                $user->id,
                $params['resto'],
                $question->id,
                'Getting question'
            );

            return StdResponseHelper::make(true, '', $question);
        } catch (\Exception $e) {
            return StdResponseHelper::make(false, $e->getMessage());
        }
    }

    public function answer_question(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user' => ['required'],
            'resto' => ['required'],
            'question_id' => ['required', 'numeric'],
            'status' => ['required', 'boolean']
        ], [
            'user.required' => '\'user\' param is required',
            'resto.required' => '\'resto\' param is required'
        ]);

        if ($validator->stopOnFirstFailure()->fails()) {
            return StdResponseHelper::make(false, $validator->messages()->first());
        }

        \parse_str(
            \parse_url($request->fullUrl(), PHP_URL_QUERY),
            $params
        );

        try {

            $user = QravedUser::where('qraved_user_mapping_id', $params['user'])->first();

            DB::transaction(function () use ($request, $user, $params) {

                $answer = UserAnswer::create([
                    'qraved_user_id' => $user->id,
                    'question_id' => $request->question_id,
                    'answer' => '',
                    'status' => $request->status
                ]);

                UserLogHelper::createAnswerQuestionLog(
                    $user->id,
                    $params['resto'],
                    $request->question_id,
                    $answer->id,
                    'Answer the question'
                );


            });

            return StdResponseHelper::make(true, '');

        } catch (\Exception $e) {
            return StdResponseHelper::make(false, $e->getMessage());
        }
    }
}
