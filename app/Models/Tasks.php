<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Lists;

class Tasks extends Model
{
    use HasFactory;

    public function lists() {
        return $this->hasMany(Lists::class);
    }
}
