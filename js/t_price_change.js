var sub_cat;
$(document).ready(function(){
    $("#code").blur(function(){
        check_code();
    });
    
    $("#grid").tableScroll({height:355});
    
    $("#sub_cat").change(function(){
        set_select("sub_cat", "s_cat_des");
    });
    
    $("#main_cat").change(function(){
        set_select("main_cat", "m_cat_des");
        set_sub_cat();
    });
    
    get_sub_cat();
});

function set_sub_cat(){
    var m = $("#main_cat option:selected").val();
    var txt  = "<option value='0'>---</option>";
    if(sub_cat[m] != undefined){
        for(var i=0; i<sub_cat[m].length; i++){
            txt += "<option title='"+sub_cat[m][i][1]+"' value='"+sub_cat[m][i][0]+"'>"+sub_cat[m][i][0]+"</option>";
        }
    }
    
    $("#sub_cat").html(txt);
}

function get_sub_cat(){
    $.post("index.php/main/load_data/m_sub_cat/sub_cat_list", {
        
    }, function(r){
        sub_cat = r;
    }, "json");
}

function save(){
    $("#form_").submit();
}

function validate(){
    var v = parseFloat($("#value").val());
    if($("#main_cat option:selected").attr("value") == "0"){
            alert("Please select main category.");
            $("#main_cat").focus();
            return false;
        }else if($("#sub_cat option:selected").attr("value") == "0"){
            alert("Please select sub category.");
            $("#sub_cat").focus();
            return false;
        }else if($("#type option:selected").attr("value") == "0"){
            alert("Please select base unit.");
            $("#type").focus();
            return false;
        }else if(isNaN(v)){
            alert("Please enter value.");
            $("#value").focus();
            return false;
        }else if(v > 100){
            alert("Please enter Percentage for value.");
            $("#value").focus();
            return false;
        }else{
            return true;
        }
}
    
function set_delete(code){
    if(confirm("Are you sure delete "+code+"?")){
        loding();
        $.post("index.php/main/delete/m_items", {
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
    $.post("index.php/main/get_data/m_items", {
        code : code
    }, function(res){
        $("#code_").val(res.code);
        $("#code").val(res.code);
        $("#description").val(res.description);
        $("#department").val(res.department);
        $("#units").val(res.units);
        $("#sub_cat").val(res.sub_cat);
        $("#main_cat").val(res.main_cat);
        $("#pur_price").val(res.purchase_price);
        $("#sale_price").val(res.cost_price);
        
        if(res.is_measure == 1){
            $("#is_measure").attr("checked", "checked");
        }else{
            $("#is_measure").removeAttr("checked");
        }
        
        set_select("uints", "units_des");
        set_select("department", "dep_des");
        set_select("sub_cat", "s_cat_des");
        set_select("main_cat", "m_cat_des");
        
        loding(); input_active();
    }, "json");
}