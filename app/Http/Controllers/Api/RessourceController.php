<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ressource;
use Illuminate\Http\JsonResponse;

class RessourceController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/ressources",
     *     summary="Récupérer toutes les ressources",
     *     tags={"Ressources"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Numéro de la page",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Nombre de résultats par page",
     *         required=false,
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des ressources",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="titre", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="type", type="string"),
     *                 @OA\Property(property="prix", type="number"),
     *                 @OA\Property(property="gratuit", type="boolean")
     *             )),
     *             @OA\Property(property="total", type="integer"),
     *             @OA\Property(property="per_page", type="integer"),
     *             @OA\Property(property="current_page", type="integer")
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $limit = request('limit', 15);
        $ressources = Ressource::paginate($limit);

        return response()->json($ressources);
    }

    /**
     * @OA\Get(
     *     path="/api/ressources/{id}",
     *     summary="Récupérer une ressource spécifique",
     *     tags={"Ressources"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la ressource",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de la ressource",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="titre", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="type", type="string"),
     *             @OA\Property(property="prix", type="number")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Ressource non trouvée")
     * )
     */
    public function show($id): JsonResponse
    {
        $ressource = Ressource::findOrFail($id);
        return response()->json($ressource);
    }
}
