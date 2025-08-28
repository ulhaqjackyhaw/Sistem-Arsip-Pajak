<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Vendor extends Model
{
    protected $fillable = ['name','npwp','email','notes'];

    /**
     * The "booted" method of the model.
     * Ini akan berjalan secara otomatis SETIAP KALI vendor akan dihapus.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleting(function ($vendor) {
            // CARI user yang terhubung dengan cara APAPUN
            $userToDelete = $vendor->user()->first() ?? $vendor->userByUserId()->first();
            
            // Jika user ditemukan, HAPUS user tersebut.
            if ($userToDelete) {
                $userToDelete->delete();
            }
        });
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'vendor_id');
    }

    public function latestDocument(): HasOne
    {
        return $this->hasOne(Document::class, 'vendor_id')->latestOfMany('created_at');
    }

    // Relasi via vendors.user_id -> users.id (Cara Lama)
    public function userByUserId(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    // Relasi via users.vendor_id -> vendors.id (Cara Baru & Benar)
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'vendor_id', 'id');
    }
}