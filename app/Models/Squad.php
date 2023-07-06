<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Squad extends Model
{
    use HasFactory;

    protected $table = 'regu';

    function schedules() {
        return $this->hasMany(Schedule::class, 'regu_id');
    }

    function members() {
        return $this->hasMany(User::class, 'regu_id');
    }
}
