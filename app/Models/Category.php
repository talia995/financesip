<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
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
        'icon',
        'color',
    ];

    /**
     * Get human-readable category type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'income' => 'Pemasukan',
            'expense' => 'Pengeluaran',
            default => ucfirst($this->type),
        };
    }

    /**
     * Get fallback icon if icon is empty.
     */
    public function getDisplayIconAttribute(): string
    {
        if (! empty($this->icon)) {
            return $this->icon;
        }

        return $this->type === 'income' ? '💰' : '🛒';
    }
}

