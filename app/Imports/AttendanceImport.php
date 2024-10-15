<?php

namespace App\Imports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AttendanceImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Log the incoming row for debugging
        \Log::info('Importing row:', $row);

        // Ensure each key exists in the row before accessing
        if (isset($row['student_id']) && isset($row['student_name']) && isset($row['date_yyyy_mm_dd']) && isset($row['attendance_status'])) {
            // Convert the Excel date serial number to a PHP date
            $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date_yyyy_mm_dd']);

            return new Attendance([
                'student_id' => $row['student_id'],
                'student_name' => $row['student_name'],
                'date' => $date->format('Y-m-d'), // Format to YYYY-MM-DD
                'attendance_status' => $row['attendance_status'],
            ]);
        }

        return null;
    }

}
