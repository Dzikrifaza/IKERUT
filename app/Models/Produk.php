<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Produk extends Authenticatable
{
    public $timestamps = false;
    protected $table = 'produk';
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idkategori',
        'idsubkategori',
        'kategori',
        'subkategori',
        'judul',
        'deskripsi',
        'harga',
        'thumbnail',
        'st',
        'satuan',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

}
