<?php

namespace App\Mail;

use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SalaryPaid extends Mailable
{
    use Queueable, SerializesModels;

    public $duration;
    public $salary;
    public $idNumber;
    public $employee;

    public function __construct(Employee $employee, $duration, $salary, $idNumber)
    {
        $this->employee = $employee;
        $this->duration = $duration;
        $this->salary = $salary;
        $this->idNumber = $idNumber;
    }

    public function build()
    {
        return $this->view('emails.salary_paid')
            ->subject("Salary Paid for duration of {$this->duration}")
            ->with([
                'name' => $this->employee->name,
                'idNumber' => $this->idNumber,
                'salary' => $this->salary,
            ]);
    }
}

