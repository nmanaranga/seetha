var is_edit=0;

$(document).ready(function(){
    $("#code").blur(function(){
        check_code();
    });
    
    $("#btnReset").click(function(){
        location.href="?action=m_supplier";
    });

    $("#sup_list").click(function(){
        $("#print_pdf").submit();
    });

    // $("#btnSave").click(function(){
    // if(validate()){
    //     check_permission();
    // }    
    // });
    
    $("#grid").tableScroll({height:355,width:590});
    
    $('#tabs').tabs();

    $("#category").change(function(){
        set_select('category', 'category_id');
    });



    $("#srchee").keyup(function(){  
      $.post("index.php/main/load_data/utility/get_data_table", {
         code:$("#srchee").val(),
         tbl:"m_supplier",
         tbl_fied_names:"Code-Name",
         fied_names:"code-name"
     }, function(r){
         $("#grid_body").html(r);
     }, "text");

  });

    $(".contact").change(function(){
        set_cid($(this).attr("id"));

        $("#type_"+scid).val($("#contact_"+scid).val());


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
        set_msg("No permission to add data.");
    }else if(pid == 3){
        set_msg("No permission to edit data.");
    }else{
        set_msg(pid,"error");
    }

}
});
}


function get_data_table(){
    $.post("index.php/main/load_data/m_supplier/get_data_table", {

    }, function(r){
        $("#grid_body").html(r);
    }, "text");
}

function get_max_no(){
    $.post("index.php/main/load_data/m_supplier/generate_code", {

    }, function(r){
        // set_msg(r);
        // // $("#code").html(r);
    }, "text");
}


function check_code(){
    var code = $("#code").val();
    $.post("index.php/main/load_data/m_supplier/check_code", {
        code : code
    }, function(res){
        if(res == 1){
            if(confirm("This code ("+code+") already added. \n\n Do you need edit it?")){
                set_edit(code);
            }else{
                $("#code").val('');
            }
        }
    }, "text");
}

function validate(){


    if($("#code").val() == ""){
        set_msg("Please enter code");
        $("#code").focus();
        return false;
    }else if($("#code_gen").val()==""){
        set_msg("Please enter code key");
        $("#code_gen").focus();
        return false;
    }else if($("#name").val() == $("#name").attr("title") || $("#name").val() == ""){
        set_msg("Please enter name");
        $("#name").focus();
        return false;
    }else if($("#category").val() =="0"){
        set_msg("Please select category");
        $("#control").focus();
        return false;
    }else{
     return true;
 }


}


function set_delete(code){

   if(confirm("Are you sure delete "+code+"?")){
    loding();
    $.post("index.php/main/delete/m_supplier", {
        code : code
    }, function(res){
        if(res == 1){
            loding();
            delete_msg();
        }else{
            set_msg(res);
        }

    }, "text");
}
}

function set_edit(code){
    loding();
    $.post("index.php/main/get_data/m_supplier", {
        code : code
    }, function(res){
        $("#code_").val(res.data.code);
        $("#code").val(res.data.code);
        $("#code_gen").val(res.data.code_gen);
        $("#code").attr("readonly", true);
        $("#name").val(res.data.name);
        $("#control").val(res.data.control_acc);
        $("#contact_name").val(res.data.contact_name);
        $("#payee_name").val(res.data.payee_name);
        $("#address1").val(res.data.address1);
        $("#address2").val(res.data.address2);
        $("#address3").val(res.data.address3);
        $("#email").val(res.data.email);
        $("#doj").val(res.data.doj);
        $("#postal_code").val(res.data.postal_code);

        $("#category").val(res.data.category);
        $("#credit_period").val(res.data.credit_period);
        $("#tax_reg_no").val(res.data.tax_reg_no);

        $("#bl_reason").val(res.data.bl_reason);
        $("#bl_officer").val(res.data.bl_officer);
        $("#bl_date").val(res.data.bl_date);
        
        set_select('category', 'category_id');

       // $("#control_acc").val(res.data.phone02);
       // $("#balance").val(res.data.);

       $("#credit_limit").val(res.data.credit_limit);



       if(res.data.is_tax==1){
        $("#is_tax").attr("checked", "checked");
    }else{
        $("#is_tax").removeAttr("checked");  
    }
    if(res.data.is_inactive==1){
        $("#is_inactive").attr("checked", "checked");
    }else{
        $("#is_inactive").removeAttr("checked");  
    }
    if(res.data.is_blacklist==1){
        $("#is_blacklist").attr("checked", "checked");
    }else{
        $("#is_blacklist").removeAttr("checked");  
    }            

    for(var i=0; i<10; i++){
        $("#type_"+i).val("");
        $("#contact_"+i).val("");
        $("#tp_"+i).val("");
        $("#date_"+i).val("");
        $("#des_"+i).val("");
        $("#comment_"+i).val("");
    }

    for(var i=0; i<res.c.length; i++){
        $("#contact_"+i).val(res.c[i].type);
        $("#type_"+i).val(res.c[i].type);
        $("#des_"+i).val(res.c[i].description);
        $("#tp_"+i).val(res.c[i].tp);
    }

    for(var i=0; i<res.comment.length; i++){
        $("#date_"+i).val(res.comment[i].ddate);
        $("#comment_"+i).val(res.comment[i].comment);
    }

    $("#balance").val(res.acc);

        //$("#nic").val(res.nic);
        //$("#credit_limit").val(res.credit_limit);
        //$("#credit_days").val(res.credit_days);
        //$("#br_no").val(res.br_no);
        //$("#b_gar").val(res.bank_guarantee_code);
        
        loding(); input_active();
        is_edit=1;
    }, "json");
}