<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DocumentResource;
use App\Models\Document;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->document_name) {
            $documents = Document::select('*')->where('document_name', 'like', '%' . $request->document_name . '%')->where('uploader_id', '=', $request->uploader_id)->where('void', '=', 'false')->orderBy('id', 'desc')->paginate(15);
        } else {

            $documents = Document::where('uploader_id', '=', $request->uploader_id)->where('void', '=', 'false')->orderBy('id', 'desc')->paginate(15);
        }
        return new DocumentResource(true, 'List Data Document', $documents);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'original_name'     => 'required|max:10240',
            'uploader_id'     => 'required',
            'document_name'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $original_name = $request->file('original_name');
        // $filename = $request->document_name . "_" . $request->uploader_id . "_" . Carbon::today()->toDateString() . ".pdf";
        $filename = $original_name->hashName();
        $original_name->storeAs('public/document', $filename);

        $documents = Document::create([
            'uploader_id'     => $request->uploader_id,
            'document_name'   => $request->document_name,
            'original_name'   => $filename,
            'void'            => 'false'
        ]);

        return new DocumentResource(true, 'Data Document Berhasil Ditambahkan!', $documents);
    }

    // public function destroy(Document $document)
    // {
    //     Storage::delete('public/document/' . $document->original_name);
    //     $document->delete();

    //     return new DocumentResource(true, 'Data Document Berhasil Dihapus!', null);
    // }

    public function destroy(Request $request, Document $document)
    {
        $document->update([
            'void' => 'true',
        ]);

        return new DocumentResource(true, 'Data Document Berhasil Dihapus!', $document);
    }
}
