<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriPihak extends Model
{
    protected $table = 'kategori_pihak';
    protected $fillable = ['kategori','sub_kategori','is_active'];

    // Satu subkategori (baris ini) bisa ada banyak kunjungan
    public function kunjungan(): HasMany
    {
        return $this->hasMany(Kunjungan::class, 'kategori_pihak_id');
    }
}
