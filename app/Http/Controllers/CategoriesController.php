<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Categories;

class CategoriesController extends Controller
{
    //
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            
        ]);

        if($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $payload = $validator->validated();

        Categories::create([
            'name'=> $payload['name']
        ]);

        return response()->json([
            'Pesan' => 'Categories successfully created'
        ]);
    }
    
    function showAll(Request $request) {
        $categories = Categories::all();

        return response()->json([
            'msg' => 'Data semua produk',
            'data' => $categories
        ], 200);
    }

    function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $payload = $validator->validated();

        $categories = Categories::find($id);

        if($categories) {
            $categories->update([
                'name' => $payload['name']
            ]);

            return response()->json([
                'msg' => 'Kategori berhasil diupdate'
            ], 200);

        } else {
            return response()->json([
                'msg' => 'Kategori tidak ditemukan'
            ], 404);
        }



    }

    function delete(Request $request, $id) {
        $categories = Categories::find($id);

        if($categories) {
            $categories->delete();

            return response()->json([
                'msg' => 'Kategori berhasil di hapus'
            ], 200);
        } else {
            return response()->json([
                'msg' => 'Kategori tidak ditemukan'
            ], 404);
        }

    }
}