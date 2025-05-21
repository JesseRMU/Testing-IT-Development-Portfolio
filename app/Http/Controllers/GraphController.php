<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schip;
use Illuminate\Support\Facades\DB;

class GraphController extends Controller
{
    public function index()
    {
        $schepen = DB::table('schepen')->get();
        return response()->json($schepen);
    }
}
