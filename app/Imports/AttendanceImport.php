<?php

namespace App\Imports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AttendanceImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        \Log::info('Importing row:', $row);

        // Check if required fields are present in the row
        if (isset($row['id_no']) && isset($row['name']) && isset($row['department']) && isset($row['date_yyyy_mm_dd']) && isset($row['time_in']) && isset($row['time_out'])) {
            $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date_yyyy_mm_dd']);
            $timeIn = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['time_in']);
            $timeOut = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['time_out']);

            $lateThreshold = \DateTime::createFromFormat('H:i', '08:10');
            $timeInTimeOnly = \DateTime::createFromFormat('H:i:s', $timeIn->format('H:i:s'));
            $lateThresholdTimeOnly = \DateTime::createFromFormat('H:i:s', $lateThreshold->format('H:i:s'));

            $status = $timeInTimeOnly > $lateThresholdTimeOnly ? 'Late' : 'Not Late';

            return new Attendance([
                'id_number' => $row['id_no'], // Store the ID number directly
                'name' => $row['name'],
                'department' => $row['department'],
                'date' => $date->format('Y-m-d'),
                'time_in' => $timeIn ? $timeIn->format('Y-m-d H:i:s') : null,
                'time_out' => $timeOut ? $timeOut->format('Y-m-d H:i:s') : null,
                'status' => $status,
            ]);
        }

        return null;
    }
}
