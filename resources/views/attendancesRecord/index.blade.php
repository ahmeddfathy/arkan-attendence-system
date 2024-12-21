@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<head>
    <link rel="stylesheet" href="{{asset('css/user.css')}}">
</head>
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



        <!-- Employee Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('attendance.index') }}" method="GET" class="form-inline">
                <div class="form-group">
        <input type="text"
               list="employees-list"
               id="employee-search"
               class="form-control"
               placeholder="اختر الموظف..."
               value="{{ $selectedEmployeeName }}"
               onchange="updateEmployeeFilter(this)">

        <datalist id="employees-list">
            @foreach($employees as $employee)
                <option data-value="{{ $employee->employee_id }}" value="{{ $employee->name }}">
            @endforeach
        </datalist>

        <input type="hidden"
               name="employee_filter"
               id="employee_filter"
               value="{{ request('employee_filter') }}">
    </div>
                    <button type="submit" class="btn btn-primary mb-2 ml-2">Filter</button>
                    @if(request('employee_filter'))
                    <a href="{{ route('attendance.index') }}" class="btn btn-secondary mb-2 ml-2">Clear Filter</a>
                    @endif
                </form>
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
                            <td>{{ $record->employee_id }}</td>


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

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $records->links() }}
                </div>
            </div>
        </div>
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

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function updateEmployeeFilter(input) {
    const datalist = document.getElementById('employees-list');
    const options = datalist.getElementsByTagName('option');
    const hiddenInput = document.getElementById('employee_filter');

    for(let option of options) {
        if(option.value === input.value) {
            hiddenInput.value = option.getAttribute('data-value');
            // Submit the form
            document.getElementById('filter-form').submit();
            break;
        }
    }

    // Clear filter if input is empty
    if(input.value === '') {
        hiddenInput.value = '';
        document.getElementById('filter-form').submit();
    }
}
    </script>
    @endsection
