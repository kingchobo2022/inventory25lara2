<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockService
{
    public function setInitialStock(Product $product, int $qty): void
    {
        if($qty < 0) {
            throw ValidationException::withMessages(['inital_stock' => '초기 재고는 0 이상이어야 합니다.']);
        }

        // use 명령을 통해서 클로저에서 $product와 $qty 값을 사용할 수 있게 된다.
        // transaction() 스태틱 메소드 안에서 동작하느 DB 작업은 에러 발생시 전체가 롤백이 됩니다.
        DB::transaction(function () use ($product, $qty) {
            // 행 잠금으로 동시성 방지
            $p = Product::whereKey($product->getKey())->lockForUpdate()->first();

            // 총 재고 갱신
            $p->quantity = $qty;
            $p->save();

            // 로그 기록 ( 초기 세팅도 입고 형태로 남김)
            if ($qty > 0) {
                StockLog::create([
                    'product_id' => $p->id,
                    'change_type' => 'in',
                    'change_amount' => $qty
                ]);
            }
        });
    }

    public function adjust(Product $product, string $type, int $amount): void
    {
        if(!in_array($type, ['in', 'out'])) {
            throw ValidationException::withMessages(['action' => 'action은 in/out 이어야 합니다.']);
        }
        if($amount <= 0) {
            throw ValidationException::withMessages(['amount' => '수량은 1 이상이어야 합니다.']);
        }
        DB::transaction(function() use($product, $type, $amount) {
            $p = Product::whereKey($product->getKey())->lockForUpdate()->first();
            $newQty = $type === 'in' ? $p->quantity + $amount : $p->quantity - $amount;
            if($newQty < 0) {
                throw ValidationException::withMessages(['amount' => '현재 재고보다 많은 출고는 불가합니다.']);
            }
            $p->quantity = $newQty;
            $p->save();

            StockLog::create([
                'product_id' => $p->id,
                'change_type' => $type,
                'change_amount' => $amount
            ]);
        });
    }
}
