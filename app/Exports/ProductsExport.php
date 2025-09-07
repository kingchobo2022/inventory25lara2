<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromQuery, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        $q = Product::query()
            ->select('name','sku','quantity','price');
        if(request()->filled('search')) {
            $search = request('search');
            $q->where('name', 'like',"%{$search}%");
            if(request()->filled('sort') && request()->filled('order')) {
                $sort = request('sort');
                $order = request('order');
                $q->orderBy($sort, $order);
            }

        }

        return $q;            
    }


    public function headings(): array
    {
        return ['상품명','SKU', '수량', '가격'];
    }
}
