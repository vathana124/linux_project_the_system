<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'questions';
    protected $fillable = [
        'exam_id',
        'question_text',
        'created_at',
        'updated_at',
    ];
}
