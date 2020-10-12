$(document).ready(function(){
    $("#load_req_duplecate").css("display","none");
    $("#code").blur(function(){
        check_code();
    });
     
    $("#tgrid").tableScroll({height:355});

    $(document).on('focus','.dfrom',function(){
        $(".input_date_down_future").datepicker({
        showButtonPanel: false,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        beforeShow: function (input, inst) {
            var offset = $(input).offset();
            var height = $(input).height();
            window.setTimeout(function () {
                inst.dpDiv.css({ top: (offset.top + height + 4) + 'px', left: offset.left + 'px' })
            }, 1);
        }        
        });
    });





    $("#u_roll").keypress(function(e){
    set_cid($(this).attr("id"));
    if(e.keyCode==112){
        
        $("#pop_search").val($("#u_roll").val());
        load_items();
        $("#serch_pop").center();
        $("#blocker").css("display", "block");
        setTimeout("select_search()", 100);
    }
    if(e.keyCode==46){
        $("#u_roll").val("");
        $("#roll_des").val("");
    }
    $("#pop_search").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112) { 
            load_items($("#u_roll").val());
        }
    });
    });


    //------------------------------------


    $("#u_user").keypress(function(e){
        set_cid($(this).attr("id"));
        if($("#u_roll").val()==""){
            set_msg("Please select user role");
            $("#u_roll").focus();
        }else{
            if(e.keyCode==112){
                
                $("#pop_search").val($("#u_user").val());
                load_items2();
                $("#serch_pop").center();
                $("#blocker").css("display", "block");
                setTimeout("select_search()", 100);
            }
            if(e.keyCode==46){
                $("#u_user").val("");
                $("#user_des").val("");
            }
            $("#pop_search").keyup(function(e){
                if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112) { 
                    load_items2($("#u_user").val());
                }
            });   
        }
    });


    //-----------------------------------

    $("#u_cluster").keypress(function(e){
    set_cid($(this).attr("id"));
    if(e.keyCode==112){
        
        $("#pop_search").val($("#u_cluster").val());
        load_items3();
        $("#serch_pop").center();
        $("#blocker").css("display", "block");
        setTimeout("select_search()", 100);
    }
    if(e.keyCode==46){
        $("#u_cluster").val("");
        $("#cluster_des").val("");
    }
    $("#pop_search").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112) { 
            load_items3($("#u_cluster").val());
        }
    });
    });


    //-----------------------------------



    $("#btnDelete").click(function(){
       if($("#hid").val()!=0) {
            set_delete($("#hid").val());
       }
    });

    $("#btnPrint").click(function(){
    	if($("#hid").val()=="0")
    	{
    		set_msg("Please load data before print");
        	return false;
    	}
    	else{
        $("#print_pdf").submit();
    	}


    });


    $(document).on('click','#load_bc',function(){
        var cluster = $("#u_cluster").val();
        var user_id = $("#u_user").val();
        if($("#u_roll").val()=="" || $("#u_user").val()==""){
            set_msg("Please select user & user role.","error");
        }else{
            check_user(cluster,user_id) 
        }
    });
});



function check_user(cluster,user_id){
    loding();
    $.post("index.php/main/load_data/u_branch_to_user/check_user", {
        user_id:user_id,
    }, function(r){
            if(r=="2"){
               load_bc(cluster);
               $("#hid_user").val("1");
            }else if(r=='1'){
               check_exist_user(cluster,user_id)
               $("#hid_user").val("2");              
            }
            loding();
        }, "json");
}

function check_exist_user(cluster,user_id){
    loding();
    $.post("index.php/main/load_data/u_branch_to_user/load_exist_bc_detail", {
        cluster:cluster,
        user_id:user_id
    }, function(r){
            if(r=="2"){
               set_msg("No records");
            }else{
                 var htl ="";             
                for(var i=0; i<r.length;i++){

                    htl += "<tr class='cl3'><td style='width: 60px;'><input type='text' readonly='reaonly' style='width: 100%' class='se' name='cl_"+i+"' id='cl_"+i+"' class='g_input_txt' value='"+r[i].cl+"'/></td><td ><input type='text' readonly='reaonly' style='width: 100%;' class='se' name='clname_"+i+"' id='clname_"+i+"' class='g_input_txt' value='"+r[i].description+"'/></td><td style='width: 60px;'><input type='text' readonly='reaonly' name='bc_"+i+"' id='bc_"+i+"' style='width: 100%' class='g_input_txt' value='"+r[i].bc+"'/></td><td style=''><input type='text' style='width: 100%;' readonly='reaonly' name='bcname_"+i+"' id='bcname_"+i+"' class='g_input_txt' value='"+r[i].name+"'/></td>"


                    
                    if(r[i].is_active==1){
                        htl += "<td style='width: 60px;'><input type='checkbox'  style='width: 60px;'  checked name='active_"+i+"' id='active_"+i+"' value='1' class=''/></td>"
                    }else{
                        htl += "<td style='width: 60px;'><input type='checkbox'  style='width: 60px;' name='active_"+i+"' id='active_"+i+"' value='1' class=''/></td>"
                    }

                    if(r[i].from_date==null){
                        htl += "<td style='width: 40px;'><input type='text' class='input_date_down_future dfrom' readonly='readonly' name='datefrom_"+i+"' id='datefrom_"+i+"' value='' /></td>";
                    }else{
                        htl += "<td style='width: 40px;'><input type='text' class='input_date_down_future dfrom' readonly='readonly' name='datefrom_"+i+"' id='datefrom_"+i+"' value='"+r[i].from_date+"' /></td>";
                    }

                    if(r[i].to_date==null){
                        htl += "<td style='width: 40px;'><input type='text' class='input_date_down_future' readonly='readonly' name='dateto_"+i+"' id='dateto_"+i+"' value='' /></td></tr>"
                    }else{
                        htl += "<td style='width: 40px;'><input type='text' class='input_date_down_future' readonly='readonly' name='dateto_"+i+"' id='dateto_"+i+"' value='"+r[i].to_date+"' /></td></tr>";   
                    }
                                   
                }
                $("#t_branch").html(htl);
                $("#hid_tot").val(r.length);
                //$("#load_bc").attr("disabled","disabled");
               

            }

            loding();
        }, "json");
}

function load_bc(cluster){

    loding();
    $.post("index.php/main/load_data/u_branch_to_user/load_bc", {
        cluster:cluster
    }, function(r){
            if(r=="2"){
               set_msg("No records");
            }else{
                 var htl ="";             
                for(var i=0; i<r.length;i++){

                    htl += "<tr class='cl3'><td style='width: 40px;'><input type='text' readonly='reaonly' style='width: 80px;' class='se' name='cl_"+i+"' id='cl_"+i+"' class='g_input_txt' value='"+r[i].cl+"'/></td><td><input type='text' readonly='reaonly' style='width: 100%;' class='se' name='clname_"+i+"' id='clname_"+i+"' class='g_input_txt' value='"+r[i].description+"'/></td><td style='width: 40px;'><input type='text' readonly='reaonly' name='bc_"+i+"' id='bc_"+i+"' class='g_input_txt' value='"+r[i].bc+"'/></td><td style=''><input type='text' style='width: 100%;' readonly='reaonly' name='bcname_"+i+"' id='bcname_"+i+"' class='g_input_txt' value='"+r[i].name+"'/></td><td style='width: 60px;'><input type='checkbox'  style='width: 60px;'  name='active_"+i+"' id='active_"+i+"' value='1' class=''/></td><td style='width: 40px;'><input type='text' class='input_date_down_future dfrom' readonly='readonly' name='datefrom_"+i+"' id='datefrom_"+i+"' value='' /></td><td style='width: 40px;'><input type='text' class='input_date_down_future' readonly='readonly' name='dateto_"+i+"' id='dateto_"+i+"' value='' /></td></tr>";
                }
                $("#t_branch").html(htl);
                $("#hid_tot").val(r.length);
               // $("#load_bc").attr("disabled","disabled");

            }

            loding();
        }, "json");

}

function load_items(){
    $.post("index.php/main/load_data/utility/f1_selection_list", {
        search : $("#pop_search").val(),
        data_tbl : "u_user_role",
        field : "role_id",
        field2 : "description"
     }, function(r){
        $("#sr").html(r);
        settings();
        
    }, "text");
}

function load_items2(){
    $.post("index.php/main/load_data/utility/select_user", {
        search : $("#pop_search").val(),
        u_role : $("#u_roll").val(),
     }, function(r){
        $("#sr").html(r);
        settings2();
        
    }, "text");
}

function load_items3(){
    $.post("index.php/main/load_data/utility/f1_selection_list", {
        search : $("#pop_search").val(),
        data_tbl : "m_cluster",
        field : "code",
        field2 : "description"
     }, function(r){
        $("#sr").html(r);
        settings3();
        
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
                location.href='';   
            }else if(pid == 2){
                set_msg("No permission to add data.");
            }else if(pid == 3){
                set_msg("No permission to edit data.");
            }else{
                set_msg(pid);
            }
            loding();            
        }
    });
}



function validate(){
    if($("#u_roll").val()==""){
        set_msg("Please enter user role");
        return false
    }else if($("#u_user").val()==""){
        set_msg("Please enter user.");
        return false;
    }else{
        return true;
    }
}
    
 



function select_search(){
    $("#pop_search").focus();
   
}





function settings(){
    $("#item_list .cl").click(function(){ 
        if($(this).children().eq(0).html() != "&nbsp;"){
                       
                $("#u_roll").val($(this).children().eq(0).html());
                $("#roll_des").val($(this).children().eq(1).html());

                $("#u_user").val("");
                $("#user_des").val("");
                $("#u_user").focus();
                $("#pop_close").click();   
                       
        }else{
            $("#u_roll").val("");
            $("#pop_close").click();
            $("#u_user").focus();
        }
    });  
}


function settings2(){
    $("#item_list .cl").click(function(){ 
        if($(this).children().eq(0).html() != "&nbsp;"){
                     
                $("#u_user").val($(this).children().eq(0).html());
                $("#user_des").val($(this).children().eq(1).html());
                $("#u_cluster").focus();
                $("#pop_close").click();   
           
        }else{
            $("#u_user").val("");
            $("#pop_close").click();
            $("#u_cluster").focus();
        }
    });  
}


function settings3(){
    $("#item_list .cl").click(function(){ 
        if($(this).children().eq(0).html() != "&nbsp;"){
                
                $("#u_cluster").val($(this).children().eq(0).html());
                $("#cluster_des").val($(this).children().eq(1).html());
                $("#pop_close").click();   
            
        }else{
            $("#u_cluster").val("");
            $("#pop_close").click();
        }
    });  
}

function load_data(id){
    empty_grid();
    loding();
    $.post("index.php/main/get_data/t_internal_transfer_order/", {
        id: id
    }, function(r){

            if(r=="2"){
               set_msg("No records");
            }else{
                if(r.sum[0].is_cancel=="1"){
                    
                    $("#mframe").css("background-image", "url('img/cancel.png')");
                    $("#btnSave").attr('disabled','disabled');
                    $("#btnDelete").attr('disabled','disabled');
                    
                }
                
            $("#hid").val(id);   
            $("#id").val(id); 
            $("#qno").val(id);
            // var cluster = "<option>" + r.sum[0].description +"-"+r.sum[0].cl + "</option>";
            $("#cluster").val(r.sum[0].cl);
            var branch = "<option>" + r.sum[0].name+"-"+r.sum[0].bc + "</option>";
            $("#branch").append(branch);
            $("#note").val(r.sum[0].note);
            $("#date").val(r.sum[0].ddate); 
            $("#ref_no").val(r.sum[0].ref_no);
            $("#total2").val(r.sum[0].total_amount);
           

            for(var i=0; i<r.det.length;i++){
                $("#6_"+i).val(r.det[i].nno);
                $("#0_"+i).val(r.det[i].item_code);
                $("#n_"+i).val(r.det[i].description);
                $("#1_"+i).val(r.det[i].model);
                $("#7_"+i).val(r.det[i].item_cost);
                $("#8_"+i).val(r.det[i].min_price);
                $("#2_"+i).val(r.det[i].max_price);
                $("#3_"+i).val(r.det[i].qty);
                $("#4_"+i).val(r.det[i].quantity);
                tot_amount(i);
            }

          
            }

            loding();
        }, "json");

  
}


 function empty_grid(){
    for(var i=0; i<25; i++){
        $("#h_"+i).val(0);
        $("#0_"+i).val("");
        $("#n_"+i).val("");
        $("#t_"+i).html("&nbsp;");
        $("#1_"+i).val("");
        $("#2_"+i).val("");
        $("#3_"+i).val("");
        $("#5_"+i).val("");
        $("#4_"+i).val("");
        $("#6_"+i).val("");
        $("#7_"+i).val("");
        $("#8_"+i).val("");
        $("#9_"+i).val("");
        
    }

}

function check_item_exist(id){
    var v = true;
    $("input[type='hidden']").each(function(){
        if($(this).val() == id){
            v = false;
        }
    });
    
    return v;
}
