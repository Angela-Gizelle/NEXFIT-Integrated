<?php

namespace App\Console\Commands;

use App\Models\MemberPackage;
use Illuminate\Console\Command;

class ExpireMemberships extends Command
{
    protected $signature   = 'memberships:expire';
    protected $description = 'Mark overdue member_package records as Expired and deactivate members with no remaining active packages.';

    public function handle(): int
    {
        // Step 1 — find all Active packages whose coverage_end is in the past.
        $overduePackages = MemberPackage::where('status', 'Active')
            ->whereDate('coverage_end', '<', now()->toDateString())
            ->get();

        if ($overduePackages->isEmpty()) {
            $this->info('No expired packages found.');
            return self::SUCCESS;
        }

        $packageCount = 0;
        $memberCount  = 0;

        foreach ($overduePackages as $pkg) {
            // Step 2 — expire the package.
            $pkg->update(['status' => 'Expired']);
            $packageCount++;

            // Step 3 — check whether this member still has any Active package.
            $member = $pkg->member;

            // Guard: only process members who actually have packages.
            // Newly-created members with no packages are never touched.
            if (!$member) {
                continue;
            }

            $hasActivePackage = $member->packages()
                ->where('status', 'Active')
                ->exists();

            // Step 4 — if no active packages remain, deactivate the member.
            if (!$hasActivePackage) {
                $member->update(['status' => 'Inactive']);
                $memberCount++;
            }
        }

        $this->info("{$packageCount} package(s) expired. {$memberCount} member(s) set to Inactive.");
        return self::SUCCESS;
    }
}
