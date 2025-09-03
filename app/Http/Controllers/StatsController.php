<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function Index()
    {
        $title = '통계';

        // 총 상품 수량
        $totalQuantity = Product::sum('quantity'); // select sum(quantity) from products;

        // 총 상품 종류
        $productCount = Product::count(); // select count(*) from products;

        // 수량이 10 미만인 상품 목록
        $products = Product::where('quantity', '<', 10)->get();

        return view('stats.index', compact('title', 'totalQuantity', 'productCount', 'products'));
    }
}
