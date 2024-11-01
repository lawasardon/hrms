@extends('layouts.main')

@section('content')
    <div class="content container-fluid" id="aquaPayroll">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Aqua Leave List Details</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Aqua Leave List</li>
                    </ul>
                </div>
            </div>
        </div>

        <x-modal submitMethod="updateLeaveStatus" modalId="payrollModal" title="Aqua Leave List" submitId="submitEdit"
            submitText="Save Changes">
            <form>

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
                                    <tr>
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
            el: '#aquaPayroll',
            data: {
                payrollData: [],
                employeePayroll: {
                    status: '',
                    name: '',
                    salary: 0,
                    over_time: 0,
                    rate_perday: 0,
                    original_salary: 0
                },

            },
            mounted() {
                this.allAquaPayroll();
            },
            watch: {
                'employeePayroll.over_time': function(newValue) {
                    this.calculateSalaryWithOvertime(newValue);
                }
            },
            methods: {
                allAquaPayroll() {
                    axios.get("{{ route('aqua.payroll.calculation') }}")
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
                calculateSalaryWithOvertime(overtime) {
                    // Reset to original salary if overtime is empty or 0
                    if (!overtime || overtime === 0) {
                        this.employeePayroll.salary = this.employeePayroll.original_salary;
                        return;
                    }

                    // Calculate hourly rate (daily rate divided by 8 hours)
                    const hourlyRate = this.employeePayroll.rate_perday / 8;

                    // Calculate overtime hourly rate (125% of regular hourly rate)
                    const overtimeHourlyRate = hourlyRate * 1.25;

                    // Calculate total overtime pay
                    const overtimePay = overtimeHourlyRate * overtime;

                    // Add overtime pay to original salary
                    this.employeePayroll.salary = Number(this.employeePayroll.original_salary) + overtimePay;

                    // Round to 2 decimal places
                    this.employeePayroll.salary = Math.round(this.employeePayroll.salary * 100) / 100;
                },
                updateLeaveStatus() {
                    Swal.fire({
                        title: 'Processing...',
                        text: '...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Create payload with the updated data
                    const payload = {
                        duration: this.employeePayroll.duration,
                        total_working_days: this.employeePayroll.total_working_days,
                        over_time: this.employeePayroll.over_time,
                        salary: this.employeePayroll.salary,
                        status: this.employeePayroll.status
                    };

                    axios.post(`{{ route('aqua.update.payroll', '') }}/${this.employeePayroll.id}`, payload)
                        .then(response => {
                            const index = this.payrollData.findIndex(leave => leave.id === this.employeePayroll
                                .id);
                            if (index !== -1) {
                                this.payrollData.splice(index, 1, response.data.payroll);
                            }
                            $('#payrollModal').modal('hide');

                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Payroll updated successfully',
                            });

                            this.allAquaPayroll();
                        })
                        .catch(error => {
                            console.error('Error updating payroll status', error.response ? error.response
                                .data : error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Failed to update payroll status. Please try again.',
                            });
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
