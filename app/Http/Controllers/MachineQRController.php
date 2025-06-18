<?php

namespace App\Http\Controllers;

use App\Imports\MachineQRImport;
use App\Models\MachineQR;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use SimpleSoftwareIO\QrCode\Facades\QrCode as FacadesQrCode;

class MachineQRController extends Controller
{
    public function index(Request $request)
    {

        if ($request->void) {
            $machineqrs = MachineQR::select('*')->where('void', '=', $request->void)->get();
        } else {
            $machineqrs = MachineQR::select('*')->where('void', '=', 'false')->get();
        }
        return view('machineqr.index', compact('machineqrs'));
    }

    public function create()
    {
        $items = DB::connection('smartit')->table('ms_barang')->select('barang_code', 'barang_name')->where('barang_status', '=', 'Active')->get();
        return view('machineqr.create', compact('items'));
    }

    public function store(Request $request)
    {
        MachineQR::create([
            'customs_code' => $request->customs_code,
            'machine_code' => $request->machine_code,
            'brand' => $request->brand,
            'type' => $request->type,
            'machine_name' => $request->machine_name,
            'serial_number' => $request->serial_number,
            'void' => 'false'
        ]);

        Alert::success('Create Successfully!', 'Machine QR ' . $request->customs_code . ' successfully created!');
        return redirect()
            ->route('machineqr.create');
    }

    public function find($id)
    {
        $machineqrs = MachineQR::findOrFail($id);
        $items = DB::connection('smartit')->table('ms_barang')->select('barang_code', 'barang_name')->where('barang_status', '=', 'Active')->get();
        return view('machineqr.update', compact('machineqrs', 'items'));
    }

    public function update(Request $request)
    {
        $machineqrs = MachineQR::findOrFail($request->id);

        $machineqrs->fill([
            'customs_code' => $request->customs_code,
            'machine_code' => $request->machine_code,
            'brand' => $request->brand,
            'type' => $request->type,
            'machine_name' => $request->machine_name,
            'serial_number' => $request->serial_number,
        ]);

        $machineqrs->save();
        Alert::success('Update Successfully!', 'Machine QR ' . $request->customs_code . ' successfully updated!');
        return redirect()
            ->route('machineqr.index');
    }

    public function void(Request $request)
    {
        $machineqrs = MachineQR::findOrFail($request->id);
        $machineqrs->fill([
            'void' => 'true',
        ]);
        $machineqrs->save();

        Alert::success('Void Successfully!', 'Machine QR "' . $machineqrs->machine_name . '" successfully voided!');
        return redirect('machineqr/index');
    }

    public function restore(Request $request)
    {
        $machineqrs = MachineQR::findOrFail($request->id);
        $machineqrs->fill([
            'void' => 'false',
        ]);
        $machineqrs->save();

        Alert::success('Restore Successfully!', 'Machine QR "' . $machineqrs->machine_name . '" successfully restored!');
        return redirect('machineqr/index');
    }

    public function batchqr()
    {
        $machineqrs = MachineQR::all()->where('void', '=', 'false');
        foreach ($machineqrs as $machineqr) {
            $qr_data = $machineqr->customs_code . "_" . $machineqr->machine_code . "_" . $machineqr->brand . "_" . $machineqr->type . "_" . $machineqr->serial_number;
            $qrImageName = $qr_data . '.png';
            // $fileImageName = $qr_data . '.jpg';

            // // Create image (base64) from some text
            // $string = $machineqr->customs_code;
            // $width  = 150;
            // $height = 150;
            // $font   = 3;
            // $im = @imagecreate($width, $height);
            // $text_color = imagecolorallocate($im, 0, 0, 0); //black text
            // // white background
            // // $background_color = imagecolorallocate ($im, 255, 255, 255);
            // // transparent background
            // $transparent = imagecolorallocatealpha($im, 0, 0, 0, 127);
            // imagefill($im, 0, 0, $transparent);
            // imagesavealpha($im, true);
            // imagestring($im, $font, 30, 130, $string, $text_color);
            // ob_start();
            // imagepng($im);
            // $imstr = base64_encode(ob_get_clean());
            // imagedestroy($im);

            // // Save Image in folder from string base64
            // $img = 'data:image/png;base64,' . $imstr;
            // $image_parts = explode(";base64,", $img);
            // $image_type_aux = explode("image/", $image_parts[0]);
            // $image_type = $image_type_aux[1];
            // $image_base64 = base64_decode($image_parts[1]);
            // $file = 'public/machineqr/text/' . $qr_data . '.jpg';
            // // Move to folder
            // // file_put_contents($file, $image_base64);
            // Storage::put('public/machineqr/text/' . $fileImageName, $image_base64);

            // Generate QRCode PNG and save put image above with merge funtion 
            // $qr = FacadesQrCode::format('png')->margin(10)->merge(storage_path('app/public/machineqr/text/') . $qr_data . '.jpg', 1, true)->size(200)->generate($qr_data);
            $qr = FacadesQrCode::format('png')->generate($qr_data);

            Storage::put('public/machineqr/' . $qrImageName, $qr);

            $updatemachineqr = MachineQR::findOrFail($machineqr->id);

            $updatemachineqr->fill([
                'qr_code' => $qrImageName,
            ]);

            $updatemachineqr->save();
        }
        Alert::success('Batch Successfully!', 'QR Code successfully generated!');
        return redirect('/machineqr/index');
    }

    public function generateqr($id)
    {
        $machineqr = MachineQR::findOrFail($id);

        $qr_data = $machineqr->customs_code . "_" . $machineqr->machine_code . "_" . $machineqr->brand . "_" . $machineqr->type . "_" . $machineqr->serial_number;
        $qr = FacadesQrCode::format('png')->generate($qr_data);
        $qrImageName = $qr_data . '.png';

        Storage::put('public/machineqr/' . $qrImageName, $qr);

        $machineqr->fill([
            'qr_code' => $qrImageName,
        ]);

        $machineqr->save();

        Alert::success('Generate QR Successfully!', 'QR Code successfully generated!');
        return redirect('/machineqr/index');
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = $file->hashName();
        $path = $file->storeAs('public/excel/', $nama_file);
        $import = Excel::import(new MachineQRImport(), storage_path('app/public/excel/' . $nama_file));
        Storage::delete($path);

        if ($import) {
            Alert::success('Import Successfully!', 'Machine data successfully imported!');
            return redirect()->intended('machineqr/index')->with(['success' => 'Data Berhasil Diimport!']);
        } else {
            return redirect()->intended('machineqr/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }
}
