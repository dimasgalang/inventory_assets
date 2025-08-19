<?php

namespace App\Http\Controllers;

use App\Models\ControlCard;
use App\Models\ControlServices;
use App\Models\InventoryQR;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ControlCardController extends Controller
{
    public function index(Request $request)
    {
        if ($request->void) {
            $controlcards = ControlCard::select('*')
                ->leftJoin('control_services', 'control_services.control_id', '=', 'control_card.control_category')
                ->where('control_card.void', '=', $request->void)
                ->get();
        } else {
            $controlcards = ControlCard::select('*')
                ->leftJoin('control_services', 'control_services.control_id', '=', 'control_card.control_category')
                ->where('control_card.void', '=', 'false')
                ->get();
        }
        return view('controlcard.index', compact('controlcards'));
    }

    public function create()
    {
        $services = ControlServices::all();
        $suppliers = DB::connection('smartit')->table('ms_supplier')->select('supplier_code', 'supplier_name')->where('supplier_status', '=', 'Active')->get();
        $items = InventoryQR::all();
        return view('controlcard.create', compact('items', 'services', 'suppliers'));
    }


    public function store(Request $request)
    {
        ControlCard::create([
            'assets_number' => $request->assets_number,
            'control_category' => $request->control_category,
            'control_date' => $request->control_date,
            'control_price' => $request->control_price,
            'supplier_code' => $request->supplier_code,
            'supplier_name' => $request->supplier_name,
            'void' => 'false'
        ]);

        Alert::success('Create Successfully!', 'Control Card ' . $request->assets_number . ' successfully created!');
        return redirect()
            ->route('controlcard.create');
    }

    public function void(Request $request)
    {
        $controlcards = ControlCard::findOrFail($request->id);
        $controlcards->fill([
            'void' => 'true',
        ]);
        $controlcards->save();

        Alert::success('Void Successfully!', 'Control Card "' . $controlcards->assets_number . '" successfully voided!');
        return redirect('controlcard/index');
    }

    public function restore(Request $request)
    {
        $controlcards = ControlCard::findOrFail($request->id);
        $controlcards->fill([
            'void' => 'false',
        ]);
        $controlcards->save();

        Alert::success('Restore Successfully!', 'Control Card "' . $controlcards->assets_number . '" successfully restored!');
        return redirect('controlcard/index');
    }

    public function scan(Request $request)
    {
        if ($request->assets) {
            $controlcards = ControlCard::select('*')
                ->leftJoin('control_services', 'control_services.control_id', '=', 'control_card.control_category')
                ->where('control_card.assets_number', '=', $request->assets)
                ->get();
        } else {
            $controlcards = ControlCard::select('*')->where('assets_number', '=', null)->get();
        }
        return view('controlcard/scan', compact('controlcards'));
    }

    public function fetchsupplier($supplier_id)
    {
        $suppliers = DB::connection('smartit')->table('ms_supplier')->select('supplier_code', 'supplier_name')->where('supplier_status', '=', 'Active')->where('supplier_code', '=', $supplier_id)->get();

        return response()->json($suppliers);
    }
}
