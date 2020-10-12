$(document).ready(function(){

    var p_code = window.location.search.split('=')[2];

    if(p_code != undefined){
        load_data(p_code);
    }

    $("#btnReset").click(function(){
      location.href="index.php?action=t_debit_note";
  });

    /* $("#pop_search").css("display","none");*/

    $("#no").keydown(function(e){
        if(e.keyCode==13){
            $("#hid").val($('#no').val());    
            $("#no").attr("readonly","readonly");
            $("#pdf_no").attr({"value":$("#no").val(),"Title":$("#no").val()});
            load_data($("#no").val());      
        }
    });

    $("#btnApprove").click(function(){
        $("#is_approve").val(1);
        if(validate()){
            $("#btnApprove").attr("disabled","disabled");
            save();
        }
    });

    $(".prntPdf").click(function(){
     //   pop_print();
 });

    $("#customer").click(function(){
        $("#c_type").val("1");
    });

    $("#supplier").click(function(){
        $("#c_type").val("2");
    }); 

    $("#internal").click(function(){
        $("#c_type").val("3");
    });

    $("#btnPrint").click(function(){
        $("#print_pdf").submit();
    });

    $("#btnStatuss").click(function() {
        if($("#hid").val()!=0){
            if(confirm("Are You Sure To Approve (Change Status) Debit Note?")){
                status_update();
            }else{
                location.href='';
            }
        }else{
            set_msg("please load debit note before approve");
        }
    });

    $("#btnDelete").click(function(){
     if($("#hid").val()!=0) {
        set_delete($("#hid").val());
    }else{
        set_msg("Please load the record","error");
    }
});

    $("#code_s").keydown(function(e){ 
        if(e.keyCode==112){
          if($("#c_type").val()=="1"){
           $("#pop_search").val($("#code_s").val());
           load_customer(); 
           $("#serch_pop").center();
           $("#blocker").css("display", "block");
           setTimeout("$('#pop_search').focus()", 100);   

           $("#pop_search").keyup(function(e){
              if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                load_customer();
            }
        });      
       }else if($("#c_type").val()=="2"){
        $("#pop_search2").val($("#code_s").val());
        load_supplier(); 
        $("#serch_pop2").center();
        $("#blocker2").css("display", "block");
        setTimeout("$('#pop_search2').focus()", 100);   

        $("#pop_search2").keyup(function(e){
          if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
            load_supplier();
        }
    });     
    }else if($("#c_type").val()=="3"){
      $("#pop_search2").val($("#code_s").val());
      load_currentAcc(); 
      $("#serch_pop2").center();
      $("#blocker2").css("display", "block");
      setTimeout("$('#pop_search2').focus()", 100);   

      $("#pop_search2").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
          load_currentAcc();
      }
  });     
  }
}
if(e.keyCode==46){
 $("#code_s").val("");
 $("#s_ac_").val("");
}   
});


    $("#emp").keydown(function(e){ 
        if(e.keyCode==112){
            $("#pop_search12").val($("#emp").val());
            load_emp(); 
            $("#serch_pop12").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search12').focus()", 100);   

            $("#pop_search12").keyup(function(e){
              if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                load_emp();
            }
        });      
        }
        if(e.keyCode==46){
         $("#emp").val("");
         $("#emp_des").val("");
     }   
 });

    $("#acc").keydown(function(e){ 
        if(e.keyCode==112){    
            $("#pop_search14").val($("#acc").val());
            load_acc();
            $("#serch_pop14").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search14').focus()", 100);   
        }
        $("#pop_search14").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                load_acc();
            }
        });
        if(e.keyCode==46){
            $("#acc").val("");
            $("#acc_id").val("");
        }  
    });


    $("#grid").tableScroll({height:355});
    
    $(".s_ac").focus(function(){
        if(! $("input[type=radio]").is(':checked')){
            alert("Please select customer or supplier");  
        }
    });

    $("#customer , #supplier").click(function(){
        empty_grid();
    });

    $("#customer").click(function(){
        //$(".s_ac").attr({"id":"code1","name":"code_s"});
        //$(".s_ac_").attr("id","code1_id");
        $("#is_customer").attr({"value":"1","Title":"1"});
        $("#cus_or_sup").attr({"value":"Customer","Title":"Customer"});
        /*$("#code1").autocomplete('index.php/main/load_data/m_customer/auto_com', {
            width: 350,
            multiple: false,
            matchContains: true,
            formatItem: formatItems,
            formatResult: formatItemsResult
            });

            $("#code1").keydown(function(e){
                if(e.keyCode == 13){
                    set_cus_values($(this));
                }
            });
        
            $("#code1").blur(function(){
                set_cus_values($(this));
            });*/
        });

    $("#supplier").click(function(){
        //$(".s_ac").attr({"id":"supplier1","name":"code_s"});
        //$(".s_ac_").attr("id","supplier1_id");
        $("#is_customer").attr({"value":"0","Title":"0"});
        $("#cus_or_sup").attr({"value":"Supplier","Title":"Supplier"});
        /*$("#supplier1").autocomplete('index.php/main/load_data/m_supplier/auto_com', {
            width: 350,
            multiple: false,
            matchContains: true,
            formatItem: formatItems,
            formatResult: formatItemsResult
            });


            $("#supplier1").keydown(function(e){
                if(e.keyCode == 13){
                    set_cus_values($(this));
                }
            });
        
            $("#supplier1").blur(function(){
                set_cus_values($(this));
            });*/
        });

    $("#acc").autocomplete('index.php/main/load_data/t_debit_note/auto_com', {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatItems,
        formatResult: formatItemsResult
    });

    $("#acc").keydown(function(e){
        if(e.keyCode == 13){
            set_cus_values2($(this));
        }
    });

    $("#acc").blur(function(){
        set_cus_values2($(this));
    });
}); 

function status_update(){
    $.post("index.php/main/load_data/t_debit_note/status_updation", {
      nno:$("#hid").val()
  }, function(r){
      if(r==1){
        alert("Updation Successfully");
        location.href="";
    } 
}, "text");
}


function load_emp(){
   $.post("index.php/main/load_data/utility/f1_selection_list", {
      data_tbl:"m_employee",
      field:"code",
      field2:"name",
      preview2:"Employee Name",
      search : $("#pop_search12").val() 
  }, function(r){
      $("#sr12").html(r);
      settings_emp();      
  }, "text");
}


function settings_emp(){
    $("#item_list .cl").click(function(){        
        $("#emp").val($(this).children().eq(0).html());
        $("#emp_des").val($(this).children().eq(1).html());
        $("#pop_close12").click();                
    })    
}

function load_supplier(){
  $.post("index.php/main/load_data/utility/f1_selection_list", {
      data_tbl:"m_supplier",
      field:"code",
      field2:"name",
      preview2:"Supplier Name",
      search : $("#pop_search2").val() 
  }, function(r){
      $("#sr2").html(r);
      settings_sup();      
  }, "text");
}

function settings_sup(){
    $("#item_list .cl").click(function(){        
        $("#code_s").val($(this).children().eq(0).html());
        $("#s_ac_").val($(this).children().eq(1).html());
        $("#pop_close2").click();                
    })    
}

function load_customer(){
    $.post("index.php/main/load_data/utility/f1_selection_list_customer", {
        data_tbl:"m_customer",
        field:"code",
        field2:"name",
        field3:"nic",
        preview1:"Customer ID",
        preview2:"Customer Name",
        preview3:"Customer NIC",
        search : $("#pop_search").val() 
    }, function(r){
        $("#sr").html("");
        $("#sr").html(r);
        settings_cus();            
    }, "text");
}

function settings_cus(){
    $("#item_list .cl").click(function(){        
        $("#code_s").val($(this).children().eq(0).html());
        $("#s_ac_").val($(this).children().eq(1).html());
        $("#pop_close").click();                
    })    
}

function load_acc() {
    $.post("index.php/main/load_data/utility/f1_selection_list", {
      data_tbl:"m_account",
      field:"code",
      field2:"description",
      preview2:"Account Name",
      search : $("#pop_search14").val() 
  }, function(r){
      $("#sr14").html(r);
      settings_acc();      
  }, "text");
}

function settings_acc(){
    $("#item_list .cl").click(function(){        
        $("#acc").val($(this).children().eq(0).html());
        $("#acc_id").val($(this).children().eq(1).html());
        $("#pop_close14").click();                
    })    
}
function empty_grid(){
    $(".s_ac , .s_ac_").val("");
    $("#acc").val("");
    $("#acc_id").val("");
    $("#amount").val("");
    $("#description").val("");
}

function pop_print(){
    $("#pop_search").val();
    load_data();
    $("#pop_search").select();       
    $("#serch_pop").center();
    $("#blocker").css("display", "block");
    setTimeout("select_search()", 100); 
}

function set_cus_values(f){
    var v = f.val();
    v = v.split("|");
    
    if(v.length == 2){
        f.val(v[0]);
        $("#code1_id").val(v[1]);   
        $("#supplier1_id").val(v[1]);
    }
}

function set_cus_values2(f){
    var v = f.val();
    v = v.split("|");
    if(v.length == 2){
        f.val(v[0]);
        $("#acc_id").val(v[1]);       
    }
}

function formatItems(row){
    return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
}

function formatItemsResult(row){

    return row[0]+"|"+row[1];
}


function select_search(){
    $("#pop_search").focus();
}
function save(){ 
    $("#pdf_no").val($("#no").val());
    $("#qno").val($("#no").val());
    $("#is_duplicate").val("1");
    var frm = $('#form_');
    loding();
    $.ajax({
        type: frm.attr('method'),
        url: frm.attr('action'),
        data: frm.serialize(),
        success: function (pid){
            var sid=pid.split('@');
            if(sid[0]==1){
             $("#btnSave").attr("disabled",true);
             loding(); 
               //sucess_msg();
               
               if(confirm("Save Completed, Do You Want A print?")){
                if($("#is_prnt").val()==1){
                    $("#qno").val(sid[1]);
                    $("#print_pdf").submit();
                }
                location.href="";
            }else{
                location.href="";
            }
        }else if(pid == 2){
            alert("No permission to add data.");
        }else if(pid == 3){
            alert("No permission to edit data.");
        }else{
            loding();
            set_msg(pid,"error");
        }

    }
});
}

function check_code(){
    loding();
    var code = $("#code").val();
    $.post("index.php/main/load_data/t_debit_note/check_code", {
        code : code
    }, function(res){
        if(res == 1){
            if(confirm("This code ("+code+") already added. \n\n Do you need edit it?")){
                set_edit(code);
            }else{
                $("#code").val('');
                $("#code").attr("readonly", false);
            }
        }
        loding();
    }, "text");
}

function validate(){
    if($(".s_ac").val() == ""){
        alert("Please enter code.");
        $(".s_ac").focus();
        return false;
    }else if($(".s_ac").val()==$("#acc").val()){
        alert("Cr and Dr accounts cannot be the same");
        $("#code_s").focus();
        return false;
    }else if($("#description").val()== ""){
        alert("Please enter description.");
        $("#description").focus();
        return false;
    }else if($("#amount").val()==""){
        alert("Please Enter valid Amount.");
        $("#amount").focus();
        return false;
    }else if($("#emp").val()==""){
        alert("Please Officer.");
        $("#emp").focus();
        return false;
    }else{
        return true;
    }
}

// function validate_settlement(no)
// {
//     $.post("index.php/main/load_data/t_debit_note/validate_settlement", {
//     no : no
//     }, function(res){

//         if(res.sum == 0){
//             $("#btnSave").removeAttr("disabled", "disabled");
//             $("#btnDelete").removeAttr("disabled", "disabled");
//             $("#btnPrint").removeAttr("disabled", "disabled");
//             return 0;
//         }
//         else{
//             $s="Debit Note has settled by "+res.sum.sub_trans_code +" No : "+ res.sum.sub_trans_no
//             alert($s);

//             $("#btnSave").attr("disabled", "disabled");
//             $("#btnDelete").attr("disabled", "disabled");
//             $("#btnPrint").attr("disabled", "disabled");

//             return 1;
//         }
//         loding();
//     }, "json");
// }

function set_delete(no){
    if(confirm("Are you sure delete "+no+"?")){
        loding();
        $.post("index.php/main/delete/t_debit_note", {
            no : no
        }, function(res){
            if(res == 1){
                loding();
                delete_msg();
            }else if(res == 2){
                alert("No permission to delete data.");
            }else{
                loding();
                set_msg(res,"error");
            }
            
        }, "text");
    }
}



function set_edit(code){
    loding();
    $.post("index.php/main/get_data/t_debit_note", {
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
        loding(); 
    }, "json");
}

function load_data(num){
 $.post("index.php/main/get_data/t_debit_note", {
    num:num
},
function(r){
    if(r==2){
        alert("No Records.");
    }
    else{

        $("#qno").val(num);
        $("#no").val(num);
        $("#hid").val(num);
        $("#ref_code").val(r.det[0].ref_trans_code);
        $("#typ").val(r.det[0].dis_type);
        $("#emp").val(r.det[0].employee);
        $("#emp_des").val(r.det[0].emp_name);
        $("#btnApprove").attr("disabled",false);

        if(parseFloat(r.det[0].is_customer)=='1'){
            $("#cus_or_sup").attr({"value":"Customer","Title":"Customer"}); 
            $("#is_customer").val(1);
            $("#customer").attr('checked',true );
            $("#c_type").val("1");
        }
        else if(parseFloat(r.det[0].is_customer)=='2'){
            $("#cus_or_sup").attr({"value":"Supplier","Title":"Supplier"});
            $("#is_customer").val(0);
            $("#supplier").attr('checked',true );
            $("#c_type").val("2");
        }else{
           $("#cus_or_sup").attr({"value":"Account","Title":"Account"});
           $("#is_customer").val(3);
           $("#internal").attr('checked',true );
           $("#c_type").val("3");
       }
       if (parseInt(r.det[0].is_cancel)!=0) {
        set_msg('Debit Note canceled',"error");
        $("#btnSave").attr("disabled", "disabled");
        $("#btnDelete").attr("disabled", "disabled");
        $("#btnApprove").attr("disabled", "disabled");

        $("#form").css("background-image", "url('img/cancel.png')");
        $("#form").css("background-repeat", "repeat-x");
        $("#form").css("background-position", "center");
    }
            else if(parseInt(r.det[0].ref_trans_code)!=18){  //credit notes by purchase return cannot be edited by credit note screen
                set_msg('You cannot edit this transaction',"error");
                $("#btnSave").attr("disabled", "disabled");
                $("#btnDelete").attr("disabled", "disabled");
                $("#btnPrint").attr("disabled", "disabled");
                $("#btnApprove").attr("disabled", "disabled");
            }else{
                $("#btnSave").removeAttr("disabled", "disabled");
                $("#btnDelete").removeAttr("disabled", "disabled");
                $("#btnPrint").removeAttr("disabled", "disabled");
                //$("#btnApprove").attr("disabled", "disabled");
            }

            if(r.det[0].is_approve!=0){
                $("#btnSave").attr("disabled", "disabled");
                $("#btnDelete").attr("disabled", "disabled");
                $("#btnApprove").attr("disabled", "disabled");
                $("#form").css("background-image", "url('img/approved1.png')");
            }
            
            $(".s_ac").val(r.det[0].code);
            $("#no").attr("readonly","readonly")
            $("#description").val(r.det[0].memo);
            $("#amount").val(r.det[0].amount);

            $("#ref_no").val(r.det[0].ref_no);
            $("#amount").val(r.det[0].amount);
            $("#date").val(r.det[0].ddate);
            $(".s_ac_").val(r.det[0].name);
            $("#acc").val(r.det[0].acc_code);
            $("#acc_id").val(r.det[0].description);


            if(r.settle!="2"){
                $(".settle").css("display","block");
                var s="<thead>";
                s+="<tr>";            
                s+="<th class='tb_head_th'>Cluster</th>";
                s+="<th class='tb_head_th'>Branch</th>";
                s+="<th class='tb_head_th'>Date</th>";
                s+="<th class='tb_head_th'>Account</th>";
                s+="<th class='tb_head_th'>Transaction</th>";
                s+="<th class='tb_head_th'>No</th>";
                s+="<th class='tb_head_th'>Amount</th>";
                s+="</tr>";
                s+="</thead>";   
                for(var d=0; d<r.settle.length; d++){
                    s+="<tr>";
                    s+="<td>"+r.settle[d].cl+"</td>";
                    s+="<td>"+r.settle[d].branch+"</td>";
                    s+="<td>"+r.settle[d].ddate+"</td>";
                    s+="<td>"+r.settle[d].acc_code+"</td>";
                    s+="<td>"+r.settle[d].description+"</td>";
                    s+="<td>"+r.settle[d].sub_trans_no+"</td>";
                    s+="<td>"+r.settle[d].amount+"</td>";
                    s+="</tr>";
                }
                $(".settlement").html(s);
            }
        }


    },
    "json");
}

function load_currentAcc(){
  $.post("index.php/main/load_data/utility/f1_selection_list", {
    data_tbl:"m_account",
    field:"code",
    field2:"description",
    preview2:"Account Name",
    add_query:" AND type IN ('102000001','103000004')",
    search : $("#pop_search2").val() 
}, function(r){
    $("#sr2").html(r);
    settings_accs();      
}, "text");
}

function settings_accs(){
  $("#item_list .cl").click(function(){        
    $("#code_s").val($(this).children().eq(0).html());
    $("#s_ac_").val($(this).children().eq(1).html());
    $("#pop_close2").click();                
})    
}
