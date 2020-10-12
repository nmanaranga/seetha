$(document).ready(function(){
    $("#card_no").blur(function(){
        check_code();
    });

     
    $("#grid").tableScroll({height:355});

    $("#card_no").keydown(function(e){   
        if(e.keyCode==13){
            get_customer();
            get_point_history();
            get_invoice_history();
        }

        if(e.keyCode==46||e.keyCode==8){
            $("#customer_id").val("");
            $("#address").val("");
            $("#tp").val("");
            $("#email").val("");
            $(".tb_row_tdr").val("");                       
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
                sucess_msg();
            }else if(pid == 2){
                alert("No permission to add data.");
            }else if(pid == 3){
                alert("No permission to edit data.");
            }else{
                alert("Error : \n"+pid);
            }
            loding();
        }
    });
}

function get_data_table(){
    $.post("index.php/main/load_data/t_privilege_card_update/get_data_table", {
        
    }, function(r){
        $("#grid_body").html(r);
    }, "text");
}

$("#pop_search").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { load_items();}
    });


$("#id").keydown(function(e){
        if(e.keyCode == 13){
            $(this).blur();
            load_data($(this).val());
        }
    });


function check_code(){
    loding();
    var card_no = $("#card_no").val();
    $.post("index.php/main/load_data/t_privilege_card_update/check_code", {
        card_no : card_no
    }, function(res){
        if(res == 1){
            if(confirm("This code ("+card_no+") already added. \n\n Do you need edit it?")){
                $("#code_").val(1);
            }else{
                location.reload();
                $("#code").val('');
                $("#code").attr("readonly", false);
            }
        }
        loding();
    }, "text");
}

function validate(){
    if($("#card_no").val() === $("#card_no").attr('title') || $("#card_no").val() == ""){
        alert("Please enter Card No.");
        $("#code").focus();
        return false;
    }else if($("#customer_id").val() === $("#customer_id").attr('title') || $("#customer_id").val() == ""){
        alert("Please enter Customer ID.");
        $("#description").focus();
        return false;
    }else if($("#customer_id").val() === $("#card_no").val()){
        alert("Please enter deferent values for Customer ID & Card No.");
        $("#des").focus();
        return false;
    }else{
        return true;
    }
}
    
function set_delete(code){
    if(confirm("Are you sure delete "+code+"?")){
        loding();
        $.post("index.php/main/delete/t_privilege_card_update", {
            code : code
        }, function(res){
            if(res == 1){
                delete_msg();
            }else if(res == 2){
                alert("No permission to delete data.");
            }else{
                alert("Item deleting fail.");
            }
            loding();
        }, "text");
    }
}

function is_edit($mod){
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

function select_search(){
    $("#pop_search").focus();  
}

function load_items(){
    $.post("index.php/main/load_data/t_privilege_card_update/item_list_all", {
        search : $("#pop_search").val()
    }, function(r){
        $("#sr").html(r);
        settings();
        //$("#pop_search").select();      
    }, "text");
}

function settings(){                                                                                                
    $("#item_list .cl").click(function(){
        if($(this).children().eq(0).html() != "&nbsp;"){
        
            $("#customer_id").val($(this).children().eq(0).html());
            $("#address").val($(this).children().eq(1).html());
            $("#tp").val($(this).children().eq(2).html());
            $("#email").val($(this).children().eq(3).html());

            $("#pop_close").click();
            get_invoice_history();
            get_point_history();
             
        }else{

            $("#customer_id").val("");
            $("#address").val("");
            $("#tp").val("");
            $("#email").val("");
           
            $("#pop_close").click();
        }
    });
}

function get_invoice_history(){    
    $.post("index.php/main/load_data/t_privilege_card_update/get_invoice_history",{
        code:$("#card_no").val()
        },function(r){
        for (var i = 0; i <r.a.length ; i++) {
            $("#invno_"+i).val(r.a[i].nno);
            $("#date"+i).val(r.a[i].ddate);
            $("#amount_"+i).val(r.a[i].net_amount);
            
        }
        },
    "json");
} 

function get_point_history(){
  $.post("index.php/main/load_data/t_privilege_card_update/get_point_history",{
        card_no:$("#card_no").val()
        },
        function(r){
        for (var i = 0; i < r.a.length ; i++) {
            $("#trcode_"+i).val(r.a[i].trans_type);
            $("#ddate"+i).val(r.a[i].ddate);
            $("#trno_"+i).val(r.a[i].trans_no);
            $("#point2_"+i).val(r.a[i].cr);
            $("#point1"+i).val(r.a[i].dr);
            $("#earned").val(r.b[0].sdr);
            $("#used").val(r.c[0].scr);
            $("#balance").val(r.b[0].sdr-r.c[0].scr);
        }     
        },
    "json");
}

function get_customer(){
    $.post("index.php/main/load_data/t_privilege_card_update/get_customer",{
        card_no:$("#card_no").val()
        },
        function(r){
            $("#customer_id").val(r.a[0].code);
            $("#address").val(r.a[0].address2+" ,  "+r.a[0].address3);
            $("#tp").val(r.a[0].tp);
            $("#email").val(r.a[0].email);
            if (r.a[0].inactive) {
                $("#act_in").val('inactive');    
            }
            else{
               $("#act_in").val('active'); 
            }            
        },
    "json");
}
