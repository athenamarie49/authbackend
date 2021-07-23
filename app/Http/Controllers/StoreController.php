<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Exception;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function show(Store $store) {
        return response()->json($store,200);
    }

    public function search(Request $request) {
        $request->validate(['key'=>'string|required']);

        $stores = Store::where('brand','like',"%$request->key%")
            ->orWhere('description','like',"%$request->key%")->get();

        return response()->json($stores, 200);
    }

    public function store(Request $request) {
        $request->validate([
            'brand' => 'string|required',
            'description' => 'string|required',
            'product' => 'string|required',
            'contained_in' => 'numeric',
            'value' => 'numeric|required',
        ]);

        try {
            $store = Store::create($request->all());
            return response()->json($store, 202);
        }catch(Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ],500);
        }

    }

    public function update(Request $request, Store $store) {
        try {
            $store->update($request->all());
            return response()->json($store, 202);
        }catch(Exception $ex) {
            return response()->json(['message'=>$ex->getMessage()], 500);
        }
    }

    public function destroy(Store $store) {
        $store->delete();
        return response()->json(['message'=>'Store deleted.'],202);
    }

    public function index() {
        $stores = Store::orderBy('brand')->get();
        return response()->json($stores, 200);
    }
}

