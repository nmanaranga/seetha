var root = new Array();
$(document).ready(function(){
    set_session(true);
    
    $("#type, #root, #paper, #area").change(function(){
        set_session(true);
    });
    
    $("#wd").keyup(function(){
        set_session(true);
    });
    
    $("#print").click(function(){
        window.open("/index.php/prints/report_forms/r_age_analyze_supplier");
    });
    
    $("#item_code").keydown(function(e){
        if(e.keyCode == 13){
            var val = $(this).val();
            val = val.split("~");
            if(val != "" && val.length == 2){
                $("#item_id").val(val[0]);
                $(this).val(val[1]);
            }
            set_session(true);
        }
    });
    
    $("#item_code").blur(function(){
        
        var val = $(this).val();
        val = val.split("~");
        if(val != "" && val.length == 2){
            $("#item_id").val(val[0]);
            $(this).val(val[1]);
        }
        set_session(true);
    });
    
    $("#area").change(function(){
        set_root();
    });
    
    get_route_list()
});

function set_root(){
    var txt = "<option value='0'>---</option>";
    var area = $("#area option:selected").val();
    if(root[area] != undefined){
        for(var i=0; i<root[area].length; i++){
            txt += "<option value='"+root[area][i][0]+"'>"+root[area][i][1]+"</option>";
        }
    }
    
    $("#root").html(txt);
}

function get_route_list(){
    $.post("index.php/main/load_data/m_root/select_area_wise", {
        
    }, function(r){
        root = r;
    }, "json");
}

function set_cus_values(f){
    
    var v = f.val();
    v = v.split("~");
    if(v.length == 2){
	f.val(v[0]);
	$("#customer").val(v[0]);
	$("#cus_des").val(v[1]);
	$("#cus_des").attr("class", "input_txt_f");
        set_session();
    }
}

function formatCustomer(row){
    return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
}

function formatCustomerResult(row){
    return row[0]+"~"+row[1];
}


function set_session(l){
    $.post("/index.php/main/load_data/loder/report_session", {
        paper : $("#paper option:selected").val(),
        type : $("#type option:selected").val(),
        area : $("#area option:selected").val(),
        root : $("#root option:selected").val()
    }, function(r){
        if(l){ load_report(); }
    }, "text");
}

function load_report(){
    $.post("/index.php/main/load_data/r_age_analyze_supplier/report?echo=true", {
        
    }, function(r){
        $("#report_view").html(r);
    }, "json");
}