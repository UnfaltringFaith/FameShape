<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display the authenticated user's information.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function index(Request $request)
    {
        // Return the authenticated user's information
        return response()->json([
            'user' => $request->user()
        ]);
    }
}
