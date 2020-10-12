var storse = 0;var is_edit=0;
$(document).ready(function(){
    $("#btnApprove").attr("disabled",true);
    $("#tgrid").tableScroll({height:300});
   
    $("#id").keydown(function(e){
        set_cid($(this).attr("id"));
        if(e.keyCode == 13){
            $(this).blur();
            load_data($(this).val());
        }
    });
    
    $(".chk").click(function(){
        set_cid($(this).attr("id"));
        get_selected(scid);
    });

    $("#btnPrint").click(function(){
        $("#print_pdf").submit();
    });

    $("#btnApprove").click(function(){
        $("#is_approve").val("1");
        save();
    });

    $("#load_credits").click(function(){
        if($("#merchant_id").val() !=""){
            load_credit_card();
        }else{
            set_msg("Please enter merchant id");
        }
    });

    $(".pr").keyup(function(e){
        set_cid($(this).attr("id"));
        update_amounts();
    });

    $("#btnReset").click(function(){
      location.href="";
    });

    $("#btnDelete").click(function(){
        if($("#hid").val()!="0"){
            set_delete($("#id").val());
        }else{
            set_msg("Please load record for delete");
        }
    });

    $("#merchant_id").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search").val($("#merchant_id").val());
            load_bank();
            $("#serch_pop").center();
            $("#blocker").css("display", "block");
            setTimeout("select_search()", 100);
        }

       $("#pop_search").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                 load_bank();
            }
        }); 

        if(e.keyCode == 46){
            $("#merchant_id").val("");
            $("#merchant_des").val("");
            $("#merchant_acc").val("");
        }
    });

    $("#bank_acc").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search2").val($("#bank_acc").val());
            load_bank_acc();
            $("#serch_pop2").center();
            $("#blocker2").css("display", "block");
            setTimeout("select_search2()", 100);
        }

       $("#pop_search2").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                 load_bank_acc();
            }
        }); 

        if(e.keyCode == 46){
            $("#bank_acc").val("");
            $("#bank_des").val("");
        }
    });

});

function update_amounts(){
    var balance = parseFloat(0);
    if($("#amnt_"+scid).val() !="" && $("#acom_"+scid).val() != ""){
       balance = parseFloat($("#amnt_"+scid).val()) - parseFloat($("#acom_"+scid).val());
       $("#bal_"+scid).val(m_round(balance));
    }
    get_selected(scid);

}

function get_selected(scid){
    var balance =parseFloat(0);
    var tot = parseFloat(0); 
    var sys_tot = parseFloat(0); 
    var bal =parseFloat(0);
    var sys_bal =parseFloat(0);
    var fnl_bal_tot =parseFloat(0);
    var fnl_bal =parseFloat(0);
    var basic_tot=parseFloat(0);
    var basic_bal=parseFloat(0);
    var fnl_asc_tot=parseFloat(0);
    var fnl_asc_bal=parseFloat(0);
    var diff=parseFloat(0);

    $(".bl").each(function(e){
        if($("#amnt_"+e).val()!="" && $("#5_"+e).is(":checked") && $("#acom_"+e).val()!=""){            
            tot += parseFloat($("#amnt_"+e).val());
            sys_tot += parseFloat($("#scom_"+e).val());
            fnl_asc_tot += parseFloat($("#acom_"+e).val());

            if($("#bal_"+e).val()!=""){
                fnl_bal_tot += parseFloat($("#bal_"+e).val());
            }
        }
         
        if(!$("#5_"+e).is(":checked") && $("#amnt_"+e).val()!="" && $("#acom_"+e).val()!="" ){
            bal += parseFloat($("#amnt_"+e).val());
            sys_bal += parseFloat($("#scom_"+e).val());
            fnl_asc_bal += parseFloat($("#acom_"+e).val());
            if($("#bal_"+e).val()!=""){
                fnl_bal += parseFloat($("#bal_"+e).val());
            }
        }

        if($("#acom_"+e).val()!="" && $("#bal_"+e).val()!=""){
            basic_tot += parseFloat($("#acom_"+e).val());
            basic_bal += parseFloat($("#bal_"+e).val());
            diff += parseFloat($("#scom_"+e).val()) - parseFloat($("#acom_"+e).val());
        }
    });

    $("#chk_amount").val(m_round(tot)); 
    $("#unchk_amount").val(m_round(bal)); 

    $("#chk_sys").val(m_round(sys_tot)); 
    $("#unchk_sys").val(m_round(sys_bal)); 

    $("#chk_act").val(m_round(fnl_asc_tot)); 
    $("#unchk_act").val(m_round(fnl_asc_bal));

    $("#chk_bal").val(m_round(fnl_bal_tot)); 
    $("#unchk_bal").val(m_round(fnl_bal));

    $("#total_act").val(m_round(basic_tot)); 
    $("#total_bal").val(m_round(basic_bal));

    $("#difference").val(m_round(diff));
    
  
}

function load_credit_card(){
    empty_grid();
    $.post("index.php/main/load_data/t_credit_card_reconcil/load_credit_cards", {
        from_date : $("#from_date").val(),
        to_date   : $("#to_date").val(),
        merchant  : $("#merchant_id").val()
    }, function(r){
        if(r != 2){
            var amount = parseFloat(0);          
            var sys_c = parseFloat(0);    
            var bal_tot = parseFloat(0);         
            for(var i=0; i<r.length; i++){
              var trnas = r[i].description;
              var branch = r[i].name;  
              var bal = parseFloat(0);     
              $("#date_"+i).val(r[i].date);
              $("#tcodedes_"+i).val(trnas.toLowerCase());             
              $("#tcode_"+i).val(r[i].trans_code);
              $("#tno_"+i).val(r[i].trans_no);
              $("#bcname_"+i).val(branch.toLowerCase());
              $("#tbc_"+i).val(r[i].bc);
              $("#tcl_"+i).val(r[i].cl);
              $("#cardn_"+i).val(r[i].card_no);              
              $("#amnt_"+i).val(r[i].amount);
              $("#scom_"+i).val(r[i].commission);
              $("#acom_"+i).val(r[i].commission);
              amount += parseFloat(r[i].amount);
              sys_c += parseFloat(r[i].commission);
              bal = parseFloat(r[i].amount) - parseFloat(r[i].commission);
              bal_tot += parseFloat(r[i].amount) - parseFloat(r[i].commission);
              $("#bal_"+i).val(m_round(bal));
            }
            $("#total_amount").val(m_round(amount));   
            $("#total_sys").val(m_round(sys_c));   
            $("#total_act").val(m_round(sys_c));  
            $("#total_bal").val(m_round(bal_tot));           
        }else{
            set_msg("No records");
        }
    }, "json");

}

function load_bank(){
        $.post("index.php/main/load_data/t_credit_card_reconcil/bank_list", {
            search : $("#pop_search").val() 
        }, function(r){
            $("#sr").html(r);
            settings_bank();            
        }, "text");
}

function settings_bank(){
   $("#item_list .cl").click(function(){        
        $("#merchant_id").val($(this).children().eq(0).html());
        $("#merchant_des").val($(this).children().eq(2).html());
        $("#merchant_acc").val($(this).children().eq(3).html());
        $("#pop_close").click();                
    })   
}

function load_bank_acc(){
        $.post("index.php/main/load_data/t_credit_card_reconcil/bank_acc_list", {
            search : $("#pop_search2").val() 
        }, function(r){
            $("#sr2").html(r);
            settings_bank_acc();            
        }, "text");
}

function settings_bank_acc(){
   $("#item_list .cl").click(function(){        
        $("#bank_acc").val($(this).children().eq(0).html());
        $("#bank_des").val($(this).children().eq(1).html());
        $("#pop_close2").click();                
    })   
}

function set_delete(){
    var id = $("#hid").val();
    if(id != 0){
        if(confirm("Are you sure delete cheque deposit no? "+id)){
            loding();
            $.post("index.php/main/delete/t_credit_card_reconcil", {
                id : id
            }, function(r){
                if(r != 1){
                    loding();
                    set_msg(r);
                }else{
                    loding();
                    delete_msg();
                }
            }, "text");
        }
    }else{
        alert("Please load record");
    }
}

function empty_grid(){
    for(var x=0; x<25; x++){
        $("#date_"+x).val("");
        $("#tcodedes_"+x).val("");             
        $("#tcode_"+x).val("");
        $("#tno_"+x).val("");
        $("#bcname_"+x).val("");
        $("#tbc_"+x).val("");
        $("#tcl_"+x).val("");
        $("#cardn_"+x).val("");              
        $("#amnt_"+x).val("");
        $("#scom_"+x).val("");
        $("#acom_"+x).val("");
        $("#bal_"+x).val("");
        $("#5_"+x).removeAttr("checked");
    }
   

  
}

function load_data(id){
    empty_grid();
    $.post("index.php/main/get_data/t_credit_card_reconcil/", {
        id : id
    }, function(r){
        if(r!= 2){
            $("#id").attr("readonly","readonly");
            $("#btnApprove").attr("disabled",false);
            $("#hid").val(r.sum[0].nno);
            $("#id").val(r.sum[0].nno);
            $("#qno").val(r.sum[0].nno);
            $("#date").val(r.sum[0].ddate);
            $("#merchant_id").val(r.sum[0].merchant_id);
            $("#merchant_acc").val(r.sum[0].acc_no);
            $("#merchant_des").val(r.sum[0].bank_des);
            $("#description").val(r.sum[0].description);
            $("#from_date").val(r.sum[0].from_date);
            $("#to_date").val(r.sum[0].to_date);
            $("#total_amount").val(r.sum[0].tot_amount);
            $("#total_sys").val(r.sum[0].sys_comm_tot);
            $("#total_act").val(r.sum[0].actual_comm_tot);
            $("#total_bal").val(r.sum[0].tot_balance);

            $("#bank_acc").val(r.sum[0].bank_acc);
            $("#bank_des").val(r.sum[0].bank_name);
            
            for(var x=0; x<r.det.length; x++){
                $("#date_"+x).val(r.det[x].trans_date);
                $("#tcodedes_"+x).val(r.det[x].description.toLowerCase());             
                $("#tcode_"+x).val(r.det[x].trans_code);
                $("#tno_"+x).val(r.det[x].trans_no);
                $("#bcname_"+x).val(r.det[x].name.toLowerCase());
                $("#tcl_"+x).val(r.det[x].trans_cl);
                $("#tbc_"+x).val(r.det[x].trans_bc);
                $("#cardn_"+x).val(r.det[x].credit_card_no);              
                $("#amnt_"+x).val(r.det[x].actual_amount);
                $("#scom_"+x).val(r.det[x].actual_comm);
                $("#acom_"+x).val(r.det[x].actual_comm);
                $("#bal_"+x).val(r.det[x].actual_balance);
                if(r.det[x].is_reconcil==1){
                    $("#5_"+x).attr("checked",true);
                }else{
                    $("#5_"+x).attr("checked",false);
                }
                get_selected(x);
            } 
            if(r.sum[0].is_cancel==1){
              $("#btnDelete").attr("disabled", "disabled");
              $("#btnSave").attr("disabled", "disabled");
              $("#btnApprove").attr("disabled", "disabled");
              $("#mframe").css("background-image", "url('img/cancel.png')");
            }
            if(r.sum[0].is_approve==1){
              $("#btnDelete").attr("disabled", "disabled");
              $("#btnApprove").attr("disabled", "disabled");
              $("#btnSave").attr("disabled", "disabled");
              $("#mframe").css("background-image", "url('img/approved1.jpg')");
            }
            $("#load_credits").attr("disabled","disabled");
                    
        }else{
            set_msg("No records");
        }
    }, "json");
}


function select_search(){
    $("#pop_search").focus();
}

function select_search2(){
    $("#pop_search2").focus();
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

function validate(){
    var v = false;
   
    if($("#chk_amount").val() ==0 || $("#chk_amount").val()==""){
        set_msg("Please Select Credit card For reconsillation !");
        v = false;
    }else if($("#bank_acc").val() ==""){
        set_msg("Please enter bank account");
    }else{
        v = true;
    }    
    
    return v;
}

function save(){
    $("#qno").val($("#id").val());
    var frm = $('#form_');
	loding();
	$.ajax({
	  type: frm.attr('method'),
	  url: frm.attr('action'),
	  data: frm.serialize(),
	  success: function (pid) {  
  		if(pid==1){
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



