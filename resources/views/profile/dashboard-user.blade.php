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
            <a href="{{ route('user.downloadAttendanceReport',  Auth::id()) }}" class="btn btn-primary">Download Attendance Report</a>

        </div>


    </div>
</div>

@endsection
