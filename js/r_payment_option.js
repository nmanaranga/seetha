$(document).ready(function(){

	$("#btnReset").click(function(){
		location.href="index.php?action=r_payment_option";
	});

    $("#code").blur(function(){
        check_code();
    });
     
    $("#grid").tableScroll({height:355});

    $("#type").change(function(){
        var type=$('#type').val();
        set_edit(type);
    });

});

function save(){
    var frm = $('#form_');
    loding();
    $.ajax({
	type: frm.attr('method'),
	url: frm.attr('action'),
	data: frm.serialize(),
	success: function (pid){
            if(pid == 1){
                loding();
                sucess_msg();               
            }else if(pid == 2){
                alert("No permission to add data.");
            }else if(pid == 3){
                alert("No permission to edit data.");
            }else{
                loding();
                alert("Error : \n"+pid);
            }
            
        }
    });
}




function check_code(){
        loding();
    var code = $("#code").val();
    $.post("index.php/main/load_data/r_payment_option/check_code", {
        code : code
    }, function(res){
        if(res == 1){
            if(confirm("This code ("+code+") already added. \n\n Do you need edit it?")){
                set_edit(code);
            }else{
                $("#code").val('');
		$("#code").attr("readonly", false);
            }
        }
        loding();
    }, "text");
}

function validate(){
    if($("#type").val() == "0"){
        alert("Please enter type.");
        $("#type").focus();
        return false;
    }else{
        return true;
    }
}
    
function set_delete(code){
    if(confirm("Are you sure delete "+code+"?")){
        loding();
        $.post("index.php/main/delete/r_payment_option", {
            code : code
        }, function(res){
            if(res == 1){
                get_data_table();
            }else if(res == 2){
                alert("No permission to delete data.");
            }else{
                alert("Item deleting fail.");
            }
            loding();
        }, "text");
    }
}

function is_edit($mod)
{
    $.post("index.php/main/is_edit/user_permissions/is_edit", {
        module : $mod
        
    }, function(r){
       if(r==1)
           {
             $("#btnSave").removeAttr("disabled", "disabled");
           }
       else{
             $("#btnSave").attr("disabled", "disabled");
       }
       
    }, "json");

}
    
function set_edit(type){
    loding();
    $.post("index.php/main/get_data/r_payment_option", {
        type : type
    }, function(res){
        $("#code_").val(res.type);
        $("#type").val(type);
	
        
            if(res.cash == 1){
                $("#cash").attr("checked", "checked");
            }else{
                $("#cash").removeAttr("checked");
            }

             if(res.chq_receive == 1){
                $("#chq_receive").attr("checked", "checked");
            }else{
                $("#chq_receive").removeAttr("checked");
            }

             if(res.credit_card == 1){
                $("#credit_card").attr("checked", "checked");
            }else{
                $("#credit_card").removeAttr("checked");
            }

             if(res.crn == 1){
                $("#crn").attr("checked", "checked");
            }else{
                $("#crn").removeAttr("checked");
            }

             if(res.bank_deposit == 1){
                $("#bank_deposit").attr("checked", "checked");
            }else{
                $("#bank_deposit").removeAttr("checked");
            }

             if(res.discount == 1){
                $("#discount").attr("checked", "checked");
            }else{
                $("#discount").removeAttr("checked");
            }

             if(res.advance == 1){
                $("#advance").attr("checked", "checked");
            }else{
                $("#advance").removeAttr("checked");
            }

             if(res.gift_voucher == 1){
                $("#gift_voucher").attr("checked", "checked");
            }else{
                $("#gift_voucher").removeAttr("checked");
            }

             if(res.credit == 1){
                $("#credit").attr("checked", "checked");
            }else{
                $("#credit").removeAttr("checked");
            }

             if(res.privilege_card == 1){
                $("#privilege_card").attr("checked", "checked");
            }else{
                $("#privilege_card").removeAttr("checked");
            }

             if(res.drn == 1){
                $("#drn").attr("checked", "checked");
            }else{
                $("#drn").removeAttr("checked");
            }

             if(res.chq_issue == 1){
                $("#chq_issue").attr("checked", "checked");
            }else{
                $("#chq_issue").removeAttr("checked");
            }

            if(res.installment == 1){
                $("#installment").attr("checked", "checked");
            }else{
                $("#installment").removeAttr("checked");
            }

            if(res.post_dated_cheques == 1){
                $("#post_dated_cheques").attr("checked", "checked");
            }else{
                $("#post_dated_cheques").removeAttr("checked");
            }
            
        
       // is_edit('010');
       loding(); 

        // input_active();
    }, "json");
}