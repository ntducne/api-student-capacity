<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Judge extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $table = 'judges';
    protected $fillable = [
        'user_id', 'contest_id'
    ];

    public function judge_round()
    {
        return $this->belongsToMany(Round::class, 'judges_rounds', 'round_id', 'judge_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}