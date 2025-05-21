<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schip;
use Illuminate\Support\Facades\DB;

class GraphController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * returning the database as $evenementen
     */
    public function index()
    {
        $evenementen = DB::table('evenementen')->get();
        return response()->json($evenementen);
    }
}
