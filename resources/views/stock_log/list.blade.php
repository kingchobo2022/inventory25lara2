@extends('layout')

@section('main')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">📋 입출고 이력</h2>
        <a href="product_form.php" class="btn btn-primary">+ 상품 추가</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>일시</th>
                    <th>상품(SKU)</th>
                    <th>입출고</th>
                    <th>수량</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
				<tr>
					<td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
					<td>{{ ($log->product?->name ?? '삭제된 상품') }} ({{ ($log->product?->sku ?? '-')  }})</td>
					<td>{{ $log->change_type }}</td>
					<td>{{ $log->change_amount }}</td>
				</tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flext justify-content-center mt-4">
        {{ $logs->links() }}
    </div>
  
</div>	

@endsection
