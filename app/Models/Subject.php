<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'subject_name',
    ];

    // Subject → Class
    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    // Subject → Exams
    public function exams()
    {
        return $this->hasMany(Exam::class, 'subject_id');
    }
}
