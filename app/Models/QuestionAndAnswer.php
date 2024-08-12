<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\Builder\Builder;


class QuestionAndAnswer extends Model
{
    use HasFactory;
    use SoftDeletes;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;
    protected $primaryKey = 'id';
    protected $table = 'question_and_answers';
    protected $fillable = [
        'category_id',
        'question',
        'answer',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function newEloquentBuilder($query)
    {
        return new Builder($query);
    }

    public function category()
    {
        switch ($this->category_id) {
            case 1:
                return 'Thực tập';
            case 2:
                return 'Việc làm';
            case 3:
                return 'Sự kiện';
            default:
                return 'Khác';
        }
    }

    public function getStatus()
    {
        switch ($this->status) {
            case 1:
                return 'Active';
            case 0:
                return 'Inactive';
            default:
                return 'Deleted';
        }
    }

    public function created_by()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function updated_by()
    {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }

    public function deleted_by()
    {
        return $this->hasOne(User::class, 'id', 'deleted_by');
    }
}