<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Report - Arkan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* CSS Variables */
        :root {
            --primary-blue: #3BB4E5;
            --primary-dark: #2C3E50;
            --secondary-blue: #2980B9;
            --accent-blue: #E8F4F8;
            --gray-100: #F8F9FA;
            --gray-200: #E9ECEF;
            --gray-800: #343A40;
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
            --radius-md: 0.5rem;
            --radius-lg: 1rem;
            --font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            --spacing-sm: 0.5rem;
            --spacing-md: 1rem;
            --spacing-lg: 1.5rem;
            --spacing-xl: 2rem;
        }

        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Base Styles */
        body {
            font-family: var(--font-family);
            background-color: var(--gray-100);
            color: var(--gray-800);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: var(--spacing-xl);
        }

        /* Header Styles */
        .header {
            background: white;
            border-radius: var(--radius-lg);
            padding: var(--spacing-xl);
            margin-bottom: var(--spacing-xl);
            box-shadow: var(--shadow-md);
        }

        .company-info {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: var(--spacing-xl);
            align-items: center;
        }

        .logo-container {
            padding-right: var(--spacing-xl);
            border-right: 2px solid var(--accent-blue);
        }

        .company-logo {
            width: 180px;
            height: auto;
        }

        .company-details h1 {
            color: var(--primary-dark);
            font-size: 1.75rem;
            margin-bottom: var(--spacing-sm);
        }

        .company-details p {
            color: var(--gray-800);
            margin: var(--spacing-sm) 0;
        }

        /* Card & Report Components */
        .card {
            background: white;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            padding: var(--spacing-lg);
            margin-bottom: var(--spacing-lg);
        }

        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--spacing-xl);
        }

        .report-title {
            color: var(--primary-dark);
            font-size: 1.5rem;
            font-weight: 600;
        }

        .employee-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: var(--spacing-md);
            background: var(--accent-blue);
            border-radius: var(--radius-md);
            padding: var(--spacing-md);
            margin-bottom: var(--spacing-xl);
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 0.875rem;
            color: var(--gray-800);
            margin-bottom: var(--spacing-sm);
        }

        .info-value {
            font-weight: 500;
            color: var(--primary-dark);
        }

        /* Table Styles */
        .table-container {
            overflow-x: auto;
            background: white;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-md);
        }

        .attendance-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .attendance-table th {
            background: var(--primary-blue);
            color: white;
            font-weight: 500;
            text-align: left;
            padding: var(--spacing-md);
            white-space: nowrap;
        }

        .attendance-table tr:nth-child(even) {
            background: var(--gray-100);
        }

        .attendance-table td {
            padding: var(--spacing-md);
            border-bottom: 1px solid var(--gray-200);
        }

        .status {
            display: inline-flex;
            align-items: center;
            padding: calc(var(--spacing-sm)/2) var(--spacing-md);
            border-radius: 1rem;
            font-weight: 500;
            font-size: 0.875rem;
        }

        .status-present {
            background: #E8F5E9;
            color: #2E7D32;
        }

        .status-late {
            background: #FFF3E0;
            color: #E65100;
        }

        .status-absent {
            background: #FFEBEE;
            color: #C62828;
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
            }

            .container {
                padding: 0;
            }

            .attendance-table th {
                background-color: var(--primary-blue) !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .container {
                padding: var(--spacing-md);
            }

            .company-info {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .logo-container {
                padding-right: 0;
                border-right: none;
                border-bottom: 2px solid var(--accent-blue);
                padding-bottom: var(--spacing-md);
                margin-bottom: var(--spacing-md);
            }

            .company-details h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <header class="header">
            <div class="company-info">
                <div class="logo-container">
                    <img src="https://th.bing.com/th/id/OIP.bz3odABZqEOm4oHcNvrL5QHaHa?rs=1&pid=ImgDetMain" alt="Arkan Logo" class="company-logo">
                </div>
                <div class="company-details">
                    <h1>Arkan Economic Consultancy</h1>
                    <p>Riyadh, Kingdom of Saudi Arabia</p>
                    <p>Phone: +966-XX-XXXXXXX | Email: info@arkan.com</p>
                </div>
            </div>
        </header>

        <!-- Report Header -->
        <div class="card">
            <div class="report-header">
                <h2 class="report-title">Attendance Report</h2>

            </div>

            <!-- Employee Information -->
            <div class="employee-info">
                <div class="info-item">
                    <span class="info-label">Employee Name</span>
                    <span class="info-value">{{ $user->name }} </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Employee ID</span>
                    <p>{{ $user->id }}</p>
                </div>
                <div class="info-item">
                    <span class="info-label">Department</span>
                    <span class="info-value">{{ $user->department }}</span>
                </div>

            </div>
        </div>

        <!-- Attendance Table -->
        <div class="table-container">
            <table class="attendance-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Day</th>
                        <th>Status</th>
                        <th>Shift</th>
                        <th>Shift Hours</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Delay (min)</th>
                        <th>Early Leave (min)</th>
                        <th>Overtime (hrs)</th>
                        <th>Penalty</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attendanceRecords as $record)
                    <tr>
                        <td>{{ $record->attendance_date }}</td>
                        <td>{{ $record->day }}</td>
                        <td>
                            <span class="status {{$record->status }}">
                                {{ $record->status }}
                            </span>
                        </td>
                        <td>{{ $record->shift }}</td>
                        <td>{{ $record->shift_hours }}</td>
                        <td>{{ $record->entry_time }}</td>
                        <td>{{ $record->exit_time }}</td>
                        <td>{{ $record->delay_minutes }}</td>
                        <td>{{ $record->early_minutes }}</td>
                        <td>{{ $record->overtime_hours }}</td>
                        <td>{{ $record->penalty }}</td>
                        <td>{{ $record->notes }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
