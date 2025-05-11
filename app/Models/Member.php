<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'membership_date',
        'status',
    ];

    protected $casts = [
        'membership_date' => 'date',
    ];

    /**
     * Get the borrows for the member.
     */
    public function borrows(): HasMany
    {
        return $this->hasMany(Borrow::class);
    }

    /**
     * Get the active borrows for the member.
     */
    public function activeBorrows(): HasMany
    {
        return $this->hasMany(Borrow::class)->whereNull('returned_at');
    }
} 