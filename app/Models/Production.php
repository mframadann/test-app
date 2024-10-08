<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Production extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = "production";
    public $timestamps = false;


    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, "product_id");
    }
}
