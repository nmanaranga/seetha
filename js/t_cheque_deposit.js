var storse = 0;var is_edit=0;
$(document).ready(function(){

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


    $("#cheque_date").datepicker({ minDate: 0,dateFormat: 'yy-mm-dd' });

    $("#load_cheque").click(function(){
      //load_cheque();
      empty_grid();
      /*var fullDate = new Date()
      var twoDigitMonth = ((fullDate.getMonth().length+1) === 1)? (fullDate.getMonth()+1) : '0' + (fullDate.getMonth()+1);
      var twoDigitDate = fullDate.getDate()+"";if(twoDigitDate.length==1) twoDigitDate="0" +twoDigitDate;
      var currentDate = fullDate.getFullYear()+"-"+ twoDigitMonth +"-"+twoDigitDate;
      var sel_date=$("#cheque_date").val();

      if(currentDate>=sel_date){*/
          load_cheque();
     /* }else{
        set_msg('Wrong Date Selection,Please Check Date');
    }*/
});

    $("#btnDelete").click(function(){
        if($("#hid").val()!="0"){
            set_delete($("#id").val());
        }else{
            set_msg("Please load record for delete");
        }
    });


    $("#banck_acc").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search").val($("#banck_acc").val());
            load_acc();
            $("#serch_pop").center();
            $("#blocker").css("display", "block");
            setTimeout("select_search()", 100);
        }

        $("#pop_search").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
             load_acc();
         }
     }); 

        if(e.keyCode == 46){
            $("#acc").val("");
            $("#banck_acc").val("");
        }
    });


});

function get_selected(scid){
    var balance =parseFloat(0);
    var tot = parseFloat($("#total").val()); 
    var bal =parseFloat(0);
    $(".bl").each(function(e){
        if($("#0_"+e).val()!="" && $("#5_"+e).is(":checked")){
         balance += parseFloat($("#4_"+e).val());
     }
 });
    $("#tot_settle").val(balance); 
    if(!isNaN($("#total").val())){
        bal = tot - balance ;
        $("#tot_balance").val(bal);
    } 
    
}

function load_cheque(){
    empty_grid();
    $.post("index.php/main/load_data/t_cheque_deposit/load_cheques", {
        date : $("#cheque_date").val(),
        chq_n: $("#cheque_no").val()
    }, function(r){
        if(r != 2){
            var tot = parseFloat(0);            
            for(var i=0; i<r.length; i++){
              $("#0_"+i).val(r[i].account);
              $("#n_"+i).val(r[i].bank_name);
              $("#bank_"+i).val(r[i].bank);
              $("#1_"+i).val(r[i].branch_name);
              $("#branch_"+i).val(r[i].branch);
              $("#2_"+i).val(r[i].cheque_no);
              $("#3_"+i).val(r[i].bank_date);
              $("#4_"+i).val(r[i].amount);
              $("#t_code_"+i).val(r[i].trans_code);
              $("#t_no_"+i).val(r[i].trans_no);

              tot += parseFloat(r[i].amount);
          }
          $("#total").val(m_round(tot));           
      }else{
        alert("No records");
    }
}, "json");

}

function load_acc(){
    $.post("index.php/main/load_data/t_cheque_deposit/acc_list", {
        search : $("#pop_search").val() 
    }, function(r){
        $("#sr").html(r);
        settings_acc();            
    }, "text");
}

function settings_acc(){
   $("#item_list .cl").click(function(){        
    $("#banck_acc").val($(this).children().eq(0).html());
    $("#acc").val($(this).children().eq(1).html());
    $("#pop_close").click();                
})   
}


function set_delete(){
    var id = $("#hid").val();
    if(id != 0){
        if(confirm("Are you sure delete cheque deposit no? "+id)){
            loding();
            $.post("index.php/main/delete/t_cheque_deposit", {
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
    for(var x=0; x<50; x++){
        $("#0_"+x).val("");
        $("#h_"+x).val("");
        $("#n_"+x).val("");
        $("#bank_"+x).val("");
        $("#1_"+x).val("");
        $("#branch_"+x).val("");
        $("#2_"+x).val("");
        $("#3_"+x).val("");
        $("#4_"+x).val("");
        $("#t_code_"+x).val("");
        $("#t_no_"+x).val("");        
        $("#5_"+x).removeAttr("checked");
    }
    

    
}

function load_data(id){
    empty_grid();
    $.post("index.php/main/get_data/t_cheque_deposit/", {
        id : id
    }, function(r){
        if(r!= 2){
            $("#id").attr("readonly","readonly");
            $("#hid").val(r.sum[0].nno);
            $("#id").val(r.sum[0].nno);
            $("#qno").val(r.sum[0].nno);
            $("#date").val(r.sum[0].ddate);
            $("#banck_acc").val(r.sum[0].bank_id);
            $("#acc").val(r.sum[0].description);
            $("#tot_balance").val(r.sum[0].balance);
            $("#total").val(r.sum[0].total);
            $("#tot_settle").val(r.sum[0].settle);
            
            for(var x=0; x<r.det.length; x++){
                $("#0_"+x).val(r.det[x].account);
                $("#n_"+x).val(r.det[x].bank_name);
                $("#bank_"+x).val(r.det[x].bank);
                $("#1_"+x).val(r.det[x].branch_name);
                $("#branch_"+x).val(r.det[x].branch);
                $("#2_"+x).val(r.det[x].cheque);
                $("#3_"+x).val(r.det[x].bank_date);
                $("#4_"+x).val(r.det[x].amount);
                $("#t_code_"+x).val(r.det[x].trans_code);
                $("#t_no_"+x).val(r.det[x].trans_no);
                $("#5_"+x).attr("checked",true);
            } 
            if(r.sum[0].is_cancel==1){
              $("#btnDelete").attr("disabled", "disabled");
              $("#btnSave").attr("disabled", "disabled");
              $("#mframe").css("background-image", "url('img/cancel.png')");
          }
          $("#load_cheque").attr("disabled","disabled");
          
      }else{
        set_msg("No records");
    }
}, "json");
}


function select_search(){
    $("#pop_search").focus();
    $("#pop_search").val("");
}


function load_accounts(){
    $.post("index.php/main/load_data/m_account/account_list", {
        search : $("#pop_search").val()       
    }, function(r){
        $("#sr").html(r);
        settings();
    }, "text");
}

function settings(){
    $("#item_list tr").click(function(){
        if($(this).children().eq(0).html() != "&nbsp;"){
            if(check_item_exist($(this).children().eq(0).html())){
                $("#0_"+scid).val($(this).children().eq(0).html());
                $("#h_"+scid).val($(this).children().eq(0).html());
                $("#n_"+scid).val($(this).children().eq(1).html());
                $("#1_"+scid).val('');
                $("#2_"+scid).val('');
                $("#2_"+scid).val($(this).children().eq(7).html());
                $("#1_"+scid).focus();$("#pop_close").click();
            }else{
                alert("Account code "+$(this).children().eq(1).html()+" is already added.");
            }
        }else{
            $("#n_"+scid).val("");
            $("#0_"+scid).val("");
            $("#1_"+scid).val("");
            $("#2_"+scid).val("");
            $("#3_"+scid).val("");
            set_total();$("#pop_close").click();
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

function validate(){
    var v = false;
    
    if($("#banck_acc").val() == ''){
        set_msg("Please select bank account");
        v = false;
    }else if($("#tot_settle").val() ==0 || $("#tot_settle").val()==""){
        set_msg("Please Select Cheques For Deposit !");
        v = false;
    }else{
        v = true;
    }    
    
    return v;
}

function save()
{
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



