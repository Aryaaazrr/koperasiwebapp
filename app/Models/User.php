<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'id_users';
    protected $fillable = [
        'nama',
        'username',
        'password',
        'noTelp',
        'alamat',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    protected $attributes = [
        'id_role' => 3,
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role', 'id_role');
    }

    public function detail_simpanan()
    {
        return $this->hasMany(DetailSimpanan::class, 'id_users', 'id_users');
    }

    public function detail_pinjaman()
    {
        return $this->hasMany(DetailPinjaman::class, 'id_users', 'id_users');
    }

    public function history_transaksi()
    {
        return $this->hasMany(HistoryTransaksi::class, 'id_users', 'id_users');
    }
}
