<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\TValiation;

class AdminController extends Controller
{
    /**
     * 管理画面トップページ
     *
     * @return void
     */
    public function index()
    {
        return view('admin.index');
    }


    /**
     * カラーチェック画面
     *
     * @return void
     */
    public function checkColors(){
        $valiations = TValiation::with('product')->where('is_active', '=', true)->limit(1000)->get();
        // return $valiations;
        return view('admin.check-colors', compact('valiations'));
    }
}
