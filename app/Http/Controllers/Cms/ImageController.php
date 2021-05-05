<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ImageController extends Controller
{
    public function question_images($path)
    {
        $path = storage_path('app/question_images/'. $path);
        if (!File::exists($path)) abort(404);
        $response = response(File::get($path), 200)
                    ->header("Content-Type", File::mimeType($path));
        return $response;
    }

    public function answer_image($path)
    {
        $path = storage_path('app/answer_image/'. $path);
        if (!File::exists($path)) abort(404);
        $response = response(File::get($path), 200)
                    ->header("Content-Type", File::mimeType($path));
        return $response;
    }
}
