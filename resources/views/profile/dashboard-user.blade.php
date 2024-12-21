@extends('layouts.app')




@section('content')



<head>
    <link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
    <style>
        a {
            text-decoration: none;
        }
    </style>
</head>
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
<div class="dashboard-container">
    <div class="dashboard-header">
        <h1 class="text-3xl font-bold mb-2">Welcome, {{ Auth::user()->name }}</h1>
        <p class="text-white/80">Manage your attendance and leaves</p>
    </div>

    <div class="action-cards">
        <div class="action-card">
            <div class="action-icon">
                <i class="fas fa-clock text-white text-2xl"></i>
            </div>
            <h3 class="text-xl font-semibold mb-3">Mark Attendance</h3>
            <p class="text-gray-600 mb-4">Record your attendance for today</p>
            <a href="{{ route('attendances.create') }}" class="btn-dashboard">
                Mark Attendance
            </a>
        </div>

        <div class="action-card">
            <div class="action-icon">
                <i class="fas fa-calendar-plus text-white text-2xl"></i>
            </div>
            <h3 class="text-xl font-semibold mb-3">Mark Leave</h3>
            <p class="text-gray-600 mb-4">Submit a leave request</p>
            <a href="{{ route('leaves.create') }}" class="btn-dashboard">
                Request Leave
            </a>
            <a href="{{ route('user.downloadAttendanceReport',  Auth::user()->employee_id )}}" class="btn btn-primary">Download Attendance Report</a>

        </div>


    </div>
</div>

@if($salaryFiles->count() > 0)
    <div class="container mt-5">
        <h3 class="text-center mb-4">Your Salary Sheets</h3>
        <div class="row justify-content-center">
            @foreach($salaryFiles as $file)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $file->month }}</h5>
                            <p class="card-text">Click below to view your salary sheet for <strong>{{ $file->month }}</strong>.</p>
                            <a href="{{ url('/salary-sheet/' . $file->user_id . '/' . $file->month . '/' . basename($file->file_path)) }}"
                               class="btn btn-primary btn-sm" target="_blank">
                                View Salary Sheet
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@else
    <div class="container mt-5">
        <div class="alert alert-info text-center" role="alert">
            <p class="mb-0">No salary sheets available.</p>
        </div>
    </div>
@endif


@endsection
