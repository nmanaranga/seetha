
$(document).ready(function(){

  $("#load").click(function(){
    load_vouchers();         
  });

  $("#grid").tableScroll({height:455,width:1140});    
  $("#pop_search").gselect();
});


function load_vouchers(){
  $.post("index.php/main/load_data/chq_pending_list/voucher_list_all", {
    from_date : $("#from_date").val(),
    to_date : $("#to_date").val()
  }, function(r){
    $("#item_ld").html("");
    if(r==2){
      set_msg("No Records");
    }else{
      $("#item_ld").html(r);
      settings5();
    }
   }, "text");
}


function settings5(){
  $("#grid .cl").click(function(){  
    $(".cl").children().find('input').css('background-color', '#f9f9ec');  
    $(this).children().find('input').css('background-color', '#66CCFF');    
    var no = $(this).children().find('input').eq(1).val();
    var chq_no = $(this).children().find('input').eq(5).val();
    var t_no = $(this).children().find('input').eq(10).val();
    //location.href="?action=chq_print&xxx="+no+"|"+chq_no+"|"+t_no+"";  
    window.open("?action=chq_print&xxx="+no+"|"+chq_no+"|"+t_no+"","_blank");        
  });
}




