@extends('layouts.main')

@section('content')
    <div class="content container-fluid" id="allAquaLeaveList">
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

        <x-modal submitMethod="updateLeaveStatus" modalId="editModalLeaveList" title="Aqua Leave List" submitId="submitEdit"
            submitText="Save Changes">
            <form>
                <div class="form-group">
                    <label class="col-form-label">Date Filed:</label>
                    <input type="text" class="form-control" v-model="currentLeave.date_filed" disabled>
                </div>

                <div class="form-group">
                    <label class="col-form-label">Name:</label>
                    <input type="text" class="form-control" v-model="currentLeave.name" disabled>
                </div>

                <div class="form-group">
                    <label class="col-form-label">Date Start:</label>
                    <input type="text" class="form-control" v-model="currentLeave.date_start" disabled>
                </div>

                <div class="form-group">
                    <label class="col-form-label">Date End:</label>
                    <input type="text" class="form-control" v-model="currentLeave.date_end" disabled>
                </div>

                <div class="form-group">
                    <label class="col-form-label">Type of Day:</label>
                    <input type="text" class="form-control" v-model="currentLeave.type_of_day" disabled>
                </div>

                <div class="form-group">
                    <label class="col-form-label">Reason to Leave:</label>
                    <input type="text" class="form-control" v-model="currentLeave.reason_to_leave" disabled>
                </div>

                <div class="form-group">
                    <label>Type of Leave <span class="text-danger">*</span></label>
                    <select class="form-control" v-model="currentLeave.status" required>
                        <option value="" disabled>Select Type of Leave</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>

                <div class="form-group" v-if="currentLeave.status === 'rejected'">
                    <label class="col-form-label">Reason for Rejection:</label>
                    <textarea class="form-control" v-model="currentLeave.reason_of_rejection"></textarea>
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
                                        <th>Date Filed</th>
                                        <th>Department</th>
                                        <th>Name</th>
                                        <th>Date Start</th>
                                        <th>Date End</th>
                                        <th>Total Days</th>
                                        <th>Type of Day</th>
                                        <th>Type of Leave</th>
                                        <th>Reason to Leave</th>
                                        <th>Reason of Rejection</th>
                                        <th>Status</th>
                                        @hasrole('admin')
                                            <th class="text-end">Action</th>
                                        @endhasrole
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="data in leaveList" :id="data.id">
                                        <td>@{{ formatDate(data.date_filed) }}</td>
                                        <td>@{{ data.department.name }}</td>
                                        <td>@{{ data.name }}</td>
                                        <td>@{{ formatDate(data.date_start) }}</td>
                                        <td>@{{ formatDate(data.date_end) }}</td>
                                        <td>@{{ data.total_days_leave }}</td>
                                        <td>@{{ data.type_of_day }}</td>
                                        <td>@{{ data.type_of_leave }}</td>
                                        <td>@{{ data.reason_to_leave }}</td>
                                        <td>@{{ data.reason_of_rejection || 'N/A' }}</td>
                                        <td>
                                            <span :class="getStatusClass(data.status)">
                                                @{{ data.status }}
                                            </span>
                                        </td>
                                        @hasrole('admin')
                                            <td class="text-end"
                                                :hidden="data.status === 'approved' || data.status === 'rejected'">
                                                <div class="actions">
                                                    <a href="javascript:;" class="btn btn-sm bg-danger-light"
                                                        @click="openEditModal(data)">
                                                        <i class="feather-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        @endhasrole
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
            el: '#allAquaLeaveList',
            data: {
                leaveList: [],
                currentLeave: {
                    date_filed: '',
                    name: '',
                    date_start: '',
                    date_end: '',
                    type_of_day: '',
                    reason_to_leave: '',
                    reason_of_rejection: '',
                    status: '',
                },
            },
            mounted() {
                this.allLeaveList();
            },
            methods: {
                allLeaveList() {
                    axios.get("{{ route('aqua.leave.list.data') }}")
                        .then(response => {
                            this.leaveList = response.data;
                        })
                        .catch(error => {
                            console.error('Error fetching leave list', error.response ? error.response.data :
                                error);
                        });
                },
                openEditModal(data) {
                    this.currentLeave = {
                        ...data,
                        reason_of_rejection: '',
                    };
                    $('#editModalLeaveList').modal('show');
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

                    const payload = {
                        status: this.currentLeave.status,
                    };

                    if (this.currentLeave.status === 'rejected') {
                        payload.reason_of_rejection = this.currentLeave.reason_of_rejection;
                    }

                    axios.post(`{{ route('aqua.leave.list.update', '') }}/${this.currentLeave.id}`, payload)
                        .then(response => {
                            const index = this.leaveList.findIndex(leave => leave.id === this.currentLeave.id);
                            if (index !== -1) {
                                this.leaveList.splice(index, 1, response.data.employee);
                            }
                            $('#editModalLeaveList').modal('hide');

                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: '...',
                            });

                            this.allLeaveList();
                        })
                        .catch(error => {
                            console.error('Error updating leave status', error.response ? error.response.data :
                                error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Failed to update leave status. Please try again.',
                            });
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
