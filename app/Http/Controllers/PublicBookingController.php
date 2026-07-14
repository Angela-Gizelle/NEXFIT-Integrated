<?php

namespace App\Http\Controllers;

use App\Models\Service;

class PublicBookingController extends Controller
{
    public function assessment()
    {
        // Set booking_type from URL immediately so the step bar
        // renders correctly BEFORE the form is submitted.
        // ?type=member → 4 steps, ?type=guest → 3 steps
        if (request()->has('type')) {
            session(['booking_type' => request('type')]);
        }

        // If no type in URL but session already has one, keep it.
        // If neither, default to guest.
        if (!session('booking_type')) {
            session(['booking_type' => 'guest']);
        }

        return view('booking.assessment');
    }

    public function openGym()
    {
        $service = Service::where('name', 'Open Gym Access')->first();
        return view('booking.open_gym', compact('service'));
    }
}