<form action="{{ route('attendance.upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="attendance_file" required>
    <button type="submit">Upload Attendance</button>
</form>
