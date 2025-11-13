<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Kunjungan extends Model
{
    protected $table = 'kunjungan';
    protected $fillable = [
        'tamu_id','kategori_pihak_id','jumlah_peserta','keperluan',
        'tanggal_kunjungan','waktu_kunjungan','status_sekarang','dokumen','host_id'
    ];
    protected $casts = ['tanggal_kunjungan' => 'date'];

    public function tamu(): BelongsTo { return $this->belongsTo(Tamu::class, 'tamu_id'); }
    public function kategoriPihak(): BelongsTo { return $this->belongsTo(KategoriPihak::class, 'kategori_pihak_id'); }
    public function host(): BelongsTo { return $this->belongsTo(User::class, 'host_id'); }
    public function riwayatStatus(): HasMany { return $this->hasMany(StatusKunjungan::class, 'kunjungan_id'); }

    // Helper workflow (optional)
    public function approve(?User $byHost = null): bool
    {
        if ($this->status_sekarang !== 'menunggu') {
            return false;
        }

        DB::transaction(function () use ($byHost) {
            $old = $this->status_sekarang;

            $this->forceFill([
                'status_sekarang' => 'diterima',
                'host_id'         => $byHost?->id, // boleh null
            ])->save();

            // kalau punya tabel riwayat_status dan kolom nullable, catat perubahan
            $this->riwayatStatus()->create([
                'old_status'          => $old,
                'new_status'          => 'diterima',
                'changed_by_user_id'  => $byHost?->id, // boleh null
                // tanpa 'note'
            ]);
        });

        return true;
    }

    public function reject(?User $byHost = null): bool
    {
        if ($this->status_sekarang !== 'menunggu') {
            return false;
        }

        DB::transaction(function () use ($byHost) {
            $old = $this->status_sekarang;

            $this->forceFill([
                'status_sekarang' => 'ditolak',
                'host_id'         => $byHost?->id, // boleh null
            ])->save();

            $this->riwayatStatus()->create([
                'old_status'          => $old,
                'new_status'          => 'ditolak',
                'changed_by_user_id'  => $byHost?->id, // boleh null
                // tanpa 'note'
            ]);
        });

        return true;
    }
}
