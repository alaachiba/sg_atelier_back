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
    // public function destroy(string $id)
    // {
    //     $inscription = Inscription::find($id);
    //     if (!$inscription) {
    //         return response()->json(['message' => 'Inscription non trouvée'], 404);
    //     }

    //     $inscription->delete();

    //     return response()->json(['message' => 'Inscription supprimée']);
    // }

    public function destroy($atelierId, Request $request)
{
    $user = $request->user();

    // Cherche l'inscription liée à cet atelier pour cet utilisateur
    $inscription = $user->inscriptions()->where('atelier_id', $atelierId)->first();
    if (!$inscription) {
        return response()->json(['message' => 'Inscription non trouvée.'], 404);
    }

    // Supprime la relation (annule l'inscription)
    $user->inscriptions()->detach($atelierId);

    return response()->json(['message' => 'Inscription annulée avec succès.']);
}

    public function mesInscriptions(Request $request)
    {
        $user = $request->user();
        $inscriptions = $user->inscriptions()->with('formateur')->get();
        return response()->json($inscriptions);
    }

    public function destroyByAtelier(Request $request, $atelierId)
    {
        $user = $request->user();

        // Chercher l'inscription qui correspond au participant et à l'atelier
        $inscription = Inscription::where('utilisateur_id', $user->id)
                                  ->where('atelier_id', $atelierId)
                                  ->first();

        if (!$inscription) {
            return response()->json(['message' => 'Inscription non trouvée'], 404);
        }

        $inscription->delete();

        return response()->json(['message' => 'Inscription annulée avec succès']);
    }

}

