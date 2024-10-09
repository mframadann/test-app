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
            if ($record->spk_creation_date) {
                $start = Carbon::parse($record->spk_creation_date);
                $end = Carbon::now();

                $durationInSeconds = $start->diffInSeconds($end);

                if ($durationInSeconds < 0) {
                    $durationInSeconds = 0;
                }

                $hours = floor($durationInSeconds / 3600);
                $minutes = floor(($durationInSeconds % 3600) / 60);
                $seconds = $durationInSeconds % 60;

                $formattedDuration = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

                $record->required_duration = $formattedDuration;
            }
        });
    }
}
