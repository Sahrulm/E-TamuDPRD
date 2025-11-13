<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tamu extends Model
{
    protected $table = 'tamu';
    protected $fillable = ['nama','email','no_hp','instansi_kategori','instansi_nama'];

    public function kunjungan(): HasMany
    {
        return $this->hasMany(Kunjungan::class, 'tamu_id');
    }
}
