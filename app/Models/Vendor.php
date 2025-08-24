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
        // dokumen terbaru berdasar created_at
        return $this->hasOne(Document::class, 'vendor_id')->latestOfMany('created_at');
    }
}
