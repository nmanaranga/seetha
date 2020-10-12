$(document).ready(function(){
    $("#code").blur(function(){
        check_code();
    });
    $("#rate").keyup(function(){
        this.value=this.value.replace(/[^\d\.]/g,'');
    });

    $("#btnReset").click(function(){
   	location.href="?action=r_additional_items";
   });

    
    $("#grid").tableScroll({height:355, width:590});



    $("#account_id").autocomplete('index.php/main/load_data/m_account/auto_com', {
    width: 350,
    multiple: false,
    matchContains: true,
    formatItem: formatItems,
    formatResult: formatItemsResult
    });


    $("#account_id").keydown(function(e){
        if(e.keyCode == 13){
            set_cus_values($(this));
        }
    });

    $("#account_id").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search4").val($("#account_id").val());
            load_data_addif1();
            $("#serch_pop4").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search4').focus()", 100);
        }

       $("#pop_search4").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                 load_data_addif1();
            }
        }); 
        if(e.keyCode == 46){
            $("#account_id").val("");
            $("#account").val("");
        }
    });


    $("#account_id").blur(function(){
        set_cus_values($(this));
    });

    $("#srchee").keyup(function(){  
		$.post("index.php/main/load_data/utility/get_data_table", {
	                code:$("#srchee").val(),
	                tbl:"r_additional_item",
	                tbl_fied_names:"Code-Description",
	        		fied_names:"code-description"
	             }, function(r){
	        $("#grid_body").html(r);
	    }, "text");
	});



});


function load_data_addif1(){
      $.post("index.php/main/load_data/utility/f1_selection_list", {
          data_tbl:"m_account",
          field:"code",
          field2:"description",
          preview2:"Account Name",
          search : $("#pop_search4").val() 
      }, 
      function(r){
          $("#sr4").html(r);
          settings_addif1();            
      }, "text");
  }

  function settings_addif1(){
      $("#item_list .cl").click(
        function(){        
          $("#account_id").val($(this).children().eq(0).html());
          $("#account").val($(this).children().eq(1).html());
          $("#pop_close4").click();                
      })    
  }



 function set_cus_values(f){
            var v = f.val();
            v = v.split("|");
             if(v.length == 2){
                f.val(v[0]);
                $("#account").val(v[1]);
        }
    }


    function formatItems(row){
        return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
    }

    function formatItemsResult(row){
        return row[0]+"|"+row[1];
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
    $.post("index.php/main/load_data/r_additional_items/get_data_table", {
        
    }, function(r){
        $("#grid_body").html(r);
    }, "text");
}

function check_code(){
        loding();
    var code = $("#code").val();
    $.post("index.php/main/load_data/r_additional_items/check_code", {
        code : code
    }, function(res){
        if(res == 1){
            if(confirm("This code ("+code+") already added. \n\n Do you need edit it?")){
                set_edit(code);
            }else{
                $("#code").val('');
            }
        }
        loding();
    }, "text");
}

function validate(){
    if($("#code").val() == $("#code").attr('title') || $("#code").val() == ""){
            set_msg("Please enter code.");
            $("#code").focus();
            return false;
        }else if($("#description").val() === $("#description").attr('title') || $("#description").val() == ""){
            set_msg("Please enter description.");
            $("#description").focus();
            return false;
        }else if($("#rate").val() === $("#rate").attr('title') || $("#rate").val() == ""){
            set_msg("Please enter rate.");
            $("#rate").focus();
            return false;
		}else if($("#account_id").val() === $("#account_id").attr('title') || $("#account_id").val() == ""){
            set_msg("Please enter account.");
            $("#account_id").focus();
            return false;
        }else{
            return true;
        }
}
    
function set_delete(code){
    if(confirm("Are you sure delete transaction no "+code+"?")){
       loding();
       $.post("index.php/main/delete/r_additional_items", {
           code : code
       }, function(res){
           if(res == 1){
               loding();
               delete_msg();
           }else{
               set_msg(res);
           }
           
       }, "text");
   }
}


    
function set_edit(code){
    loding();
    $.post("index.php/main/get_data/r_additional_items", {
        code : code
    }, function(res){
        $("#code_").val(res.code);
        $("#code").val(res.code);
        $("#code").attr("readonly", true);
        $("#description").val(res.description);
		$("#rate").val(res.rate);
        $("#account").val(res.acc_des);
        $("#account_id").val(res.account);
        $("#type").val(res.type);
        
        if(res.is_add==1){
          $("#is_add").attr("checked", "checked");
        }else{
          $("#is_add").removeAttr("checked");
        }

        if(res.is_reverse==1){
          $("#is_reverse").attr("checked", "checked");
        }else{
          $("#is_reverse").removeAttr("checked");
        }
        
        loding(); input_active();
    }, "json");
}