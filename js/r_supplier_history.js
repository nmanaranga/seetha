$(document).ready(function(){
    set_session(true);
    
    $("#from, #customer_code, #paper").change(function(){
        set_session(true);
    });
    
    $("#wd").keyup(function(){
        set_session(true);
    });
    
    $("#print").click(function(){
        window.open("/index.php/prints/report_forms/r_supplier_history");
    });
    
    $("#ssupplier").keydown(function(e){
        if(e.keyCode == 13){
            var val = $(this).val();
            val = val.split("~");
            if(val != "" && val.length == 2){
                $("#supplier").val(val[0]);
                $(this).val(val[1]);
            }
            set_session(true);
        }
    });
    
    $("#ssupplier").blur(function(){
        
        var val = $(this).val();
        val = val.split("~");
        if(val != "" && val.length == 2){
            $("#supplier").val(val[0]);
            $(this).val(val[1]);
        }
        set_session(true);
    });
    
    $("#ssupplier").autocomplete('/index.php/main/load_data/m_supplier/auto_com', {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatItem,
        formatResult: formatResult
    });
});

function formatItem(row){
    return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
}

function formatResult(row){
    return row[0]+"~"+row[1];
}

function set_session(l){
    $.post("/index.php/main/load_data/loder/report_session", {
        from : $("#from").val(),
        paper : $("#paper option:selected").val(),
        customer : $("#supplier").val()
    }, function(r){
        if(l){ load_report(); }
    }, "text");
}

function load_report(){
    $.post("/index.php/main/load_data/r_supplier_history/report?echo=true", {
        
    }, function(r){
        $("#report_view").html(r);
    }, "json");
}