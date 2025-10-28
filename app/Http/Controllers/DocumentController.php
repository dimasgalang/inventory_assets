<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\SysLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;
use RealRashid\SweetAlert\Facades\Alert;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->void) {
            $documents = Document::select('document.*', 'users.name')->leftJoin('users', 'document.uploader_id', '=', 'users.id')->where('document.void', '=', $request->void)->orderBy('id', 'desc')->get();
        } else {
            $documents = Document::select('document.*', 'users.name')->leftJoin('users', 'document.uploader_id', '=', 'users.id')->where('document.void', '=', 'false')->orderBy('id', 'desc')->get();
        }
        return view('document.index', compact('documents'));
    }

    public function fetchdocument($id)
    {
        $fetchdocument = Document::select('document.*', 'users.name')->leftJoin('users', 'document.uploader_id', '=', 'users.id')->where('approval.id', '=', $id)->get();
        // dd($fetchapproval);
        return response()->json($fetchdocument);
    }

    public function void(Request $request)
    {
        $documents = Document::findOrFail($request->id);
        $documents->fill([
            'void' => 'true',
        ]);
        $documents->save();

        $username = Auth::user()->name;
        $agent = new Agent();
        $agent->setUserAgent(request()->userAgent());
        $ipAddress = request()->ip();
        $browser = $agent->browser();
        $os = $agent->platform();
        SysLog::create([
            'username' => $username,
            'activity' => 'Void Document ' . $documents->document_name,
            'menu' => 'Document',
            'log_date' => now(),
            'ip_address' => $ipAddress,
            'browser_type' => $browser,
            'os' => $os,
        ]);

        Alert::success('Void Successfully!', 'Document "' . $documents->document_name . '" successfully voided!');
        return redirect('document/index');
    }

    public function restore(Request $request)
    {
        $documents = Document::findOrFail($request->id);
        $documents->fill([
            'void' => 'false',
        ]);
        $documents->save();

        $username = Auth::user()->name;
        $agent = new Agent();
        $agent->setUserAgent(request()->userAgent());
        $ipAddress = request()->ip();
        $browser = $agent->browser();
        $os = $agent->platform();
        SysLog::create([
            'username' => $username,
            'activity' => 'Restore Document ' . $documents->document_name,
            'menu' => 'Document',
            'log_date' => now(),
            'ip_address' => $ipAddress,
            'browser_type' => $browser,
            'os' => $os,
        ]);

        Alert::success('Restore Successfully!', 'Document "' . $documents->document_name . '" successfully restored!');
        return redirect('document/index');
    }
}
