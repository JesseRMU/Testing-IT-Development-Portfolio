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
    $search = $request->input('search'); // Haal zoekterm op

    // Query voor data met paginatie
    $paginationData = \DB::table('evenementen')
        ->select(
            'evenement_id as id',
            'naam_ivs90_bestand as required_field',
            'regelnummer_in_bron', // Weergeven als regelnummer_in_bron
            \DB::raw("DATE(evenement_begin_datum) as date_field"), // Alleen het datumdeel
            'steiger_id',
            'wachthaven_id'
        )
        ->when($request->input('filter') == 'valid', function ($query) {
            return $query->where('is_valid', 1);
        })
        ->when($request->input('filter') == 'invalid', function ($query) {
            return $query->where('is_valid', 0);
        })
   ->when($search, function ($query, $search) {
       return $query->where(function ($q) use ($search) {
           $q->where('regelnummer_in_bron', 'LIKE', "%$search%")
             ->orWhere('naam_ivs90_bestand', 'LIKE', "%$search%")
             ->orWhere('steiger_id', 'LIKE', "%$search%")
             ->orWhere('wachthaven_id', 'LIKE', "%$search%");
       });
   })
        ->paginate(25)
        ->withQueryString(); // Houd filterparameters en zoekterm in de paginatie-links

    // Converteer data naar array
    $data = $paginationData->items();
    $validationResults = DataValidationService::validateDataset(array_map(function ($item) {
        return (array) $item;
    }, $data));

    // Retourneer geïncludeerde zoekterm aan de view
    return view('validation.index', [
        'data' => $paginationData,
        'errors' => $validationResults,
    ]);
}

public function exportErrors(Request $request)
{
    $data = $request->input('data');
    $errors = $request->input('errors');
    $filename = 'foutgegevens_' . now()->format('Ymd_His') . '.csv';

    $handle = fopen(storage_path("app/{$filename}"), 'w');
    fputcsv($handle, ['Rijnummer', 'Foutmeldingen', 'Gegevens']); // Headers

    foreach ($errors as $rowIndex => $errorMessages) {
        // Converteer rijdata naar string voor export
        $rowData = json_encode($data[$rowIndex], JSON_UNESCAPED_UNICODE);
        fputcsv($handle, [$rowIndex, implode(' | ', $errorMessages), $rowData]);
    }

    fclose($handle);

    return response()->download(storage_path("app/{$filename}"))->deleteFileAfterSend();
}
  public function removeRow($rowIndex)
  {
      try {
          \DB::table('evenementen')->where('evenement_id', $rowIndex)->delete();

          return redirect()->route('validate-data')->with('success', 'Rij succesvol verwijderd.');
      } catch (\Exception $e) {
          return back()->with('error', 'Verwijderen mislukt: ' . $e->getMessage());
      }
  }
  public function removeInvalidData(Request $request)
  {
      $ids = $request->input('selected_rows');
      if (!empty($ids)) {
          \DB::table('evenementen')->whereIn('evenement_id', $ids)->delete();
      }
      $filterApplied = $request->input('filter');
\Log::info("Filter toegepast: $filterApplied");
      return redirect()->route('validate-data')->with('success', 'Geselecteerde rijen verwijderd.');
  }
   public function destroy($id)
   {
       try {
           \Log::info("Verwijder actie gestart voor ID: {$id}");
           $evenement = Evenement::findOrFail($id);
           $evenement->delete();

           \Log::info('Evenement verwijderd: ' . $id);
           return redirect()->route('evenementen.index')->with('success', 'Gegevens succesvol verwijderd.');
       } catch (\Exception $e) {
           \Log::error('Fout bij verwijderen: ' . $e->getMessage());
           return back()->with('error', 'Het verwijderen is mislukt: ' . $e->getMessage());
       }
   }
}
