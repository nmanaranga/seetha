$(document).ready(function(){
    set_session(true);
    
    $("#from, #type, #paper, #stores").change(function(){
        set_session(true);
    });
    
    $("#wd").keyup(function(){
        set_session(true);
    });
    
    $("#print").click(function(){
        window.open("/index.php/prints/report_forms/r_stock2");
    });
});

function set_session(l){
    $.post("/index.php/main/load_data/loder/report_session", {
        from : $("#from").val(),
        to : $("#to").val(),
        paper : $("#paper option:selected").val(),
        wd : $("#wd").val(),
        sto : $("#stores option:selected").val(),
        type : $("#type option:selected").val()
    }, function(r){
        if(l){ load_report(); }
    }, "text");
}

function load_report(){
    
    $.post("/index.php/main/load_data/r_stock2/report?echo=true", {
        
    }, function(r){
        $("#report_view").html(r);
    }, "json");
}