<!DOCTYPE html>
<html>

<head>
    <title>Import Users</title>
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
                Import Users
            </button>
        </div>

        <!-- Modal 1 -->
        <div class="modal fade" id="importModal1" tabindex="-1" role="dialog" aria-labelledby="importModal1Label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModal1Label">Import User </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('user.import') }}" method="post" enctype="multipart/form-data">
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

        <!-- Users Table -->
        <div class="card mt-4">
            <div class="card-header">
                <h4 class="mb-0">User Information</h4>
            </div>
            <div class="card-body">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Employee Number</th>
                            <th>Age</th>
                            <th>Date of Birth</th>
                            <th>National ID Number</th>
                            <th>Phone Number</th>
                            <th>Start Date of Employment</th>
                            <th>Department</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->employee_number }}</td>
                            <td>{{ $user->age }}</td>
                            <td>{{ $user->date_of_birth }}</td>
                            <td>{{ $user->national_id_number }}</td>
                            <td>{{ $user->phone_number }}</td>
                            <td>{{ $user->start_date_of_employment }}</td>
                            <td>{{ $user->department }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">No users available</td>
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
