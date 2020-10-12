$(document).ready(function(){
    set_session(true);
    
    $("#by").val('r_bin_card');
    $("#format").val('2');
    $("#page").val('A4');
    $("#headr").val('true');
    $("#orientation").val('P');
    
    $("#from, #item_code, #paper, #stores,#root").change(function(){
        set_session(true);
    });
    
    $("#wd").keyup(function(){
        set_session(true);
    });
    
    //$("#print").click(function(){
    //    window.open("index.php/prints/report_forms/r_bin_card");
    //});
    
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
    
    $("#item_code").autocomplete('/index.php/main/load_data/m_items/auto_com', {
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
        to : $("#to").val(),
        paper : $("#root option:selected").val(),
        wd : $("#wd").val(),
        stores : $("#stores option:selected").val(),
        item_code : $("#item_id").val()
    }, function(r){
        if(l){ load_report(); }
    }, "text");
}

function load_report(){
    $.post("/index.php/main/load_data/r_bin_card/report?echo=true", {
        
    }, function(r){
        $("#report_view").html(r);
    }, "json");
}