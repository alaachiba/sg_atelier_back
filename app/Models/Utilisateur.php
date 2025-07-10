<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Utilisateur extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'utilisateurs';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'mot_de_passe',
        'role',
    ];

    protected $hidden = [
        'mot_de_passe',
    ];

    public function ateliersAnime()
    {
        return $this->hasMany(Atelier::class, 'formateur_id');
    }

    public function inscriptions()
    {
        return $this->belongsToMany(Atelier::class, 'inscriptions', 'utilisateur_id', 'atelier_id');
    }
}
