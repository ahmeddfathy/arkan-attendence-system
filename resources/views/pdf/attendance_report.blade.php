<!DOCTYPE html>
<html lang="{{ str_contains($user->name, 'أ') ? 'ar' : 'en' }}" dir="{{ str_contains($user->name, 'أ') ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Report - Arkan Economic Consultancy</title>
    <style>
        @font-face {
            font-family: 'Cairo';
            src: url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap');
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', Arial, sans-serif;
            line-height: 1.6;
            background: #fff;
            color: #1a1a1a;
            font-size: 14px;
        }

        .report-container {
            width: 100%;
            max-width: 1140px;
            margin: 0 auto;
            padding: 20px;
        }

        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 20px;
        }

        .company-logo {
            width: 100px;
            height: auto;
        }

        .company-details {
            text-align: {{ str_contains($user->name, 'أ') ? 'right' : 'left' }};
        }

        .company-details h1 {
            color: #1e3a8a;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .employee-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
        }

        .info-group {
            background: white;
            padding: 15px;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .info-label {
            color: #6b7280;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .info-value {
            color: #111827;
            font-weight: 600;
        }

        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .attendance-table th,
        .attendance-table td {
            padding: 12px;
            text-align: {{ str_contains($user->name, 'أ') ? 'right' : 'left' }};
            border: 1px solid #e5e7eb;
        }

        .attendance-table th {
            background: #1e3a8a;
            color: white;
            font-weight: 600;
            white-space: nowrap;
        }

        .attendance-table tr:nth-child(even) {
            background: #f8fafc;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-present { background: #dcfce7; color: #166534; }
        .status-absent { background: #fee2e2; color: #991b1b; }
        .status-late { background: #fef3c7; color: #92400e; }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .report-container {
                width: 100%;
                max-width: none;
                margin: 0;
                padding: 20px;
            }

            .attendance-table th {
                background-color: #1e3a8a !important;
                color: white !important;
            }

            .status-badge {
                border: 1px solid currentColor;
            }
        }

        @page {
            size: A4 landscape;
            margin: 1cm;
        }
    </style>
</head>
<body>
    <div class="report-container">
        <header class="report-header">
            <div class="logo-container">
                <img src="{{ public_path('images/logo.png') }}" alt="Arkan Logo" class="company-logo">
            </div>
            <div class="company-details">
                <h1>{{ __('Arkan Economic Consultancy') }}</h1>
                <p>{{ __('Riyadh, Kingdom of Saudi Arabia') }}</p>
                <p>{{ __('Phone:') }} +966-XX-XXXXXXX | {{ __('Email:') }} info@arkan.com</p>
            </div>
        </header>

        <div class="employee-info">
            <div class="info-group">
                <div class="info-label">{{ __('Employee Name') }}</div>
                <div class="info-value">{{ $user->name }}</div>
            </div>
            <div class="info-group">
                <div class="info-label">{{ __('Employee ID') }}</div>
                <div class="info-value">{{ $user->employee_id }}</div>
            </div>
            <div class="info-group">
                <div class="info-label">{{ __('Department') }}</div>
                <div class="info-value">{{ $user->department }}</div>
            </div>
            <div class="info-group">
                <div class="info-label">{{ __('Report Period') }}</div>
                <div class="info-value">{{ now()->format('F Y') }}</div>
            </div>
        </div>

        <table class="attendance-table">
            <thead>
                <tr>
                    <th>{{ __('Date') }}</th>
                    <th>{{ __('Day') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Shift') }}</th>
                    <th>{{ __('Shift Hours') }}</th>
                    <th>{{ __('Check In') }}</th>
                    <th>{{ __('Check Out') }}</th>
                    <th>{{ __('Delay (min)') }}</th>
                    <th>{{ __('Early Leave (min)') }}</th>
                    <th>{{ __('Overtime (hrs)') }}</th>
                    <th>{{ __('Penalty') }}</th>
                    <th>{{ __('Notes') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($attendanceRecords as $record)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($record->attendance_date)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($record->attendance_date)->format('l') }}</td>
                    <td>
                        <span class="status-badge status-{{ strtolower($record->status) }}">
                            {{ __($record->status) }}
                        </span>
                    </td>
                    <td>{{ $record->shift }}</td>
                    <td>{{ $record->shift_hours }}</td>
                    <td>{{ $record->entry_time ? \Carbon\Carbon::parse($record->entry_time)->format('h:i A') : '-' }}</td>
                    <td>{{ $record->exit_time ? \Carbon\Carbon::parse($record->exit_time)->format('h:i A') : '-' }}</td>
                    <td>{{ $record->delay_minutes ?: '-' }}</td>
                    <td>{{ $record->early_minutes ?: '-' }}</td>
                    <td>{{ $record->overtime_hours ?: '-' }}</td>
                    <td>{{ $record->penalty ?: '-' }}</td>
                    <td>{{ $record->notes ?: '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
