<?php

namespace App\Http\Controllers;

use App\Models\ControlCard;
use App\Models\ControlServices;
use App\Models\InventoryQR;
use App\Models\SysLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;
use RealRashid\SweetAlert\Facades\Alert;
use Barryvdh\DomPDF\Facade\Pdf;

class ControlCardController extends Controller
{
    public function index(Request $request)
    {
        if ($request->void) {
            $controlcards = ControlCard::select('*')
                ->leftJoin('control_services', 'control_services.control_id', '=', 'control_card.control_category')
                ->leftJoin('inventoryqr', 'control_card.assets_number', '=', 'inventoryqr.assets_number')
                ->where('control_card.void', '=', $request->void)
                ->get();
        } else {
            $controlcards = ControlCard::select('*')
                ->leftJoin('control_services', 'control_services.control_id', '=', 'control_card.control_category')
                ->leftJoin('inventoryqr', 'control_card.assets_number', '=', 'inventoryqr.assets_number')
                ->where('control_card.void', '=', 'false')
                ->orderBy('control_card.control_date', 'desc')
                ->get();
        }
        $itemcontrols = ControlCard::select('control_card.assets_number', 'inventoryqr.item_name', 'inventoryqr.location')
            ->leftJoin('inventoryqr', 'inventoryqr.assets_number', '=', 'control_card.assets_number')
            ->distinct()
            ->get();
        return view('controlcard.index', compact('controlcards', 'itemcontrols'));
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

        $username = Auth::user()->name;
        $agent = new Agent();
        $agent->setUserAgent(request()->userAgent());
        $ipAddress = request()->ip();
        $macAddress = get_mac_address($ipAddress);
        $browser = $agent->browser();
        $os = $agent->platform();
        SysLog::create([
            'username' => $username,
            'activity' => 'Create Control Card ' . $request->assets_number,
            'menu' => 'Control Card',
            'log_date' => now(),
            'ip_address' => $ipAddress,
            'mac_address' => $macAddress,
            'browser_type' => $browser,
            'os' => $os,
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

        $username = Auth::user()->name;
        $agent = new Agent();
        $agent->setUserAgent(request()->userAgent());
        $ipAddress = request()->ip();
        $macAddress = get_mac_address($ipAddress);
        $browser = $agent->browser();
        $os = $agent->platform();
        SysLog::create([
            'username' => $username,
            'activity' => 'Void Control Card ' . $controlcards->assets_number,
            'menu' => 'Control Card',
            'log_date' => now(),
            'ip_address' => $ipAddress,
            'mac_address' => $macAddress,
            'browser_type' => $browser,
            'os' => $os,
        ]);

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

        $username = Auth::user()->name;
        $agent = new Agent();
        $agent->setUserAgent(request()->userAgent());
        $ipAddress = request()->ip();
        $macAddress = get_mac_address($ipAddress);
        $browser = $agent->browser();
        $os = $agent->platform();
        SysLog::create([
            'username' => $username,
            'activity' => 'Restore Control Card ' . $controlcards->assets_number,
            'menu' => 'Control Card',
            'log_date' => now(),
            'ip_address' => $ipAddress,
            'mac_address' => $macAddress,
            'browser_type' => $browser,
            'os' => $os,
        ]);

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

    public function generateControlCardPDF(Request $request)
    {
        $document = "";
        // $qrcodes = InventoryQR::all();
        // $document = "All Assets QR Codes Sticker.pdf";
        if ($request->exporttype == "all") {
            $controlcards = ControlCard::select('*')->orderBy('control_card.assets_number', 'asc')
                ->leftJoin('inventoryqr', 'control_card.assets_number', '=', 'inventoryqr.assets_number')->orderBy('control_card.control_date', 'asc');
            $document = "All Assets Control Card.pdf";
        } else {
            $controlcards = ControlCard::select('control_card.*','inventoryqr.location','control_services.control_name','inventoryqr.item_name')->whereBetween('control_card.assets_number', [$request->from_assets_number, $request->to_assets_number])
                ->leftJoin('control_services', 'control_services.control_id', '=', 'control_card.control_category')
                ->leftJoin('inventoryqr', 'control_card.assets_number', '=', 'inventoryqr.assets_number')
                ->orderBy('control_card.assets_number', 'asc')
                ->orderBy('control_card.control_date', 'asc')
                ->get();
            $document = "Assets Control Card (" . $request->from_assets_number . " - " . $request->to_assets_number . ").pdf";
        }
        $data = ['title' => $document];
        $pdf = Pdf::loadView('/pdf/controlcardA4', compact('data', 'controlcards'));
        // return view('pdf.controlcardA4', compact('data', 'controlcards'));

        $username = Auth::user()->name;
        $agent = new Agent();
        $agent->setUserAgent(request()->userAgent());
        $ipAddress = request()->ip();
        $macAddress = get_mac_address($ipAddress);
        $browser = $agent->browser();
        $os = $agent->platform();
        SysLog::create([
            'username' => $username,
            'activity' => 'Generate PDF Control Card ' . $document,
            'menu' => 'Control Card',
            'log_date' => now(),
            'ip_address' => $ipAddress,
            'mac_address' => $macAddress,
            'browser_type' => $browser,
            'os' => $os,
        ]);
        return $pdf->download($document);
    }
}
