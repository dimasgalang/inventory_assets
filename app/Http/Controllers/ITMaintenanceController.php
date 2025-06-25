<?php

namespace App\Http\Controllers;

use App\Models\InventoryQR;
use App\Models\ITMaintenance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class ITMaintenanceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->void) {
            $itmaintenances = ITMaintenance::select('*')->where('void', '=', $request->void)->get();
        } else {
            $itmaintenances = ITMaintenance::select('*')->where('void', '=', 'false')->get();
        }
        return view('itmaintenance.index', compact('itmaintenances'));
    }

    public function fetchassetscomputer()
    {
        $assets = InventoryQR::select('*')->where('type', 'like', '%KOMPUTER%')->get();
        foreach ($assets as $asset) {
            ITMaintenance::updateOrCreate([
                'month' => Carbon::now()->format('m'),
                'year' => Carbon::now()->format('Y'),
                'assets_number' => $asset->assets_number,
                'void' => 'false'
            ]);
        }

        Alert::success('Fetch Successfully!', 'Fetch Data Inventory successfull!');
        return redirect('itmaintenance/index');
    }

    public function create()
    {
        return view('itmaintenance.create');
    }

    public function void(Request $request)
    {
        $itmaintenances = ITMaintenance::findOrFail($request->id);
        $itmaintenances->fill([
            'void' => 'true',
        ]);
        $itmaintenances->save();

        Alert::success('Void Successfully!', 'Inventory QR "' . $itmaintenances->assets_number . '" successfully voided!');
        return redirect('itmaintenance/index');
    }

    public function restore(Request $request)
    {
        $itmaintenances = ITMaintenance::findOrFail($request->id);
        $itmaintenances->fill([
            'void' => 'false',
        ]);
        $itmaintenances->save();

        Alert::success('Restore Successfully!', 'Inventory QR "' . $itmaintenances->assets_number . '" successfully restored!');
        return redirect('itmaintenance/index');
    }
}
