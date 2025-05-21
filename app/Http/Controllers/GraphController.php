<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schip;
use Illuminate\Support\Facades\DB;

class GraphController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * returning the database as $schepen
     */
    public function index()
    {
        $schepen = DB::table('schepen')->get();
        return response()->json($schepen);
    }
}
