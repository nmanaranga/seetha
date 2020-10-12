$(document).ready(function(){
    $(":checkbox").removeAttr('checked');
    $("#grid").tableScroll({height:355});
    $("#user").change(function(){
        load_data();
    });

    $(".pop").keypress(function(e){
        set_cid($(this).attr("id"));
        if(e.keyCode==112){
            $("#pop_search").val();
            load_items();    
            $("#serch_pop").center();
            $("#blocker").css("display", "block");
            setTimeout("select_search()", 100);
        }

        if(e.keyCode==46){
            $("#rolid_"+scid).val("");
            $("#rolname_"+scid).val("");
            $("#isactive_"+scid).attr('checked', false);

        }
    });

    $("#pop_search").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112){
            load_items();
        }
    });

    $("#code").keypress(function(e){
        if(e.keyCode==13){
            load_data($(this).val());
        }
    })

    $("#srchee").keyup(function(){  
        $.post("index.php/main/load_data/utility/get_data_table", {
            code:$("#srchee").val(),
            tbl:"s_permission_level_sum",
            tbl_fied_names:"Code-Description",
            fied_names:"code-description",
            col2:"Y"
            }, function(r){
        $("#grid_body").html(r);
        }, "text");
    });
});


function load_items(){
     $.post("index.php/main/load_data/s_permission_level_order/roll_list_all", {
        search : $("#pop_search").val()
    }, function(r){
        $("#sr").html(r);
        settings();
    }, "text");
}

function select_search(){
  $("#pop_search").focus();
}

$("#pop_search").gselect();


function settings(){
  $("#user_list .cl").click(function(){
    if($(this).children().eq(0).html() != "&nbsp;"){
      if(check_item_exist($(this).children().eq(0).html())){  
        $("#hid_"+scid).val($(this).children().eq(0).html());
        $("#rolid_"+scid).val($(this).children().eq(0).html());
        $("#rolname_"+scid).val($(this).children().eq(1).html());
      }else{
        set_msg("Roll ID "+$(this).children().eq(0).html()+" is already added.");
      }
        $("#pop_close").click();
    }else{
        $("#rollid_"+scid).val("");
        $("#rollname_"+scid).val("");
        $("#datefrom_"+scid).val("0000-00-00");
        $("#dateto_"+scid).val("0000-00-00"); 
        $("#pop_close").click();
    }
  });
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

// function load_data(id){
//     empty_grid();
//     $.post("index.php/main/get_data/s_permission_level_order/", {
//         id : id
//     }, function(r){
//         if(r!=2){


//         $("#code").val(r.sum[0].code);
//         $("#c_hidden").val(r.sum[0].code);
//         $("#c_name").val(r.sum[0].name);
//         $("#description").val(r.sum[0].description);
//         if(r.sum[0].all_role == 1){
//             $("#all_role").attr('checked',true);          
//         }
//         if(r.sum[0].is_active == 1){
//             $("#is_active").attr('checked',true);          
//         }

//         for(var i=0; i<r.det.length; i++){
//             $("#rolid_"+i).val(r.det[i].role_id);
//             $("#rolname_"+i).val(r.det[i].description);

//         }
//        }    
//     }, "json");
// }

function get_data(){
    var x = y = new Array(); var a = 0;
    $("#tgrid tr").each(function(){
        y = new Array();
        $(this).find("td").each(function(i){
            if(i==0){
                y[0] = $(this).html();
            }else if(i == 2){
                y[1] = $(this).children().attr("checked")? 1:0;
            }else if(i == 3){
                y[2] = $(this).children().val();
            }else if(i == 4){
                y[3] = $(this).children().val();
            }
        });
        if(y[1] == 1) { x[a++] = y; }
    });
    return x;
}



function save(){
    var frm = $("#form_");
    $.ajax({
    type: frm.attr('method'),
    url: frm.attr('action'),
    data: frm.serialize(),
    success: function (pid) {
            if(pid == 1){
                location.href="";
            }else if(pid == 2){
                set_msg("You don't have permission for save records.", 'error');
            }else if(pid == 3){
                set_msg("You don't have permission for edit records.", 'error');
            }else{
                set_msg("Error : "+pid);
            }
    }
    });
}



function check_code(){
    var code = $("#code").val();
    $.post("index.php/main/load_data/s_permission_level_order/check_code", {
        code : code
    }, function(res){
        if(res == 1){
            if(confirm("This code ("+code+") already added. \n\n Do you need edit it?")){
                set_edit(code);
            }else{
                $("#code").val('');
            }
        }
    }, "text");
}



function validate(){
    if($("#code").val()==""){
        set_msg("Please enter code.");
        return false;
    }else if($("#c_name").val()==""){
        set_msg("Please enter name.");
        return false;
    }else if($("#description").val()==""){
        set_msg("Please enter description.");
        return false;
    }else{
        return true;
    }
}

    

function set_delete(code){
    if(confirm("Are you sure delete "+code+"?")){
        loding();
        $.post("index.php/main/delete/s_permission_level_order", {
            code : code
        }, function(res){
            if(res == 1){
                location.href="";
            }else{
                set_msg("Item deleting fail.","error");             
            }
            loding();
        }, "text");
    }
}

    

function set_edit(code){
    loding();
    empty_grid();
    $.post("index.php/main/get_data/s_permission_level_order", {
        code : code
    }, function(res){
        $("#code").val(res.sum[0].code);
        $("#c_name").val(res.sum[0].name);
        $("#description").val(res.sum[0].description);

        $("#code_").val(res.sum[0].code);
        if(res.sum[0].is_check_all_role==1){
            $("#all_role").attr("checked","checked");
        }else{
            $("#all_role").removeAttr("checked");
        }

       if(res.sum[0].is_active==1){
            $("#is_active").attr("checked","checked");
        }else{
            $("#is_active").removeAttr("checked");
        }

        $("#code").attr("readonly", true);
     
        for(x=0;res.det.length>x;x++){
            $("#no_"+x).val(res.det[x].num);
            $("#rolid_"+x).val(res.det[x].role_id);
            $("#rolname_"+x).val(res.det[x].role_description);
        }

        loding(); 
    }, "json");
}



function empty_grid(){
    
}