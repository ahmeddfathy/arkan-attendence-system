@extends('layouts.app')

@section('content')
<div class="attendance-section py-5" data-aos="fade-up">
    <div class="container">
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header bg-gradient text-white p-4">
                <h3 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-calendar-check me-2"></i> Leaves Records
                </h3>
            </div>
            <div class="card-body p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row mb-4">
                    <div class="col-md-6 d-flex align-items-center justify-content-start mb-2 mb-md-0">
                        <a href="{{ route('leaves.create') }}" class="btn btn-primary btn-lg" data-aos="fade-right">
                            <i class="bi bi-plus-circle me-2"></i> Check Out
                        </a>
                    </div>
                    <div class="col-md-6">
                        <div class="search-box" data-aos="fade-left">
                            <input type="text" class="form-control" placeholder="Search records...">
                        </div>
                    </div>
                </div>

                <div class="table-responsive" data-aos="fade-up">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th scope="col">#</th>
                <th scope="col">User</th>
                <th scope="col">Check-in Time</th>
                <th scope="col">Status</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($leaves as $attendance)
                <tr class="align-middle">
                    <td>{{ $attendance->id }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle me-2">
                                {{ substr($attendance->user->name, 0, 1) }}
                            </div>
                            <div class="text-truncate" style="max-width: 150px;">{{ $attendance->user->name }}</div>
                        </div>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i:s') }}</td>
                    <td>
                        <span class="badge bg-success">Present</span>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('leaves.show', $attendance->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                            <form action="{{ route('leaves.destroy', $attendance->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this record?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .attendance-section {
        background-color: #f8f9fa;
    }

    .bg-gradient {
        background: linear-gradient(45deg, #2C3E50, #3498DB);
    }

    .avatar-circle {
        width: 35px;
        height: 35px;
        background-color: #3498DB;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    .search-box {
        max-width: 300px;
    }

    .search-box .form-control {
        border-radius: 20px;
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .btn-group .btn {
        border-radius: 20px;
        margin: 0 2px;
    }

    @media (max-width: 768px) {
        .card-body {
            padding: 1rem;
        }

        .search-box {
            max-width: 100%;
            margin-top: 1rem;
        }

        .btn-lg {
            font-size: 14px;
            padding: 0.75rem 1rem;
        }

        .table {
            font-size: 14px;
        }
    }

    .table-responsive {
    overflow-x: auto; /* السماح بتمرير الجدول عند الأحجام الصغيرة */
}

@media (max-width: 768px) {
    .avatar-circle {
        width: 30px;
        height: 30px;
        font-size: 12px;
    }

    .btn-group .btn {
        font-size: 12px;
        padding: 0.5rem;
    }

    .table th, .table td {
        font-size: 14px;
        white-space: nowrap;
    }
}

</style>
@endpush
@endsection
