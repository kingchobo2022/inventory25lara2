<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="products.php">재고관리</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="{{ route('product') }}">상품</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('product.input') }}">상품 추가</a></li>
        <li class="nav-item"><a class="nav-link" href="stock_log.php">입출고 이력</a></li>
        <li class="nav-item"><a class="nav-link" href="stats.php">통계</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('logout') }}">로그아웃</a></li>
      </ul>
    </div>
  </div>
</nav>
