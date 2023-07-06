<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    // Custom table name
    protected $table = 'jadwal';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'regu_id',
        'periode_id',
        'diterima',
        'minggu',
        'hari',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'diterima' => 'boolean',
    ];

    function period() {
        return $this->belongsTo(Period::class, 'periode_id');
    }

    function squad() {
        return $this->belongsTo(Squad::class,  'regu_id');
    }
}
