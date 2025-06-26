<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;

class DataValidationService
{
    /**
     * Valideer een enkele rij in de dataset.
     *
     * @param array $row
     * @return array
     */
    public static function validateRow(array $row): array
    {
        $rules = [
            'evenement_id' => 'required|integer|distinct',
            'evenement_begin_datum' => 'required|date_format:Y-m-d',
            'evenement_eind_datum' => 'nullable|date_format:Y-m-d|after_or_equal:evenement_begin_datum',
            'wachthaven.naam' => 'required|string|regex:/^[^\d]*$/',
            'steiger.steiger_id' => 'nullable|integer|exists:steigers,steiger_id',
            'lengte' => 'required|numeric|min:1|max:400',
            'breedte' => 'required|numeric|min:1|max:50',
            'diepgang' => 'required|numeric|min:0.1|max:20',
            'vlag_code' => 'nullable|integer|min:1|max:999',
            'beladingscode' => 'nullable|integer|between:1,9',
        ];

        $messages = [
            'evenement_id.required' => 'Het veld ID is verplicht.',
            'evenement_id.integer' => 'ID moet numeriek zijn.',
            'evenement_begin_datum.required' => 'De begindatum is verplicht.',
            'wachthaven.naam.required' => 'De wachthavennaam is verplicht.',
            'steiger.steiger_id.exists' => 'De opgegeven steiger bestaat niet.',
            'lengte.required' => 'Lengte van het schip is vereist.',
            'breedte.required' => 'Breedte van het schip is vereist.',
            'diepgang.required' => 'Diepgang van het schip is vereist.',
            'vlag_code.integer' => 'Vlagcode moet numeriek zijn.',
        ];

        $validator = Validator::make($row, $rules, $messages);

        return $validator->fails() ? $validator->errors()->all() : [];
    }

    /**
     * Valideer een volledige dataset en retourneer fouten.
     *
     * @param array $dataset
     * @return array
     */
    public static function validateDataset(array $dataset): array
    {
        \Log::info('Start dataset validatie');
        $invalidRows = [];

        try {
            foreach ($dataset as $index => $row) {
                $errors = [];

                // VALIDATIE: Controleer of evenement_id aanwezig en numeriek is
                if (empty($row['id']) || !is_numeric($row['id'])) {
                    $errors[] = 'Evenement ID is verplicht en moet numeriek zijn.';
                    \Log::error("Evenement ID ontbreekt in rij $index.");
                }

                // Controleer of de datum geldig is
                if (empty($row['date_field'])) {
                    \Log::warning("Geen datum gevonden in rij $index. Gebruik standaardwaarde.");
                    $row['date_field'] = '01-01-2000';
                }

                $validFormats = ['d-m-Y', 'Y-m-d', 'd/m/Y'];
                if ($row['date_field'] === 'Ongeldige datum') {
                    $errors[] = 'Ongeldige datum opgegeven in het "date_field"-veld.';
                    \Log::error("Ongeldige datum gevonden in rij $index. Waarde: Ongeldige datum");
                } elseif (
                    \DateTime::createFromFormat('d-m-Y', $row['date_field']) === false &&
                    \DateTime::createFromFormat('Y-m-d', $row['date_field']) === false
                ) {
                    $errors[] = 'De datum moet een geldig formaat hebben (DD-MM-YYYY of YYYY-MM-DD).';
                    \Log::error("Ongeldig datumformaat in rij $index. Waarde: " . ($row['date_field'] ?? 'leeg'));
                }

                // Validatie van andere velden
                if (empty($row['steiger_id']) || empty($row['wachthaven_id'])) {
                    $errors[] = 'Wachthaven en steiger mogen niet leeg zijn.';
                }

                if (isset($row['duur']) && ($row['duur'] < 0 || $row['duur'] > 14400)) {
                    $errors[] = 'Duur van het evenement mag niet negatief zijn of langer dan 10 dagen.';
                }

                if (isset($row['lengte']) && ($row['lengte'] < 5 || $row['lengte'] > 400)) {
                    $errors[] = 'De lengte van het schip moet tussen 5 en 400 meter liggen.';
                }

                // Voeg fouten toe aan invalidRows
                if (! empty($errors)) {
                    $invalidRows[$index] = $errors;
                }
            }
        } catch (\Exception $e) {
            \Log::error('Fout in validatie: ' . $e->getMessage());
        }

        \Log::info('Validatie voltooid', ['invalidRows' => $invalidRows]);

        return $invalidRows;
    }
}
