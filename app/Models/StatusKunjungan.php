<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatusKunjungan extends Model
{
    protected $table = 'status_kunjungan';
    public $timestamps = false;
    protected $fillable = ['kunjungan_id','old_status','new_status','changed_by_user_id','note','changed_at'];
    protected $casts = ['changed_at' => 'datetime'];

    public function kunjungan(): BelongsTo { return $this->belongsTo(Kunjungan::class, 'kunjungan_id'); }
    public function changedBy(): BelongsTo { return $this->belongsTo(User::class, 'changed_by_user_id'); }
}
