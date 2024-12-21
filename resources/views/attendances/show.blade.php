@extends('layouts.app')

@section('content')
<div class="attendance-show-section py-5" data-aos="fade-up">
    <div class="container">
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header bg-gradient text-white p-4">
                <h3 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-person-badge me-2"></i> Attendance Details
                </h3>
            </div>
            <div class="card-body p-4">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th scope="row">Attendance ID</th>
                            <td>{{ $attendance->id }}</td>
                        </tr>
                        <tr>
                            <th scope="row">User</th>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-2">
                                        {{ substr($attendance->user->name, 0, 1) }}
                                    </div>
                                    {{ $attendance->user->name }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Check-in Time</th>
                            <td>{{ \Carbon\Carbon::parse($attendance->check_in_time)->format('Y-m-d H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Created At</th>
                            <td>{{ $attendance->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Updated At</th>
                            <td>{{ $attendance->updated_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('attendances.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i> Back to List
                    </a>
                
                    <form action="{{ route('attendances.destroy', $attendance->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                            <i class="bi bi-trash"></i> Delete Attendance
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .attendance-show-section {
        background-color: #f8f9fa;
    }

    .bg-gradient {
        background: linear-gradient(45deg, #2C3E50, #3498DB);
    }

    .avatar-circle {
        width: 40px;
        height: 40px;
        background-color: #3498DB;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.2rem;
    }

    table th, table td {
        vertical-align: middle;
    }
</style>
@endpush