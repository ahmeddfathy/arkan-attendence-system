<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        .header { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Attendance Report</h2>
        <p>User: {{ $user->name }} | ID: {{ $user->id }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Day</th>
                <th>Status</th>
                <th>Shift</th>
                <th>Shift Hours</th>
                <th>Entry Time</th>
                <th>Exit Time</th>
                <th>Delay (min)</th>
                <th>Early (min)</th>
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
                    <td>{{ $record->status }}</td>
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
</body>
</html>
