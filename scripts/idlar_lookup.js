$(document).ready(function(){

$("#idlar_emp_number").on("keyup", function(){

    let empNum = $(this).val();

    if(empNum.length > 0){

        $.ajax({
            url: "logic/get_employee.php",
            method: "POST",
            data: {employee_number: empNum},
            dataType: "json",

            success:function(data){

                if(data){
                    $("#idlar_emp_name").val(data.employee_name);
                    $("#idlar_division").val(data.functional_division);
                }else{
                    $("#idlar_emp_name").val("");
                    $("#idlar_division").val("");
                }

            }

        });

    }

});

});