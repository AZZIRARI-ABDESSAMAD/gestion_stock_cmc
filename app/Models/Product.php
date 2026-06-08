<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'quantity',
    ];

    public function demandes()
    {
        return $this->belongsToMany(Demande::class)
            ->withPivot('quantite_demandee', 'quantite_approuvee');
    }

    /**
     * Check if product is low in stock.
     */
    public function isLowStock(): bool
    {
        return $this->quantity < 5;
    }
}
