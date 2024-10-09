@extends('layouts.main')

@section('content')
    <div class="content container-fluid" id="storeEmployee">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Add Employee</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="departments.html">Aqua</a></li>
                        <li class="breadcrumb-item active">Add Employee</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form @submit.prevent="saveEmployee">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="form-title"><span>Employee Details</span></h5>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label>Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" v-model="name" required>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label>Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" v-model="email" required>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label>Address <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" v-model="address" required>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label>Phone <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" v-model="phone" required>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label>Gender <span class="text-danger">*</span></label>
                                        <select class="form-control" v-model="gender" required>
                                            <option value="" disabled>Select Gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label>Birthday <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" v-model="birthday" required>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group">
                                        <label>Religion <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" v-model="religion" required>
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
            el: '#storeEmployee',
            data: {
                department_id: 1,
                name: '',
                email: '',
                address: '',
                phone: '',
                gender: '',
                birthday: '',
                religion: '',
            },
            methods: {
                saveEmployee() {
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

                    axios.post("{{ route('aqua.store.employee') }}", {
                            department_id: this.department_id,
                            name: this.name,
                            email: this.email,
                            address: this.address,
                            phone: this.phone,
                            gender: this.gender,
                            birthday: this.birthday,
                            religion: this.religion,
                        })
                        .then(response => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Employee added successfully',
                                text: response.data.message,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                            }).then(() => {
                                window.location.href = `/aqua/employee/list`;
                            });
                        })
                        .catch(error => {
                            console.error('Error adding employee', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'An error occurred',
                                text: error.response?.data?.message ||
                                    'An error occurred while adding the employee.',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                            });
                        });
                }
            }
        });
    </script>
@endpush
