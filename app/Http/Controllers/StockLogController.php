<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class StockLogController extends Controller
{
    public function Input(Request $request, $id)
    {
        // queryString action 의 default value 는 in 으로 한다.
        $action = $request->query('action', 'in');

        if (!in_array($action, ['in', 'out'])) {
            abort(400, '잘못된 action 값입니다.');
        }
        $title = ($action == 'in') ? '입고 등록' : '출고 등록';

        // Product 조회 ( or 404 )
        $product = Product::findOrFail($id);

        // Blade 뷰에 Product 전달
        return view('stock.input', compact('product', 'title', 'action'));
    }
}
