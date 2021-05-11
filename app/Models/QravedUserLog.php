<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QravedUserLog extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Get the user associated with the QravedUserLog
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(QravedUser::class, 'id', 'qraved_user_id');
    }

    /**
     * Get the answer associated with the QravedUserLog
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function answer()
    {
        return $this->hasOne(UserAnswer::class, 'id', 'user_answer_id');
    }

    /**
     * Get the restaurant associated with the QravedUserLog
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function restaurant()
    {
        return $this->hasOne(Restaurant::class, 'id', 'restaurant_id');
    }

    /**
     * Get the question associated with the QravedUserLog
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function question()
    {
        return $this->hasOne(QuestionBank::class, 'id', 'question_id');
    }
}
