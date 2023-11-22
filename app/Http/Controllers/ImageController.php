<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    //
    public function uploadImage(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', //maximo 2mb
        ]);

        if ($validator->fails()) {
            $ok = false;
            $errors = $validator->errors()->all();
            return response()->json(compact('ok', 'errors'), 400);
        }

        $file = $request->file('image');
        $fileName = Str::random(40) . '.' . $file->getClientOriginalExtension();
        $file->storeAs('images', $fileName, 'public');

        $ok = true;
        $data = $fileName;
        return response()->json(compact('ok', 'data'), 201);
    }

    public function deleteImage(String $filename)
    {
        $path = 'public/images/' . $filename;
        if (Storage::exists($path)) {
            Storage::delete($path);
            $ok = true;
            $message = "Imagen eliminada correctamente";
            return response()->json(compact('ok', 'message'));
        }
        abort(404);
    }

    //servir imagen
    public function getImage(String $filename)
    {
        $path = 'public/images/' . $filename;
        if (Storage::exists($path)) {
            return response()->file(storage_path('app/' . $path));
        }
        abort(404);
    }
}
