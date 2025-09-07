@extends('layout')

@section('main')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">📋 상품 목록</h2>
        <a href="product_form.php" class="btn btn-primary">+ 상품 추가</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form class="row g-3 mb-3" method="get">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="상품명 또는 SKU 검색" value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <select name="sort" class="form-select">
                <option value="name" {{ request('sort', 'name') === 'name' ? 'selected' : '' }}>상품명</option>
                <option value="quantity" {{ request('sort')  === 'quantity' ? 'selected' : '' }}>수량</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="order" class="form-select">
                <option value="asc" {{ request('order', 'asc') === 'asc' ? 'selected' : '' }}>오름차순</option>
                <option value="desc" {{ request('order') === 'desc' ? 'selected' : '' }}>내림차순</option>
            </select>
        </div>
        <div class="col-md-1">
            <button class="btn btn-outline-secondary w-100">검색</button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('products.export', request()->query()) }}" class="btn btn-outline-primary w-100">Excel Export</a>
        </div>

    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>상품명</th>
                    <th>SKU</th>
                    <th>수량</th>
                    <th>가격</th>
                    <th>관리</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
				<tr>
					<td>{{ $product->id }}</td>
					<td>{{ $product->name }}</td>
					<td>{{ $product->sku }}</td>
					<td>{{ number_format($product->quantity) }}</td>
					<td>{{ number_format($product->price) }}원</td>
					<td>
						<div class="btn-group btn-group-sm" role="group">
							<a href="{{ route('product.edit', $product) }}" class="btn btn-outline-primary">수정</a>
							<a href="#" class="btn btn-outline-danger btn_del" data-id="{{ $product->id }}">삭제</a>
							<a href="{{ route('stock.input', ['id' => $product->id, 'action' => 'in']) }}" class="btn btn-outline-success">입고</a>
							<a href="{{ route('stock.input', ['id' => $product->id, 'action' => 'out']) }}" class="btn btn-outline-warning">출고</a>
						</div>
					</td>
				</tr>
                @endforeach
            </tbody>
        </table>
    </div>

    

        {{ $products->links() }}

   
</div>	

{{-- 공용 삭제폼 --}}
<form id="deleteForm" method="POST" style="display:none">
    @csrf
    @method('DELETE')
</form>

<script>
const btn_dels = document.querySelectorAll('.btn_del');
btn_dels.forEach( (el) => {
    el.addEventListener("click", function(e) {
        //e.preventDefault();
        //alert(this.dataset.id);
        if(!confirm('정말 삭제하시겠습니까?')) {
            return false;
        }

        const form = document.getElementById('deleteForm');
        form.action = '/product/' + this.dataset.id;
        form.submit();
    });
});    
</script>

@endsection
