<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Attendance</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body {
    margin: 0;
    background: #000;
    font-family: -apple-system, BlinkMacSystemFont, sans-serif;
    color: #fff;
}

/* TOP HEADER */
.header {
    padding: 16px;
    background: #000;
    border-bottom: 2px solid #FFD700;
}

.header h2 {
    margin: 0;
    font-size: 18px;
    color: #FFD700;
}

.header .dates {
    font-size: 13px;
    color: #ccc;
    margin-top: 4px;
}

/* EXPIRED BANNER */
.expired-banner {
    background: #1a1a1a;
    color: #ff4d4d;
    text-align: center;
    padding: 10px;
    font-weight: bold;
    border-bottom: 1px solid #333;
}

/* MONTH */
.month {
    padding: 14px;
}

.month-title {
    font-weight: 700;
    margin-bottom: 10px;
    color: #FFD700;
}

/* CALENDAR GRID */
.weekdays, .days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    text-align: center;
}

.weekdays div {
    font-size: 12px;
    color: #aaa;
    margin-bottom: 6px;
}

/* DAY CELL */
.day {
    height: 42px;
    width: 42px;
    margin: 4px auto;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

/* PRESENT */
.present {
    background: #FFD700;
    color: #000;
    font-weight: 700;
}

/* DISABLED / OUTSIDE RANGE */
.disabled {
    color: #555;
}

/* EXPIRED MEMBERSHIP LOCK */
.expired .day {
    opacity: 0.5;
}
</style>
</head>
<body>

@php
    use Carbon\Carbon;

    $today = Carbon::today();
    $isExpired = $today->gt($endDate);

    $start = $startDate->copy()->startOfMonth();
    $end = $endDate->copy()->endOfMonth();
    $period = \Carbon\CarbonPeriod::create($start, '1 month', $end);
@endphp

<!-- HEADER -->
<div class="header">
    <h2>{{ $member->full_name }}</h2>
    <div class="dates">
        {{ $startDate->format('M d, Y') }} → {{ $endDate->format('M d, Y') }}
    </div>
</div>

@if ($isExpired)
    <div class="expired-banner">
        Membership Expired
    </div>
@endif

<div class="{{ $isExpired ? 'expired' : '' }}">

@foreach ($period as $month)
@php
    $firstDay = $month->copy()->startOfMonth();
    $lastDay = $month->copy()->endOfMonth();
@endphp

<div class="month">
    <div class="month-title">
        {{ $month->format('F Y') }}
    </div>

    <div class="weekdays">
        <div>Sun</div><div>Mon</div><div>Tue</div>
        <div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
    </div>

    <div class="days">
        {{-- Empty slots --}}
        @for ($i = 0; $i < $firstDay->dayOfWeek; $i++)
            <div></div>
        @endfor

        {{-- Days --}}
        @for ($d = 1; $d <= $lastDay->day; $d++)
            @php
                $dateObj = $month->copy()->day($d);
                $date = $dateObj->toDateString();

                $isPresent = in_array($date, $attendedDates);
                $isValid =
                    $dateObj->between($startDate, $endDate);
            @endphp

            <div class="day
                {{ $isPresent ? 'present' : '' }}
                {{ !$isValid ? 'disabled' : '' }}">
                {{ $d }}
            </div>
        @endfor
    </div>
</div>
@endforeach

</div>

</body>
</html>
