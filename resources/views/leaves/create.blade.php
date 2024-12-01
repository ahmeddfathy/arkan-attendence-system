@extends('layouts.app')

@section('content')
<div class="create-attendance-section py-5" data-aos="fade-up">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-gradient text-white p-4">
                        <h3 class="mb-0 d-flex align-items-center">
                            <i class="bi bi-plus-circle me-2"></i>
                            New Attendance Record
                        </h3>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('leaves.store') }}" method="POST" class="needs-validation" novalidate>
                            @csrf
                            <div class="mb-4">
                                <label for="user_id" class="form-label">Select Employee</label>
                                <select name="user_id" id="user_id" class="form-select form-select-lg" required>
                                    <option value="">Choose an employee...</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Please select an employee.
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="check_in_time" class="form-label">Check-in Time</label>
                                <input type="datetime-local"
                                       name="check_in_time"
                                       id="check_in_time"
                                       class="form-control form-control-lg"
                                       disabled>
                                <small class="text-muted">Current time will be used automatically</small>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-check-circle me-2"></i>Save Leave
                                </button>
                                <a href="{{ route('leaves.index') }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="bi bi-arrow-left me-2"></i>Back to List
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .create-attendance-section {
        background-color: #f8f9fa;
        min-height: calc(100vh - 76px);
    }

    .bg-gradient {
        background: linear-gradient(45deg, #2C3E50, #3498DB);
    }

    .card {
        transition: all 0.3s ease;
    }

    .form-label {
        font-weight: 500;
        color: #2C3E50;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border-radius: 10px;
        padding: 0.75rem 1rem;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #3498DB;
        box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
    }

    .btn {
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: linear-gradient(45deg, #2C3E50, #3498DB);
        border: none;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
    }

    .btn-outline-secondary:hover {
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .card {
            margin: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Form validation
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>
@endpush
