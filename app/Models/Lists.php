<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Tasks;

class Lists extends Model
{
    use HasFactory;

    protected $fillable =  [
        'id',
        'item',
        'done',
        'tasks_id'
    ];

    public function task() {
        return $this->belongsTo(Tasks::class);
    }

}
