<?php

namespace App\Models;

use App\Enums\BusinessType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $table = 'stores';

    protected $fillable = [
        'name',
        'email',
        'address',
        'phone',
        'business_type',
    ];

    protected $guarded = [];

    protected $casts = [
        'business_type' => BusinessType::class,
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
