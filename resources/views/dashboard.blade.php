@extends('layouts.app')


@section('content')

<head>
    <link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
</head>

    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1 class="text-3xl font-bold mb-2">Welcome, {{ Auth::user()->name }}</h1>
            <p class="text-white/80">Manage your team's attendance and leaves</p>
        </div>

        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
                <div class="stat-value">{{ $totalEmployees }}</div>
                <div class="stat-label">Total Employees</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-check text-white text-xl"></i>
                </div>
                <div class="stat-value">{{ $presentToday }}</div>
                <div class="stat-label">Present Today</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-clock text-white text-xl"></i>
                </div>
                <div class="stat-value">{{ $checkedOutToday }}</div>
                <div class="stat-label">On Leave</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-chart-line text-white text-xl"></i>
                </div>
                <div class="stat-value">{{ $attendanceRate }}%</div>
                <div class="stat-label">Attendance Rate</div>
            </div>
        </div>

        <div class="action-cards">
            <div class="action-card">
                <div class="action-icon">
                    <i class="fas fa-clipboard-list text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3">Manage Attendance</h3>
                <p class="text-gray-600 mb-4">View and manage employee attendance records</p>
                <a href="{{ route('attendances.index') }}" class="btn-dashboard">
                    View Attendance
                </a>
            </div>

            <div class="action-card">
                <div class="action-icon">
                    <i class="fas fa-calendar-alt text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3">Manage Leaves</h3>
                <p class="text-gray-600 mb-4">Handle employee leave requests</p>
                <a href="{{ route('leaves.index') }}" class="btn-dashboard">
                    View Leaves
                </a>
            

            </div>
        </div>
    </div>


@endsection
