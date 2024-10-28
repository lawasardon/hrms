@extends('layouts.main')

@section('content')
    <div class="content container-fluid" id="allRates">
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
            <form @submit.prevent="updateLeaveStatus">
                <div class="form-group">
                    <label class="col-form-label">Id:</label>
                    <input type="text" class="form-control" v-model="employeeRate.employee.id" disabled>
                </div>

                <div class="form-group">
                    <label class="col-form-label">Name:</label>
                    <input type="text" class="form-control" v-model="employeeRate.employee.name" disabled>
                </div>

                <div class="form-group">
                    <label class="col-form-label">Monthly Rate:</label>
                    <input type="text" class="form-control" v-model="employeeRate.monthly_rate">
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
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="data in employeeRatesData" :key="data.employee.id">
                                        <td>@{{ data.employee.id }}</td>
                                        <td>@{{ data.department_id == 1 ? 'Aqua' : 'Laminin' }}</td>
                                        <td>@{{ data.employee.name }}</td>
                                        <td>@{{ data.monthly_rate }}</td>
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
            el: '#allRates',
            data: {
                employeeRatesData: [],
                employeeRate: {
                    employee: {
                        id: '',
                        name: ''
                    },
                    monthly_rate: ''
                },
            },
            mounted() {
                this.employeeRates();
            },
            methods: {
                employeeRates() {
                    axios.get("{{ route('show.all.employee.rates.data') }}")
                        .then(response => {
                            this.employeeRatesData = response.data;
                        })
                        .catch(error => {
                            console.error('Error fetching employee rates', error.response ? error.response
                                .data : error);
                        });
                },

                openEditModal(data) {
                    this.employeeRate = {
                        employee: {
                            id: data.employee.id,
                            name: data.employee.name,
                        },
                        monthly_rate: data.monthly_rate,
                    };
                    $('#payrollModal').modal('show');
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

                    axios.post(`{{ route('update.employee.rate', '') }}/${this.employeeRate.employee.id}`, {
                            monthly_rate: this.employeeRate.monthly_rate
                        })
                        .then(response => {
                            const index = this.employeeRatesData.findIndex(leave => leave.employee.id === this
                                .employeeRate.employee.id);
                            if (index !== -1) {
                                this.employeeRatesData.splice(index, 1, response.data.rate);
                            }
                            $('#payrollModal').modal('hide');

                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Monthly rate updated successfully.',
                            });

                            this.employeeRates();
                        })
                        .catch(error => {
                            console.error('Error updating monthly rate', error.response ? error.response.data :
                                error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Failed to update monthly rate. Please try again.',
                            });
                        });
                },
            },
        });
    </script>
@endpush
