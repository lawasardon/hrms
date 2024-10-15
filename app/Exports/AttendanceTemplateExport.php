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
            ['Student ID', 'Student Name', 'Date (YYYY-MM-DD)', 'Attendance Status'],
            ['EX: 12345', 'EX: John Doe', 'EX: 2024-01-01', ''],
            ['EX: 67890', 'EX: Jane Smith', 'EX: 2024-01-01', ''],
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(25);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(20); // Set width for Date column

                // Define the dropdown options for attendance status
                $dropdownOptions = '"Present,Absent"';

                // Apply dropdown to rows 2 to 100 in column D
                for ($row = 2; $row <= 100; $row++) {
                    // Set up attendance status dropdown
                    $attendanceCell = 'D' . $row;
                    $event->sheet->getDelegate()->getCell($attendanceCell)
                        ->getDataValidation()
                        ->setType(DataValidation::TYPE_LIST)
                        ->setErrorStyle(DataValidation::STYLE_STOP)
                        ->setAllowBlank(true)
                        ->setFormula1($dropdownOptions)
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
