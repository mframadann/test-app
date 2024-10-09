<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SlaughterHouse extends Model
{
    use HasFactory;
    public $timestamps = false;
    use HasUuids;

    protected $table = "slaughter_house";

    public static function boot()
    {
        parent::boot();

        static::updating(function ($record) {
            if ($record->entry_time_to_slaughterhouse && $record->finish_time_of_slaughter) {
                $start = Carbon::parse($record->entry_time_to_slaughterhouse);
                $end = Carbon::parse($record->finish_time_of_slaughter);

                $durationInSeconds = $start->diffInSeconds($end);

                if ($durationInSeconds < 0) {
                    $durationInSeconds = 0;
                }

                $hours = floor($durationInSeconds / 3600);
                $minutes = floor(($durationInSeconds % 3600) / 60);
                $seconds = $durationInSeconds % 60;

                $formattedDuration = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

                $record->duration_of_slaughter = $formattedDuration;
            }
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, "product_id");
    }
}
