<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;

class ProductExportController extends Controller
{
    public function __invoke()
    {
        return Excel::download(new ProductsExport(), 'products.xlsx');
    }
}
