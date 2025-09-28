<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class WorkingHour extends Model
{
    protected $fillable = [
        'persona_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    public function isWithinWorkingHours(User $user, Carbon $dateTime): bool
{
    $dayOfWeek = $dateTime->dayOfWeek; // 0 (Sunday) to 6 (Saturday)
    $time = $dateTime->format('H:i:s');

    return $user->workingHours()
        ->where('day_of_week', $dayOfWeek)
        ->where('start_time', '<=', $time)
        ->where('end_time', '>=', $time)
        ->exists();
}
}
