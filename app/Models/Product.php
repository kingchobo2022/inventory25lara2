<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $guarded = []; 

    static public function searchKeyword()
    {
        $return = self::select('id', 'name', 'sku', 'price', 'quantity', 'created_at');
        return $return->paginate(1);
    }

    public function stockLogs()
    {
        return $this->hasMany(StockLog::class);
    }
    
}
