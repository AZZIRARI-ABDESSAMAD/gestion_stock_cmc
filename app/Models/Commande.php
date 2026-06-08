<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = ['reference', 'status', 'user_id'];

    /**
     * Get the user who placed this order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the products in this order.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
                    ->withPivot('quantite_commander', 'quantite_valide');
    }

    /**
     * Helpers to check status.
     */
    public function isOnCours(): bool
    {
        return $this->status === 'on cours';
    }

    public function isValidated(): bool
    {
        return $this->status === 'valide';
    }

    public function isRefused(): bool
    {
        return $this->status === 'refuser';
    }

    public function isShipped(): bool
    {
        return $this->status === 'expediee';
    }

    public function isDelivered(): bool
    {
        return $this->status === 'livre';
    }
}
