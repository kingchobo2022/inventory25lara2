<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function Index(Request $request) {

        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'sort' => ['nullable', Rule::in(['name', 'quantity'])],
            'order' => ['nullable', Rule::in(['asc', 'desc'])],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:200'],
        ]);

        $search = $validated['search'] ?? null;
        $sort = $validated['sort'] ?? 'name';
        $order = $validated['order'] ?? 'asc';
        $perPage = $validated['per_page'] ?? 2;
        
        $title = '상품목록';
        $products = Product::searchKeyword($search, $sort, $order, $perPage);
        return view('product.list', compact('title', 'products'));
    }
    public function Input() {
        $title = '상품추가';
        return view('product.input', compact('title'));
    }

    public function Edit(Product $product) {
        $title = '상품수정';
        return view('product.edit', compact('product','title'));
    }

    public function Update(Request $request, Product $product)
    {
        // 1. 입력값 검증
        $request->validate([
            'name' => 'required|string',
            'sku' => 'required|string|unique:products,sku,' . $product->id, // input과 달리 수정에서는 자기 자신 제외가 추가된다는 게 중요하죠!!
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // 2. 데이터 업데이트
        $product->name = $request->input('name');
        $product->sku = $request->input('sku');
        $product->price = $request->input('price');

        // 3. 이미지 처리
        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
                // Storage 파사드를 이용해서 삭제.
                // 향후, 저장 장치가 변하더라도 코드 수정없이 유연하게 대응할 수가 있다.
            }
            $path = $request->file('image')->store('uploads', 'public');
            // $path = Storage::disk('public')->putFile('uploads', $request->file('image'));
            // 주석과 같이 동작한다고 보면 됩니다.
            $product->image = $path;
        }
        // 4. 저장
        $product->save();

        // 5. 결과 응답
        return redirect()->route('product')->with('success', '상품이 수정됩니다.');
    }

    public function Destroy($id) {
        $product = Product::findOrFail($id);

        // 이미지 파일이 존재하면 삭제
        if(!empty($product->image)){
            // uplads/.. 형태 그대로 public 디스크에서 삭제
            Storage::disk('public')->delete($product->image);    
        }
        // DB row 삭제
        $product->delete();

        return redirect()->route('product')->with('success', '상품이 삭제되었습니다.');
    }

    public function Store(Request $request, StockService $stock) {
        // if (empty($request->input('name'))) {
        //     return redirect()->back()->withInput()->with('error', '상품명은 필수입력 값입니다.');
        // }

        $request->validate([
            'name' => 'required|unique:products,name',
            'sku' => 'required|string|unique:products,sku',
            'quantity' => 'required|numeric',
            'price' => 'required|numeric',
        ], [
            'name.required' => '상품명은 필수입력항목입니다.',
            'name.unique' => '이미 등록된 상품명입니다.',
            'sku.required' => 'SKU는 필수입력항목입니다.',
            'sku.string' => 'SKU는 문자열이어야 합니다.',
            'sku.unique' => '이미 등록된 SKU입니다.',
            'quantity.required' => '수량은 필수입력항목입니다.',
            'quantity.numeric' => '수량은 숫자여야 합니다.',
            'price.required' => '가격은 필수입력항목입니다.',
            'price.numeric' => '가격은 숫자여야 합니다.',
        ]);

        $arr = [
            'name' => $request->input('name'),
            'sku' => $request->input('sku'),
            'quantity' => 0, // 상품 재고는 0으로 생성
            'price' => $request->input('price'),
        ];

        // 상품 이미지를 선택한 경우 (업로드한 경우)
        if(!empty($request->file('image'))) {
            $request->validate(['image' => 'required|file|mimes:jpg,jpeg,gif,png|max:4096']); // 4MB 이하 파일만 허용.
            $path = $request->file('image')->store('uploads', 'public');
            $arr['image'] = $path;
        }

        // 대량할당
        $product = Product::create($arr);

        // 초기 재고가 있으면 세팅 + 입출고 기록
        if (isset($request->quantity) && $request->quantity > 0) {
            $stock->setInitialStock($product, (int) $request->quantity);
        }

        return redirect()->route('dashboard')->with('success', '등록되었습니다.');
    }
}
