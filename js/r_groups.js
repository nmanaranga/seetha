$(document).ready(function(){

 	$("#btnReset").click(function(){
   	location.href="?action=r_groups";
	});

    $("#code").blur(function(){
        check_code();
    });
     
    $("#grid").tableScroll({height:355,width:590});

    $("#emp_s").click(function(){
        window.open("?action=m_employee_details","_blank");   
    });   
  
    $("#officer").keydown(function(e){ 
    if(e.keyCode==112){
      $("#pop_search").val($("#officer").val());
      load_emp();
      $("#serch_pop").center();
      $("#blocker").css("display", "block");
      setTimeout("$('#pop_search').focus()", 100);   
    }
    $("#pop_search").keyup(function(e){
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
        load_emp();
      }
    });
    if(e.keyCode==46){
       $("#officer").val("");
       $("#officer2").val("");
    }  
   });

    $("#srchee").keyup(function(){  
        $.post("index.php/main/load_data/utility/get_data_table", {
                code:$("#srchee").val(),
                tbl:"r_groups",
                tbl_fied_names:"Code-Name",
                fied_names:"code-name",
                group:"Y"
             }, function(r){
            $("#grid_body").html(r);
        }, "text");
    });

});

function load_emp(){
  $.post("index.php/main/load_data/utility/f1_selection_list_emp", {
      filter_emp_cat:"salesman",
      search : $("#pop_search").val() 
  }, function(r){
      $("#sr").html(r);
      settings_emp();      
 }, "text");
}

function settings_emp(){
    $("#item_list .cl").click(function(){        
        $("#officer").val($(this).children().eq(0).html());
        $("#officer2").val($(this).children().eq(1).html());
        $("#pop_close").click();                
    })    
}

function set_cus_values(f){
            var v = f.val();
            v = v.split("-");
            
                if(v.length == 2){
                f.val(v[0]);
                $("#officer2").val(v[1]);
               // $("#officer2").attr("class", "input_txt_f");
            

        }
    }

    function formatItems(row){
        return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
    }

    function formatItemsResult(row){
        return row[0]+"-"+row[1];
    }



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
            }else if(pid == 3){
                set_msg("No permission to edit data.");
            }else{
                set_msg(pid);
            }
        }
    });
}

function get_data_table(){
    $.post("index.php/main/load_data/r_groups/get_data_table", {
        
    }, function(r){
        $("#grid_body").html(r);
    }, "text");
}


function check_code(){
        loding();
    var code = $("#code").val();
    $.post("index.php/main/load_data/r_groups/check_code", {
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
    if($("#code").val() == ""){
        set_msg("Please enter code.");
        $("#code").focus();
        return false;
    }else if($("#name").val() === $("#name").attr('title') || $("#name").val() == ""){
        set_msg("Please enter name.");
        $("#name").focus();
        return false;
    }else if($("#officer").val() === $("#officer").attr('title') || $("#officer").val() == ""){
        set_msg("Please enter officer.");
        $("#officer").focus();
        return false;
    }else{
        return true;
    }
}
    
function set_delete(code){
    if(confirm("Are you sure delete "+code+"?")){
        loding();
        $.post("index.php/main/delete/r_groups", {
            code : code
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

function is_edit($mod)
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

}
    
function set_edit(code){
    loding();
    $.post("index.php/main/get_data/r_groups", {
        code : code
    }, function(res){
         input_active();
        $("#code_").val(res.code);
        $("#code").val(res.code);
	    $("#code").attr("readonly", true);
        $("#name").val(res.name);
        $("#sales_category").val(res.category);
        $("#officer").val(res.officer);
        $("#fdate").val(res.fdate);
        $("#tdate").val(res.tdate);
        
   
        $.post("index.php/main/get_data/m_employee",{
            code:res.officer
        }, function(r){
            input_active();

            $("#officer2").val(r.name);
        },"json");

           if(res.inactive == 1){
            $("#inactive").attr("checked", "checked");
        }else{
            $("#inactive").removeAttr("checked");
        }
        
       // is_edit('010');
        loding(); 
        input_active();
    }, "json");
}