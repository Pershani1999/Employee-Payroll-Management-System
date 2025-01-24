/* Login Form Submit Script Start */
if ( $('#login-form').length > 0 ) {
    $(document).on('submit', '#login-form', function(e) {
        e.preventDefault();
        
        var form = $(this);
        $.ajax({
            type     : "POST",
            dataType : "json",
            async    : true,
            cache    : false,
            url      : baseurl + "ajax/?case=LoginProcessHandler",
            data     : form.serialize(),
            success  : function(result) {
                if ( result.code == 0 ) {
                    window.location.href = result.result;
                } else {
                    $.notify({
                        icon: 'glyphicon glyphicon-remove-circle',
                        message: result.result,
                    },{
                        allow_dismiss: false,
                        type: "danger",
                        placement: {
                            from: "top",
                            align: "right"
                        },
                        z_index: 9999,
                    });
                }
            }
        });
    });
}
/* End of Script */

/* Attendance Form Submit Script Start */
if ( $('#attendance-form').length > 0 ) {
    $(document).on('submit', '#attendance-form', function(e) {
        e.preventDefault();
        
        var form = $(this);
        $.ajax({
            type     : "POST",
            dataType : "json",
            async    : true,
            cache    : false,
            url      : baseurl + "ajax/?case=AttendanceProcessHandler",
            data     : form.serialize(),
            success  : function(result) {
                if ( result.code == 0 ) {
                    form[0].reset();
                    $('#action_btn').text(result.next);
                    if ( result.complete == 2 ) {
                        form.remove();
                    }
                    $.notify({
                        icon: 'glyphicon glyphicon-ok-circle',
                        message: result.result,
                    },{
                        allow_dismiss: false,
                        type: "success",
                        placement: {
                            from: "bottom",
                            align: "right"
                        },
                        z_index: 9999,
                    });
                } else {
                    $.notify({
                        icon: 'glyphicon glyphicon-remove-circle',
                        message: result.result,
                    },{
                        allow_dismiss: false,
                        type: "danger",
                        placement: {
                            from: "bottom",
                            align: "right"
                        },
                        z_index: 9999,
                    });
                }
            }
        });
    });
}
/* End of Script */

$(document).ready(function() {
    /* Attendance Table Script Start */
    if ( $('#attendance').length > 0 ) {
        var att_table = $('#attendance').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": baseurl + "ajax/?case=LoadingAttendance",
            "order": [0, 'desc'],
            "columnDefs": [{
                "targets": 0,
                "className": "dt-center"
            }, {
                "targets": 1,
                "className": "dt-center"
            }]
        });
    }
    /* End of Script */

    
    $(document).ready(function() {
        // Function to check if DataTable is already initialized
        function isDataTableInitialized(id) {
            return $.fn.DataTable.isDataTable(id);
        }
    
        // Gross Salary Table Script
        if ($('#gross_salary').length > 0) {
            if (!isDataTableInitialized('#gross_salary')) {
                $('#gross_salary').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "ajax": baseurl + "ajax/?case=LoadGrossSalaries",
                    "order": [2, 'desc'], // Order by Month
                    "columnDefs": [{
                        "targets": 0,
                        "className": "dt-center"
                    }, {
                        "targets": 1,
                        "className": "dt-center"
                    }, {
                        "targets": 2,
                        "className": "dt-center"
                    }, {
                        "targets": 3,
                        "className": "dt-center"
                    }, {
                        "targets": 4,
                        "className": "dt-center"
                    }]
                });
            }
        }
    });
    
    
    /* Salary Table Script Start */
    if ( $('#admin-salary').length > 0 ) {
        var admin_sal_table = $('#admin-salary').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": baseurl + "ajax/?case=LoadingSalaries",
            "order": [0, 'desc'],
            "columnDefs": [{
                "targets": 0,
                "className": "dt-center"
            }]
        });
    }
    if ( $('#emp-salary').length > 0 ) {
        var emp_sal_table = $('#emp-salary').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": baseurl + "ajax/?case=LoadingSalaries",
            "order": [0, 'desc']
        });
    }
    /* End of Script */

    if ( $('#employees').length > 0 ) {
        /* Employee Table Script Start */
        var emp_table = $('#employees').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": baseurl + "ajax/?case=LoadingEmployees",
            "columnDefs": [{
                "targets": 0,
                "className": "dt-center"
            }, {
                "targets": 1,
                "orderable": false,
                "className": "dt-center"
            }, {
                "targets": -1,
                "orderable": false,
                "data": null,
                "className": "dt-center",
                "defaultContent": '<button class="btn btn-warning btn-xs manageSalary"><i class="fa fa-money"></i></button> <button class="btn btn-primary btn-xs addSalary"><i class="fa fa-gratipay"></i></button> <button class="btn btn-success btn-xs editEmp"><i class="fa fa-edit"></i></button> <button class="btn btn-danger btn-xs deleteEmp"><i class="fa fa-trash"></i></button>'
            }]
        });
        /* End of Script */

        /* Pay Salary Script Start */
        $('#employees tbody').on('click', '.manageSalary', function(e) {
            e.preventDefault();

            var data = emp_table.row($(this).parents('tr')).data();
            var paylink = baseurl + 'pay-salary/' + data[0] + '/';
            $('#SalaryMonthModal a').each(function() {
                var month = $(this).data('month');
                var year = $(this).data('year');
                $(this).attr('href', paylink + month + '/' + year + '/');
            });
            $('#SalaryMonthModal').modal('show');
        });
        /* End of Script */

        /* Add Salary Script Start */
        $('#employees tbody').on('click', '.addSalary', function(e) {
            e.preventDefault();

            var data = emp_table.row($(this).parents('tr')).data();
            $('#empcode').val(data[0]);
            $.ajax({
                type     : "POST",
                dataType : "json",
                async    : true,
                cache    : false,
                url      : baseurl + "ajax/?case=GetAllPayheadsExceptEmployeeHave",
                data     : 'emp_code=' + data[0],
                success  : function(result) {
                    $('#all_payheads').html('');
                    if ( result.code == 0 ) {
                        for ( var i in result.result ) {
                            $('#all_payheads').append($("<option></option>")
                                .attr({
                                    "value": result.result[i].payhead_id
                                })
                                .text(
                                    result.result[i].payhead_name + ' (' + jsUcfirst(result.result[i].payhead_type) + ')')
                                .addClass((result.result[i].payhead_type=='earnings'?'text-success':'text-danger'))
                            ); 
                        }
                    }
                }
            });
            $.ajax({
                type     : "POST",
                dataType : "json",
                async    : true,
                cache    : false,
                url      : baseurl + "ajax/?case=GetEmployeePayheadsByID",
                data     : 'emp_code=' + data[0],
                success  : function(result) {
                    $('#selected_payheads, #selected_payamount').html('');
                    if ( result.code == 0 ) {
                        for ( var i in result.result ) {
                            $('#selected_payheads').append($("<option></option>")
                                .attr({
                                    "value": result.result[i].payhead_id,
                                    "selected": "selected"
                                })
                                .text(
                                    result.result[i].payhead_name + ' (' + jsUcfirst(result.result[i].payhead_type) + ')'
                                )
                                .addClass((result.result[i].payhead_type=='earnings'?'text-success':'text-danger'))
                            );
                            $('#selected_payamount').append($("<input />")
                                .attr({
                                    "type": "text",
                                    "name": "pay_amounts[" + result.result[i].payhead_id + "]",
                                    "id": "pay_amounts_" + result.result[i].payhead_id,
                                    "placeholder": result.result[i].payhead_name,
                                    "value": result.result[i].default_salary
                                })
                                .addClass('form-control')
                            );
                        }
                    }
                }
            });
            $('#ManageModal').modal('show');
        });
        /* End of Script */

        /* Delete Employee Script Start */
        $('#employees tbody').on('click', '.editEmp', function(e) {
            e.preventDefault();

            var data = emp_table.row($(this).parents('tr')).data();
            $.ajax({
                type     : "POST",
                dataType : "json",
                async    : true,
                cache    : false,
                url      : baseurl + "ajax/?case=GetEmployeeByID",
                data     : 'emp_code=' + data[0],
                success  : function(result) {
                    if ( result.code == 0 ) {
                        $('#emp_id').val(result.result.emp_id);
                        $('#emp_code').text(result.result.emp_code);
                        $('#first_name').val(result.result.first_name);
                        $('#last_name').val(result.result.last_name);
                        $('#dob').val(result.result.dob).datepicker('update');
                        $('#gender').val(result.result.gender);
                        
                        
                        $('#address').val(result.result.address);
                        
                        $('#email').val(result.result.email);
                        $('#mobile').val(result.result.mobile);
                        
                        $('#identity_no').val(result.result.identity_no);
                       
                        $('#joining_date').val(result.result.joining_date).datepicker('update');
                        
                        $('#designation').val(result.result.designation);
                       
                        
                        $('#bank_name').val(result.result.bank_name);
                        $('#branch_name').val(result.result.branch_name);
                        $('#account_no').val(result.result.account_no);
                        
                        $('#epf_account').val(result.result.epf_account);
                        $('#etf_account').val(result.result.etf_account);
                        $('#EditEmpModal').modal('show');
                    } else {
                        $.notify({
                            icon: 'glyphicon glyphicon-remove-circle',
                            message: result.result,
                        },{
                            allow_dismiss: false,
                            type: "danger",
                            placement: {
                                from: "top",
                                align: "right"
                            },
                            z_index: 9999,
                        });
                    }
                }
            });
        });
        /* End of Script */

        /* Delete Employee Script Start */
        $('#employees tbody').on('click', '.deleteEmp', function(e) {
            e.preventDefault();

            var conf = confirm('Are you sure you want to delete this employee?');
            if ( conf ) {
                var data = emp_table.row($(this).parents('tr')).data();
                $.ajax({
                    type     : "POST",
                    dataType : "json",
                    async    : true,
                    cache    : false,
                    url      : baseurl + "ajax/?case=DeleteEmployeeByID",
                    data     : 'emp_code=' + data[0],
                    success  : function(result) {
                        if ( result.code == 0 ) {
                            $.notify({
                                icon: 'glyphicon glyphicon-ok-circle',
                                message: result.result,
                            },{
                                allow_dismiss: false,
                                type: "success",
                                placement: {
                                    from: "top",
                                    align: "right"
                                },
                                z_index: 9999,
                            });
                            emp_table.ajax.reload(null, false);
                        } else {
                            $.notify({
                                icon: 'glyphicon glyphicon-remove-circle',
                                message: result.result,
                            },{
                                allow_dismiss: false,
                                type: "danger",
                                placement: {
                                    from: "top",
                                    align: "right"
                                },
                                z_index: 9999,
                            });
                        }
                    }
                });
            }
        });
        /* End of Script */

        
        /* Add Payhead To Employee Script Start */
        $(document).on('click', '#selectHeads', function() {
            $('#all_payheads').find(':selected').each(function() {
                var val = $(this).val();
                var name = $(this).text();
                $('#selected_payamount').append($("<input />")
                    .attr({
                        "type": "text",
                        "name": "pay_amounts[" + val + "]",
                        "id": "pay_amounts_" + val,
                        "placeholder": name
                    })
                    .addClass('form-control')
                );
            });
            moveItems('#all_payheads', '#selected_payheads');
        });
        $(document).on('click', '#removeHeads', function() {
            $('#selected_payheads').find(':selected').each(function() {
                var val = $(this).val();
                $('#pay_amounts_' + val).remove();
            });
            moveItems('#selected_payheads', '#all_payheads');
        });
        /* End of Script */
    }  

    

    /* Date Picker Script Start */
    if ( $('.datepicker').length > 0 ) {
        $('.datepicker').datepicker({
            format: 'mm/dd/yyyy',
            autoclose: true
        });
    }
    if ( $('.multidatepicker').length > 0 ) {
        $('.multidatepicker').datepicker({
            format: 'mm/dd/yyyy',
            startDate : new Date(),
            multidate: true,
            autoclose: true
        });
    }
    /* End of Script */

    /* Stylish Radio Input Script Start */ 
    if ( $('input[type="radio"].minimal').length > 0 ) {
        $('input[type="radio"].minimal').iCheck({
            radioClass: 'iradio_minimal-blue'
        });
    }
    /* End of Script */

    /* Designation Table Script Start */
    if ( $('#empdesignations').length > 0 ) {
        $('#empdesignations').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": baseurl + "ajax/?case=LoadingDesignations",
            "columnDefs": [{
                "targets": 0,
                "className": "dt-center"
            }, {
                "targets": 3,
                "className": "dt-center"
            }, {
                "targets": 4,
                "className": "dt-center"
            }]
        });
    }
    /* End of Script */

    
    

    if ( $('#designations').length > 0 ) {
        /* Designation Table Script Start */
        var desi_table = $('#designations').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": baseurl + "ajax/?case=LoadingDesignations",
            "columnDefs": [{
                "targets": 0,
                "className": "dt-center"
            }, {
                "targets": 3,
                "className": "dt-center"
            }, {
                "targets": 4,
                "className": "dt-center"
            }, {
                "targets": -1,
                "orderable": false,
                "data": null,
                "className": "dt-center",
                "defaultContent": '<button class="btn btn-success btn-xs editDesignation"><i class="fa fa-edit"></i></button> <button class="btn btn-danger btn-xs deleteDesignation"><i class="fa fa-trash"></i></button>'
            }]
        });
        /* End of Script */

        /* Edit Designation Script Start */
        $('#designations tbody').on('click', '.editDesignation', function(e) {
            e.preventDefault();

            var data = desi_table.row($(this).parents('tr')).data();
            $.ajax({
                type     : "POST",
                dataType : "json",
                async    : true,
                cache    : false,
                url      : baseurl + "ajax/?case=GetDesignationByID",
                data     : 'id=' + data[0],
                success  : function(result) {
                    if ( result.code == 0 ) {
                        $("#designation_id").val(result.result.designation_id);
                        $("#designation").val(result.result.designation);
                        $("#normal_rate").val(result.result.normal_rate);
                        $("#ot_rate").val(result.result.ot_rate);
                        
                        $("#DesignationModal").modal('show');
                    } else {
                        $.notify({
                            icon: 'glyphicon glyphicon-remove-circle',
                            message: result.result,
                        },{
                            allow_dismiss: false,
                            type: "danger",
                            placement: {
                                from: "top",
                                align: "right"
                            },
                            z_index: 9999,
                        });
                    }
                }
            });
        });
        /* End of Script */

        /* Delete Designation Script Start */
        $('#designations tbody').on('click', '.deleteDesignation', function(e) {
            e.preventDefault();

            var conf = confirm('Are you sure you want to delete this designation?');
            if ( conf ) {
                var data = desi_table.row($(this).parents('tr')).data();
                $.ajax({
                    type     : "POST",
                    dataType : "json",
                    async    : true,
                    cache    : false,
                    url      : baseurl + "ajax/?case=DeleteDesignationByID",
                    data     : 'id=' + data[0],
                    success  : function(result) {
                        if ( result.code == 0 ) {
                            $.notify({
                                icon: 'glyphicon glyphicon-ok-circle',
                                message: result.result,
                            },{
                                allow_dismiss: false,
                                type: "success",
                                placement: {
                                    from: "top",
                                    align: "right"
                                },
                                z_index: 9999,
                            });
                            desi_table.ajax.reload(null, false);
                        } else {
                            $.notify({
                                icon: 'glyphicon glyphicon-remove-circle',
                                message: result.result,
                            },{
                                allow_dismiss: false,
                                type: "danger",
                                placement: {
                                    from: "top",
                                    align: "right"
                                },
                                z_index: 9999,
                            });
                        }
                    }
                });
            }
        });
        /* End of Script */
    }

    /* Designation Modal Close Script Start */
    if ( $('#EditEmpModal').length > 0 ) {
        $('#EditEmpModal').on('hidden.bs.modal', function () {
            $("#emp_code").empty();
            $('#edit-emp-form')[0].reset();
        });
    }
    /* End of Script */

    /* Designation Modal Close Script Start 
    if ( $('#DesignationModal').length > 0 ) {
        $('#DesignationModal').on('hidden.bs.modal', function () {
            $("#designation_id").val('');
            $("#compulsory_designation").iCheck('check');
            $('#designation-form')[0].reset();
        });
    }
   

   
    if ( $('#ManageModal').length > 0 ) {
        $('#ManageModal').on('hidden.bs.modal', function () {
            $("#empcode").val('');
            $('#selected_payheads').html('');
        });
    }
    /* End of Script */


    /* Assign Payhead to Employee Form Submit Script Start */
    if ( $('#assign-payhead-form').length > 0 ) {
        $('#assign-payhead-form').on('submit', function(e) {
            e.preventDefault();

            var form = $(this);
            $.ajax({
                type     : "POST",
                dataType : "json",
                async    : true,
                cache    : false,
                url      : baseurl + "ajax/?case=AssignPayheadsToEmployee",
                data     : form.serialize(),
                success  : function(result) {
                    if ( result.code == 0 ) {
                        $.notify({
                            icon: 'glyphicon glyphicon-ok-circle',
                            message: result.result,
                        },{
                            allow_dismiss: false,
                            type: "success",
                            placement: {
                                from: "top",
                                align: "right"
                            },
                            z_index: 9999,
                        });
                        $('#ManageModal').modal('hide');
                    } else {
                        $.notify({
                            icon: 'glyphicon glyphicon-remove-circle',
                            message: result.result,
                        },{
                            allow_dismiss: false,
                            type: "danger",
                            placement: {
                                from: "top",
                                align: "right"
                            },
                            z_index: 9999,
                        });
                    }
                }
            });
        });
    }
    /* End of Script */

    /* Designation Form Submit Script Start */
    if ( $('#designation-form').length > 0 ) {
        $('#designation-form').on('submit', function(e) {
            e.preventDefault();

            var form = $(this);
            $.ajax({
                type     : "POST",
                dataType : "json",
                async    : true,
                cache    : false,
                url      : baseurl + "ajax/?case=InsertUpdateDesignations",
                data     : form.serialize(),
                success  : function(result) {
                    if ( result.code == 0 ) {
                        $.notify({
                            icon: 'glyphicon glyphicon-ok-circle',
                            message: result.result,
                        },{
                            allow_dismiss: false,
                            type: "success",
                            placement: {
                                from: "top",
                                align: "right"
                            },
                            z_index: 9999,
                        });
                        desi_table.ajax.reload(null, false);
                        $('#DesignationModal').modal('hide');
                    } else {
                        $.notify({
                            icon: 'glyphicon glyphicon-remove-circle',
                            message: result.result,
                        },{
                            allow_dismiss: false,
                            type: "danger",
                            placement: {
                                from: "top",
                                align: "right"
                            },
                            z_index: 9999,
                        });
                    }
                }
            });
        });
    }
    /* End of Script */

    /* Employee Edit Form Submit Script Start */
    if ( $('#edit-emp-form').length > 0 ) {
        $('#edit-emp-form').on('submit', function(e) {
            e.preventDefault();

            var form = $(this);
            $.ajax({
                type     : "POST",
                dataType : "json",
                async    : true,
                cache    : false,
                url      : baseurl + "ajax/?case=EditEmployeeDetailsByID",
                data     : form.serialize(),
                success  : function(result) {
                    if ( result.code == 0 ) {
                        $.notify({
                            icon: 'glyphicon glyphicon-ok-circle',
                            message: result.result,
                        },{
                            allow_dismiss: false,
                            type: "success",
                            placement: {
                                from: "top",
                                align: "right"
                            },
                            z_index: 9999,
                        });
                        emp_table.ajax.reload(null, false);
                        $('#EditEmpModal').modal('hide');
                    } else {
                        $.notify({
                            icon: 'glyphicon glyphicon-remove-circle',
                            message: result.result,
                        },{
                            allow_dismiss: false,
                            type: "danger",
                            placement: {
                                from: "top",
                                align: "right"
                            },
                            z_index: 9999,
                        });
                    }
                }
            });
        });
    }
    /* End of Script */

    if ( $('#payheads').length > 0 ) {
        /* Payhead Table Script Start */
        var pay_table = $('#payheads').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": baseurl + "ajax/?case=LoadingPayheads",
            "columnDefs": [{
                "targets": 0,
                "className": "dt-center"
            }, {
                "targets": 3,
                "className": "dt-center"
            }, {
                "targets": -1,
                "orderable": false,
                "data": null,
                "className": "dt-center",
                "defaultContent": '<button class="btn btn-success btn-xs editPayheads"><i class="fa fa-edit"></i></button> <button class="btn btn-danger btn-xs deletePayheads"><i class="fa fa-trash"></i></button>'
            }]
        });
        /* End of Script */

        /* Edit Payhead Script Start */
        $('#payheads tbody').on('click', '.editPayheads', function(e) {
            e.preventDefault();

            var data = pay_table.row($(this).parents('tr')).data();
            $.ajax({
                type     : "POST",
                dataType : "json",
                async    : true,
                cache    : false,
                url      : baseurl + "ajax/?case=GetPayheadByID",
                data     : 'id=' + data[0],
                success  : function(result) {
                    if ( result.code == 0 ) {
                        $("#payhead_id").val(result.result.payhead_id);
                        $("#payhead_name").val(result.result.payhead_name);
                        $("#payhead_desc").val(result.result.payhead_desc);
                        $("#payhead_type").val(result.result.payhead_type);
                        $("#PayheadsModal").modal('show');
                    } else {
                        $.notify({
                            icon: 'glyphicon glyphicon-remove-circle',
                            message: result.result,
                        },{
                            allow_dismiss: false,
                            type: "danger",
                            placement: {
                                from: "top",
                                align: "right"
                            },
                            z_index: 9999,
                        });
                    }
                }
            });
        });
        /* End of Script */

        /* Delete Payhead Script Start */
        $('#payheads tbody').on('click', '.deletePayheads', function(e) {
            e.preventDefault();

            var conf = confirm('Are you sure you want to delete this payhead?');
            if ( conf ) {
                var data = pay_table.row($(this).parents('tr')).data();
                $.ajax({
                    type     : "POST",
                    dataType : "json",
                    async    : true,
                    cache    : false,
                    url      : baseurl + "ajax/?case=DeletePayheadByID",
                    data     : 'id=' + data[0],
                    success  : function(result) {
                        if ( result.code == 0 ) {
                            $.notify({
                                icon: 'glyphicon glyphicon-ok-circle',
                                message: result.result,
                            },{
                                allow_dismiss: false,
                                type: "success",
                                placement: {
                                    from: "top",
                                    align: "right"
                                },
                                z_index: 9999,
                            });
                            pay_table.ajax.reload(null, false);
                        } else {
                            $.notify({
                                icon: 'glyphicon glyphicon-remove-circle',
                                message: result.result,
                            },{
                                allow_dismiss: false,
                                type: "danger",
                                placement: {
                                    from: "top",
                                    align: "right"
                                },
                                z_index: 9999,
                            });
                        }
                    }
                });
            }
        });
        /* End of Script */
    }

    /* Payhead Modal Close Script Start */
    if ( $('#PayheadsModal').length > 0 ) {
        $('#PayheadsModal').on('hidden.bs.modal', function () {
            $("#payhead_id").val('');
            $('#payhead-form')[0].reset();
        });
    }
    /* End of Script */

    /* Payhead Form Submit Script Start */
    if ( $('#payhead-form').length > 0 ) {
        $('#payhead-form').on('submit', function(e) {
            e.preventDefault();

            var form = $(this);
            $.ajax({
                type     : "POST",
                dataType : "json",
                async    : true,
                cache    : false,
                url      : baseurl + "ajax/?case=InsertUpdatePayheads",
                data     : form.serialize(),
                success  : function(result) {
                    if ( result.code == 0 ) {
                        $.notify({
                            icon: 'glyphicon glyphicon-ok-circle',
                            message: result.result,
                        },{
                            allow_dismiss: false,
                            type: "success",
                            placement: {
                                from: "top",
                                align: "right"
                            },
                            z_index: 9999,
                        });
                        pay_table.ajax.reload(null, false);
                        $('#PayheadsModal').modal('hide');
                    } else {
                        $.notify({
                            icon: 'glyphicon glyphicon-remove-circle',
                            message: result.result,
                        },{
                            allow_dismiss: false,
                            type: "danger",
                            placement: {
                                from: "top",
                                align: "right"
                            },
                            z_index: 9999,
                        });
                    }
                }
            });
        });
    }
    /* End of Script */

    /* Salary Form Submit Script Start */
    if ( $('#payslip-form').length > 0 ) {
        $('#payslip-form').on('submit', function(e) {
            e.preventDefault();

            var form = $(this);
            $.ajax({
                type     : "POST",
                dataType : "json",
                async    : true,
                cache    : false,
                url      : baseurl + "ajax/?case=GeneratePaySlip",
                data     : form.serialize(),
                success  : function(result) {
                    if ( result.code == 0 ) {
                        window.location.reload();
                    } else {
                        $.notify({
                            icon: 'glyphicon glyphicon-remove-circle',
                            message: result.result,
                        },{
                            allow_dismiss: false,
                            type: "danger",
                            placement: {
                                from: "top",
                                align: "right"
                            },
                            z_index: 9999,
                        });
                    }
                }
            });
        });
    }
    /* End of Script */

    /* Profile Edit Form Submit Script Start */
    if ( $('#profile-form').length > 0 ) {
        $('#profile-form').on('submit', function(e) {
            e.preventDefault();

            var form = $(this);
            $.ajax({
                type     : "POST",
                dataType : "json",
                async    : true,
                cache    : false,
                url      : baseurl + "ajax/?case=EditProfileByID",
                data     : form.serialize(),
                success  : function(result) {
                    if ( result.code == 0 ) {
                        $.notify({
                            icon: 'glyphicon glyphicon-ok-circle',
                            message: result.result,
                        },{
                            allow_dismiss: false,
                            type: "success",
                            placement: {
                                from: "top",
                                align: "right"
                            },
                            z_index: 9999,
                        });
                    } else {
                        $.notify({
                            icon: 'glyphicon glyphicon-remove-circle',
                            message: result.result,
                        },{
                            allow_dismiss: false,
                            type: "danger",
                            placement: {
                                from: "top",
                                align: "right"
                            },
                            z_index: 9999,
                        });
                    }
                }
            });
        });
    }
    /* End of Script */

    /* Password Edit Form Submit Script Start */
    if ( $('#password-form').length > 0 ) {
        $('#password-form').on('submit', function(e) {
            e.preventDefault();

            var form = $(this);
            $.ajax({
                type     : "POST",
                dataType : "json",
                async    : true,
                cache    : false,
                url      : baseurl + "ajax/?case=EditLoginDataByID",
                data     : form.serialize(),
                success  : function(result) {
                    if ( result.code == 0 ) {
                        form[0].reset();
                        $.notify({
                            icon: 'glyphicon glyphicon-ok-circle',
                            message: result.result,
                        },{
                            allow_dismiss: false,
                            type: "success",
                            placement: {
                                from: "top",
                                align: "right"
                            },
                            z_index: 9999,
                        });
                    } else {
                        $.notify({
                            icon: 'glyphicon glyphicon-remove-circle',
                            message: result.result,
                        },{
                            allow_dismiss: false,
                            type: "danger",
                            placement: {
                                from: "top",
                                align: "right"
                            },
                            z_index: 9999,
                        });
                    }
                }
            });
        });
    }
    /* End of Script */

    /* advance Table Script Start */
   if ( $('#alladvances').length > 0 ) {
    var advance_table = $('#alladvances').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": baseurl + "ajax/?case=LoadingAlladvances",
        "columnDefs": [{
            "targets": 0,
            "className": "dt-center"
        }, {
            "targets": -1,
            "orderable": false,
            "data": null,
            "className": "dt-center",
            "defaultContent": '<button class="btn btn-success btn-xs approveadvance"><i class="fa fa-check"></i></button> <button class="btn btn-danger btn-xs rejectadvance"><i class="fa fa-close"></i></button>'
        }]
    });

    /* Approve advance Application Script Start */
    $('#alladvances tbody').on('click', '.approveadvance', function(e) {
        e.preventDefault();

        var data = advance_table.row($(this).parents('tr')).data();
        $.ajax({
            type     : "POST",
            dataType : "json",
            async    : true,
            cache    : false,
            url      : baseurl + "ajax/?case=ApproveadvanceApplication",
            data     : 'id=' + data[0],
            success  : function(result) {
                if ( result.code == 0 ) {
                    $.notify({
                        icon: 'glyphicon glyphicon-ok-circle',
                        message: result.result,
                    },{
                        allow_dismiss: false,
                        type: "success",
                        placement: {
                            from: "top",
                            align: "right"
                        },
                        z_index: 9999,
                    });
                    advance_table.ajax.reload(null, false);
                } else {
                    $.notify({
                        icon: 'glyphicon glyphicon-remove-circle',
                        message: result.result,
                    },{
                        allow_dismiss: false,
                        type: "danger",
                        placement: {
                            from: "top",
                            align: "right"
                        },
                        z_index: 9999,
                    });
                }
            }
        });
    });
    /* End of Script */

    /* Approve advance Application Script Start */
    $('#alladvances tbody').on('click', '.rejectadvance', function(e) {
        e.preventDefault();

        var data = advance_table.row($(this).parents('tr')).data();
        $.ajax({
            type     : "POST",
            dataType : "json",
            async    : true,
            cache    : false,
            url      : baseurl + "ajax/?case=RejectadvanceApplication",
            data     : 'id=' + data[0],
            success  : function(result) {
                if ( result.code == 0 ) {
                    $.notify({
                        icon: 'glyphicon glyphicon-ok-circle',
                        message: result.result,
                    },{
                        allow_dismiss: false,
                        type: "success",
                        placement: {
                            from: "top",
                            align: "right"
                        },
                        z_index: 9999,
                    });
                    advance_table.ajax.reload(null, false);
                } else {
                    $.notify({
                        icon: 'glyphicon glyphicon-remove-circle',
                        message: result.result,
                    },{
                        allow_dismiss: false,
                        type: "danger",
                        placement: {
                            from: "top",
                            align: "right"
                        },
                        z_index: 9999,
                    });
                }
            }
        });
    });
    /* End of Script */
}
/* End of Script */



/* Delete advance Script Start */
$('#aladvances tbody').on('click', '.deleteadvances', function(e) {
    e.preventDefault();

    var conf = confirm('Are you sure you want to delete this advance record?');
    if ( conf ) {
        var data = pay_table.row($(this).parents('tr')).data();
        $.ajax({
            type     : "POST",
            dataType : "json",
            async    : true,
            cache    : false,
            url      : baseurl + "ajax/?case=DeleteadvanceByID",
            data     : 'id=' + data[0],
            success  : function(result) {
                if ( result.code == 0 ) {
                    $.notify({
                        icon: 'glyphicon glyphicon-ok-circle',
                        message: result.result,
                    },{
                        allow_dismiss: false,
                        type: "success",
                        placement: {
                            from: "top",
                            align: "right"
                        },
                        z_index: 9999,
                    });
                    pay_table.ajax.reload(null, false);
                } else {
                    $.notify({
                        icon: 'glyphicon glyphicon-remove-circle',
                        message: result.result,
                    },{
                        allow_dismiss: false,
                        type: "danger",
                        placement: {
                            from: "top",
                            align: "right"
                        },
                        z_index: 9999,
                    });
                }
            }
        });
    }
});
/* End of Script */


/* advance Table Script Start */
if ( $('#myadvances').length > 0 ) {
    var myadvance = $('#myadvances').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": baseurl + "ajax/?case=LoadingMyadvances",
        "columnDefs": [{
            "targets": 0,
            "className": "dt-center"
        }]
    });
}
/* End of Script */

/* advance Apply Form Submit Script Start */
if ( $('#advance-form').length > 0 ) {
    $('#advance-form').on('submit', function(e) {
        e.preventDefault();

        var form = $(this);
        $.ajax({
            type     : "POST",
            dataType : "json",
            async    : true,
            cache    : false,
            url      : baseurl + "ajax/?case=ApplyadvanceToAdminApproval",
            data     : form.serialize(),
            success  : function(result) {
                if ( result.code == 0 ) {
                    form[0].reset();
                    $.notify({
                        icon: 'glyphicon glyphicon-ok-circle',
                        message: result.result,
                    },{
                        allow_dismiss: false,
                        type: "success",
                        placement: {
                            from: "top",
                            align: "right"
                        },
                        z_index: 9999,
                    });
                    myadvance.ajax.reload(null, false);
                } else {
                    $.notify({
                        icon: 'glyphicon glyphicon-remove-circle',
                        message: result.result,
                    },{
                        allow_dismiss: false,
                        type: "danger",
                        placement: {
                            from: "top",
                            align: "right"
                        },
                        z_index: 9999,
                    });
                }
            }
        });
    });
}
/* End of Script */


    /* loan Table Script Start */
    if ( $('#allloans').length > 0 ) {
        var loan_table = $('#allloans').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": baseurl + "ajax/?case=LoadingAllloans",
            "columnDefs": [{
                "targets": 0,
                "className": "dt-center"
            }, {
                "targets": -1,
                "orderable": false,
                "data": null,
                "className": "dt-center",
                "defaultContent": '<button class="btn btn-success btn-xs approveloan"><i class="fa fa-check"></i></button> <button class="btn btn-danger btn-xs rejectloan"><i class="fa fa-close"></i></button>'
            }]
        });

        /* Approve loan Application Script Start */
        $('#allloans tbody').on('click', '.approveloan', function(e) {
            e.preventDefault();

            var data = loan_table.row($(this).parents('tr')).data();
            $.ajax({
                type     : "POST",
                dataType : "json",
                async    : true,
                cache    : false,
                url      : baseurl + "ajax/?case=ApproveloanApplication",
                data     : 'id=' + data[0],
                success  : function(result) {
                    if ( result.code == 0 ) {
                        $.notify({
                            icon: 'glyphicon glyphicon-ok-circle',
                            message: result.result,
                        },{
                            allow_dismiss: false,
                            type: "success",
                            placement: {
                                from: "top",
                                align: "right"
                            },
                            z_index: 9999,
                        });
                        loan_table.ajax.reload(null, false);
                    } else {
                        $.notify({
                            icon: 'glyphicon glyphicon-remove-circle',
                            message: result.result,
                        },{
                            allow_dismiss: false,
                            type: "danger",
                            placement: {
                                from: "top",
                                align: "right"
                            },
                            z_index: 9999,
                        });
                    }
                }
            });
        });
        /* End of Script */

        /* Approve loan Application Script Start */
        $('#allloans tbody').on('click', '.rejectloan', function(e) {
            e.preventDefault();

            var data = loan_table.row($(this).parents('tr')).data();
            $.ajax({
                type     : "POST",
                dataType : "json",
                async    : true,
                cache    : false,
                url      : baseurl + "ajax/?case=RejectloanApplication",
                data     : 'id=' + data[0],
                success  : function(result) {
                    if ( result.code == 0 ) {
                        $.notify({
                            icon: 'glyphicon glyphicon-ok-circle',
                            message: result.result,
                        },{
                            allow_dismiss: false,
                            type: "success",
                            placement: {
                                from: "top",
                                align: "right"
                            },
                            z_index: 9999,
                        });
                        loan_table.ajax.reload(null, false);
                    } else {
                        $.notify({
                            icon: 'glyphicon glyphicon-remove-circle',
                            message: result.result,
                        },{
                            allow_dismiss: false,
                            type: "danger",
                            placement: {
                                from: "top",
                                align: "right"
                            },
                            z_index: 9999,
                        });
                    }
                }
            });
        });
        /* End of Script */
    }
    /* End of Script */



    /* Delete Loan Script Start */
    $('#alloans tbody').on('click', '.deleteloans', function(e) {
        e.preventDefault();

        var conf = confirm('Are you sure you want to delete this loan record?');
        if ( conf ) {
            var data = pay_table.row($(this).parents('tr')).data();
            $.ajax({
                type     : "POST",
                dataType : "json",
                async    : true,
                cache    : false,
                url      : baseurl + "ajax/?case=DeleteloanByID",
                data     : 'id=' + data[0],
                success  : function(result) {
                    if ( result.code == 0 ) {
                        $.notify({
                            icon: 'glyphicon glyphicon-ok-circle',
                            message: result.result,
                        },{
                            allow_dismiss: false,
                            type: "success",
                            placement: {
                                from: "top",
                                align: "right"
                            },
                            z_index: 9999,
                        });
                        pay_table.ajax.reload(null, false);
                    } else {
                        $.notify({
                            icon: 'glyphicon glyphicon-remove-circle',
                            message: result.result,
                        },{
                            allow_dismiss: false,
                            type: "danger",
                            placement: {
                                from: "top",
                                align: "right"
                            },
                            z_index: 9999,
                        });
                    }
                }
            });
        }
    });
    /* End of Script */


    /* loan Table Script Start */
    if ( $('#myloans').length > 0 ) {
        var myloan = $('#myloans').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": baseurl + "ajax/?case=LoadingMyloans",
            "columnDefs": [{
                "targets": 0,
                "className": "dt-center"
            }]
        });
    }
    /* End of Script */

    /* loan Apply Form Submit Script Start */
    if ( $('#loan-form').length > 0 ) {
        $('#loan-form').on('submit', function(e) {
            e.preventDefault();

            var form = $(this);
            $.ajax({
                type     : "POST",
                dataType : "json",
                async    : true,
                cache    : false,
                url      : baseurl + "ajax/?case=ApplyloanToAdminApproval",
                data     : form.serialize(),
                success  : function(result) {
                    if ( result.code == 0 ) {
                        form[0].reset();
                        $.notify({
                            icon: 'glyphicon glyphicon-ok-circle',
                            message: result.result,
                        },{
                            allow_dismiss: false,
                            type: "success",
                            placement: {
                                from: "top",
                                align: "right"
                            },
                            z_index: 9999,
                        });
                        myloan.ajax.reload(null, false);
                    } else {
                        $.notify({
                            icon: 'glyphicon glyphicon-remove-circle',
                            message: result.result,
                        },{
                            allow_dismiss: false,
                            type: "danger",
                            placement: {
                                from: "top",
                                align: "right"
                            },
                            z_index: 9999,
                        });
                    }
                }
            });
        });
    }
    /* End of Script */


   /* Payments Table Script Start */
if ( $('#payments').length > 0 ) {
    var paymentsTable = $('#payments').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": baseurl + "ajax/?case=LoadingPayments",
        "columnDefs": [{
            "targets": 0,
            "className": "dt-center"
        }, {
            "targets": 1,
            "className": "dt-center"
        }, {
            "targets": 2,
            "className": "dt-center"
        }, {
            "targets": 3,
            "className": "dt-center"
        }, {
            "targets": 4,
            "className": "dt-center"
        }, {
            "targets": 5,
            "className": "dt-center"
        }]
    });
}
/* End of Script */

/* Payments Form Submit Script Start */
if ( $('#paymentForm').length > 0 ) {
    $('#paymentForm').on('submit', function(e) {
        e.preventDefault();

        var form = $(this);
        $.ajax({
            type     : "POST",
            dataType : "json",
            async    : true,
            cache    : false,
            url      : baseurl + "ajax/?case=InsertPayments",
            data     : form.serialize(),
            success  : function(result) {
                if ( result.code == 0 ) {
                    $.notify({
                        icon: 'glyphicon glyphicon-ok-circle',
                        message: result.result,
                    },{
                        allow_dismiss: false,
                        type: "success",
                        placement: {
                            from: "top",
                            align: "right"
                        },
                        z_index: 9999,
                    });
                    paymentsTable.ajax.reload(null, false);
                } else {
                    $.notify({
                        icon: 'glyphicon glyphicon-remove-circle',
                        message: result.result,
                    },{
                        allow_dismiss: false,
                        type: "danger",
                        placement: {
                            from: "top",
                            align: "right"
                        },
                        z_index: 9999,
                    });
                }
            }
        });
    });
}
/* End of Script */
 
});

function moveItems(origin, dest) {
    $(origin).find(':selected').appendTo(dest);
}

function jsUcfirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function openInNewTab(url) {
    var win = window.open(url, '_blank');
    win.focus();
}

function sendPaySlipByMail(emp_code, month) {
    $.ajax({
        type     : "POST",
        dataType : "json",
        async    : true,
        cache    : false,
        url      : baseurl + "ajax/?case=SendPaySlipByMail",
        data     : 'emp_code=' + emp_code + '&month=' + month,
        success  : function(result) {
            if ( result.code == 0 ) {
                $.notify({
                    icon: 'glyphicon glyphicon-ok-circle',
                    message: result.result,
                },{
                    allow_dismiss: false,
                    type: "success",
                    placement: {
                        from: "top",
                        align: "right"
                    },
                    z_index: 9999,
                });
            } else {
                $.notify({
                    icon: 'glyphicon glyphicon-remove-circle',
                    message: result.result,
                },{
                    allow_dismiss: false,
                    type: "danger",
                    placement: {
                        from: "top",
                        align: "right"
                    },
                    z_index: 9999,
                });
            }
        }
    });
}