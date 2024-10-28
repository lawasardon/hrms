<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class AttendanceTemplateExport implements FromArray, WithEvents
{
    public function array(): array
    {
        // Fetch employees with their department information
        $employees = Employee::with('department')->get(['id_number', 'name', 'department_id']);

        // Create the header row for the export
        $data = [['ID No.', 'Name', 'Department', 'Date (YYYY-MM-DD)', 'Time In', 'Time Out']];

        // Populate data rows with employee information
        foreach ($employees as $employee) {
            $departmentName = $employee->department ? $employee->department->name : '';
            $data[] = [$employee->id_number, $employee->name, $departmentName, '', '06:00 AM', '06:00 AM'];
        }

        return $data;
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                // Set column widths
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(25); // Adjusted width for date column
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(15); // Added for Time Out column

                // Set number format for time columns
                $event->sheet->getDelegate()->getStyle('E2:E100')->getNumberFormat()->setFormatCode('hh:mm AM/PM');
                $event->sheet->getDelegate()->getStyle('F2:F100')->getNumberFormat()->setFormatCode('hh:mm AM/PM');

                // Apply data validation for time and date fields
                for ($row = 2; $row <= 100; $row++) {
                    $timeInCell = 'E' . $row; // Updated column reference for Time In
                    $timeOutCell = 'F' . $row; // Updated column reference for Time Out
                    $dateCell = 'D' . $row; // Date column

                    // Time In validation
                    $event->sheet->getDelegate()->getCell($timeInCell)
                        ->getDataValidation()
                        ->setType(DataValidation::TYPE_TIME)
                        ->setErrorStyle(DataValidation::STYLE_STOP)
                        ->setAllowBlank(true)
                        ->setShowErrorMessage(true)
                        ->setErrorTitle('Invalid Time')
                        ->setError('Please enter a valid time in HH:MM format.');

                    // Time Out validation
                    $event->sheet->getDelegate()->getCell($timeOutCell)
                        ->getDataValidation()
                        ->setType(DataValidation::TYPE_TIME)
                        ->setErrorStyle(DataValidation::STYLE_STOP)
                        ->setAllowBlank(true)
                        ->setShowErrorMessage(true)
                        ->setErrorTitle('Invalid Time')
                        ->setError('Please enter a valid time in HH:MM format.');

                    // Date validation
                    $event->sheet->getDelegate()->getCell($dateCell)
                        ->getDataValidation()
                        ->setType(DataValidation::TYPE_DATE)
                        ->setErrorStyle(DataValidation::STYLE_STOP)
                        ->setAllowBlank(true)
                        ->setOperator(DataValidation::OPERATOR_BETWEEN)
                        ->setFormula1('DATE(2020,1,1)')
                        ->setFormula2('DATE(2030,12,31)')
                        ->setShowErrorMessage(true)
                        ->setErrorTitle('Invalid Date')
                        ->setError('Please enter a valid date between 2020-01-01 and 2030-12-31.');
                }
            },
        ];
    }
}
