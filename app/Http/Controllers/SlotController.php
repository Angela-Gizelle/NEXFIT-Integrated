<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;

class SlotController extends Controller
{
    /**
     * GET /api/slots/available
     *
     * Query params:
     *   service_id  (required)
     *   trainer_id  (optional)
     *   date        (optional, defaults to today)
     */
    public function available(Request $request)
    {
        $query = Schedule::with(['trainer', 'service'])
            ->where('service_id', $request->service_id)
            ->where('is_full', false)
            ->where('is_active', true)
            ->where('date', '>=', $request->date ?? now()->toDateString())
            ->orderBy('date')
            ->orderBy('start_time');

        if ($request->filled('trainer_id')) {
            $query->where('trainer_id', $request->trainer_id);
        }

        $slots = $query->take(20)->get()->map(fn($s) => [
            'id'           => $s->id,
            'date'         => $s->date->format('Y-m-d'),
            'start_time'   => $s->start_time,
            'end_time'     => $s->end_time,
            'trainer_name' => $s->trainer?->name,
            'remaining'    => $s->max_capacity - $s->booked_count,
        ]);

        return response()->json([
            'available' => true,
            'slots'     => $slots,
        ]);
    }
}
