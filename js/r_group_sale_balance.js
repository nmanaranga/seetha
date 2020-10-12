$(document).ready(function(){

    $("#cluster").change(function() {
        $("#clusters").val($(this).val());
    });
    $("#branch").change(function() {
        $("#branchs").val($(this).val());
    });
    
    $("#cluster").change(function(){
        
        var path;
    
        if($("#cluster").val()!=0)
        {
            path="index.php/main/load_data/r_stock_report/get_branch_name2";
        }
        else
        {
            path="index.php/main/load_data/r_stock_report/get_branch_name3";
        }


        $.post(path,{
        cl:$(this).val(),
        },function(res){
        $("#branch").html(res);
        },'text');  
        
    });

    $("#code").keydown(function(e){

    if(e.keyCode==112){
      $("#pop_search").val($("#code").val());
      load_code();
      $("#serch_pop").center();
      $("#blocker").css("display", "block");
      setTimeout("$('#pop_search').focus()", 100);   
    }
    $("#pop_search").keyup(function(e){
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
        load_code();
      }
    });
    if(e.keyCode==46){
       $("#code").val("");
       $("#des").val("");
      }  
   });

    $("#btnprint").click(function(){
        
           $("#print_pdf").submit(); 
    }); 

    $("#btnReset").click(function(){
    location.href="?action=r_group_sale_balance";
  });

});

function load_code(){
  $.post("index.php/main/load_data/utility/f1_selection_list", {
      data_tbl:"r_groups",
      field:"code",
      field2:"name",
      preview2:"Name",
      search : $("#pop_search").val() 
  }, function(r){
      $("#sr").html(r);
      settings_code();      
 }, "text");
}

function settings_code(){
    $("#item_list .cl").click(function(){        
        $("#code").val($(this).children().eq(0).html());
        $("#des").val($(this).children().eq(1).html());
        $("#pop_close").click();                
    })    
}

