<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
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

        Alert::success('Restore Successfully!', 'Document "' . $documents->document_name . '" successfully restored!');
        return redirect('document/index');
    }
}
