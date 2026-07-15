<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\TrainerSession;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class SchedulingController extends Controller
{
    /** Calendar operating hours / slot size — change these three and the
     *  whole grid (rows, validation, JS) follows automatically. */
    private const DAY_START = '06:00';
    private const DAY_END   = '21:00'; // exclusive — last slot starts at 20:30
    private const SLOT_MINUTES = 30;

    public function index(Request $request)
    {
        $trainers = Trainer::orderBy('name')->get(['id', 'name']);

        $trainerId = (int) $request->query('trainer', $trainers->first()->id ?? 0);

        // Week anchor: any date inside the target week (defaults to today),
        // normalised to that week's Monday so Prev/Next/Today just swap
        // this one query param.
        $anchor = $request->query('week')
            ? Carbon::parse($request->query('week'))
            : Carbon::today();
        $weekStart = $anchor->copy()->startOfWeek(Carbon::MONDAY);
        $weekEnd   = $weekStart->copy()->addDays(6);

        $today = Carbon::today();

        $days = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $weekStart->copy()->addDays($i);
            $days[] = [
                'date'      => $date->toDateString(),
                'label'     => $date->format('D'),
                'dateLabel' => $date->format('M j'),
                'isToday'   => $date->isSameDay($today),
                'isPast'    => $date->lt($today),
            ];
        }

        $slots = $this->buildSlots();

        $members = Member::where('status', 'Active')
            ->orderBy('full_name')
            ->get(['id', 'full_name']);

        $bookings = [];
        if ($trainerId) {
            $sessions = TrainerSession::with('member:id,full_name')
                ->where('trainer_id', $trainerId)
                ->whereBetween('session_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
                ->where('status', 'booked')
                ->get();

            foreach ($sessions as $session) {
                $dateKey = $session->session_date->toDateString();
                $timeKey = Carbon::parse($session->start_time)->format('H:i');
                $bookings[$dateKey][$timeKey] = $session;
            }
        }

        return view('staff.scheduling.index', [
            'trainers'      => $trainers,
            'trainerId'     => $trainerId,
            'members'       => $members,
            'days'          => $days,
            'slots'         => $slots,
            'bookings'      => $bookings,
            'weekStart'     => $weekStart,
            'weekEnd'       => $weekEnd,
            'prevWeek'      => $weekStart->copy()->subWeek()->toDateString(),
            'nextWeek'      => $weekStart->copy()->addWeek()->toDateString(),
            'todayWeek'     => Carbon::today()->startOfWeek(Carbon::MONDAY)->toDateString(),
            'slotMinutes'   => self::SLOT_MINUTES,
        ]);
    }

    public function store(Request $request)
    {
        $slotTimes = collect($this->buildSlots())->pluck('time')->all();

        $validated = $request->validate([
            'trainer_id'   => ['required', Rule::exists('trainers', 'id')],
            'member_id'    => ['required', Rule::exists('members', 'id')],
            'session_date' => ['required', 'date'],
            'start_time'   => ['required', 'date_format:H:i', Rule::in($slotTimes)],
            'notes'        => ['nullable', 'string', 'max:500'],
        ]);

        $start = Carbon::createFromFormat('H:i', $validated['start_time']);
        $end   = $start->copy()->addMinutes(self::SLOT_MINUTES);

        $exists = TrainerSession::where('trainer_id', $validated['trainer_id'])
            ->where('session_date', $validated['session_date'])
            ->where('start_time', $start->format('H:i:s'))
            ->where('status', 'booked')
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'That slot was just booked by someone else. Please pick another.',
            ], 422);
        }

        $session = TrainerSession::create([
            'trainer_id'   => $validated['trainer_id'],
            'member_id'    => $validated['member_id'],
            'session_date' => $validated['session_date'],
            'start_time'   => $start->format('H:i:s'),
            'end_time'     => $end->format('H:i:s'),
            'status'       => 'booked',
            'notes'        => $validated['notes'] ?? null,
        ])->load('member:id,full_name');

        return response()->json([
            'message' => 'Session booked.',
            'session' => [
                'id'          => $session->id,
                'member_name' => $session->member->full_name ?? 'Member',
                'notes'       => $session->notes,
                'start_label' => $session->start_label,
                'end_label'   => $session->end_label,
                'date'        => $session->session_date->toDateString(),
                'time'        => $start->format('H:i'),
            ],
        ]);
    }

    public function destroy(TrainerSession $trainerSession)
    {
        $trainerSession->delete();

        return response()->json(['message' => 'Session cancelled.']);
    }

    /**
     * @return array<int, array{time: string, label: string}>
     */
    private function buildSlots(): array
    {
        $slots = [];
        $cursor = Carbon::createFromFormat('H:i', self::DAY_START);
        $end    = Carbon::createFromFormat('H:i', self::DAY_END);

        while ($cursor->lt($end)) {
            $slots[] = [
                'time'   => $cursor->format('H:i'),
                'label'  => $cursor->format('g:i A'),
                'isHour' => $cursor->minute === 0,
            ];
            $cursor->addMinutes(self::SLOT_MINUTES);
        }

        return $slots;
    }
}
