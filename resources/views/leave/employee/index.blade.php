@extends('layouts.main')

@section('content')
    <div class="content container-fluid" id="leaveForm">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Leave Form</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Leave Form</li>
                    </ul>
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
                        <button type="btn" class="btn btn-primary">Search</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="card-body">

                        <div class="page-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="page-title">My Leaves</h3>
                                </div>
                                <div class="col-auto text-end float-end ms-auto download-grp">
                                    <a href="{{ route('employee.leave.create') }}" class="btn btn-primary"><i
                                            class="fas fa-plus"></i>
                                        Add New</a>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table
                                class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                                <thead class="student-thread">
                                    <tr>
                                        <th>Date Filed</th>
                                        <th>Department</th>
                                        <th>Name</th>
                                        <th>Date Start</th>
                                        <th>Date End</th>
                                        <th>Type of Day</th>
                                        <th>Reason to Leave</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="data in leaveList" :id="data.id">

                                        <td>@{{ data.date_filed }}</td>
                                        <td>@{{ data.department.name }}</td>
                                        <td>@{{ data.name }}</td>
                                        <td>@{{ data.date_start }}</td>
                                        <td>@{{ data.date_end }}</td>
                                        <td>@{{ data.date_end }}</td>
                                        <td>@{{ data.reason_to_leave }}</td>
                                        <td>
                                            <span :class="getStatusClass(data.status)">
                                                @{{ data.status }}
                                            </span>
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
            el: '#leaveForm',
            data: {
                leaveList: [],
            },
            mounted() {
                this.myLeaveList();
            },
            methods: {
                myLeaveList() {
                    axios.get("{{ route('employee.leave.list.data') }}")
                        .then(response => {
                            this.leaveList = response.data.map(leave => {
                                return {
                                    ...leave,
                                    date_filed: new Date(leave.date_filed).toLocaleDateString('en-US', {
                                        year: 'numeric',
                                        month: 'long',
                                        day: 'numeric'
                                    }),
                                    date_start: new Date(leave.date_start).toLocaleDateString('en-US', {
                                        year: 'numeric',
                                        month: 'long',
                                        day: 'numeric'
                                    }),
                                    date_end: new Date(leave.date_end).toLocaleDateString('en-US', {
                                        year: 'numeric',
                                        month: 'long',
                                        day: 'numeric'
                                    }),
                                };
                            });
                        })
                        .catch(error => {
                            console.error('Error fetching leave list', error.response ? error.response.data :
                                error);
                        });
                },

                getStatusClass(status) {
                    switch (status) {
                        case 'pending':
                            return 'badge badge-warning';
                        case 'approved':
                            return 'badge badge-success';
                        case 'rejected':
                            return 'badge badge-danger';
                        default:
                            return '';
                    }
                },
            }
        });
    </script>
@endpush
