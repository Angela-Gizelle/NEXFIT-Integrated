<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

// Session Management module — REQ-SI-03 (aggregate credit balance table).
class SessionCreditInventoryController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $members = Member::query()
            ->with('creditBalance')
            ->when($search !== '', function ($query) use ($search) {
                $query->where('full_name', 'like', "%{$search}%");
            })
            ->orderBy('full_name')
            ->get();

        $totals = [
            'purchased' => 0,
            'conducted' => 0,
            'remaining' => 0,
            'forfeited' => 0,
        ];

        foreach ($members as $member) {
            $balance = $member->creditBalance;
            $totals['purchased'] += $balance?->credits_purchased ?? 0;
            $totals['conducted'] += $balance?->credits_conducted ?? 0;
            $totals['remaining'] += $balance?->credits_remaining ?? 0;
            $totals['forfeited'] += $balance?->credits_forfeited ?? 0;
        }

        return view('staff.session-credit-inventory.index', [
            'members' => $members,
            'search'  => $search,
            'totals'  => $totals,
        ]);
    }
}
