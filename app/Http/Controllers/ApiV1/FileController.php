<?php

namespace App\Http\Controllers\ApiV1;

use App\Http\Controllers\Controller;
use App\Models\TemporaryFile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function file(Request $request, $folder, $filename)
    {
        $path = 'files/'.$folder.'/'.$filename;
        $storage = Storage::disk('local');

        if (!$storage->exists($path)) {
            return response()->json(['errors' => 'File not found'], 404);
        }

        $mime = $storage->mimeType($path);
        return response(
            $storage->get($path), 200, [
                'Content-Type' => $mime,
                'Content-Disposition' => 'inline; filename="'.$filename.'"'
            ]
        );
    }

    public function upload(Request $request)
    {
        $file = $request->file('file');

        if (is_array($file)) {
            $result = [];

            foreach ($file as $item) {
                $filename = $this->saveFileToTmpDirectory($item);
                $result['filename'][] = $filename;
            }

            return response()->json($result, 200);
        }

        $filename = $this->saveFileToTmpDirectory($file);

        return response()->json([
            'filename' => $filename
        ], 200);
    }

    private function saveFileToTmpDirectory(UploadedFile $file)
    {
        $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
        $file->storeAs('tmp', $filename);

        $tmpFileDb = new TemporaryFile();
        $tmpFileDb->id = $filename;
        $tmpFileDb->user_filename = $file->getClientOriginalName();
        $tmpFileDb->created_at = Carbon::now();
        $tmpFileDb->save();

        return $filename;
    }
}
