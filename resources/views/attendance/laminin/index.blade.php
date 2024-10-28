@extends('layouts.main')
@section('content')
    <div class="content container-fluid" id="allEmployeeListOfLaminin">

        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Laminin Attendance</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="students.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Laminin Attendance</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="student-group-form">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search by ID ...">
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search by Name ...">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search by Phone ...">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="search-student-btn">
                        <button type="button" class="btn btn-primary">Search</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table comman-shadow">
                    <div class="card-body">

                        {{-- <div class="page-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="page-title">All Employee</h3>
                                </div>
                                <div class="col-auto text-end float-end ms-auto download-grp">
                                    <a href="{{ route('attendance.show.upload.page') }}" class="btn btn-primary"><i
                                            class="fas fa-plus"></i> Upload Attendance</a>
                                    <a href="{{ route('attendance.downloadable.template') }}"
                                        class="btn btn-outline-primary me-2"><i class="fas fa-download"></i>
                                        Download Attendace Template</a>
                                </div>
                            </div>
                        </div> --}}

                        <div class="table-responsive">
                            <table
                                class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                                <thead class="student-thread">
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Department</th>
                                        <th>Date</th>
                                        <th>Time In</th>
                                        <th>Time Out</th>
                                        <th>Status</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="data in employeeList" :key="data.id">
                                        <td>@{{ data.id_number }}</td>
                                        <td>
                                            <h2 class="table-avatar">
                                                {{-- <a href="student-details.html" class="avatar avatar-sm me-2"><img
                                                        class="avatar-img rounded-circle"
                                                        src="assets/img/profiles/avatar-01.jpg" alt="User Image"></a> --}}
                                                <a href="student-details.html">@{{ data.name }}</a>
                                            </h2>
                                        </td>
                                        <td>@{{ data.department }}</td>
                                        <td>@{{ formatDate(data.date) }}</td>
                                        <td>@{{ data.time_in }}</td>
                                        <td>@{{ data.time_out }}</td>
                                        <td>
                                            <span :class="getAttendanceStatus(data.attendance_status)">
                                                @{{ data.attendance_status }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <div class="actions">
                                                <a href="javascript:;" class="btn btn-sm bg-success-light me-2">
                                                    <i class="feather-eye"></i>
                                                </a>
                                                <a href="edit-student.html" class="btn btn-sm bg-danger-light">
                                                    <i class="feather-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('js')
    <script>
        new Vue({
            el: '#allEmployeeListOfLaminin',
            data: {
                employeeList: [],
            },
            mounted() {
                this.allEmployeeOfAqua();
            },
            methods: {
                allEmployeeOfAqua() {
                    axios.get("{{ route('attendance.list.laminin.data') }}")
                        .then(response => {
                            this.employeeList = response.data;
                        })
                        .catch(error => {
                            console.error('Error fetching employee', error.response ? error.response.data :
                                error);
                        });
                },
                getAttendanceStatus(status) {
                    switch (status) {
                        case 'Not Late':
                            return 'badge badge-success';
                        case 'Late':
                            return 'badge badge-danger';
                        default:
                            return '';
                    }
                },
                formatDate(dateString) {
                    const options = {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    };
                    return new Date(dateString).toLocaleDateString('en-US', options);
                },
            }
        });
    </script>
@endpush
