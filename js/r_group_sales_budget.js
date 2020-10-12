$(document).ready(function(){


$(".gridDiv").css("overflow","scroll").css("height","250px");

$("#btnReset").click(function(){
    location.href="?action=r_group_sales_budget";
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
       $("#fdate").val("");
       $("#tdate").val("");
       $("#catogory").val("");
       $("#cat").val("");
    }  
   });




 $(document).on('keydown', ".fo", function (e) {
        set_cid($(this).attr("id")); 
        if (e.keyCode == 112) {
            load_budget_code();
            $("#serch_pop2").center();
            $("#blocker2").css("display", "block");
             setTimeout("$('#pop_search2').focus()", 100); 
        }
    });

 $(document).on('keydown',".fo",function (e) {
        set_cid($(this).attr("id"));
         if (e.keyCode == 46) {
            $("#0_" + scid).val("");
            $("#n_" + scid).val("");
            $("#1_" + scid).val("");
            $("#2_" + scid).val("");
            
        }

     });
 $(document).on('keyup', ".dr_amount", function (e) {
       dr_total();
    });

 $(document).on('keyup', ".cr_amount", function (e) {
       cr_total();
    });

 $("#btnSave").click(function(){
           save();   
    });

$("#btnDelete").click(function(){

           set_delete();   
    });

$("#btnCancel").click(function(){
  if($("#hid").val()!=0) {
    set_cancel($("#hid").val());
  }else{
     set_msg("Please Load Data");
        return false;
  }
  });

$("#btnPrint").click(function(){
    if($("#hid").val()=="0"){
      set_msg("Please load data before print");
      return false;
    }
    else
    {
      $("#print_pdf").submit();
    }
  });

$("#no").keydown(function(e){
        if(e.keyCode == 13){
            $(this).blur();
            load_data($(this).val());
            $(this).attr("readonly","readonly");
            $("#btnSave").attr("disabled","disabled")
            
        }
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
        load_all_data($(this).children().eq(0).html());
        $("#pop_close").click();                
    })    
}

function load_all_data(code){
    $.post("index.php/main/load_data/r_group_sales_budget/load_all_data", {
        code:code
      },function(r){
        
          if(r.det==2){
          set_msg("No records");
        }else{
            $("#fdate").val(r.det[0].fdate);
            $("#tdate").val(r.det[0].tdate);
            $("#catogory").val(r.det[0].category);
            $("#cat").val(r.det[0].description);
          }
      }, "json");
}

  function load_budget_code(){
    $.post("index.php/main/load_data/utility/f1_selection_list", {
        search : $("#pop_search2").val(),
        field2 :'description',
        data_tbl:'m_account'
    }, function(r){
        $("#sr2").html(r);
        settings_budget_code();
    }, "text"); 
}

function settings_budget_code(){
    $("#item_list .cl").click(function(){
        $("#0_"+scid).val($(this).children().eq(0).html());
        $("#n_"+scid).val($(this).children().eq(1).html());

        $("#pop_close2").click();
    });
}

function dr_total(){
   var total=loop=0;
   $(".dr_amount").each(function(){
        var tot=parseFloat($("#1_"+loop).val());
        if(!isNaN(tot)){    
          total=total+tot;
        }   
        loop++;
    });
    $("#dr_tot").val(m_round(total));
}

function cr_total(){
  var cr_total=loop=0;
$(".cr_amount").each(function(){
        var cr_tot=parseFloat($("#2_"+loop).val());
        if(!isNaN(cr_tot)){    
          cr_total=cr_total+cr_tot;
        }  
        loop++;
    });
    $("#cr_tot").val(m_round(cr_total));
  }

  function validate() {
    if($("#code").val()==""){
        set_msg("Please select Group Sales Code");
        return false;
    }
  }

  function save(){
        var frm = $('#form_');
        validate();
        loding();
        $.ajax({
      type: frm.attr('method'),
      url: frm.attr('action'),
      data: frm.serialize(),
      success: function (pid){
                if(pid == 1){               
                    $("#btnSave").attr("disabled",true);
                    loding();
                    /*if(confirm("Save Completed, Do You Want A print?")){
                        if($("#is_prnt").val()==1){
                            $("#qno").val($("#id").val());
                            $("#print_pdf").submit();
                        }
                        reload_form();
                      }else{
                        location.href="";
                      }  */
                      reload_form();
                }else if(pid == 2){
                    set_msg("No permission to add data.");
                }else if(pid == 3){
                    set_msg("No permission to edit data.");
                }else{
                     loding();
                    set_msg(pid);
                }
                            
            }
        });
    }

function reload_form(){
    setTimeout(function(){
    location.href= '';
  },50); 
}

function load_data(id){  
    loding();
    $.post("index.php/main/get_data/r_group_sales_budget/", {
        id: id,

    }, function(r){

        if(r=="2"){
            set_msg("No records");
        }else{
            
            $("#hid").val(id); 
            $("#nno").val(id); 
            $("#ddate").val(r.sales[0].ddate);
            $("#no").val(id); 
            $("#code").val(r.sales[0].code);
            $("#des").val(r.sales[0].name);
            $("#fdate").val(r.sales[0].fdate);
            $("#tdate").val(r.sales[0].tdate);
            $("#catogory").val(r.sales[0].cat_id); 
            $("#cat").val(r.sales[0].category); 
            $("#dr_tot").val(r.sales[0].total_dr); 
            $("#cr_tot").val(r.sales[0].total_cr); 
            $("#note").val(r.sales[0].note);

            for(var i=0; i<r.det.length;i++){
                $("#0_"+i).val(r.det[i].acc_code);
                $("#n_"+i).val(r.det[i].dr_amount);
                $("#1_"+i).val(r.det[i].dr_amount);
                $("#2_"+i).val(r.det[i].cr_amount);
              }
              if(r.sales[0].is_cancel==1){
                $("#r_group_sb").css("background-image","url('img/cancel.png')")
              }

        }
        loding();
    }, "json");  

}
    function set_delete(){
       var id=$("#hid").val();
        if(id=="0"){
            set_msg("Please Load Data");
        return false;
        }else{      
        var id=$("#hid").val();
    if(confirm("Are you sure delete "+id+"?")){
        loding();
        $.post("index.php/main/delete/r_group_sales_budget", {
            id : id
        }, function(res){
            if(res == 1){
                loding();
                delete_msg();
            }else if(res == 2){
                set_msg("No permission to delete data.");
            }else{
                set_msg(res);
            }
        }, "text");
    }
}
}

    function set_cancel(id){
    if(confirm("Are you sure delete "+id+"?")){
        loding();
        $.post("index.php/main/load_data/r_group_sales_budget/cancel", {
            id : id
        }, function(res){
            if(res == 1){
                loding();
                delete_msg();
            }else if(res == 2){
                set_msg("No permission to delete data.");
            }else{
                set_msg(res);
            }
        }, "text");
    }
}


