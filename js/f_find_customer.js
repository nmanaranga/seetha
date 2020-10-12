$(document).ready(function(){

$("#btnPrint").click(function(){
        $("#print_pdf").submit();
    });

$("#pop_searchs").keyup(function(){

       load_items();
         
    });   

$("#pop_search_area").keyup(function(){

       load_items();
         
    });   

	 load_items();

	 $("#grid").tableScroll({height:355,width:1190});

$("#find_date").click(function(){
       load_items();
    });

});

function load_items(){
        $.post("index.php/main/load_data/f_find_customer/item_list_all", {
            search : $("#pop_searchs").val(),
            date : $("#date").val(),
            area: $("#pop_search_area").val(),
        }, function(r){
            $("#item_ld").html(r);
            
        }, "text");
    }

    


