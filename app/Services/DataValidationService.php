<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;

class DataValidationService
{
    /**
     * Valideer een enkele rij in de dataset.
     * @param array $row
     * @return array
     */
    public static function validateRow(array $row): array
    {
        $rules = [
            'required_field' => 'required|string',
            'id' => 'required|integer|distinct',
            'price' => 'nullable|numeric|min:0|max:10000',
            'date_field' => 'nullable|date_format:d-m-Y',
        ];

        $validator = Validator::make($row, $rules);

        return $validator->fails() ? $validator->errors()->all() : [];
    }

    /**
     * Markeer foutieve rijen in een dataset.
     * @param array $data
     * @return array
     */
    public static function validateDataset(array $data): array
    {
        $invalidRows = [];

        foreach ($data as $index => $row) {
            $errors = self::validateRow($row);

            if (!empty($errors)) {
                $invalidRows[$index] = $errors;
            }
        }

        return $invalidRows;
    }
}
