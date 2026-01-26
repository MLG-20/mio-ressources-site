<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FinancialTransaction extends Model
{
    use HasFactory;

    // AUTORISER L'ENREGISTREMENT DE CES CHAMPS (Audit Trail)
    protected $fillable = [
        'user_id', 
        'type', 
        'amount', 
        'reference', 
        'description'
    ];

    /**
     * Relation : La transaction appartient à un utilisateur (le professeur crédité)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}