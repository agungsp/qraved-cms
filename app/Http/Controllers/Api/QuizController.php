<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuestionBank;
use App\Helpers\StdResponseHelper;
use Illuminate\Support\Facades\Validator;

class QuizController extends Controller
{
    public function get()
    {
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

            return StdResponseHelper::make(true, '', $question);
        } catch (\Exception $e) {
            return StdResponseHelper::make(false, $e->getMessage());
        }
    }

    public function answer_question(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question_id' => ['required', 'numeric'],
            'status' => ['required', 'boolean']
        ]);

        if ($validator->stopOnFirstFailure()->fails()) {
            return StdResponseHelper::make(false, $validator->messages()->first());
        }

    }
}
