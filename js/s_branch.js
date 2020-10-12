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
    $.post("index.php/main/load_data/s_branch/check_code", {
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
        }else{
            return true;
        }
}
    
function set_delete(code){
    if(confirm("Are you sure delete "+code+"?")){
        loding();
        $.post("index.php/main/delete/s_branch", {
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
    $.post("index.php/main/get_data/s_branch", {
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
        $("#email").val(res.email);
        
        loding(); input_active();
    }, "json");
}