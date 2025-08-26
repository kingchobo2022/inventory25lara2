<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockLog extends Model
{
    protected $table = 'stock_logs';

    protected $guarded = [];

    protected $casts = [
        'change_amount' => 'integer',
        'created_at' => 'datetime',  
        'updated_at' => 'datetime',
    ];

    // 최신순으로 정렬하는 scope
    public function scopeLatestFirst($query)
    {
        return $query->orderByDesc('created_at')->orderByDesc('id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
