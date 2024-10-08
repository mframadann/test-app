<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

class GreenHouse extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = "greenhouse";
    public $timestamps = false;


    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, "product_id");
    }

    public static function boot()
    {
        parent::boot();

        static::updating(function ($record) {
            if ($record->deadline_for_slaughterhouse_entry && $record->entry_time_to_slaughterhouse) {
                $start = Carbon::parse($record->deadline_for_slaughterhouse_entry);
                $end = Carbon::parse($record->entry_time_to_slaughterhouse);

                $duration = $start->diffInHours($end, false);

                $record->required_duration = $duration;
            }
        });
    }
}
