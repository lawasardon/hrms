<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class AttendanceTemplateExport implements FromArray, WithEvents
{
    public function array(): array
    {
        return [
            ['Name', 'Department', 'Date (YYYY-MM-DD)', 'Attendance Status'],
            ['John Doe', 'aqua', '2024-01-01', 'Present'],
            ['Jane Smith', 'laminin', '2024-01-01', 'Absent'],
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(25); // Set width for Date column
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(20); // Set width for Department column

                // Define the dropdown options for attendance status
                $attendanceDropdownOptions = '"Present,Absent"';

                // Define the dropdown options for department
                $departmentDropdownOptions = '"aqua,laminin"';

                // Apply dropdowns to rows 2 to 100
                for ($row = 2; $row <= 100; $row++) {
                    // Set up attendance status dropdown in column D
                    $attendanceCell = 'D' . $row;
                    $event->sheet->getDelegate()->getCell($attendanceCell)
                        ->getDataValidation()
                        ->setType(DataValidation::TYPE_LIST)
                        ->setErrorStyle(DataValidation::STYLE_STOP)
                        ->setAllowBlank(true)
                        ->setFormula1($attendanceDropdownOptions)
                        ->setShowErrorMessage(true)
                        ->setShowDropDown(true);

                    // Set up department dropdown in column B
                    $departmentCell = 'B' . $row;
                    $event->sheet->getDelegate()->getCell($departmentCell)
                        ->getDataValidation()
                        ->setType(DataValidation::TYPE_LIST)
                        ->setErrorStyle(DataValidation::STYLE_STOP)
                        ->setAllowBlank(true)
                        ->setFormula1($departmentDropdownOptions)
                        ->setShowErrorMessage(true)
                        ->setShowDropDown(true);

                    // Set date validation for column C
                    $dateCell = 'C' . $row;
                    $event->sheet->getDelegate()->getCell($dateCell)
                        ->getDataValidation()
                        ->setType(DataValidation::TYPE_DATE)
                        ->setErrorStyle(DataValidation::STYLE_STOP)
                        ->setAllowBlank(true)
                        ->setOperator(DataValidation::OPERATOR_BETWEEN)
                        ->setFormula1('DATE(2020,1,1)') // Minimum date
                        ->setFormula2('DATE(2030,12,31)') // Maximum date
                        ->setShowErrorMessage(true)
                        ->setErrorTitle('Invalid Date')
                        ->setError('Please enter a valid date between 2020-01-01 and 2030-12-31.');
                }
            },
        ];
    }
}
