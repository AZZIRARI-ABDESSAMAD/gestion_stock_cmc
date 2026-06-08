<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'quantity', 'category_id'];

    /**
     * Get the category of the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the orders containing this product.
     */
    public function commandes(): BelongsToMany
    {
        return $this->belongsToMany(Commande::class)
                    ->withPivot('quantite_commander', 'quantite_valide');
    }

    /**
     * Determine if stock is critically low.
     */
    public function isLowStock(): bool
    {
        return $this->quantity < 5;
    }
}
