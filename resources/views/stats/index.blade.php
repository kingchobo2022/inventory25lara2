@extends('layout')

@section('main')
    <div class="py-4">
		<h2 class="mb-4 text-center">📊 재고 통계</h2>

		<div class="row mb-4">
			<div class="col-md-6 mb-3">
				<div class="card shadow-sm border-start border-4 border-primary">
					<div class="card-body">
						<h5 class="card-title">총 상품 수량</h5>
						<p class="display-5 fw-bold text-primary mb-0">{{ $totalQuantity }}</p>
					</div>
				</div>
			</div>
			<div class="col-md-6 mb-3">
				<div class="card shadow-sm border-start border-4 border-danger">
					<div class="card-body">
						<h5 class="card-title">총 상품 종류</h5>
						<p class="display-5 fw-bold text-danger mb-0">{{ $productCount }}</p>
					</div>
				</div>
			</div>
		</div>

		<div class="card shadow-sm">
			<div class="card-header bg-white">
				<h5 class="mb-0">⚠️ 재고 부족 상품 (10개 미만)</h5>
			</div>
			<div class="table-responsive">
				<table class="table table-hover align-middle mb-0">
					<thead class="table-light">
						<tr>
							<th>상품명</th>
							<th>SKU</th>
							<th>수량</th>
						</tr>
					</thead>
					<tbody>
                        @forelse($products as $product)
						<tr>
							<td>{{ $product->name }}</td>
							<td>{{ $product->sku }}</td>
							<td><span class="badge bg-warning text-dark">{{ $product->quantity }}</span></td>
						</tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">✅ 모든 상품 재고가 충분합니다.</td>
                        </tr>                            
                        @endforelse
					</tbody>
				</table>
			</div>
		</div>
	</div>


@endsection
