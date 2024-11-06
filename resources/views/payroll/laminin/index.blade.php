@extends('layouts.main')

@section('content')
    <div class="content container-fluid" id="lamininPayroll">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Laminin Payroll Details</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Laminin Payroll Details</li>
                    </ul>
                </div>
            </div>
        </div>

        <x-modal submitMethod="storePayroll" modalId="payrollModal" title="Laminin Payroll Details" submitId="submitEdit"
            submitText="Save Changes">
            <form>

                <div class="form-group">
                    <label class="col-form-label">Id:</label>
                    <input type="text" class="form-control" v-model="employeePayroll.employee_id" disabled>
                </div>

                <div class="form-group">
                    <label class="col-form-label">Id Number:</label>
                    <input type="text" class="form-control" v-model="employeePayroll.id_number" disabled>
                </div>

                <div class="form-group">
                    <label class="col-form-label">Overtime Earnings:</label>
                    <input type="text" class="form-control" v-model="employeePayroll.overtime_earnings" disabled>
                </div>


                <div class="form-group">
                    <label class="col-form-label">Name:</label>
                    <input type="text" class="form-control" v-model="employeePayroll.name" disabled>
                </div>

                <div class="form-group">
                    <label class="col-form-label">Duration:</label>
                    <input type="text" class="form-control" v-model="employeePayroll.duration" disabled>
                </div>

                <div class="form-group">
                    <label class="col-form-label">Hours Overtime:</label>
                    <input type="number" class="form-control" v-model="employeePayroll.over_time">
                </div>

                <div class="form-group">
                    <label>Payroll Status <span class="text-danger">*</span></label>
                    <select class="form-control" v-model="employeePayroll.status" required>
                        <option value="" disabled>Select Payroll Status</option>
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                        <option value="hold">hold</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="col-form-label">Salary:</label>
                    <input type="text" class="form-control" v-model="employeePayroll.salary" disabled>
                </div>
            </form>
        </x-modal>

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table
                                class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                                <thead class="student-thread">
                                    <tr align="center">
                                        <th>Id</th>
                                        <th>Department</th>
                                        <th>Name</th>
                                        <th>Monthly Rate</th>
                                        <th>Rate Perday</th>
                                        <th>Duration</th>
                                        <th>Working Days</th>
                                        <th>Hours Overtime</th>
                                        <th>Government Deduction</th>
                                        <th>Late</th>
                                        <th>Loan</th>
                                        <th>Salary</th>
                                        <th>Status</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="data in payrollData" :id="data.id">
                                        <td>@{{ data.id_number }}</td>
                                        <td>@{{ data.department_id == 1 ? 'Aqua' : 'Laminin' }}</td>
                                        <td>@{{ data.name }}</td>
                                        <td>@{{ data.monthly_rate }}</td>
                                        <td>@{{ data.rate_perday }}</td>
                                        <td>@{{ data.duration }}</td>
                                        <td>@{{ data.total_working_days }}</td>
                                        <td>@{{ data.over_time }}</td>
                                        <td>@{{ data.total_gov_deduction }}</td>
                                        <td>@{{ data.late }}</td>
                                        <td>@{{ data.loan }}</td>
                                        <td>@{{ data.salary }}</td>
                                        <td>
                                            <span :class="getStatusClass(data.status)">
                                                @{{ data.status }}
                                            </span>
                                        </td>
                                        <td class="text-end" :hidden="data.status === 'paid'">
                                            <div class="actions">
                                                <a href="javascript:;" class="btn btn-sm bg-danger-light"
                                                    @click="openEditModal(data)">
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
            el: '#lamininPayroll',
            data: {
                payrollData: [],
                employeePayroll: {
                    employee_id: '',
                    id_number: '',
                    name: '',
                    salary: 0,
                    status: '',
                    over_time: 0,
                    rate_perday: 0,
                    original_salary: 0,
                },

            },
            mounted() {
                this.allLamininPayroll();
            },
            watch: {
                'employeePayroll.over_time': function(newValue) {
                    this.calculateSalaryWithOvertime(newValue);
                }
            },
            methods: {
                allLamininPayroll() {
                    axios.get("{{ route('laminin.payroll.calculation') }}")
                        .then(response => {
                            this.payrollData = response.data;
                        })
                        .catch(error => {
                            console.error('Error fetching payroll', error.response ? error.response.data :
                                error);
                        });
                },
                openEditModal(data) {
                    this.employeePayroll = {
                        ...data,
                        original_salary: data.salary,
                        over_time: data.over_time || 0
                    };
                    $('#payrollModal').modal('show');
                },
                // calculateSalaryWithOvertime(overtime) {
                //     if (!overtime || overtime === 0) {
                //         this.employeePayroll.salary = this.employeePayroll.original_salary;
                //         return;
                //     }
                //     const hourlyRate = this.employeePayroll.rate_perday / 8;
                //     const overtimeHourlyRate = hourlyRate * 1.25;
                //     const overtimePay = overtimeHourlyRate * overtime;
                //     this.employeePayroll.salary = Number(this.employeePayroll.original_salary) + overtimePay;
                //     this.employeePayroll.salary = Math.round(this.employeePayroll.salary * 100) / 100;
                // },

                calculateSalaryWithOvertime(overtime) {
                    if (!overtime || overtime === 0) {
                        this.employeePayroll.salary = this.employeePayroll.original_salary;
                        this.employeePayroll.overtime_earnings = 0; // Set to 0 if no overtime
                        return;
                    }
                    const hourlyRate = this.employeePayroll.rate_perday / 8;
                    const overtimeHourlyRate = hourlyRate * 1.25;
                    const overtimePay = overtimeHourlyRate * overtime;
                    this.employeePayroll.overtime_earnings = overtimePay; // Update overtime earnings
                    this.employeePayroll.salary = Number(this.employeePayroll.original_salary) + overtimePay;
                    this.employeePayroll.salary = Math.round(this.employeePayroll.salary * 100) / 100;
                },

                storePayroll() {
                    Swal.fire({
                        title: 'Processing...',
                        text: '...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Prepare the payload to match backend validation
                    const payload = {
                        department_id: this.employeePayroll.department_id,
                        employee_id: this.employeePayroll.employee_id,
                        id_number: this.employeePayroll.id_number,
                        duration: this.employeePayroll.duration,
                        salary: this.employeePayroll.salary,
                        over_time: this.employeePayroll.over_time,
                        total_deduction: this.employeePayroll.total_gov_deduction,
                        status: this.employeePayroll.status,
                    };

                    axios.post('{{ route('laminin.store.payroll') }}', payload)
                        .then(response => {
                            $('#payrollModal').modal('hide');

                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Payroll updated successfully',
                            });

                            this.allLamininPayroll();
                        })
                        .catch(error => {
                            console.error('Error updating payroll status', error.response ? error.response
                                .data : error);

                            // Display specific validation errors if available
                            if (error.response && error.response.data.errors) {
                                const errorMessages = Object.values(error.response.data.errors).flat();
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Validation Error!',
                                    html: errorMessages.join('<br>'),
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Failed to update payroll status. Please try again.',
                                });
                            }
                        });
                },
                getStatusClass(status) {
                    switch (status) {
                        case 'pending':
                            return 'badge badge-warning';
                        case 'paid':
                            return 'badge badge-success';
                        case 'hold':
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
            },
        });
    </script>
@endpush

@push('css')
    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
@endpush
