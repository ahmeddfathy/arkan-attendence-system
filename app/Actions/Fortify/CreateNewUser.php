<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'employee_number' => ['required', 'string', 'max:50', 'unique:users'],
            'age' => ['required', 'integer', 'min:18'],
            'date_of_birth' => ['required', 'date'],
            'national_id_number' => ['required', 'string', 'max:20', 'unique:users'],
            'phone_number' => ['required', 'string', 'max:20'],
            'start_date_of_employment' => ['required', 'date'],
            'last_contract_start_date' => ['nullable', 'date'],
            'last_contract_end_date' => ['nullable', 'date'],
            'job_progression' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
        ])->validate();


        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => bcrypt($input['password']),
            'employee_number' => $input['employee_number'],
            'age' => $input['age'],
            'date_of_birth' => $input['date_of_birth'],
            'national_id_number' => $input['national_id_number'],
            'phone_number' => $input['phone_number'],
            'start_date_of_employment' => $input['start_date_of_employment'],
            'last_contract_start_date' => $input['last_contract_start_date'],
            'last_contract_end_date' => $input['last_contract_end_date'],
            'job_progression' => $input['job_progression'],
            'department' => $input['department'],
        ]);

    }
}
