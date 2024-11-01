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
                {{-- <div class="form-group">
                    <label class="col-form-label">Id:</label>
                    <input type="text" class="form-control" v-model="employeeRate.id" disabled>
                </div> --}}

                <div class="form-group">
                    <label class="col-form-label">Name:</label>
                    <input type="text" class="form-control" v-model="employeeRate.name" disabled>
                </div>

                <div class="form-group">
                    <label class="col-form-label">Monthly Rate:</label>
                    <input type="number" class="form-control" v-model="employeeRate.monthly_rate">
                </div>

                <div class="form-group">
                    <label class="col-form-label">SSS:</label>
                    <input type="number" class="form-control" v-model="employeeRate.sss">
                </div>

                <div class="form-group">
                    <label class="col-form-label">Pag Ibig:</label>
                    <input type="number" class="form-control" v-model="employeeRate.pag_ibig">
                </div>

                <div class="form-group">
                    <label class="col-form-label">Phil Health:</label>
                    <input type="number" class="form-control" v-model="employeeRate.phil_health">
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
                                        <th>SSS</th>
                                        <th>Pag Ibig</th>
                                        <th>Phil Health</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="data in employeeRatesData" :key="data.id">
                                        <td>@{{ data.id_number }}</td>
                                        <td>@{{ data.department_id == 1 ? 'Aqua' : 'Laminin' }}</td>
                                        <td>@{{ data.name }}</td>
                                        <td>@{{ formatMoney(data.monthly_rate) }}</td>
                                        <td>@{{ formatMoney(data.rate_per_day) }}</td>
                                        <td>@{{ formatMoney(data.sss) }}</td>
                                        <td>@{{ formatMoney(data.pag_ibig) }}</td>
                                        <td>@{{ formatMoney(data.phil_health) }}</td>
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
                    id: '',
                    name: '',
                    monthly_rate: '',
                    sss: '',
                    pag_ibig: '',
                    phil_health: '',
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
                        id: data.id,
                        name: data.name,
                        monthly_rate: data.monthly_rate,
                        sss: data.sss,
                        pag_ibig: data.pag_ibig,
                        phil_health: data.phil_health,
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

                    axios.post(`{{ route('store.rate.and.deduction', '') }}/${this.employeeRate.id}`, {
                            monthly_rate: this.employeeRate.monthly_rate,
                            sss: this.employeeRate.sss,
                            pag_ibig: this.employeeRate.pag_ibig,
                            phil_health: this.employeeRate.phil_health,
                        })
                        .then(response => {
                            const index = this.employeeRatesData.findIndex(leave => leave.id === this
                                .employeeRate.id);
                            if (index !== -1) {
                                this.employeeRatesData.splice(index, 1, response
                                    .data);
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

                formatMoney(value) {
                    if (!value) return '0.00';
                    return parseFloat(value).toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
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
