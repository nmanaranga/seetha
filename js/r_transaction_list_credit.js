$(document).ready(function(){
	

  var OrAction=$("#print_pdf").attr("action");
  $("#printExcel").click(function(){
    $("#print_pdf").attr("action",OrAction+"/excelReports/generate");
    $("#print_pdf").submit();
    $("#print_pdf").attr("action",OrAction);        
  });

  $("input[type='radio']").click(function(){
    if($(".gr").is(":checked",true)){
      $(".gr_f").css("display","block");
    }else{
      $(".gr_f").css("display","none");
    }
  });


  $("#btnPrint").click(function(){
    if($("#by").val()!=""){
      $("#print_pdf").attr("action",OrAction+"/reports/generate");
      $("#print_pdf").submit();
      $("#print_pdf").attr("action",OrAction);
    }else{
      alert("Please select report type");
      return false;
    }
  });

  $('input:radio').click(function () {
    if ($(this).attr('excel')) {
      $('#printExcel').removeAttr("disabled")
    } else{
      $('#printExcel').attr("disabled","disabled")
    };
  });

  $("input[type='radio']").click(function(){
    var thId=$(this).attr("title");
    if(thId=='r_credit_sales_gross_profit'){
      $("#MnTbl").find( "tr" ).show();        
        // $("#MnTbl").find( "tr" ).hide();   
        // $("#MnTbl").find( "tr" ).eq(6).show();
        $("#MnTbl").find( "tr" ).eq(1).hide();
        $("#MnTbl").find( "tr" ).eq(2).hide();
        $("#MnTbl").find( "tr" ).eq(3).hide();
      }else{
        $("#MnTbl").find( "tr" ).hide();
        // $("#MnTbl").find( "tr" ).find( "td" ).show(); 
        $("#MnTbl").find( "tr" ).eq(0).show();
        $("#MnTbl").find( "tr" ).eq(1).show();
        $("#MnTbl").find( "tr" ).eq(2).show();
        $("#MnTbl").find( "tr" ).eq(3).show();
      } 
    });



  $("#r_customer").keydown(function(e){
    if(e.keyCode == 112){
      $("#pop_search2").val($("#r_customer").val());
      load_data9();
      $("#serch_pop2").center();
      $("#blocker").css("display", "block");
      setTimeout("$('#pop_search2').focus()", 100);
    }

    $("#pop_search2").keyup(function(e){

      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
       load_data9();
     }
   }); 

    if(e.keyCode == 46){
      $("#r_customer").val("");
      $("#r_customer_des").val("");
    }
  });

  
  $("#group_id").keydown(function(e){
    if(e.keyCode == 112){
      $("#pop_search14").val($("#group_id").val());
      load_group();
      $("#serch_pop14").center();
      $("#blocker").css("display", "block");
      setTimeout("$('#pop_search14').focus()", 100);
    }

    $("#pop_search14").keyup(function(e){
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
       load_group();
     }
   }); 

    if(e.keyCode == 46){
      $("#group_id").val("");
      $("#group_name").val("");
    }
  });

});


function load_group(){
  $.post("index.php/main/load_data/utility/f1_selection_list", {
    data_tbl:"r_groups",
    field:"code",
    field2:"name",
    preview1:"Group Code",
    preview2:"Group Description",
    add_query:" AND cl='"+$("#cluster").val()+"' AND bc='"+$("#branch").val()+"' AND category='"+$("#sales_category").val()+"' ",
    search : $("#pop_search14").val() 
  }, function(r){
    $("#sr14").html(r);
    settings_group();            
  }, "text");
}

function settings_group(){
  $("#item_list .cl").click(function(){        
    $("#group_id").val($(this).children().eq(0).html());
    $("#group_name").val($(this).children().eq(1).html());        
    $("#pop_close14").click();                
  })    
}

function load_data9(){
  $.post("index.php/main/load_data/utility/f1_selection_list_customer", {
    data_tbl:"m_customer",
    field:"code",
    field2:"name",
    field3:"nic",
    preview1:"Customer ID",
    preview2:"Customer Name",
    preview3:"Customer NIC",
    search : $("#pop_search2").val() 
  }, function(r){
    $("#sr2").html(r);
    settings9();            
  }, "text");
}

function settings9(){
  $("#item_list .cl").click(function(){        
    $("#r_customer").val($(this).children().eq(0).html());
    $("#r_customer_des").val($(this).children().eq(1).html());        
    $("#pop_close2").click();                
  })    
}