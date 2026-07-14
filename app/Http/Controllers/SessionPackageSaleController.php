<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberCreditBalance;
use App\Models\SessionPackage;
use App\Models\SessionPackageSale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Session Management module — REQ-SP-01..07 (Session Package Sales).
 * Gated behind the `auth:staff` guard at the route level.
 */
class SessionPackageSaleController extends Controller
{
    // -------------------------------------------------------------------------
    // Session Package Sales list (REQ-SP-07)
    // -------------------------------------------------------------------------
    public function index(Request $request)
    {
        $query = SessionPackageSale::with(['member', 'sessionPackage', 'processedBy'])
            ->orderByDesc('sale_date')
            ->orderByDesc('sale_time');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->whereHas('member', fn ($mq) => $mq->where('full_name', 'like', "%$s%"))
                  ->orWhere('walkin_name', 'like', "%$s%");
            });
        }
        if ($request->filled('package_id')) {
            $query->where('session_package_id', $request->package_id);
        }
        if ($request->filled('payment_mode')) {
            $query->where('payment_mode', $request->payment_mode);
        }
        if ($request->filled('from')) {
            $query->whereDate('sale_date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('sale_date', '<=', $request->to);
        }

        $sales    = $query->paginate(15)->withQueryString();
        $packages = SessionPackage::active()->orderBy('name')->get();

        // Summary stats
        $todayTotal = SessionPackageSale::whereDate('sale_date', today())->sum('amount_paid');
        $weekTotal  = SessionPackageSale::whereBetween('sale_date', [now()->startOfWeek(), now()->endOfWeek()])->sum('amount_paid');
        $monthTotal = SessionPackageSale::whereMonth('sale_date', now()->month)->whereYear('sale_date', now()->year)->sum('amount_paid');

        return view('package-sales.index', compact('sales', 'packages', 'todayTotal', 'weekTotal', 'monthTotal'));
    }

    // -------------------------------------------------------------------------
    // Record new session package sale form
    // -------------------------------------------------------------------------
    public function create()
    {
        $members  = Member::where('status', 'Active')->orderBy('full_name')->get();
        $packages = SessionPackage::active()->orderBy('program')->orderBy('name')->get();
        return view('package-sales.create', compact('members', 'packages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id'          => 'nullable|exists:members,id',
            'walkin_name'        => 'nullable|string|max:100',
            'session_package_id' => 'required|exists:session_packages,id',
            'pricing_type'       => 'required|in:standard,student,pwd,promo',
            'amount_paid'        => 'required|numeric|min:0',
            'payment_mode'       => 'required|in:cash,gcash,bank_transfer,other',
            'reference_number'   => 'nullable|string|max:100',
            'sale_type'          => 'required|in:new_enrollment,renewal,additional,walkin',
            'sale_date'          => 'required|date',
            'sale_time'          => 'required',
            'notes'              => 'nullable|string|max:500',
        ]);

        if (empty($validated['member_id']) && empty($validated['walkin_name'])) {
            return back()->withErrors(['member_id' => 'Either a member or a walk-in name is required.'])->withInput();
        }

        $package = SessionPackage::findOrFail($validated['session_package_id']);

        DB::transaction(function () use ($validated, $package) {
            SessionPackageSale::create(array_merge($validated, ['processed_by' => Auth::guard('staff')->id()]));

            // Credit the member's balance (REQ-SP-05)
            if (!empty($validated['member_id'])) {
                $balance = MemberCreditBalance::firstOrCreate(
                    ['member_id' => $validated['member_id']],
                    ['credits_purchased' => 0, 'credits_conducted' => 0, 'credits_remaining' => 0, 'credits_forfeited' => 0]
                );
                $balance->addCredits($package->session_credits);
            }
        });

        return redirect()->route('package-sales.index')->with('success', 'Package sale recorded and credits updated.');
    }

    // -------------------------------------------------------------------------
    // Show individual sale / printable receipt (REQ-SP-06)
    // -------------------------------------------------------------------------
    public function show(SessionPackageSale $packageSale)
    {
        $packageSale->load(['member', 'sessionPackage', 'processedBy']);
        return view('package-sales.show', compact('packageSale'));
    }
}
