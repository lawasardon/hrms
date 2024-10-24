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
        $employees = Employee::with('department')->get(['name', 'department_id']);
        $data = [['Name', 'Department', 'Date (YYYY-MM-DD)', 'Attendance Status']];

        foreach ($employees as $employee) {
            $departmentName = $employee->department ? $employee->department->name : '';
            $data[] = [$employee->name, $departmentName, '', ''];
        }

        return $data;
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(20);
                $attendanceDropdownOptions = '"Present,Absent"';

                for ($row = 2; $row <= 100; $row++) {
                    $attendanceCell = 'D' . $row;
                    $event->sheet->getDelegate()->getCell($attendanceCell)
                        ->getDataValidation()
                        ->setType(DataValidation::TYPE_LIST)
                        ->setErrorStyle(DataValidation::STYLE_STOP)
                        ->setAllowBlank(true)
                        ->setFormula1($attendanceDropdownOptions)
                        ->setShowErrorMessage(true)
                        ->setShowDropDown(true);

                    $dateCell = 'C' . $row;
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
