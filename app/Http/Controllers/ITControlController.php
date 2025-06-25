<?php

namespace App\Http\Controllers;

use App\Imports\ITControlImport;
use App\Models\ITControl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class ITControlController extends Controller
{
    public function index(Request $request)
    {
        if ($request->void) {
            $itcontrols = ITControl::select('itcontrol.*', 'users.name', 'inventoryqr.item_name')->leftJoin('inventoryqr', 'itcontrol.assets_number', '=', 'inventoryqr.assets_number')->leftJoin('users', 'itcontrol.user_id', '=', 'users.id')->where('itcontrol.void', '=', $request->void)->get();
        } else {
            $itcontrols = ITControl::select('itcontrol.*', 'users.name', 'inventoryqr.item_name')->leftJoin('inventoryqr', 'itcontrol.assets_number', '=', 'inventoryqr.assets_number')->leftJoin('users', 'itcontrol.user_id', '=', 'users.id')->where('itcontrol.void', '=', 'false')->get();
        }
        return view('itcontrol.index', compact('itcontrols'));
    }

    public function create()
    {
        return view('itcontrol.create');
    }


    public function store(Request $request)
    {
        ITControl::create([
            'user_id' => $request->user_id,
            'assets_number' => $request->assets_number,
            'device_name' => $request->device_name,
            'windows_license' => $request->windows_license,
            'windows_password' => $request->windows_password,
            'office_license' => $request->office_license,
            'office_email' => $request->office_email,
            'office_password' => $request->office_password,
            'void' => 'false'
        ]);

        Alert::success('Create Successfully!', 'IT Control ' . $request->assets_number . ' successfully created!');
        return redirect()
            ->route('itcontrol.create');
    }

    public function void(Request $request)
    {
        $itcontrols = ITControl::findOrFail($request->id);
        $itcontrols->fill([
            'void' => 'true',
        ]);
        $itcontrols->save();

        Alert::success('Void Successfully!', 'IT Control "' . $itcontrols->assets_number . '" successfully voided!');
        return redirect('itcontrol/index');
    }

    public function restore(Request $request)
    {
        $itcontrols = ITControl::findOrFail($request->id);
        $itcontrols->fill([
            'void' => 'false',
        ]);
        $itcontrols->save();

        Alert::success('Restore Successfully!', 'IT Control "' . $itcontrols->assets_number . '" successfully restored!');
        return redirect('itcontrol/index');
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = $file->hashName();
        $path = $file->storeAs('public/itcontrol/', $nama_file);
        $import = Excel::import(new ITControlImport(), storage_path('app/public/itcontrol/' . $nama_file));
        Storage::delete($path);

        if ($import) {
            Alert::success('Import Successfully!', 'IT Control data successfully imported!');
            return redirect()->intended('itcontrol/index')->with(['success' => 'Data Berhasil Diimport!']);
        } else {
            return redirect()->intended('itcontrol/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }
}
