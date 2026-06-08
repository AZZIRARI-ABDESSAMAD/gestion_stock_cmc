<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Space extends Model
{
    use HasFactory;

    protected $fillable = ['name_espace'];

    /**
     * Get the users in this space.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
