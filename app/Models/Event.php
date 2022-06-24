<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Event extends Authenticatable
{
    public $timestamps = false;
    protected $table = 'event';
    protected $primaryKey = 'id_event';
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_event',
        'tanggal',
        'htm',
        'thumbnail',
    ];
}
