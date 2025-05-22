<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Borrow extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'requested_at',
        'approved_at',
        'rejected_at',
        'borrowed_at',
        'returned_at',
        'due_date',
        'is_reservation',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'borrowed_at' => 'datetime',
        'returned_at' => 'datetime',
        'due_date' => 'datetime',
        'is_reservation' => 'boolean',
    ];

    /**
     * Get the book that was borrowed.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Get the user who borrowed the book.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isPending()
    {
        return is_null($this->approved_at) && is_null($this->rejected_at);
    }

    public function isApproved()
    {
        return !is_null($this->approved_at);
    }

    public function isRejected()
    {
        return !is_null($this->rejected_at);
    }

    public function isReturned()
    {
        return !is_null($this->returned_at);
    }

    public function isOverdue()
    {
        return $this->isApproved() && !$this->isReturned() && $this->due_date->isPast();
    }
}
