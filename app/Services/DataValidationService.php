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
            'evenement_id' => 'required|integer|distinct', // Uniek en numeriek ID
            'evenement_begin_datum' => 'required|date_format:Y-m-d', // Geldig datumformaat
            'evenement_eind_datum' => 'nullable|date_format:Y-m-d|after_or_equal:evenement_begin_datum', // Einddatum optioneel, maar moet na de begindatum zijn
            'wachthaven.naam' => 'required|string|regex:/^[^\d]*$/', // Wachthaven verplicht en mag geen cijfers bevatten
            'steiger.steiger_id' => 'nullable|integer|exists:steigers,steiger_id', // Steiger optioneel maar moet bestaan
            'lengte' => 'required|numeric|min:1|max:400', // Lengte in meters
            'breedte' => 'required|numeric|min:1|max:50', // Breedte in meters
            'diepgang' => 'required|numeric|min:0.1|max:20', // Diepgang in meters
            'vlag_code' => 'nullable|integer|min:1|max:999', // Optionele vlagcode
            'beladingscode' => 'nullable|integer|between:1,9', // Beladingscode tussen 1 en 9
        ];

        $messages = [
            'evenement_id.required' => 'Het veld ID is verplicht.',
            'evenement_id.integer' => 'ID moet numeriek zijn.',
            'evenement_id.distinct' => 'ID moet uniek zijn.',
            'evenement_begin_datum.required' => 'De begindatum is verplicht.',
            'evenement_begin_datum.date_format' => 'De begindatum moet het formaat YYYY-MM-DD hebben.',
            'evenement_eind_datum.date_format' => 'De einddatum moet het formaat YYYY-MM-DD hebben.',
            'evenement_eind_datum.after_or_equal' => 'De einddatum moet na of gelijk aan de begindatum zijn.',
            'wachthaven.naam.required' => 'De wachthavennaam is verplicht.',
            'wachthaven.naam.regex' => 'De wachthavennaam mag geen cijfers bevatten.',
            'steiger.steiger_id.exists' => 'De opgegeven steiger bestaat niet.',
            'lengte.required' => 'Lengte van het schip is vereist.',
            'lengte.numeric' => 'Lengte moet een numerieke waarde hebben.',
            'lengte.max' => 'Lengte mag niet groter zijn dan 400 meter.',
            'breedte.required' => 'Breedte van het schip is vereist.',
            'breedte.numeric' => 'Breedte moet een numerieke waarde hebben.',
            'breedte.max' => 'Breedte mag niet groter zijn dan 50 meter.',
            'diepgang.required' => 'Diepgang van het schip is vereist.',
            'diepgang.numeric' => 'Diepgang moet een numerieke waarde hebben.',
            'diepgang.max' => 'Diepgang mag niet groter zijn dan 20 meter.',
            'beladingscode.between' => 'De beladingscode moet tussen 1 en 9 liggen.',
        ];

        $validator = Validator::make($row, $rules, $messages);

        return $validator->fails() ? $validator->errors()->all() : [];
    }

    /**
     * Valideer een volledige dataset en retourneer invalidaties.
     *
     * @param array $dataset
     * @return array
     */
    public static function validateDataset(array $dataset): array
    {
        \Log::info('Start dataset validatie');
        $invalidRows = []; // Zorg ervoor dat dit altijd wordt geretourneerd, zelfs als het leeg is.

        try {
            foreach ($dataset as $index => $row) {
                $errors = [];

                // VALIDATIE: Controleer of evenement_id aanwezig en numeriek is
                if (empty($row['id']) || !is_numeric($row['id'])) {
                    $errors[] = 'Evenement ID is verplicht en moet numeriek zijn.';
                    \Log::error("Evenement ID ontbreekt in rij $index.");
                }

                // Controleer op ongeldige waarde door user-defined standaardwaarde
                if (empty($row['date_field'])) {
                    \Log::warning("Geen datum gevonden in rij $index. Gebruik standaardwaarde.");
                    $row['date_field'] = '01-01-2000';
                }

                $validFormats = ['d-m-Y', 'Y-m-d', 'd/m/Y']; // Voeg extra formaten toe als nodig
                if ($row['date_field'] === 'Ongeldige datum') {
                    $errors[] = 'Ongeldige datum opgegeven in het "date_field"-veld. Controleer de invoer.';
                    \Log::error("Ongeldige datum gevonden in rij $index. Waarde: Ongeldige datum");
                } elseif (
                   \DateTime::createFromFormat('d-m-Y', $row['date_field']) === false &&
                   \DateTime::createFromFormat('Y-m-d', $row['date_field']) === false
                ) {
                    $errors[] = 'De datum moet een geldig formaat hebben (DD-MM-YYYY of YYYY-MM-DD).';
                    \Log::error("Ongeldig datumformaat in rij $index. Waarde: " . ($row['date_field'] ?? 'leeg'));
                }

                if (empty($row['steiger_id']) || empty($row['wachthaven_id'])) {
                    $errors[] = 'Wachthaven en steiger mogen niet leeg zijn.';
                }

                if (isset($row['duur']) && ($row['duur'] < 0 || $row['duur'] > 14400)) {
                    $errors[] = 'Duur van het evenement mag niet negatief zijn of langer dan 10 dagen.';
                }

                if (isset($row['lengte']) && ($row['lengte'] < 5 || $row['lengte'] > 400)) {
                    $errors[] = 'De lengte van het schip moet tussen 5 en 400 meter liggen.';
                }

                // Voeg gevonden fouten toe aan de lijst met invalidRows
                if (!empty($errors)) {
                    $invalidRows[$index] = $errors;
                }
            }

        } catch (\Exception $e) {
            \Log::error("Fout in validatie: " . $e->getMessage());
        }

        \Log::info('Validatie voltooid', ['invalidRows' => $invalidRows]);

        // Altijd een array retourneren
        return $invalidRows;
    }
}
