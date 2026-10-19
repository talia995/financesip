<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'type',
        'account_number',
        'initial_balance',
        'current_balance',
        'is_active',
        'notes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'initial_balance' => 'decimal:2',
            'current_balance' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get human-readable account type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'bank' => 'Bank',
            'e-wallet' => 'E-Wallet',
            'cash' => 'Tunai / Cash',
            default => ucfirst($this->type),
        };
    }
}

