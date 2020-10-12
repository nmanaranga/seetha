$(document).ready(function(){

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


    $("#bank_acc").keypress(function(e){
        set_cid($(this).attr("id"));
        if(e.keyCode==112){
            $("#pop_search").val($("#bank_acc").val());
            load_bank_acc();
            $("#serch_pop").center();
            $("#blocker").css("display", "block");
            setTimeout("select_search()", 100);
        }
        if(e.keyCode==46){
            $("#bank_acc").val("");
            $("#bank_des").val("");
        }
        $("#pop_search").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112) { 
                load_bank_acc();
            }
        });
    });


    $("#btnDelete").click(function(){
     if($("#hid").val()!=0) {
        set_delete($("#hid").val());
    }
});


    $(document).on('click','#load_bc',function(){
        var cluster = $("#u_cluster").val();
        var bank_id = $("#bank_acc").val();
        if($("#u_roll").val()=="" || $("#u_user").val()==""){
            set_msg("Please select user & user role.","error");
        }else{
            check_user(cluster,bank_id) 
        }
    });


});



function load_bank_acc(){
    $.post("index.php/main/load_data/utility/f1_selection_list", {
        search : $("#pop_search").val(),
        data_tbl : "m_account",
        field : "code",
        field2 : "description",
        add_query:" AND is_bank_acc='1'"
    }, function(r){
        $("#sr").html(r);
        settings();
    }, "text");
}

function settings(){
    $("#item_list .cl").click(function(){ 
        if($(this).children().eq(0).html() != "&nbsp;"){
            $("#bank_acc").val($(this).children().eq(0).html());
            $("#bank_des").val($(this).children().eq(1).html());
            $("#pop_close").click();   
        }
    });  
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
    if($("#bank_acc").val()==""){
        set_msg("Please enter Bank");
        return false
    }else{
        return true;
    }
}

function select_search(){
    $("#pop_search").focus();

}

function check_user(cluster,bank_id){
    loding();
    $.post("index.php/main/load_data/m_allocate_bank_branch/check_user", {
        bank_id:bank_id,
    }, function(r){
        if(r=="2"){
         load_bc(cluster);
         $("#hid_user").val("1");
     }else if(r=='1'){
         check_exist_user(cluster,bank_id)
         $("#hid_user").val("2");              
     }
     loding();
 }, "json");
}

function check_exist_user(cluster,bank_id){
    loding();
    $.post("index.php/main/load_data/m_allocate_bank_branch/load_exist_bc_detail", {
        cluster:cluster,
        bank_id:bank_id
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

    }
    $("#t_branch").html(htl);
    $("#hid_tot").val(r.length);

}

loding();
}, "json");
}

function load_bc(cluster){

    loding();
    $.post("index.php/main/load_data/m_allocate_bank_branch/load_bc", {
        cluster:cluster
    }, function(r){
        if(r=="2"){
         set_msg("No records");
     }else{
       var htl ="";             
       for(var i=0; i<r.length;i++){

        htl += "<tr class='cl3'><td style='width: 40px;'><input type='text' readonly='reaonly' style='width: 80px;' class='se' name='cl_"+i+"' id='cl_"+i+"' class='g_input_txt' value='"+r[i].cl+"'/></td><td><input type='text' readonly='reaonly' style='width: 100%;' class='se' name='clname_"+i+"' id='clname_"+i+"' class='g_input_txt' value='"+r[i].description+"'/></td><td style='width: 40px;'><input type='text' readonly='reaonly' name='bc_"+i+"' id='bc_"+i+"' class='g_input_txt' value='"+r[i].bc+"'/></td><td style=''><input type='text' style='width: 100%;' readonly='reaonly' name='bcname_"+i+"' id='bcname_"+i+"' class='g_input_txt' value='"+r[i].name+"'/></td><td style='width: 60px;'><input type='checkbox'  style='width: 60px;'  name='active_"+i+"' id='active_"+i+"' value='1' class=''/></td></tr>";
    }
    $("#t_branch").html(htl);
    $("#hid_tot").val(r.length);

}

loding();
}, "json");

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
