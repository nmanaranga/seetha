
$(document).ready(function(){
    $("#card_no").blur(function(){
        check_code();
    });

     
    $("#grid").tableScroll({height:355});
    $("#customer_id").keydown(function(e){    
        if(e.keyCode==112){
           //alert("ok");
            $("#pop_search").val();
            load_items();
            $("#pop_search").select();       
            $("#serch_pop").center();
            $("#blocker").css("display", "block");
            setTimeout("select_search()", 100);
        }

        if(e.keyCode==46||e.keyCode==8){
            $("#customer_id").val("");
            $("#address").val("");
            $("#tp").val("");
            $("#email").val("");
            //location.reload();
            $(".tb_row_tdr").val("");
                       
        }


       
    });

 load_items();
     $("#pop_search").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { load_items();
        }
    });
    
    $("#tgrid").tableScroll({height:300});

    $("#pop_search").gselect();

   
    $("#nno").keydown(function(e){
        if(e.keyCode == 13){
            $(this).blur();
            load_data($(this).val());
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
                isucess_msg();
            }else if(pid == 2){
                alert("No permission to add data.");
            }else if(pid == 3){
                alert("No permission to edit data.");
            }else{
                loding();
                alert("Error : \n"+pid);
            }
            
        }
    });
}

function get_data_table(){
    $.post("/index.php/main/load_data/t_privilege_card/get_data_table", {
        
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
    $.post("index.php/main/load_data/t_privilege_card/check_code", {
        card_no : card_no
    }, function(res){
        if(res == 1){
            if(confirm("This code ("+card_no+") already added. \n\n Do you need edit it?")){
                //set_edit(card_no);
            }else{
                $("#code").val('');
                $(".tb_row_tdr").val("");
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
        $.post("index.php/main/delete/t_privilege_card", {
            code : code
        }, function(res){
            if(res == 1){
                loding();
                delete_msg();
            }else if(res == 2){
                alert("No permission to delete data.");
            }else{
                loding();
                alert("Item deleting fail.");
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
    $.post("index.php/main/get_data/t_privilege_card", {
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
function select_search(){
    $("#pop_search").focus();
  
}

function load_items(){
    $.post("index.php/main/load_data/t_privilege_card/item_list_all", {
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
            $.post("/index.php/main/load_data/t_privilege_card/get_invoice_history",{
                    code:$("#customer_id").val()
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
              $.post("/index.php/main/load_data/t_privilege_card/get_point_history",{
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





