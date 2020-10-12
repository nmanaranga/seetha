$(document).ready(function(){


    $("#tgrid").tableScroll({height:300});

    $(".tot").keyup(function(){
        total();
    });

    $("#id").keyup(function(e){
        if(e.keyCode == 13){
            load_datas($(this).val());
        }
    });

    $("#btnDelete").click(function(){
        set_delete($("#id").val());
    });

    $("#Delete").click(function(){
        delete_record($("#id").val());
    });

    $("#btnPrint").click(function(){
        if($("#hid").val()!="0"){
            $("#print_pdf").submit();
        }else{
            set_msg("Please load records before print.")
        }
    });

    $("#customer_id").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search").val($("#customer_id").val());
            load_data();
            $("#serch_pop").center();
            $("#blocker").css("display", "block");
            setTimeout("select_search()", 100);
        }

        $("#pop_search").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
               load_data();
           }
       }); 

        if(e.keyCode == 46){
            $("#customer").val("");
            $("#customer_id").val("");
        }
    });


    $("#officer_id").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search2").val($("#officer_id").val());
            load_data1();
            $("#serch_pop2").center();
            $("#blocker2").css("display", "block");
            setTimeout("select_search2()", 100);
        }

        $("#pop_search2").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
               load_data1();
           }
       }); 

        if(e.keyCode == 46){
            $("#officer").val("");
            $("#officer_id").val("");
        }
    });

    $(".fo").keydown(function(e){  
        set_cid($(this).attr("id"));
        if(e.keyCode==112){
            $("#pop_search13").val($("#0_"+scid).val());
            load_items();
            $("#serch_pop13").center();
            $("#blocker").css("display", "block");
            setTimeout("select_search13()", 100);
        }
        $("#pop_search13").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                load_items();
            }
        });
        if(e.keyCode==46){
            $("#h_"+scid).val("");
            $("#0_"+scid).val("");
            $("#n_"+scid).val("");
            $("#1_"+scid).val("");
            $("#2_"+scid).val("");
            $("#3_"+scid).val("");
            $("#4_"+scid).val("");
            $("#5_"+scid).val("");
            $("#6_"+scid).val("");
            total();
        }

    });



});

function load_items(){        
   $.post("index.php/main/load_data/t_receipt_temp/bank_list_all", {
    search : $("#pop_search13").val(),
}, function(r){
    $("#sr13").html(r);
    settings2();
}, "text");
}

function load_data(){
    if($("#curr_acc").is(':checked')){
        $.post("index.php/main/load_data/t_receipt_temp/currentAcc_list", {
            search : $("#pop_search").val() 
        }, function(r){
            $("#sr").html(r);
            settings();            
        }, "text");
    }else{
        $.post("index.php/main/load_data/t_cash_sales_sum/customer_list", {
            search : $("#pop_search").val() 
        }, function(r){
            $("#sr").html(r);
            settings();            
        }, "text");

    }


}

function load_data1(){
    $.post("index.php/main/load_data/utility/f1_selection_list", {
        data_tbl:"m_employee",
        field:"code",
        field2:"name",
        preview2:"Employee Name",
        search : $("#pop_search2").val() 
    }, function(r){
        $("#sr2").html(r);
        settings1();            
    }, "text");
}


function select_search(){
    $("#pop_search").focus();
}

function select_search2(){
    $("#pop_search2").focus();
}

function select_search13(){
    $("#pop_search13").focus();
}

function settings(){
    $("#item_list .cl").click(function(){        
        $("#customer_id").val($(this).children().eq(0).html());
        $("#customer").val($(this).children().eq(1).html());
        $("#pop_close").click();                
    })    
}


function settings1(){
    $("#item_list .cl").click(function(){        
        $("#officer_id").val($(this).children().eq(0).html());
        $("#officer").val($(this).children().eq(1).html());
        $("#pop_close2").click();                
    })    
}

function settings2(){
    $("#item_list .cl").click(function(){
        //if(check_item_exist($(this).children().eq(0).html()+"-"+$(this).children().eq(2).html())){         
            $("#h_"+scid).val($(this).children().eq(0).html()+"-"+$(this).children().eq(2).html());
            $("#0_"+scid).val($(this).children().eq(2).html());
            $("#n_"+scid).val($(this).children().eq(3).html());
            $("#1_"+scid).val($(this).children().eq(0).html()); 
            $("#2_"+scid).val($(this).children().eq(1).html());
            total();
            $("#pop_close13").click();
            
        /*}else{
            set_msg("Bank Branch "+$(this).children().eq(1).html()+" is already added.");
        }*/
        $("#pop_close13").click();
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

function total(){
    var total=parseFloat(0);
    $(".tot").each(function(e){
        if($("#6_"+e).val()!=""){
            total+= parseFloat($("#6_"+e).val());
        }
    });
    $("#tot_dr").val(total);
}

function save(){
    $("#qno").val($("#id").val());
    var frm = $('#form_');
    loding();
    $.ajax({
       type: frm.attr('method'),
       url: frm.attr('action'),
       data: frm.serialize(),
       success: function (pid){
        if(pid == 1){
            $("#btnSave").attr("disabled",true);
            loding();
            if(confirm("Save Completed, Do You Want A print?")){
                if($("#is_prnt").val()==1){
                    $("#print_pdf").submit();
                }
                location.href="";
            }else{
                location.href="";
            }
        }else{
            loding();
            set_msg(pid);
        }

    }
});
}



function validate(){
    var st=1;
    for(var x=0; x<25; x++){
        if($("#0_"+x).val()!=""){
            if($("#3_"+x).val()=="" || $("#4_"+x).val()=="" || $("#6_"+x).val()==""){
                st=2;
            }
        }
    }

    if($("#customer_id").val()==""){
        set_msg("Please select customer");
        return false;
    }else if($("#officer_id").val()==""){
        set_msg("Please select officer");
        return false;
    }else if(st==2){
        set_msg("Please fill all the required fields");
        return false;
    }else{
        return true; 
    }


}

function set_delete(code){
    if(confirm("Are you sure delete acknowledgement no "+code+"?")){
        loding();
        $.post("index.php/main/delete/t_receipt_temp", {
            code : code
        }, function(res){
            if(res == 1){
             location.href="";
         }else{
             set_msg(res);
         }
         loding();
     }, "text");
    }
}

function delete_record(code){
    if(confirm("Are you sure delete acknowledgement no "+code+"?")){
        loding();
        $.post("index.php/main/load_data/t_receipt_temp/delete_records", {
            code : code
        }, function(res){
            if(res == 1){
             location.href="";
         }else{
             set_msg(res);
         }
         loding();
     }, "text");
    }
}



function load_datas(code){
    loding();
    $.post("index.php/main/get_data/t_receipt_temp", {
        id : code
    }, function(res){
        if(res==2){
            set_msg("No records");
        }        
        else{
            $("#id").attr("readonly","readonly");
            $("#qno").val(res.sum[0].nno);
            $("#dt").val(res.sum[0].date);
            $("#cus_id").val(res.sum[0].customer);
            $("#salesp_id").val(res.sum[0].employee);
            
            $("#id").val(res.sum[0].nno);
            $("#hid").val(res.sum[0].nno);
            $("#date").val(res.sum[0].date);
            $("#ref_no").val(res.sum[0].ref_no);
            $("#customer_id").val(res.sum[0].customer);
            $("#customer").val(res.sum[0].cus_name);
            $("#officer_id").val(res.sum[0].employee);
            $("#officer").val(res.sum[0].emp_name);
            $("#remark").val(res.sum[0].remarks);
            $("#tot_dr").val(res.sum[0].total);


            if(res.sum[0].is_cancel==1)
            {
                $("#form_").css("background-image", "url('img/cancel.png')");
                $("#btnDelete").attr("disabled", true);
                $("#btnSave").attr("disabled", true);
            }

            if(res.sum[0].curr_acc==1)
            {
                $("#curr_acc").attr("checked",true);
                
            }

            for(var i=0; i<res.det.length;i++){
             $("#h_"+i).val(res.det[i].bank+"-"+res.det[0].branch);   
             $("#0_"+i).val(res.det[i].bank); 
             $("#n_"+i).val(res.det[i].bank_name);     
             $("#1_"+i).val(res.det[i].branch); 
             $("#2_"+i).val(res.det[i].branch_name);
             $("#3_"+i).val(res.det[i].account); 
             $("#4_"+i).val(res.det[i].cheque_no);  
             $("#5_"+i).val(res.det[i].realize_date); 
             $("#6_"+i).val(res.det[i].amount);     
         }

     }
     loding();
 }, "json");
}