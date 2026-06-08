<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'teacher_name',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('quantite_demandee', 'quantite_approuvee');
    }

    public function isPending(): bool
    {
        return $this->status === 'en_attente';
    }

    public function isProcessed(): bool
    {
        return $this->status === 'traité';
    }
}
