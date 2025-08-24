@extends('layout')

@section('main')
<h4>입출고 처리</h4>

    @error('amount')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

<form method="post" action="">
    @csrf
    <input type="hidden" name="action" value="{{ $action }}">
    <div class="mb-3">
        <label>상품명</label>
        <h3>{{ $product->name }}</h3>
    </div>        
    <div class="mb-3">
        <label>수량</label>
        <input type="number" name="amount" class="form-control" min="1" required>
    </div>
    <button class="btn btn-primary">처리</button>
    <a href="/" class="btn btn-secondary">취소</a>
</form>

@endsection