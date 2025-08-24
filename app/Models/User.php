<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class User extends Authenticatable
{
use HasFactory, Notifiable;


protected $fillable = [
'name','email','password','role','npwp','vendor_id'
];


protected $hidden = ['password','remember_token'];


public function vendor(): BelongsTo
{
return $this->belongsTo(Vendor::class);
}


public function isAdmin(): bool { return $this->role === 'admin'; }
public function isOfficer(): bool { return $this->role === 'officer'; }
public function isVendor(): bool { return $this->role === 'vendor'; }
}