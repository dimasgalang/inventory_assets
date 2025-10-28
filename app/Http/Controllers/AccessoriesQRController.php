<?php

namespace App\Http\Controllers;

use App\Imports\AccessoriesQRImport;
use App\Imports\FabricsQRImport;
use App\Imports\InventoryQRImport;
use App\Models\AccessoriesQR;
use App\Models\FabricsQR;
use App\Models\InventoryQR;
use App\Models\SysLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Agent\Agent;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use SimpleSoftwareIO\QrCode\Facades\QrCode as FacadesQrCode;

class AccessoriesQRController extends Controller
{
    public function index(Request $request)
    {
        // if ($request->void) {
        //     $fabricqrs = FabricsQR::select('*')->where('void', '=', $request->void)->get();
        // } else {
        //     $fabricqrs = FabricsQR::select('*')->where('void', '=', 'false')->get();
        // }
        $accessoriesqrs = AccessoriesQR::all();
        return view('accessoriesqr.index', compact('accessoriesqrs'));
    }

    // public function create()
    // {
    //     return view('inventoryqr.create');
    // }


    // public function store(Request $request)
    // {
    //     InventoryQR::create([
    //         'item_number' => $request->item_number,
    //         'item_name' => $request->item_name,
    //         'void' => 'false'
    //     ]);

    //     Alert::success('Create Successfully!', 'Inventory QR ' . $request->item_number . ' successfully created!');
    //     return redirect()
    //         ->route('inventoryqr.create');
    // }

    // public function void(Request $request)
    // {
    //     $inventoryqrs = InventoryQR::findOrFail($request->id);
    //     $inventoryqrs->fill([
    //         'void' => 'true',
    //     ]);
    //     $inventoryqrs->save();

    //     Alert::success('Void Successfully!', 'Inventory QR "' . $inventoryqrs->item_name . '" successfully voided!');
    //     return redirect('inventoryqr/index');
    // }

    // public function restore(Request $request)
    // {
    //     $inventoryqrs = InventoryQR::findOrFail($request->id);
    //     $inventoryqrs->fill([
    //         'void' => 'false',
    //     ]);
    //     $inventoryqrs->save();

    //     Alert::success('Restore Successfully!', 'Inventory QR "' . $inventoryqrs->item_name . '" successfully restored!');
    //     return redirect('inventoryqr/index');
    // }

    public function batchqr()
    {
        $accessoriesqrs = AccessoriesQR::where('qr_code', '=', null)->get();
        foreach ($accessoriesqrs as $accessoriesqr) {
            $qr_data = $accessoriesqr->random_qr_code;

            $fileImageName = $qr_data . '.jpg';

            // Create image (base64) from some text
            $string = $accessoriesqr->random_qr_code;
            $width  = 200;
            $height = 200;
            $font   = 6;
            $im = @imagecreate(
                $width,
                $height
            );
            $text_color = imagecolorallocate($im, 0, 0, 0); //black text
            // white background
            // $background_color = imagecolorallocate ($im, 255, 255, 255);
            // transparent background
            $transparent = imagecolorallocatealpha($im, 0, 0, 0, 127);
            imagefill($im, 0, 0, $transparent);
            imagesavealpha($im, true);
            // imagestring($im, $font, 75, 10, $string, $text_color);
            ob_start();
            imagepng($im);
            $imstr = base64_encode(ob_get_clean());
            imagedestroy($im);


            // Save Image in folder from string base64
            $img = 'data:image/png;base64,' . $imstr;
            $image_parts = explode(";base64,", $img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $file = 'public/accessoriesqr/text/' . $qr_data . '.jpg';
            // Move to folder
            // file_put_contents($file, $image_base64);
            Storage::put('public/accessoriesqr/text/' . $fileImageName, $image_base64);

            $qr = FacadesQrCode::format('png')->margin(12)->merge(storage_path('app/public/accessoriesqr/text/') . $qr_data . '.jpg', 1, true)->size(200)->generate($qr_data);

            // $qr = FacadesQrCode::format('png')->generate($qr_data);
            // $qrImageName = $qr_data . '.png';

            Storage::put('public/accessoriesqr/' . $fileImageName, $qr);

            $updateaccessoriesqr = AccessoriesQR::findOrFail($accessoriesqr->id);

            $updateaccessoriesqr->fill([
                'qr_code' => $fileImageName,
            ]);

            $updateaccessoriesqr->save();
        }

        $username = Auth::user()->name;
        $agent = new Agent();
        $agent->setUserAgent(request()->userAgent());
        $ipAddress = request()->ip();
        $browser = $agent->browser();
        $os = $agent->platform();
        SysLog::create([
            'username' => $username,
            'activity' => 'Batch Generate QR Code for Accessories QR',
            'menu' => 'Accessories QR',
            'log_date' => now(),
            'ip_address' => $ipAddress,
            'browser_type' => $browser,
            'os' => $os,
        ]);
        Alert::success('Batch Successfully!', 'QR Code successfully generated!');
        return redirect('/accessoriesqr/index');
    }

    public function importAccessories(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = $file->hashName();
        $path = $file->storeAs('public/excel/', $nama_file);
        $import = Excel::import(new AccessoriesQRImport(), storage_path('app/public/excel/' . $nama_file));
        Storage::delete($path);

        if ($import) {
            $username = Auth::user()->name;
            $agent = new Agent();
            $agent->setUserAgent(request()->userAgent());
            $ipAddress = request()->ip();
            $browser = $agent->browser();
            $os = $agent->platform();
            SysLog::create([
                'username' => $username,
                'activity' => 'Import Accessories Data from Excel ' . $nama_file,
                'menu' => 'Accessories QR',
                'log_date' => now(),
                'ip_address' => $ipAddress,
                'browser_type' => $browser,
                'os' => $os,
            ]);
            Alert::success('Import Successfully!', 'Accessories data successfully imported!');
            return redirect()->intended('accessoriesqr/index')->with(['success' => 'Data Berhasil Diimport!']);
        } else {
            $username = Auth::user()->name;
            $agent = new Agent();
            $agent->setUserAgent(request()->userAgent());
            $ipAddress = request()->ip();
            $browser = $agent->browser();
            $os = $agent->platform();
            SysLog::create([
                'username' => $username,
                'activity' => 'Failed Import Accessories Data from Excel ' . $nama_file,
                'menu' => 'Accessories QR',
                'log_date' => now(),
                'ip_address' => $ipAddress,
                'browser_type' => $browser,
                'os' => $os,
            ]);
            return redirect()->intended('accessoriesqr/index')->with(['error' => 'Data Gagal Diimport!']);
        }
    }

    // public function generateqr($id)
    // {
    //     $inventoryqr = InventoryQR::findOrFail($id);
    //     $qr_data = $inventoryqr->item_number . "_" . $inventoryqr->assets_number . "_" . $inventoryqr->brand . "_" . $inventoryqr->type . "_" . $inventoryqr->item_name . "_" . $inventoryqr->serial_number . "_" . $inventoryqr->incoming_date . "_" . $inventoryqr->location;

    //     $fileImageName = $qr_data . '.jpg';

    //     // Create image (base64) from some text
    //     $string = $inventoryqr->assets_number;
    //     $width  = 200;
    //     $height = 200;
    //     $font   = 6;
    //     $im = @imagecreate(
    //         $width,
    //         $height
    //     );
    //     $text_color = imagecolorallocate($im, 0, 0, 0); //black text
    //     // white background
    //     // $background_color = imagecolorallocate ($im, 255, 255, 255);
    //     // transparent background
    //     $transparent = imagecolorallocatealpha($im, 0, 0, 0, 127);
    //     imagefill($im, 0, 0, $transparent);
    //     imagesavealpha($im, true);
    //     imagestring($im, $font, 75, 10, $string, $text_color);
    //     ob_start();
    //     imagepng($im);
    //     $imstr = base64_encode(ob_get_clean());
    //     imagedestroy($im);


    //     // Save Image in folder from string base64
    //     $img = 'data:image/png;base64,' . $imstr;
    //     $image_parts = explode(";base64,", $img);
    //     $image_type_aux = explode("image/", $image_parts[0]);
    //     $image_type = $image_type_aux[1];
    //     $image_base64 = base64_decode($image_parts[1]);
    //     $file = 'public/inventoryqr/text/' . $qr_data . '.jpg';
    //     // Move to folder
    //     // file_put_contents($file, $image_base64);
    //     Storage::put('public/inventoryqr/text/' . $fileImageName, $image_base64);

    //     $qr = FacadesQrCode::format('png')->margin(12)->merge(storage_path('app/public/inventoryqr/text/') . $qr_data . '.jpg', 1, true)->size(200)->generate($qr_data);

    //     // $qr = FacadesQrCode::format('png')->generate($qr_data);
    //     // $qrImageName = $qr_data . '.png';

    //     Storage::put('public/inventoryqr/' . $fileImageName, $qr);

    //     $updateinventoryqr = InventoryQR::findOrFail($inventoryqr->id);

    //     $updateinventoryqr->fill([
    //         'qr_code' => $fileImageName,
    //     ]);

    //     $updateinventoryqr->save();

    //     Alert::success('Generate QR Successfully!', 'QR Code successfully generated!');
    //     return redirect('/inventoryqr/index');
    // }

    public function generatePDF(Request $request)
    {
        $document = "";
        // $qrcodes = InventoryQR::all();
        // $document = "All Assets QR Codes Sticker.pdf";
        if ($request->exporttype == "all") {
            $qrcodes = AccessoriesQR::all();
            $document = "All Accessories QR Codes Sticker.pdf";
        } else {
            $qrcodes = AccessoriesQR::whereBetween('assets_number', [$request->from_assets_number, $request->to_assets_number])->get();
            $document = "Accessories QR Codes Sticker (" . $request->from_assets_number . " - " . $request->to_assets_number . ").pdf";
        }
        $data = ['title' => $document];
        $pdf = Pdf::loadView('/pdf/qraccessoriesA4', compact('data', 'qrcodes'));
        // return view('pdf.qrstickerA4', compact('data', 'qrcodes'));

        $username = Auth::user()->name;
        $agent = new Agent();
        $agent->setUserAgent(request()->userAgent());
        $ipAddress = request()->ip();
        $browser = $agent->browser();
        $os = $agent->platform();
        SysLog::create([
            'username' => $username,
            'activity' => 'Generate PDF QR Code for Accessories QR',
            'menu' => 'Accessories QR',
            'log_date' => now(),
            'ip_address' => $ipAddress,
            'browser_type' => $browser,
            'os' => $os,
        ]);
        return $pdf->download($document);
    }
}
