<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Attendance Records</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .modal-content {
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .card-header {
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: white;
        }

        .table-hover tbody tr:hover {
            background-color: #f1f3f5;
        }

        .table thead {
            background-color: #343a40;
            color: white;
        }

        .alert {
            border-radius: 5px;
        }

        .container {
            max-width: 1200px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <!-- Messages -->
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @elseif(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        <!-- Import Buttons -->
        <div class="d-flex mb-4">
            <button type="button" class="btn btn-primary mr-3" data-toggle="modal" data-target="#importModal1">
                Import Attendance Records
            </button>
        </div>

        <!-- Modal 1 -->
        <div class="modal fade" id="importModal1" tabindex="-1" role="dialog" aria-labelledby="importModal1Label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModal1Label">Import Attendance Records</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('attendance.import') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <input type="file" name="file" class="form-control" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Attendance Table -->
        <div class="card mt-4">
            <div class="card-header">
                <h4 class="mb-0">Attendance Records</h4>
            </div>
            <div class="card-body">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>Employee Number</th>
                            <th>Attendance Date</th>
                            <th>Day</th>
                            <th>Status</th>
                            <th>Shift</th>
                            <th>Shift Hours</th>
                            <th>Entry Time</th>
                            <th>Exit Time</th>
                            <th>Delay Minutes</th>
                            <th>Early Minutes</th>
                            <th>Working Hours</th>
                            <th>Overtime Hours</th>
                            <th>Penalty</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($records as $record)
                        <tr>
                            <td>{{ $record->employee_number }}</td>
                            <td>{{ $record->attendance_date }}</td>
                            <td>{{ $record->day }}</td>
                            <td>{{ $record->status }}</td>
                            <td>{{ $record->shift }}</td>
                            <td>{{ $record->shift_hours }}</td>
                            <td>{{ $record->entry_time }}</td>
                            <td>{{ $record->exit_time }}</td>
                            <td>{{ $record->delay_minutes }}</td>
                            <td>{{ $record->early_minutes }}</td>
                            <td>{{ $record->working_hours }}</td>
                            <td>{{ $record->overtime_hours }}</td>
                            <td>{{ $record->penalty }}</td>
                            <td>{{ $record->notes }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="14" class="text-center">No attendance records available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
