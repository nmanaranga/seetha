$(document).ready(function(){
    $("#code").blur(function(){
        check_code();
    });

    $("#grid").tableScroll({height:355, width:590});

		$("#srchee").keyup(function(){  
 			$.post("index.php/main/load_data/utility/get_data_table", {
               	 	code:$("#srchee").val(),
               	 	tbl:"r_sub_category",
                	tbl_fied_names:"Main Category-Code-Description",
			        fied_names:"main_category-code-description",
			        col4:"Y",
                    is_r:"Y"
           	 	 }, function(r){
        		$("#grid_body").html(r);
    		}, "text");

		});

    $("#sub_cat_list").click(function(){
        $("#print_pdf").submit();
    });

    $("#main_category_id").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search4").val($("#main_category_id").val());
            load_data_catf1();
            $("#serch_pop4").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search4').focus()", 100);
        }
       $("#pop_search4").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                 load_data_catf1();
            }
        }); 
        if(e.keyCode == 46){
            $("#main_category_id").val("");
            $("#main_category").val("");
        }
    });






});

    function formatItems(row){
        return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
    }

    function formatItemsResult(row){
        return row[0]+"|"+row[1];
    }

    function set_cus_values(f){
            var v = f.val();
            v = v.split("|");
            if(v.length == 2){
            f.val(v[0]);
            $("#main_category").val(v[1]);
    }
 }

 function load_data_catf1(){
      $.post("index.php/main/load_data/utility/f1_selection_list", {
          data_tbl:"r_category",
          field:"code",
          field2:"description",
          preview2:"Category Name",
          search : $("#pop_search4").val() 
      }, 
      function(r){
          $("#sr4").html(r);
          settings_catf1();            
      }, "text");
  }

  function settings_catf1(){
      $("#item_list .cl").click(
        function(){        
          $("#main_category_id").val($(this).children().eq(0).html());
          $("#main_category").val($(this).children().eq(1).html());
          //get_next_category_code($(this).children().eq(0).html());
          $("#pop_close4").click();                
      })    
  }

function get_next_category_code(c_code){
  $.post("index.php/main/load_data/utility/get_nxt_code", {
      data_tbl:"r_sub_category",
      filter_col:"main_category",
      filter_val:c_code,
      col_is_auto:"is_auto_genarate_s_category",
      col_type:"s_category_code_type",
      col_name:"max_no",
      is_filter:"1"
  }, 
  function(r){
    if(r!=""){
      var cat_code = $("#main_category_id").val()+r;
      $("#code").val(cat_code);         
    }
  }, "text");
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
    $.post("index.php/main/load_data/r_sub_cat/get_data_table", {
        
    }, function(r){
        $("#grid_body").html(r);
    }, "text");
}

function check_code(){
        loding();
    var code = $("#code").val();
    $.post("index.php/main/load_data/r_sub_cat/check_code", {
        code : code
    }, function(res){
        if(res == 1){
            if(confirm("This code ("+code+") already added. \n\n Do you need edit it?")){
                set_edit($("#code").val()+"-"+$("#main_category_id").val());
            }else{
                $("#code").val('');
            }
        }
        loding();
    }, "text");
}

function validate(){
        if($("#main_category").val() == "0"){
            set_msg("Please select main category.");
            return false;
        }else if($("#code").val() === $("#code").attr('title') || $("#code").val() == ""){
            set_msg("Please enter code.");
            $("#code").focus();
            return false;
        /*}else if($("#code").val().length<4){
            set_msg("Code must contains 4 charactors");
            $("#code").focus();
            return false;
        }else if($("#code_gen").val()==""){
            set_msg("Please enter code key");
            $("#code_gen").focus();
            return false;
        }else if($("#code_gen").val().length<2){
            set_msg("Code key must contains 2 charactors");
            $("#code_gen").focus();
            return false;*/
        }else if($("#description").val() === $("#description").attr('title') || $("#description").val() == ""){
            set_msg("Please enter description.");
            $("#description").focus();
            return false;
        }else if($("#des").val() === $("#code").val()){
            set_msg("Please enter deferent values for description & code.");
            $("#des").focus();
            return false;
        }else{
            return true;
        }
}
    
function set_delete(code){
     var c = code.split('-');
    if(confirm("Are you sure delete "+code+"?")){
        loding();
        $.post("index.php/main/delete/r_sub_cat", {
            code : c[0],
            main : c[1]
        }, function(res){
            if(res == 1){
               loding();
               delete_msg();
            }else{
                loding();
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
    
    var c = code.split('-');
    loding();
    $.post("index.php/main/get_data/r_sub_cat", {
        code : c[0],
        main : c[1]
    }, function(res){
		
        $("#code_").val(res.s_code);
        $("#code").val(res.s_code);
        $("#code_gen").val(res.code_gen);
        $("#description").val(res.s_des);
        $("#main_category_id").val(res.m_code);     
        $("#main_category").val(res.m_des);
        $("#max_nno").val(res.max_no);
        loding(); input_active();
    }, "json");
}