$(document).ready(function(){
    $("#code").blur(function(){
        check_code();
    });
    
    $("#grid").tableScroll({height:355});
});

function save(){
    $("#form_").submit();
}

function check_code(){
    var code = $("#code").val();
    $.post("index.php/main/load_data/m_agent/check_code", {
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
    if($("#code").val() == $("#code").attr("title") || $("#code").val() == ""){
            alert("Please enter code");
            $("#code").focus();
            return false;
        }else if($("#name").val() == $("#name").attr("title") || $("#name").val() == ""){
            alert("Please enter name");
            $("#name").focus();
            return false;
        }else if($("#f_name").val() == $("#f_name").attr("title") || $("#f_name").val() == ""){
            alert("Please enter full name");
            $("#f_name").focus();
            return false;
        }else if($("#nic").val() == $("#nic").attr("title") || $("#nic").val() == ""){
            alert("Please enter national identity card number");
            $("#nic").focus();
            return false;
        }else if(
                 ($("#address_no").val() == $("#address_no").attr("title")
                 || $("#address_no").val() == "")
                 && ($("#address_street").val() == $("#address_street").attr("title")
                 || $("#address_street").val() == "")
                 && ($("#address_city").val() == $("#address_city").attr("title")
                 || $("#address_city").val() == "")
                 ){
            alert("Please enter Address");
            return false;
        }else if(
                 ($("#p_mobile").val() == $("#p_mobile").attr("title")
                 || $("#p_mobile").val() == "")
                 && ($("#p_office").val() == $("#p_office").attr("title")
                 || $("#p_office").val() == "")
                 && ($("#p_fax").val() == $("#p_fax").attr("title")
                 || $("#p_fax").val() == "")
                 ){
            alert("Please enter minimum one phone number");
            return false;
        }else if($("#credit_limit").val() == $("#credit_limit").attr("title") || $("#credit_limit").val() == ""){
            alert("Please enter credit limit");
            $("#credit_limit").focus();
            return false;
        }else if($("#credit_days").val() == $("#credit_days").attr("title") || $("#credit_days").val() == ""){
            alert("Please enter credit days");
            $("#credit_days").focus();
            return false;
        }else if($("#br_no").val() == $("#br_no").attr("title") || $("#br_no").val() == ""){
            alert("Please enter BR no");
            $("#br_no").focus();
            return false;
        }else if($("#b_gar").val() == $("#b_gar").attr("title") || $("#b_gar").val() == ""){
            alert("Please enter bank guarantee");
            $("#b_gar").focus();
            return false;
        }else{
            return true;
        }
}
    
function set_delete(code){
    if(confirm("Are you sure delete "+code+"?")){
        loding();
        $.post("index.php/main/delete/m_agent", {
            code : code
        }, function(res){
            if(res == 1){
                location.reload();
            }else{
                alert("Item deleting fail.");
            }
            loding();
        }, "text");
    }
}
    
function set_edit(code){
    loding();
    $.post("index.php/main/get_data/m_agent", {
        code : code
    }, function(res){
        $("#code_").val(res.code);
        $("#code").val(res.code);
        $("#code").attr("readonly", true);
        $("#name").val(res.name);
        $("#f_name").val(res.full_name);
        $("#address_no").val(res.address01);
        $("#address_street").val(res.address02);
        $("#address_city").val(res.address03);
        $("#p_mobile").val(res.phone01);
        $("#p_office").val(res.phone02);
        $("#p_fax").val(res.phone03);
        $("#nic").val(res.nic);
        $("#credit_limit").val(res.credit_limit);
        $("#credit_days").val(res.credit_days);
        $("#br_no").val(res.br_no);
        $("#b_gar").val(res.bank_guarantee_code);
        
        loding(); input_active();
    }, "json");
}