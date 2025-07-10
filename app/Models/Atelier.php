<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atelier extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
        'date_debut',
        'date_fin',
        'lieu',
        'formateur_id',
    ];

    public function formateur()
    {
        return $this->belongsTo(Utilisateur::class, 'formateur_id');
    }

    public function participants()
    {
        return $this->belongsToMany(Utilisateur::class, 'inscriptions', 'atelier_id', 'utilisateur_id');
    }

    public function publicIndex()
    {
        $ateliers = Atelier::all(); // ou filtrer ceux ouverts aux inscriptions
        return response()->json($ateliers);
    }
}
