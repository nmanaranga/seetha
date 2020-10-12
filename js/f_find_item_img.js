$(document).ready(function(){

   $("#pop_searchs").keyup(function(){
       load_items();         
    });   
    $("#grid").tableScroll({height:355,width:905});
   // $("#tablescroll_wrapper").tableScroll({width:910});

    $("#item").keydown(function(e){
        if(e.keyCode==112){
            $("#pop_search").val();
            load_items();
            $("#serch_pop").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search').focus();", 100); 
        }
         if(e.keyCode==46){
            $("#item").val("");
            $("#item_des").val("");
            $("#item_ld").html(""); 
        }
    });
    $("#pop_search").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { 
            load_items();
        }
    });
    $("#pop_search").gselect();
});

function load_items(){
     $.post("index.php/main/load_data/utility/f1_selection_list", {
            data_tbl:"m_item",
            field:"code",
            field2:"description",
            preview2:"Description",
            search : $("#pop_search").val() 
        }, function(r){
            $("#sr").html(r);
            settings();            
        }, "text");
}

function settings(){
    $("#item_list .cl").click(function(){       
        $("#item").val($(this).children().eq(0).html());
        $("#item_des").val($(this).children().eq(1).html());
        load_images($(this).children().eq(0).html());
        $("#pop_close").click(); 
    })    
}

function load_images(item){
    loding();
    $.post("index.php/main/load_data/f_find_item_img/load_img", {
        code:item
    }, function(r){
        var y = 1;
        var tb= "";
        $("#item_ld").html(""); 
        if(r==2){
            set_msg("Images not available for this item");
            $("#item_ld").append("<td width='300px;'style='text-align:center;'><img width='150px' border='0' hspace='9' vspace='9' height='150px' src=images/non_img.jpg></td>");       
        }else{
            $("#item_ld").append("<tr>");     
            for(var x=0; x<r.length; x++){
                $("#item_ld").append("<td><img width='150px' border='0' hspace='9' vspace='9' height='150px' src='" + r[x].picture + "' style=' border-radius: 25px;border: 2px solid #7D7B76;padding: 1px;'/></td>");       
            }
            $("#item_ld").append("</tr>");    
        }
        loding();    
    }, "json");
}