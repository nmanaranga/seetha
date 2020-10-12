var root = new Array();
$(document).ready(function(){
    set_session(true);
    
    $("#from, #paper, #to,#sale").change(function(){
        set_session(true);

    });
    
    $("#wd").keyup(function(){
        set_session(true);
    });
    
    $("#print").click(function(){
        window.open("/index.php/prints/report_forms/r_qty_list");
    });
    
    $("#area").change(function(){
        set_root();
    });
    
    get_route_list();
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

function set_session(l){
    $.post("/index.php/main/load_data/loder/report_session", {
        from : $("#from").val(),
        to : $("#to").val(),
        paper : $("#paper option:selected").val(),
        sale : $("#sale option:selected").val()

    }, function(r){
        if(l){ load_report(); }
    }, "text");
}

function load_report(){
    $.post("/index.php/main/load_data/r_qty_list/report?echo=true", {
        
    }, function(r){
        $("#report_view").html(r);
    }, "json");
}