<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Atelier;

class AtelierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $ateliers = Atelier::with('formateur')->get();

    return response()->json($ateliers);
}

//     public function index(Request $request)
// {
//     //$user = $request->user();

//     // if (!$user) {
//     //     return response()->json(['message' => 'Non authentifié'], 401);
//     // }

//     // if ($user->role === 'admin') {
//     //     $ateliers = Atelier::with('formateur')->get();
//     // } elseif ($user->role === 'formateur') {
//     //     $ateliers = Atelier::where('formateur_id', $user->id)->get();
//     // } else {
//     //     return response()->json(['message' => 'Accès non autorisé.'], 403);
//     // }

//     return response()->json($ateliers);
// }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'lieu' => 'required|string|max:255',
            'formateur_id' => 'required|exists:utilisateurs,id',
        ]);

        $atelier = Atelier::create($validated);

        return response()->json($atelier, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $atelier = Atelier::with('formateur', 'participants')->find($id);
        if (!$atelier) {
            return response()->json(['message' => 'Atelier non trouvé'], 404);
        }
        return $atelier;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $atelier = Atelier::find($id);
        if (!$atelier) {
            return response()->json(['message' => 'Atelier non trouvé'], 404);
        }

        $validated = $request->validate([
            'titre' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'date_debut' => 'sometimes|required|date',
            'date_fin' => 'sometimes|required|date|after_or_equal:date_debut',
            'lieu' => 'sometimes|required|string|max:255',
            'formateur_id' => 'sometimes|required|exists:utilisateurs,id',
        ]);

        $atelier->update($validated);

        return response()->json($atelier);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $atelier = Atelier::find($id);
        if (!$atelier) {
            return response()->json(['message' => 'Atelier non trouvé'], 404);
        }

        $atelier->delete();

        return response()->json(['message' => 'Atelier supprimé']);
    }


    public function participants($id, Request $request)
{
    $user = $request->user();

    $atelier = Atelier::find($id);
    if (!$atelier) {
        return response()->json(['message' => 'Atelier non trouvé.'], 404);
    }

    // Vérifier que formateur est bien l’animateur ou que c’est un admin
    if ($user->role === 'formateur' && $atelier->formateur_id !== $user->id) {
        return response()->json(['message' => 'Accès non autorisé.'], 403);
    }

    $participants = $atelier->participants; // via relation many-to-many

    return response()->json($participants);
}
}
