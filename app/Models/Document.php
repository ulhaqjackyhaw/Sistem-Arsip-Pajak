<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    protected $fillable = [
        'vendor_id','uploaded_by','period','original_name','stored_name','mime','size','hash','path'
    ];

    // Pastikan Eloquent mem-cast timestamp jadi Carbon
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // >>> Aksesors WIB (tidak mengubah data di DB)
    public function getCreatedAtWibAttribute()
    {
        return $this->created_at?->copy()->timezone(config('app.timezone'));
    }

    public function getUpdatedAtWibAttribute()
    {
        return $this->updated_at?->copy()->timezone(config('app.timezone'));
    }

    public function vendor(): BelongsTo { return $this->belongsTo(Vendor::class); }
    public function uploader(): BelongsTo { return $this->belongsTo(User::class, 'uploaded_by'); }
}
