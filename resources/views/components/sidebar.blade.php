<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">
                    <span>Main Menu</span>
                </li>
                @hasrole('admin|hr')
                    <li class="submenu {{ request()->routeIs('') ? 'active' : '' }}">
                        <a href="#"><i class="feather-grid"></i> <span> Dashboard</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="index.html">Admin Dashboard</a></li>
                            <li><a href="teacher-dashboard.html">Teacher Dashboard</a></li>
                            <li><a href="student-dashboard.html">Student Dashboard</a></li>

                            <li><a href="teacher-dashboard.html">Teacher Dashboard</a></li>

                            <li><a href="student-dashboard.html">Student Dashboard</a></li>
                        </ul>
                    </li>
                @endhasrole
                @hasrole('admin|hr')
                    <li
                        class="submenu
                        {{ request()->routeIs(
                            'show.aqua.employee.list',
                            'show.laminin.employee.list',
                            'laminin.add.employee',
                            'aqua.add.employee',
                        )
                            ? 'active'
                            : '' }}">
                        <a href="#"><i class="fas fa-building"></i> <span> Departments</span>
                            <span class="menu-arrow"></span></a>
                        <ul>
                            <li>
                                <a href="{{ route('show.aqua.employee.list') }}"
                                    class="{{ request()->routeIs('show.aqua.employee.list') ? 'active' : '' }}">
                                    Aqua Department
                                </a>
                            </li>
                            <li><a href="{{ route('show.laminin.employee.list') }}"
                                    class="{{ request()->routeIs('show.laminin.employee.list') ? 'active' : '' }}">Laminin
                                    Department</a></li>
                            @if (request()->is('laminin/add/employee', 'aqua/add/employee'))
                                <li><a href="#"
                                        class="{{ request()->is('laminin/add/employee', 'aqua/add/employee') ? 'active' : '' }}">Add
                                        Employee</a>
                                </li>
                            @else
                            @endif
                        </ul>
                    </li>
                @endhasrole
                {{-- @hasrole('admin|hr')
                    <li class="submenu">
                        <a href="#"><i class="fas fa-users"></i> <span> Employees</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="students.html">Student List</a></li>
                            <li><a href="student-details.html">Student View</a></li>
                            <li><a href="add-student.html">Student Add</a></li>
                            <li><a href="edit-student.html">Student Edit</a></li>
                        </ul>
                    </li>
                @endhasrole --}}
                @hasrole('admin|hr|employee')
                    <li class="submenu">
                        <a href="#"><i class="far fa-calendar-check"></i> <span> Attedance</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="students.html">Student List</a></li>
                            <li><a href="student-details.html">Student View</a></li>
                            <li><a href="add-student.html">Student Add</a></li>
                            <li><a href="edit-student.html">Student Edit</a></li>
                        </ul>
                    </li>
                @endhasrole
                @hasrole('admin|hr')
                    <li class="submenu {{ request()->routeIs('aqua.leave.list') ? 'active' : '' }}">
                        <a href="#"><i class="fas fa-user-slash"></i> <span> Leave</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route('aqua.leave.list') }}"
                                    class="{{ request()->routeIs('aqua.leave.list') ? 'active' : '' }}">Aqua
                                    Leave List</a></li>
                            <li><a href="teacher-details.html">Laminin Leave List</a></li>
                            {{-- <li><a href="add-teacher.html">Teacher Add</a></li>
                            <li><a href="edit-teacher.html">Teacher Edit</a></li> --}}
                        </ul>
                    </li>
                @endhasrole
                @hasrole('employee')
                    <li
                        class="submenu {{ request()->routeIs('employee.leave.list', 'employee.leave.create') ? 'active' : '' }}">
                        <a href="#"><i class="fas fa-user-slash"></i> <span> Leave</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route('employee.leave.list') }}"
                                    class="{{ request()->routeIs('employee.leave.list') ? 'active' : '' }}">Leave Form</a>
                            </li>
                            @if (request()->is('leave/create'))
                                <li><a href="#" class="{{ request()->is('leave/create') ? 'active' : '' }}">File
                                        Leave</a>
                                </li>
                            @else
                            @endif
                        </ul>
                    </li>
                @endhasrole
                @hasrole('admin')
                    <li class="submenu">
                        <a href="#"><i class="fas fa-book-reader"></i> <span> Projects</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="subjects.html">Subject List</a></li>
                            <li><a href="add-subject.html">Subject Add</a></li>
                            <li><a href="edit-subject.html">Subject Edit</a></li>
                        </ul>
                    </li>
                @endhasrole
                @hasrole('admin|hr')
                    <li class="submenu">
                        <a href="#"><i class="fas fa-file-invoice-dollar"></i> <span> Payroll</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="fees-collections.html">Fees Collection</a></li>
                            <li><a href="expenses.html">Expenses</a></li>
                            <li><a href="salary.html">Salary</a></li>
                            <li><a href="add-fees-collection.html">Add Fees</a></li>
                            <li><a href="add-expenses.html">Add Expenses</a></li>
                            <li><a href="add-salary.html">Add Salary</a></li>
                        </ul>
                    </li>
                @endhasrole
                @hasrole('admin|hr|employee')
                    <li class="submenu">
                        <a href="#"><i class="fas fa-file-invoice-dollar"></i> <span> Loan</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="fees-collections.html">Fees Collection</a></li>
                            <li><a href="expenses.html">Expenses</a></li>
                            <li><a href="salary.html">Salary</a></li>
                            <li><a href="add-fees-collection.html">Add Fees</a></li>
                            <li><a href="add-expenses.html">Add Expenses</a></li>
                            <li><a href="add-salary.html">Add Salary</a></li>
                        </ul>
                    </li>
                @endhasrole
                @hasrole('admin|hr')
                    <li class="submenu">
                        <a href="#"><i class="fas fa-users"></i> <span> Assets</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="students.html">Student List</a></li>
                            <li><a href="student-details.html">Student View</a></li>
                            <li><a href="add-student.html">Student Add</a></li>
                            <li><a href="edit-student.html">Student Edit</a></li>
                        </ul>
                    </li>
                @endhasrole
                <li class="submenu">
                    <a href="#"><i class="fas fa-users"></i> <span> Notice</span> <span
                            class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="students.html">Student List</a></li>
                        <li><a href="student-details.html">Student View</a></li>
                        <li><a href="add-student.html">Student Add</a></li>
                        <li><a href="edit-student.html">Student Edit</a></li>
                    </ul>
                </li>
                <li>
                    <a href="settings.html"><i class="fas fa-cog"></i> <span>Settings</span></a>
                </li>
            </ul>
        </div>
    </div>
</div>
