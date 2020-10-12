
$(document).ready(function(){
    $("#code").blur(function(){
        check_code();
    });
     
    $("#grid").tableScroll({height:355});

    $("#search").keyup(function(){
      load_service();
    });

     $(".chk").click(function(){
        set_cid($(this).attr("id"));
        if($("#sel_"+scid).is(":checked")){
            $("#4_"+scid).removeAttr("readonly");

        }else{

            $("#4_"+scid).prop('readonly','true');
            $("#4_"+scid).val("");
           
        }
    });

    $("#id").keydown(function(e){
        if(e.keyCode== 13){
          $("#hid").val($(this).val());
            load_data();
        }
    });

    $("#btnPrint").click(function(){
      if($("#qno").val()==""){
        set_msg("Please Load A Record")
      }else{
      $("#print_pdf").submit();
    }
    });
    
    $("#btnReset").click(function(){
        for(i=0;i<25;i++){
          $("#sel_"+i).removeAttr("checked");

        }
    });
  
    $("#btnDelete").click(function(){
        set_delete();
    });
     $("#supplier").keydown(function(e){
        if(e.keyCode == 112){
             $("#pop_search4").val();
             select_supplier();
             $("#serch_pop4").center();   
             $("#blocker").css("display","block");
             setTimeout("$('#pop_search4').focus()", 100); 
        }
      $("#pop_search4").keyup(function(e){
          if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ){ 
            select_supplier();
            }
      });
        if(e.keyCode == 46){
            $("#supplier").val("");
            $("#sup_name").val("");
        }
     });  
 });



function save(){
    var frm = $('#form_');
    loding();
    $.ajax({
	type: frm.attr('method'),
	url: frm.attr('action'),
	data: frm.serialize(),
	success: function (pid){
            if(pid == 1){
               loding();
              sucess_msg();
            }else if(pid == 2){
              set_msg("No permission to add data.");
            }else if(pid== 3){
               set_msg("No permission to edit data.");
            }else{
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

function check_code(){
        loding();
    var code = $("#code").val();
    $.post("index.php/main/load_data/t_job_reject/check_code", {
        code : code
    }, function(res){
        if(res == 1){
            if(confirm("This code ("+code+") already added. \n\n Do you need edit it?")){
                set_edit(code);
            }else{
                $("#code").val('');
		$("#code").attr("readonly", false);
            }
        }
        loding();
    }, "text");
}

function validate(){
  
    if($("#supplier").val() === $("#supplier").attr('title') || $("#supplier").val() == ""){
        set_msg("Please Select Supplier.");
        $("#supplier").focus();
        return false;
    }else if($("#sel_"+scid).is(":checked") && $("#4_"+scid).val()==""){
        set_msg("Pleas Enter Service Reject reason");
        $("#4_"+scid).focus();
        return false;

    }else if(!$("#sel_"+scid).is(":checked")){
      set_msg("Please select a job");
      return false;
    }
    else{
      return true;
    }
}
    
function set_delete(){
    if(confirm("Are you sure, you want delete ?")){
        loding();
        $.post("index.php/main/load_data/t_job_reject/delete",{
            id: $("#hid").val()
        }, function(res){
            if(res == 1){
              reload_form();
            }else if(res == 2){
                alert("No permission to delete data.");
            }else{
                alert("Item deleting fail.");
            }
            loding();
        }, "text");
    }
}

/*function is_edit($mod)
{
    $.post("index.php/main/is_edit/user_permissions/is_edit", {
        module : $mod
        
    }, function(r){
       if(r==1)
           {
             $("#btnSave").removeAttr("disabled", "disabled");
           }
       else{
             $("#btnSave").attr("disabled", "disabled");
       }
       
    }, "json");

}*/
    
/*function set_edit(code){
    loding();
    $.post("index.php/main/get_data/t_job_reject", {
        code : code
    }, function(res){
        $("#code_").val(res.code);
        $("#code").val(res.code);
	$("#code").attr("readonly", true);
        $("#description").val(res.description);
        
           if(res.is_vehical == 1){
            $("#is_vehical").attr("checked", "checked");
        }else{
            $("#is_vehical").removeAttr("checked");
        }
        
        
        
       // is_edit('010');
        loding(); input_active();
    }, "json");
}
*/
function select_supplier(){

    $.post("index.php/main/load_data/t_job_reject/select_supplier",{
            data_tbl:"m_supplier",
            field:"code",
            field2:"name",
            search:$("#pop_search4").val()
    },function(r){
        $("#sr4").html(r);
        supplier_settings();
      }, "text");
}


function supplier_settings(){
    $("#item_list .cl").click(function(){
        $("#supplier").val($(this).children().eq(0).html());    
        $("#sup_name").val($(this).children().eq(1).html()); 
        load_service();
        $("#pop_close4").click(); 
      });
}

function load_service(){

    $.post("index.php/main/load_data/t_job_reject/load_service_items",{
          supplier:$("#supplier").val(),
          search:$("#search").val()

    },function(r){
        empty_grid();
        if(r.a==2){
          
        }else{
          for(var i = 0;i < r.a.length;i++){
             $("#0_"+i).val(r.a[i].job_no);
             $("#n_"+i).val(r.a[i].ddate);
             $("#1_"+i).val(r.a[i].cus_id);
             $("#2_"+i).val(r.a[i].name);
             $("#3_"+i).val(r.a[i].item_code);
            }
        } 
    }, "json");
}


function empty_grid(){
    $("#tgrid input").each(function(){
        $(this).val("");
    });
}
function load_data(){
  $.post("index.php/main/load_data/t_job_reject/load",{
        id:$("#id").val()
  },function(r){
      empty_grid();
      if(r.a==2){
          set_msg("No Records");
      }else{   
         if(r.a[0].is_cancel == 1){
            $("#mframe").css("background-image", "url('img/cancel.png')");
            $("#btnDelete").attr("disabled", "disabled");
            $("#btnSave").attr("disabled", "disabled");
          }
        $("#supplier").val(r.a[0].supplier_code);
        $("#sup_name").val(r.a[0].suppier_name);
        $("#comment").val(r.a[0].memo);
        $("#date").val(r.a[0].ddate);
        $("#ref_no").val(r.a[0].ref_no);
        $("#id").attr("readonly","readonly");
        $("#qno").val($("#id").val());
        for(var i=0;i < r.b.length;i++){
            var i_code=r.b[i].item_code;
            var i_name=r.b[i].Item_name;
          $("#sel_"+i).attr("checked","checked");
          $("#0_"+i).val(r.b[i].job_no);
          $("#n_"+i).val(r.b[i].ddate);
          $("#1_"+i).val(r.b[i].cus_id);
          $("#2_"+i).val(r.b[i].name);
          $("#3_"+i).val(i_code+"-"+i_name);
          $("#4_"+i).val(r.b[i].reason);
        }
  }
  },"json");

}


