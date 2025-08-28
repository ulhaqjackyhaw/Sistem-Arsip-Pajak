<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Vendor extends Model
{
    protected $fillable = ['name','npwp','email','notes'];

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'vendor_id');
    }

    public function latestDocument(): HasOne
    {
        return $this->hasOne(Document::class, 'vendor_id')->latestOfMany('created_at');
    }

    // Jika skema lama: vendors.user_id -> users.id
    public function userByUserId(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    // Jika skema baru: users.vendor_id -> vendors.id
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'vendor_id', 'id');
    }
}
