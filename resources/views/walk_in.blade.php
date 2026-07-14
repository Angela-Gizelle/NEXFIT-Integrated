<!DOCTYPE html>
<html>
<head>
    <title>NexFit - Walk-in Inquiry</title>
    <style>
        body { font-family: Arial; margin: 40px; max-width: 500px; }
        input, select, textarea { width: 100%; padding: 8px; margin-bottom: 12px; }
        button { background: #111; color: white; padding: 12px 25px; border: 0; }
        .banner { padding: 12px; margin-bottom: 20px; }
        .success { background: #f0fdf4; border: 1px solid #16a34a; }
        .intro { color: #555; margin-bottom: 20px; line-height: 1.5; }
    </style>
</head>
<body>

<h1>Walk-in Option</h1>

<p class="intro">
    Prefer to visit first? Leave your name and contact — our staff will
    reach out to schedule you.
</p>

@if(session('success'))
    <div class="banner success">{{ session('success') }}</div>
@endif

<form method="POST" action="/walk-in">
    @csrf

    {{-- Carries the assessment forward so staff can see the member
         already completed the questionnaire, in case they decide
         to follow up using that context. --}}
    <input type="hidden" name="fitness_assessment_id" value="{{ request('assessment_id') }}">

    <input name="full_name"
           placeholder="Full Name"
           value="{{ old('full_name', $prefillName ?? '') }}"
           required>

    <input name="phone" placeholder="Phone Number">
    <input name="email" placeholder="Email">

    <select name="interested_in">
        <option value="">-- Interested In (optional) --</option>
        <option value="Personal Training" @selected(request('interested_in') === 'Personal Training')>Personal Training</option>
        <option value="Pilates" @selected(request('interested_in') === 'Pilates')>Pilates</option>
        <option value="Open Gym Access" @selected(request('interested_in') === 'Open Gym Access')>Open Gym Access</option>
    </select>

    <textarea name="notes" placeholder="Anything else we should know?" rows="3"></textarea>

    <button type="submit">Submit Inquiry</button>
</form>

</body>
</html>
