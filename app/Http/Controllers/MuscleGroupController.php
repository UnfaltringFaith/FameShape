<?php

namespace App\Http\Controllers;

use App\Models\MuscleGroup;
use Illuminate\Http\Request;

class MuscleGroupController extends Controller
{
    //
    public function index()
    {
        // Logic to retrieve and return muscle groups
        $muscleGroups = MuscleGroup::all();
        return response()->json($muscleGroups);
    }
}
