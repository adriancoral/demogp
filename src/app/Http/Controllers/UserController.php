<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    use ApiResponses;

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->successfulResponse(User::all());
    }

    /**
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        return $this->successfulResponse(auth()->user());
    }
}
