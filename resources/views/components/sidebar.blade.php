<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">
                    <span>Main Menu</span>
                </li>
                @hasrole('admin|hr')
                    <li class="submenu active">
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
                    <li class="submenu">
                        <a href="#"><i class="fas fa-building"></i> <span> Departments</span>
                            <span class="menu-arrow"></span></a>
                        <ul>
                            <li>
                                <a href="{{ route('show.aqua.employee.list') }}"
                                    class="{{ request()->routeIs('show.aqua.employee.list') ? 'active' : '' }}">
                                    Aqua Department
                                </a>
                            </li>
                            <li><a href="teacher-dashboard.html">Laminin Department</a></li>
                        </ul>
                    </li>
                @endhasrole
                @hasrole('admin|hr')
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
                @endhasrole
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
                @hasrole('admin|hr|employee')
                    <li class="submenu">
                        <a href="#"><i class="fas fa-user-slash"></i> <span> Leave</span> <span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="teachers.html">Teacher List</a></li>
                            <li><a href="teacher-details.html">Teacher View</a></li>
                            <li><a href="add-teacher.html">Teacher Add</a></li>
                            <li><a href="edit-teacher.html">Teacher Edit</a></li>
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

