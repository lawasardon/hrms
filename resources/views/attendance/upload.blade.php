@extends('layouts.main')

@section('content')
    <div class="content container-fluid" id="uploadAttendance">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Upload Attendance</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="departments.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Upload Attendance</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form @submit.prevent="saveEmployee" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="form-title"><span>Upload Attendance</span></h5>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label>Date Filed <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" @change="handleFileUpload" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="student-submit">
                                        <button type="submit" class="btn btn-primary">Upload</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        new Vue({
            el: '#uploadAttendance',
            data() {
                return {
                    attendance_file: null,
                };
            },
            methods: {
                handleFileUpload(event) {
                    this.attendance_file = event.target.files[0];
                },
                saveEmployee() {
                    if (!this.attendance_file) {
                        Swal.fire({
                            icon: 'error',
                            title: 'No file selected',
                            text: 'Please select a file to upload.',
                        });
                        return;
                    }

                    const formData = new FormData();
                    formData.append('attendance_file', this.attendance_file);

                    Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    axios.post("{{ route('attendance.upload') }}", formData)
                        .then(response => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Attendance Uploaded successfully',
                                text: response.data.message,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                            }).then(() => {
                                window.location.href = `/attendance/list/all/employee`;
                            });
                        })
                        .catch(error => {
                            console.error('Error uploading attendance', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'An error occurred',
                                text: error.response?.data?.message ||
                                    'An error occurred while uploading attendance.',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                            });
                        });
                }
            }
        });
    </script>
@endpush
