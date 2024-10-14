@extends('layouts.main')

@section('content')
    <div class="content container-fluid" id="storeLeave">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">File Leave</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="departments.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">File Leave</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form @submit.prevent="submitLeave">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="form-title"><span>Leave Details</span></h5>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label>Date Filed <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" v-model="date_filed" required readonly>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label>Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" v-model="name" required>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label>Date Start <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" v-model="date_start" required>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label>Date End <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" v-model="date_end" required>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label>Type of Day <span class="text-danger">*</span></label>
                                        <select class="form-control" v-model="type_of_day" required>
                                            <option value="" disabled>Select Day</option>
                                            <option value="Whole Day">Whole Day</option>
                                            <option value="Half Day">Half Day AM</option>
                                            <option value="Half Day">Half Day PM</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label>Type of Leave <span class="text-danger">*</span></label>
                                        <select class="form-control" v-model="type_of_leave" required>
                                            <option value="" disabled>Select Type of Leave</option>
                                            <option value="Sick Leave">Sick Leave</option>
                                            <option value="Birthday Leave">Birthday Leave</option>
                                            <option value="Leave Without Pay">Leave Without Pay</option>
                                            <option value="Bereavement Leave">Bereavement Leave</option>
                                            <option value="Vacation Leave">Vacation Leave</option>
                                            <option value="Discretionary Leave">Discretionary Leave</option>
                                            <option value="Maternity Leave">Maternity Leave</option>
                                            <option value="Paternity Leave">Paternity Leave</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label>Reason to Leave <span class="text-danger">*</span></label>
                                        <textarea class="form-control" v-model="reason_to_leave" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="student-submit">
                                        <button type="submit" class="btn btn-primary">Submit</button>
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
            el: '#storeLeave',
            data: {
                myDepartment: null,
                department_id: null,
                name: '',
                date_filed: new Date().toISOString().split('T')[0],
                date_start: '',
                date_end: '',
                type_of_day: '',
                type_of_leave: '',
                reason_to_leave: '',
                status: 'pending',
            },

            mounted() {
                this.getDepartmentId();
            },

            methods: {
                getDepartmentId() {
                    axios.get("{{ route('employee.get.department.id.data') }}")
                        .then(response => {
                            this.myDepartment = response.data;
                            this.department_id = this.myDepartment; // Assign fetched value to department_id
                        })
                        .catch(error => {
                            console.error('Error fetching department ID', error.response ? error.response.data :
                                error);
                        });
                },

                submitLeave() {
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

                    axios.post("{{ route('employee.leave.store') }}", {
                            department_id: this.department_id,
                            name: this.name,
                            date_filed: this.date_filed,
                            date_start: this.date_start,
                            date_end: this.date_end,
                            type_of_day: this.type_of_day,
                            type_of_leave: this.type_of_leave,
                            reason_to_leave: this.reason_to_leave,
                            status: this.status,
                        })
                        .then(response => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Leave submitted successfully',
                                text: response.data.message,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                            }).then(() => {
                                window.location.href = `/leave/list`;
                            });
                        })
                        .catch(error => {
                            console.error('Error submitting leave', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'An error occurred',
                                text: error.response?.data?.message ||
                                    'An error occurred while adding the employee.',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                            });
                        });
                },
            }
        });
    </script>
@endpush

@push('css')
    <style>
        textarea.form-control {
            min-height: 0px;
        }
    </style>
@endpush
