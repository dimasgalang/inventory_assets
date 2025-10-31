<?php

namespace App\Http\Controllers;

use App\Models\InventoryQR;
use App\Models\ITMaintenance;
use App\Models\SysLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Agent\Agent;
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

        $username = Auth::user()->name;
        $agent = new Agent();
        $agent->setUserAgent(request()->userAgent());
        $ipAddress = request()->ip();
        $macAddress = get_mac_address($ipAddress);
        $browser = $agent->browser();
        $os = $agent->platform();
        SysLog::create([
            'username' => $username,
            'activity' => 'Void IT Maintenance ' . $itmaintenances->assets_number,
            'menu' => 'IT Maintenance',
            'log_date' => now(),
            'ip_address' => $ipAddress,
            'mac_address' => $macAddress,
            'browser_type' => $browser,
            'os' => $os,
        ]);

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

        $username = Auth::user()->name;
        $agent = new Agent();
        $agent->setUserAgent(request()->userAgent());
        $ipAddress = request()->ip();
        $macAddress = get_mac_address($ipAddress);
        $browser = $agent->browser();
        $os = $agent->platform();
        SysLog::create([
            'username' => $username,
            'activity' => 'Restore IT Maintenance ' . $itmaintenances->assets_number,
            'menu' => 'IT Maintenance',
            'log_date' => now(),
            'ip_address' => $ipAddress,
            'mac_address' => $macAddress,
            'browser_type' => $browser,
            'os' => $os,
        ]);

        Alert::success('Restore Successfully!', 'Inventory QR "' . $itmaintenances->assets_number . '" successfully restored!');
        return redirect('itmaintenance/index');
    }
}
