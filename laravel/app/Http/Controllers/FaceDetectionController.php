<?php

namespace App\Http\Controllers;

use App\Models\TProduct;
use Illuminate\Http\Request;

class FaceDetectionController extends Controller
{
    public function index()
    {
        $colors = "const colors = [
            '#FF0000', '#FF4500', '#FF6347', '#FF7F50', '#FFA07A',
            '#FA8072', '#E9967A', '#FF69B4', '#FF6EB4', '#FF1493',
            '#D02090', '#C71585', '#DB7093', '#CD5C5C', '#DC143C',
            '#B22222', '#8B0000', '#7B0000', '#008000', '#006400',
            '#191970', '#00008B'
        ];";

        return view('face-detection', compact('colors'));
    }

    public function withProduct($product_id)
    {
        $product = TProduct::with('valiations')->find($product_id);

        $hexColors = [];
        foreach ($product->valiations as $valiation) {
            if(!$valiation->is_active){ continue; }
            $hexColors[] = "'" . $valiation->hex_color_code . "'";
        }
        $colors = "const colors = [" . implode(',', $hexColors) . "];";

        return view('face-detection', compact('colors', 'product'));
    }
}
