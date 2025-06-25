<?php

namespace App\Http\Controllers;

use App\Services\DataValidationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DataValidationController extends Controller
{
    /**
     * Valideer dataset en stuur resultaat naar een view.
     */
    public function validateData(Request $request): View
    {
        // Simuleer ophalen van data uit DB (voorbeeld data)
        $data = [
            ['id' => 1, 'required_field' => '', 'price' => 5000, 'date_field' => '25-06-2025'],
            ['id' => 2, 'required_field' => 'valid data', 'price' => 12000, 'date_field' => 'invalid date'],
        ];

        $validationResults = DataValidationService::validateDataset($data);

        return view('validation.index', [
            'data' => $data,
            'errors' => $validationResults,
        ]);
    }
}
