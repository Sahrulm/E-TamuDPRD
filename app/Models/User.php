<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory; 

class User extends Authenticatable
{
    use HasFactory;
    protected $fillable = ['full_name','email','password','no_wa','role','is_active'];
    protected $hidden = ['password'];
    protected $casts = ['is_active' => 'boolean'];

    public const ROLE_ADMIN = 'admin';
    public const ROLE_HOST = 'host';
    public const ROLE_RESEPSIONIS = 'resepsionis';

    public function kunjunganDitugaskan(): HasMany
    {
        return $this->hasMany(Kunjungan::class, 'host_id');
    }

    public function perubahanStatus(): HasMany
    {
        return $this->hasMany(StatusKunjungan::class, 'changed_by_user_id');
    }
}
