$(document).ready(function(){
    $("#all").change(function(){
        if($(this).attr("checked") != undefined){
            $("input[type='checkbox']").attr("checked", "checked");
        }else{
            $("input[type='checkbox']").removeAttr("checked");
        }
    });
    
    $("#up").change(function(){
        $("#upload").submit();
    });
});

function validate(){
    return true;
}

function save(){
    $("#imp").submit();
}