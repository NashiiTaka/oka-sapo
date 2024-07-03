<?php

namespace App\Http\Controllers;

use App\Models\TProduct;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    public function index(Request $request)
    {
        try {
            $ids = explode(',', $request->ids);
        } catch (\Exception $e) {
        }

        if ($ids) {
            $products = TProduct::with('valiations')->whereIn('product_id', $ids)->get();
        }

        $products = $products ?? [];
        $message = 'Favorites';

        return view('osusume', compact('products', 'message'));
    }
}
