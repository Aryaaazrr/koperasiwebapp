<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailSimpanan extends Model
{
    use HasFactory;
    protected $table = 'detail_simpanan';
    protected $primaryKey = 'id';
    protected $guarded = [];
    // protected $dates = [
    //     'created_at' => 'datetime',
    //     'updated_at' => 'datetime',
    // ];

    public function simpanan()
    {
        return $this->belongsTo(Simpanan::class, 'id_simpanan', 'id_simpanan');
    }

    public function history_transaksi()
    {
        return $this->hasMany(HistoryTransaksi::class, 'id_detail_simpanan', 'id_detail_simpanan');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'id_users', 'id_users');
    }
}