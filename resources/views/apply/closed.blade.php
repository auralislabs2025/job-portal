<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Job Closed — {{ $jobPosting->title }} | ABN Corporation</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .apply-body { background: var(--gray-bg); min-height: 100vh; }
        .apply-header { background: var(--navy-deep); color: #fff; padding: 1rem 2rem; display: flex; align-items: center; gap: 1rem; }
        .apply-header svg { flex-shrink: 0; }
        .apply-header h1 { font-size: 1.1rem; font-weight: 700; margin: 0; }
        .apply-header .apply-subtitle { font-size: 0.8rem; color: rgba(255,255,255,0.6); }
        .closed-container { max-width: 600px; margin: 3rem auto; padding: 0 1rem; text-align: center; }
        .closed-icon { font-size: 4rem; color: var(--gray-text); margin-bottom: 1rem; }
        .closed-container h2 { font-size: 1.5rem; color: var(--navy-deep); margin-bottom: 0.5rem; }
        .closed-container .job-title { font-size: 1.1rem; color: var(--blue-corporate); font-weight: 600; margin-bottom: 0.5rem; }
        .closed-container .reason { color: var(--gray-text); margin-bottom: 1.5rem; line-height: 1.6; }
        .closed-container .deadline-info { background: var(--danger-light); color: var(--danger); padding: 0.6rem 1rem; border-radius: var(--radius-sm); display: inline-block; font-size: 0.85rem; margin-bottom: 1.5rem; }
        .closed-actions { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
    </style>
</head>
<body class="apply-body">
    <div class="apply-header">
        <svg width="32" height="32" viewBox="0 0 48 48" fill="none">
            <rect width="48" height="48" rx="8" fill="#C8A94E"/>
            <text x="50%" y="55%" dominant-baseline="middle" text-anchor="middle" fill="#0F1B2D" font-size="16" font-weight="700" font-family="sans-serif">ABN</text>
        </svg>
        <div>
            <h1>ABN Corporation</h1>
            <div class="apply-subtitle">Enterprise Recruitment Management System</div>
        </div>
    </div>

    <div class="closed-container">
        <div class="closed-icon">
            <i class="fa-solid fa-calendar-xmark"></i>
        </div>
        <h2>This Job Posting Has Closed</h2>
        <div class="job-title">{{ $jobPosting->title }}</div>
        <div class="reason">
            We're sorry, but the application period for this position has ended.
            Please check our other open positions for current opportunities.
        </div>
        <div class="deadline-info">
            <i class="fa-solid fa-clock"></i> Deadline was {{ $jobPosting->deadline?->format('F j, Y') ?? 'not specified' }}
        </div>
        <div class="closed-actions">
            <a href="{{ url('/') }}" class="btn btn-outline">
                <i class="fa-solid fa-house"></i> Go to Home
            </a>
        </div>
    </div>
</body>
</html>
