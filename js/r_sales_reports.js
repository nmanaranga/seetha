var sub_cat;

var is_edit = 0;

$(document).ready(function(){

  $("#brand").change(function() {
    set_select("brand", "brand_des");
  });
  $("#sc").tableScroll({
    height: 200
  });
  $("#item_gen").click(function() {
    generate_code();
  });
  input_active();

  $("#cluster").val($("#d_cl").val());
  cl_change();

  $('input:radio').click(function () {
    if ($(this).attr('excel')) {
     $('#printExcel').removeAttr("disabled")
   } else{
     $('#printExcel').attr("disabled","disabled")
   };

 });

  $("#department").click(function() {
    $(this).addClass("input_active");
    $("#main_category").removeClass("input_active");
    $("#sub_category").removeClass("input_active");
    $("#unit").removeClass("input_active");
    $("#supplier").removeClass("input_active");
  });
  $("#main_category").click(function() {
    $(this).addClass("input_active");
    $("#department").removeClass("input_active");
    $("#sub_category").removeClass("input_active");
    $("#unit").removeClass("input_active");
    $("#supplier").removeClass("input_active");
  });
  $("#sub_category").click(function() {
    $(this).addClass("input_active");
    $("#department").removeClass("input_active");
    $("#main_category").removeClass("input_active");
    $("#unit").removeClass("input_active");
    $("#supplier").removeClass("input_active");
  });
  $("#department").focus(function() {
    $("#department").addClass("input_active");
  });


  $("#department").keydown(function(a) {
    var b = a.keyCode || a.which;
    if (9 == b) {
      a.preventDefault();
      $("#main_category").focus();
      $("#department").removeClass("input_active");
      $("#main_category").addClass("input_active");
    }
    if (46 == b){
      $("#department").val("");
      $("#department_des").val("");
    }
  });

  $("#main_category").keydown(function(a) {
    var b = a.keyCode || a.which;
    if (9 == b) {
      a.preventDefault();
      $("#main_category").removeClass("input_active");
      $("#sub_category").addClass("input_active");
      $("#sub_category").focus();
    }
    if (46 ==b ){
      $("#main_category").val("");
      $("#main_category_des").val("");
    }
  });

  $("#sub_category").keydown(function(a) {
    var b = a.keyCode || a.which;
    if (9 == b) {
      a.preventDefault();
      $("#sub_category").removeClass("input_active");
      $("#unit").addClass("input_active");
      $("#unit").focus();
    }
    if (46 == b){
      $("#sub_category").val("");
      $("#sub_category_des").val("");
    }
  });




    var OrAction=$("#print_pdf").attr("action");//1020-106

    $("#print").click(function(){
      if($("input[type='radio']:checked").length == 0){
        alert("Please select report");
        return false;
      }
                $("#print_pdf").attr("action",OrAction+"/reports/generate");//1020-106
        $("#print_pdf").submit();//1020-106
        $("#print_pdf").attr("action",OrAction);//1020-106
      });   



    $("#printExcel").click(function(){//1020-106
      $("#print_pdf").attr("action",OrAction+"/excelReports/generate");
      $(".printExcel").submit();
      $("#print_pdf").attr("action",OrAction);        

    }); 


    $("#branch").click(function(){
     if($("#cluster").val()=="0"){
      alert("Please select cluster");
      return false;
    }
  });


    $("#department").keydown(function(e){ 
      if(e.keyCode==112){
        $("#pop_search").val($("#department").val());
        load_dep();
        $("#serch_pop").center();
        $("#blocker").css("display", "block");
        setTimeout("$('#pop_search').focus()", 100);   
      }
      $("#pop_search").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
          load_dep();
        }
      });
      if(e.keyCode==46){
        $("#department").val("");
        $("#department_des").val("");
      }  
    });


    $("#main_category").keydown(function(e){ 
      if(e.keyCode==112){
        $("#pop_search2").val($("#main_category").val());
        load_mcat();
        $("#serch_pop2").center();
        $("#blocker2").css("display", "block");
        setTimeout("$('#pop_search2').focus()", 100);   
      }
      $("#pop_search2").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
          load_mcat();
        }
      });
      if(e.keyCode==46){
        $("#main_category").val("");
        $("#main_category_des").val("");
      }  
    });

    $("#sub_category").keydown(function(e){ 
      if(e.keyCode==112){    
        $("#pop_search10").val($("#sub_category").val());
        load_scat();
        $("#serch_pop10").center();
        $("#blocker").css("display", "block");
        setTimeout("$('#pop_search10').focus()", 100);   
      }
      $("#pop_search10").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
          load_scat();
        }
      });
      if(e.keyCode==46){
        $("#sub_category").val("");
        $("#sub_category_des").val("");
      }  
    });


    $("#salesman").keydown(function(e){ 
      if(e.keyCode==112){    
        $("#pop_search14").val($("#salesman").val());
        load_salesman();
        $("#serch_pop14").center();
        $("#blocker").css("display", "block");
        setTimeout("$('#pop_search14').focus()", 100);   
      }
      $("#pop_search14").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
          load_salesman();
        }
      });
      if(e.keyCode==46){
        $("#salesman").val("");
        $("#salesman_des").val("");
      }  
    });


    $("#supplier").keydown(function(e){ 
      if(e.keyCode==112){    
        $("#pop_search14").val($("#supplier").val());
        load_supplier();
        $("#serch_pop14").center();
        $("#blocker").css("display", "block");
        setTimeout("$('#pop_search14').focus()", 100);   
      }
      $("#pop_search14").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
          load_supplier();
        }
      });
      if(e.keyCode==46){
        $("#supplier").val("");
        $("#salesman_des").val("");
      }  
    });


//--------------------------------------------------------------------------------


$("#branch").change(function(){
  $("#store").val("");


  if($("#branch").val()!=0)
  {
   $.post("index.php/main/load_data/r_stock_report/get_stores_bc",{
    bc:$(this).val(),
  },function(res){
    $("#store").html(res);
  },'text');	


 }
 else if($("#cluster").val()!="0")
 {
   $.post("index.php/main/load_data/r_stock_report/get_stores_cl",{
    cl:$("#cluster").val(),
  },function(res){
    $("#store").html(res);
  },'text');	

 }
 else
 {
   $.post("index.php/main/load_data/r_stock_report/get_stores_default",{
    cl:$("#cluster").val(),
  },function(res){
    $("#store").html(res);
  },'text');	
 }

});

$("#cluster").change(function(){
  $("#store").val("");


  var path;
  var path_store;

  if($("#cluster").val()!=0)
  {
   path="index.php/main/load_data/r_stock_report/get_branch_name2";
   path_store="index.php/main/load_data/r_stock_report/get_stores_cl";
 }
 else
 {
   path="index.php/main/load_data/r_stock_report/get_branch_name3";
   path_store="index.php/main/load_data/r_stock_report/get_stores_default";
 }


 $.post(path,{
   cl:$(this).val(),
 },function(res){
   $("#branch").html(res);
 },'text');	


 $.post(path_store,{
   cl:$(this).val(),
 },function(res){
   $("#store").html(res);
 },'text');	

});



$("#btnExit").click(function(){
  return false;
});


});

function cl_change(){
  $("#store").val("");

  var path;
  var path_store;

  if($("#cluster").val()!=0)
  {
    path="index.php/main/load_data/r_stock_report/get_branch_name2";
    path_store="index.php/main/load_data/r_stock_report/get_stores_cl";
  }
  else
  {
    path="index.php/main/load_data/r_stock_report/get_branch_name3";
    path_store="index.php/main/load_data/r_stock_report/get_stores_default";
  }


  $.post(path,{
    cl:$("#cluster").val(),
    bc:$("#d_bc").val()
  },function(res){
    $("#branch").html(res);
    $("#branch").val($("#d_bc").val());
  },'text');  


  $.post(path_store,{
    cl:$("#cluster").val(),
    bc:$("#d_bc").val()
  },function(res){
    $("#store").html(res);
    $("#branch").val($("#d_bc").val());
  },'text');  


}


function select_search() {
  $("#pop_search").focus();
}


function load_dep(){
  $.post("index.php/main/load_data/utility/f1_selection_list", {
    data_tbl:"r_department",
    field:"code",
    field2:"description",
    preview2:"Department Name",
    search : $("#pop_search").val() 
  }, function(r){
    $("#sr").html(r);
    settings_dep();      
  }, "text");
}

function settings_dep(){
  $("#item_list .cl").click(function(){        
    $("#department").val($(this).children().eq(0).html());
    $("#department_des").val($(this).children().eq(1).html());
    $("#pop_close").click();                
  })    
}

function load_mcat(){
  if($("#department").val() !=""){
    add_q=" AND de_code= '"+$("#department").val()+"'";
  }else{
    add_q="";
  }
  $.post("index.php/main/load_data/utility/f1_selection_list", {
    data_tbl:"r_category",
    field:"code",
    field2:"description",
    preview2:"Category Name",
    search : $("#pop_search2").val(),
    add_query :add_q  
  }, function(r){
    $("#sr2").html(r);
    settings_mcat();      
  }, "text");
}

function settings_mcat(){
  $("#item_list .cl").click(function(){        
    $("#main_category").val($(this).children().eq(0).html());
    $("#main_category_des").val($(this).children().eq(1).html());
    $("#pop_close2").click();                
  })    
}

function load_scat(){
  if($("#main_category").val() !=""){
    add_q=" AND main_category= '"+$("#main_category").val()+"'";
  }else{
    add_q="";
  }
  $.post("index.php/main/load_data/utility/f1_selection_list", {
    data_tbl:"r_sub_category",
    field:"code",
    field2:"description",
    preview2:"Sub Category Name",
    search : $("#pop_search10").val(),
    add_query :add_q   
  }, function(r){
    $("#sr10").html(r);
    settings_scat();      
  }, "text");
}

function settings_scat(){
  $("#item_list .cl").click(function(){        
    $("#sub_category").val($(this).children().eq(0).html());
    $("#sub_category_des").val($(this).children().eq(1).html());
    $("#pop_close10").click();                
  })    
}

function load_salesman() {
 $.post("index.php/main/load_data/utility/f1_selection_list_emp", {
  filter_emp_cat:"salesman",
  search : $("#pop_search14").val() 
}, function(r){
  $("#sr14").html(r);
  settings_salesman();      
}, "text");
}

function settings_salesman(){
  $("#item_list .cl").click(function(){        
    $("#salesman").val($(this).children().eq(0).html());
    $("#salesman_des").val($(this).children().eq(1).html());
    $("#pop_close14").click();                
  })    
}

function load_supplier() {
 $.post("index.php/main/load_data/utility/f1_selection_list", {
  data_tbl:"m_supplier",
  field:"code",
  field2:"name",
  preview2:"Supplier",
  search : $("#pop_search14").val() 
}, function(r){
  $("#sr14").html(r);
  settings_supplier();      
}, "text");
}

function settings_supplier(){
  $("#item_list .cl").click(function(){        
    $("#supplier").val($(this).children().eq(0).html());
    $("#supplier_des").val($(this).children().eq(1).html());
    $("#pop_close14").click();                
  })    
}




