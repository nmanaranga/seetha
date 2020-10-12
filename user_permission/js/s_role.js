$(document).ready(function(){

$("#tgrid").tableScroll({height:280});


$("#bc").click(function(){
    if($("#cl").val()==0){
        set_msg("Please select cluster");
    }
});

$("#code").keypress(function(e){ 
    if(e.keyCode==112){
      $("#pop_search2").val($("#code").val());
      load_code();
      $("#serch_pop2").center();
      $("#blocker2").css("display", "block");
      setTimeout("$('#pop_search2').focus()", 100);   
    }
    $("#pop_search2").keyup(function(e){
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
        load_code();
      }
    });
    if(e.keyCode==46){
       $("#code").val("");
       $("#code_").val("");
       $("#des").val("");
       $("#is_active").attr("checked",false);
    }  
}); 

$("#cl").change(function(){
    $.post("index.php/main/load_data/s_role/select3", {
    cl:$("#cl").val()
    }, function(r){
    $("#branch_det").html(r);
    },"text");
});

$("#code,#des,#cl,#bc").focus(function(){
    if($("#package").val()==0){
        set_msg("Please Select Category");
        return false;
    }
});


$("#package").change(function(){
    $("#code").val("");
    $("#code_").val("");
    $("#des").val("");
    $("#is_active").attr("checked",false);
    $.post("index.php/main/load_data/s_role/load_module_det", {
    package:$(this).val()
    }, function(r){
        var html="";
        for(var x=0; x<r.length;x++){
            html+="<tr>";
            html+="<td><input type='hidden' readonly='readonly' name='m_code_"+x+"' class='g_input_num' id='m_code_"+x+"' style='width :70px;' title='"+r[x].m_code+"' value='"+r[x].m_code+"'/>"+r[x].m_code+"</td>";
            html+="<input type='hidden' readonly='readonly' name='m_name_"+x+"' class='g_input_txt' id='m_name_"+x+"' style='width :70px;' title='"+r[x].module_name+"' value='"+r[x].module_name+"'/>";
            html+="<td><input type='hidden' readonly='readonly' name='m_description_"+x+"' class='g_input_txt' id='m_description_"+x+"' style='width :0' title='"+r[x].m_description+"'/>"+r[x].m_description+"</td>";   
            html+="<td style='text-align: center;width : 60px'><input type='checkbox' name='is_view_"+x+"' id='is_view_"+x+"' class='ob_a' value='1' title='1' onClick='check_row("+x+");' /></td>";
            html+="<td style='text-align: center;width : 60px'><input type='checkbox' name='is_add_"+x+"' id='is_add_"+x+"' class='ob_b' value='1' title='1'/></td>";
            html+="<td style='text-align: center;width : 60px'><input type='checkbox' name='is_edit_"+x+"' id='is_edit_"+x+"' class='ob_e' value='1' title='1'/></td>";
            html+="<td style='text-align: center;width : 60px'><input type='checkbox' name='is_delete_"+x+"' id='is_delete_"+x+"' class='ob_d' value='1' title='1'/></td>";
            html+="<td style='text-align: center;width : 60px'><input type='checkbox' name='is_approve_"+x+"' id='is_approve_"+x+"' class='ob_i' value='1' title='1'/></td>";
            html+="<td style='text-align: center;width : 60px'><input type='checkbox' name='is_print_"+x+"' id='is_print_"+x+"' class='ob_f' value='1' title='1'/></td>";
            html+="<td style='text-align: center;width : 70px'><input type='checkbox' name='is_re_print_"+x+"' id='is_re_print_"+x+"' class='ob_g' value='1' title='1'/></td>";
            html+="<td style='text-align: center;width : 80px'><input type='checkbox' name='is_back_date_"+x+"' id='is_back_date_"+x+"' class='ob_h' value='1' title='1'/></td>";
            html+="</tr>";
        }   

     $("#grid_det").html(html);
    },"json");
});


$("#all_view").change(function(){
  var c = $(this).attr("checked");

        $("#tgrid").find("tr").each(function(){

            $(this).find(":checkbox").each(function(i){

                if(i == 0){

                    if(c){

                        $(this).attr("checked", "checked");

                    }else{

                        $(this).removeAttr("checked");

                    }

                }

            });

        });

 });





$("#btnReset").click(function()

{

    

   $(".ob_a").removeAttr("checked");

   $(".ob_b").removeAttr("checked");

   $(".ob_c").removeAttr("checked");

   $(".ob_d").removeAttr("checked");

   $(".ob_e").removeAttr("checked");

   $(".ob_f").removeAttr("checked");

   $(".ob_g").removeAttr("checked");

   $(".ob_h").removeAttr("checked");

   $(".ob_i").removeAttr("checked");

   $(".ob_j").removeAttr("checked");

    

    

    

});





 $("#all_add").change(function(){

    var c = $(this).attr("checked");

    $("#tgrid").find("tr").each(function(){

        $(this).find(":checkbox").each(function(i){

            if(i == 1){

                if(c){

                    $(this).attr("checked", "checked");

                }else{

                    $(this).removeAttr("checked");

                }

            }

        });

    });

});



$("#all_edit").change(function(){
    var c = $(this).attr("checked");
    $("#tgrid").find("tr").each(function(){
        $(this).find(":checkbox").each(function(i){
            if(i == 2){

                if(c){

                    $(this).attr("checked", "checked");

                }else{

                    $(this).removeAttr("checked");

                }

            }

        });

    });

});



$("#all_delete").change(function(){

    var c = $(this).attr("checked");

    $("#tgrid").find("tr").each(function(){

        $(this).find(":checkbox").each(function(i){

            if(i == 3){

                if(c){

                    $(this).attr("checked", "checked");

                }else{

                    $(this).removeAttr("checked");

                }

            }

        });

    });

});


$("#all_approve").change(function(){

    var c = $(this).attr("checked");

    $("#tgrid").find("tr").each(function(){

        $(this).find(":checkbox").each(function(i){

            if(i == 4){

                if(c){

                    $(this).attr("checked", "checked");

                }else{

                    $(this).removeAttr("checked");

                }

            }

        });

    });

});



$("#all_print").change(function(){

    var c = $(this).attr("checked");

    $("#tgrid").find("tr").each(function(){

        $(this).find(":checkbox").each(function(i){

            if(i == 5){

                if(c){

                    $(this).attr("checked", "checked");

                }else{

                    $(this).removeAttr("checked");

                }

            }

        });

    });

});



$("#all_r_print").change(function(){

    var c = $(this).attr("checked");

    $("#tgrid").find("tr").each(function(){

        $(this).find(":checkbox").each(function(i){

            if(i == 6){

                if(c){

                    $(this).attr("checked", "checked");

                }else{

                    $(this).removeAttr("checked");

                }

            }

        });

    });

});



$("#all_back_date").change(function(){
    var c = $(this).attr("checked");
    $("#tgrid").find("tr").each(function(){
        $(this).find(":checkbox").each(function(i){
            if(i == 7){
                if(c){
                    $(this).attr("checked", "checked");
                }else{
                    $(this).removeAttr("checked");
                }

            }

        });

    });

});



    $("#code").autocomplete('index.php/main/load_data/s_role/auto_com', {

        width: 350,

        multiple: false,

        matchContains: true,

        formatItem: formatItems,

        formatResult: formatItemsResult

    });



    $("#code").blur(function(){

	set_cus_values($(this));

    });

    

    $("#code").keypress(function(e){

	if(e.keyCode == 13){

	    set_cus_values($(this));

	}

    });



});

function load_code(){
  $.post("index.php/main/load_data/s_role/load_user", {
      data_tbl:"u_user_role",
      field:"role_id",
      field2:"description",
      preview2:"Supplier Name",
      search : $("#pop_search2").val() 
  }, function(r){
      $("#sr2").html(r);
      settings_code();     
 }, "text");
}


function settings_code(){
    $("#item_list .cl").click(function(){        
        $("#code").val($(this).children().eq(0).html());
        $("#code_").val($(this).children().eq(0).html());
        $("#des").val($(this).children().eq(1).html());
        $("#pop_close2").click();
        load_data($("#code").val());    
    }) 
}

function set_cus_values(f){
   set_cid(f.attr("id"));
   var v = f.val();
   v = v.split("~");
 
 if(v.length == 3){
	$("#code_").val(v[0]);
	f.val(v[1]);
	$("#des").val(v[2]);
	// $("#bc").val(v[3]);
    load_data($("#code").val());
 }
    input_active(v[0]);

}



function formatItems(row){
    return "<strong> " +row[1] + "</strong> | <strong> " +row[2] + "</strong>";
}



function formatItemsResult(row){
    return row[0]+"~"+row[1]+"~"+row[2];
}



function load_data(id){
    empty_grid();
    $.post("index.php/main/get_data/s_role/", {
        id : id,
        pack : $("#package").val() 
    }, function(r){
        $("#cl").val(r.sum[0].cl);
        $("#bc").val(r.sum[0].bc);
        //$("#package").val(r.sum[0].category);
        
        $("#bc").html(r.bc_det);
        if(r.sum[0].is_active==1){
            $("#is_active").attr("checked", "checked");
        }else{
            $("#is_active").removeAttr("checked");
        }
        
        var html="";

        for(var x=0; x<r.data_tbl.length;x++){

            html+="<tr>";
            html+="<td><input type='hidden' readonly='readonly' name='m_code_"+x+"' class='g_input_num' id='m_code_"+x+"' style='width :70px;' title='"+r.data_tbl[x].m_code+"' value='"+r.data_tbl[x].m_code+"'/>"+r.data_tbl[x].m_code+"</td>";
            html+="<input type='hidden' readonly='readonly' name='m_name_"+x+"' class='g_input_txt' id='m_name_"+x+"' style='width :70px;' title='"+r.data_tbl[x].module_name+"' value='"+r.data_tbl[x].module_name+"'/>";
            html+="<td><input type='hidden' readonly='readonly' name='m_description_"+x+"' class='g_input_txt' id='m_description_"+x+"' style='width :0' title='"+r.data_tbl[x].m_description+"'/>"+r.data_tbl[x].m_description+"</td>";   
            html+="<td style='text-align: center;width : 60px'><input type='checkbox' name='is_view_"+x+"' id='is_view_"+x+"' class='ob_a' value='1' title='1' onClick='check_row("+x+");' /></td>";
            html+="<td style='text-align: center;width : 60px'><input type='checkbox' name='is_add_"+x+"' id='is_add_"+x+"' class='ob_b' value='1' title='1'/></td>";
            html+="<td style='text-align: center;width : 60px'><input type='checkbox' name='is_edit_"+x+"' id='is_edit_"+x+"' class='ob_e' value='1' title='1'/></td>";
            html+="<td style='text-align: center;width : 60px'><input type='checkbox' name='is_delete_"+x+"' id='is_delete_"+x+"' class='ob_d' value='1' title='1'/></td>";
            html+="<td style='text-align: center;width : 60px'><input type='checkbox' name='is_approve_"+x+"' id='is_approve_"+x+"' class='ob_i' value='1' title='1'/></td>";
            html+="<td style='text-align: center;width : 60px'><input type='checkbox' name='is_print_"+x+"' id='is_print_"+x+"' class='ob_f' value='1' title='1'/></td>";       
            html+="<td style='text-align: center;width : 70px'><input type='checkbox' name='is_re_print_"+x+"' id='is_re_print_"+x+"' class='ob_g' value='1' title='1'/></td>";
            html+="<td style='text-align: center;width : 80px'><input type='checkbox' name='is_back_date_"+x+"' id='is_back_date_"+x+"' class='ob_h' value='1' title='1'/></td>";
    
            html+="</tr>";
        }   
        
        $("#grid_det").html(html);

        //$("#tgrid tr").each(function(){
            var x=0;
            for(var i=0; i<r.det.length; i++){
                for(var x=0; x<r.det.length; x++){
                    if($("#m_code_"+x).val() == r.det[i].module_id){
                        if(r.det[i].is_view == 1){ $("#is_view_"+x).attr("checked", "checked"); }
                        if(r.det[i].is_add == 1){ $("#is_add_"+x).attr("checked", "checked"); }
                        if(r.det[i].is_edit == 1){ $("#is_edit_"+x).attr("checked", "checked"); }
                        if(r.det[i].is_delete == 1){ $("#is_delete_"+x).attr("checked", "checked"); }
                        if(r.det[i].is_approve == 1){ $("#is_approve_"+x).attr("checked", "checked"); }
                        if(r.det[i].is_print == 1){ $("#is_print_"+x).attr("checked", "checked"); }
                        if(r.det[i].is_re_print == 1){ $("#is_re_print_"+x).attr("checked", "checked"); }
                        if(r.det[i].is_back_date == 1){ $("#is_back_date_"+x).attr("checked", "checked"); }
                    }
                }
            }

        //});

    
        $("#code_").val(id);  
    }, "json");
}



function empty_grid(){

    $(":checkbox").removeAttr('checked');

}



function validate(){

    if($("#code").val() == $("#code").attr("title")){

        set_msg("Please enter code");

        $("#code").focus();

        return false;

    }else if($("#package").val() == 0){

         set_msg("Please enter category");

        $("#package").focus();

        return false;

    }else if($("#des").val() == $("#des").attr("title")){

         set_msg("Please enter description");

        $("#des").focus();

        return false;

    }else if($("#bc").val() == "0"){

         set_msg("Please select branch");

        return false;

    }else{

        return true;

    }

}



function save(){

    //$("#_form").submit();
    var frm = $("#_form");
    $.ajax({
    type: frm.attr('method'),
    url: frm.attr('action'),
    data: frm.serialize(),
    success: function (pid) {
        if(pid == 1){
            // input_reset(); pop_note('Saving Success.', 'g');
            // $("#nic_no").removeAttr("readonly"); $("#btnSave").val("Save");
            // $("#nic_no").focus(); $("#img").html(imag_up);
            // reset_map(); img_uploads(); $("#code_").val(0);
            alert("Save Successfully");
            location.href="";
        }else if(pid == 3){
            //alert("You don't have permission for add records");
            pop_note("You don't have permission for add records.", 'r');
        }else{
            alert("Error : "+pid);
            //pop_note("You don't have permission for save records.", 'r');
        }
    }
    });

}


function check_row(gid){

    if ($("#is_view_"+gid).is(":checked")){

        //$("#is_view_"+x).attr("checked", "checked"); 
        $("#is_add_"+gid).attr("checked", true); 
        $("#is_edit_"+gid).attr("checked", true); 
        $("#is_delete_"+gid).attr("checked", true); 
        $("#is_approve_"+gid).attr("checked", true); 
        $("#is_print_"+gid).attr("checked", true); 
        $("#is_re_print_"+gid).attr("checked", true); 
        $("#is_back_date_"+gid).attr("checked", true);

    } else{

        $("#is_add_"+gid).attr("checked", false); 
        $("#is_edit_"+gid).attr("checked", false); 
        $("#is_delete_"+gid).attr("checked", false); 
        $("#is_approve_"+gid).attr("checked", false); 
        $("#is_print_"+gid).attr("checked", false); 
        $("#is_re_print_"+gid).attr("checked", false); 
        $("#is_back_date_"+gid).attr("checked", false);

    }


}