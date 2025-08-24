<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Document extends Model
{
protected $fillable = [
'vendor_id','uploaded_by','period','original_name','stored_name','mime','size','hash','path'
];


public function vendor(): BelongsTo { return $this->belongsTo(Vendor::class); }
public function uploader(): BelongsTo { return $this->belongsTo(User::class, 'uploaded_by'); }
}