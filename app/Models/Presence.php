<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    use HasFactory;

    // Custom Table name
    protected $table = 'presensi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'keterangan',
        'anggota_id',
        'jadwal_id',
        'bukti',
    ];

    function user() {
        return $this->belongsTo(User::class, 'anggota_id');
    }

    function schedule() {
        return $this->belongsTo(Schedule::class, 'jadwal_id');
    }
}
