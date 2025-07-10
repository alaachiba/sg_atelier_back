<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscription;
use App\Models\Utilisateur;
use App\Models\Atelier;

class InscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inscription::with(['utilisateur', 'atelier'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'participant') {
        return response()->json(['message' => 'Seulement les participants peuvent s’inscrire.'], 403);
        }

        $request->validate([
            'atelier_id' => 'required|exists:ateliers,id',
    ]   );

    // Vérifier s’il n’est pas déjà inscrit
        $exists = $user->inscriptions()->where('atelier_id', $request->atelier_id)->exists();
        if ($exists) {
        return response()->json(['message' => 'Vous êtes déjà inscrit à cet atelier.'], 409);
        }

        $user->inscriptions()->attach($request->atelier_id);

        return response()->json(['message' => 'Inscription réussie.'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $inscription = Inscription::with(['utilisateur', 'atelier'])->find($id);
        if (!$inscription) {
            return response()->json(['message' => 'Inscription non trouvée'], 404);
        }
        return $inscription;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $inscription = Inscription::find($id);
        if (!$inscription) {
            return response()->json(['message' => 'Inscription non trouvée'], 404);
        }

        $inscription->delete();

        return response()->json(['message' => 'Inscription supprimée']);
    }
}
