var move_window = null; var move = false; var pos; var mpos; var z_in = 1;
$(document).ready(function(){
    $(".head").mousedown(function(e){
        move = true;
        move_window = make_id($(this).attr("id"));
        pos = $(this).offset();
        mpos = e;
        $("#window_"+move_window).css("z-index", ++z_in);
    });
    
    $(".window").click(function(){
        $(this).css("z-index", ++z_in);
    });
    
    $(".head").mouseup(function(){
        move = false;
        move_window = null;
    });
    
    $(".close").click(function(){
        move_window = make_id($(this).attr("id"));
        $("#window_"+move_window).fadeOut(100);
    });
    
    $(".min").click(function(){
        move_window = make_id($(this).attr("id"));
        if($("#window_"+move_window).css("height") != "20px"){
            localStorage.setItem(move_window, $("#window_"+move_window).css("height"));
            $("#window_"+move_window).css("height", 20);
        }else{
            $("#window_"+move_window).css("height", localStorage.getItem(move_window));
        }
    });
});

function open_win(win){
    $("#window_"+win).fadeIn(100);
}

$(document).mousemove(function(e){
    if(move == true && move_window != null){
        $("#window_"+move_window).css("top", (e.pageY - (mpos.pageY - pos.top)));
        $("#window_"+move_window).css("left", (e.pageX - (mpos.pageX - pos.left)));
    }
});

function make_id(id){
    id = id.split("_");
    
    return id[1];
}
