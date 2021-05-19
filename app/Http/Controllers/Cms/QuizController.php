<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuestionBank;
use Illuminate\Validation\Rule;


class QuizController extends Controller
{
    public function index()
    {
        return view('cms.quiz');
    }

    public function getQuestions($lastId, Request $request)
    {
        $filter = $this->parseUrlQuery($request->fullUrl());
        $questions = QuestionBank::where('id', '>', $lastId)
                                 ->when($filter->count() > 0, function ($query) use ($filter) {
                                     return $query->where('question', 'like', '%'. $filter['search'] .'%');
                                 })
                                 ->limit(10)->get();
        $hasNext = QuestionBank::when($filter->count() > 0, function ($query) use ($filter) {
                                   return $query->where('question', 'like', '%'. $filter['search'] .'%');
                               })
                               ->orderBy('id', 'desc')->first()->id ?? 0;
        return [
            'html' => view('includes.get-quiz', compact('questions'))->render(),
            'lastId' => $questions->last()->id ?? 0,
            'hasNext' => $hasNext > ($questions->last()->id ?? 0),
        ];
    }

    public function getQuestion($id)
    {
        return QuestionBank::find($id);
    }

    public function store(Request $request)
    {
        $success = true;
        $message = '';

        $request->validate([
            'question' => ['required'],
            'question_images' => ['nullable'],
            'question_imagas.*' => ['file', 'image'],
            'answer_1' => [Rule::requiredIf($request->answer_type == 1 && $request->has('use_this_1'))],
            'answer_image_1' => ['nullable', 'file', 'image'],
            'answer_2' => [Rule::requiredIf($request->answer_type == 1 && $request->has('use_this_2'))],
            'answer_image_2' => ['nullable', 'file', 'image'],
            'answer_3' => [Rule::requiredIf($request->answer_type == 1 && $request->has('use_this_3'))],
            'answer_image_3' => ['nullable', 'file', 'image'],
            'answer_4' => [Rule::requiredIf($request->answer_type == 1 && $request->has('use_this_4'))],
            'answer_image_4' => ['nullable', 'file', 'image'],
            'answer_5' => [Rule::requiredIf($request->answer_type == 1 && $request->has('use_this_5'))],
            'answer_image_5' => ['nullable', 'file', 'image'],
            'essay_answer' => [Rule::requiredIf($request->answer_type == 2)],
            'correct_answer' => [Rule::requiredIf($request->answer_type == 1)]
        ], [
            'correct_answer.required' => 'The correct answer must be chosen.'
        ]);

        $data = [
            'question' => $request->question,
            'answer_type' => $request->answer_type,
            'updated_by' => auth()->id(),
        ];

        try {

            if ($request->hasFile('question_images')) {
                $path_question_images = collect();
                foreach ($request->file('question_images') as $image) {
                    $path_question_images->push(
                        $image->storeAs(
                            'question_images',
                            md5(now()->toDateTimeString())
                            . '_question_images.'
                            . $image->extension()
                        )
                    );
                }
                $data['question_images'] = json_encode($path_question_images);
            }

            if ($request->answer_type == 1) {
                $answers = [];
                for ($i = 1; $i <= 5 ; $i++) {
                    $answer = [];
                    $answer['text'] = $request->input('answer_' . $i);
                    if ($request->hasFile('answer_image_' . $i)) {
                        $path_image = $request->file('answer_image_' . $i)
                                              ->storeAs(
                                                  'answer_image',
                                                  md5(now()->toDateTimeString())
                                                  . '_answer_image_' . $i. '.'
                                                  . $request->file('answer_image_' . $i)
                                                  ->extension()
                                              );
                        $answer['image'] = $path_image;
                    }
                    $answer['correct'] = $request->correct_answer == $i;
                    if ($request->has('use_this_' . $i)) {
                        array_push($answers, $answer);
                    }
                }
                $data['answer'] = json_encode($answers);
            }
            elseif ($request->answer_type == 2) {
                $data['answer'] = json_encode([
                    'text' => $request->essay_answer
                ]);
            }

            if (empty($request->id)) {
                $data['created_by'] = auth()->id();
                $quiz = QuestionBank::create($data);
                $message = 'New question has been created';
            }
            else {
                $quiz = QuestionBank::find($request->id)
                                        ->update($data);
                $message = 'The question has been updated';
            }

        } catch (\Exception $e) {

            $success = false;
            $message = 'Mesage: ' . $e->getMessage() . ' | line: ' . $e->getLine();

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

            $user = QuestionBank::find($request->id)->delete();
            $message = 'The question has been deleted!';

        } catch (\Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        return [
            'success' => $success,
            'message' => $message,
        ];
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
