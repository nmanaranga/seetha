$(document).ready(function(){

$("#payment_option").attr("checked", "checked");
$("#btnSave").attr("disabled","disabled");
$("#btnSavee").css("display","none");


    $("#btnPrint").click(function(){
  	    if($("#hid").val()=="0"){
  		    alert("Please load data before print");
    	    return false;
  	    }
  	    else{
            $("#print_pdf").submit();
	    }
    });

    $("#cus_s").click(function(){
      window.open("?action=m_customer","_blank"); 
    });

    $("#off_b").click(function(){
      window.open("?action=m_employee_details","_blank"); 
    });


    $("#id").keydown(function(e){
        if(e.keyCode == 13){
            $(this).blur();
            load_data($(this).val());
            load_payment_option_data($(this).val(),"24");
            $("#qno").val($('#id').val());

        }
    });

    $("#showPayments").click(function(){
      payment_opt('t_advance_sum',$("#net").val());
      $("#btnSave").removeAttr("disabled","disabled");
      if($("#hid").val()=="0" || $("#hid").val()==""){
        $("#cash").val($("#net").val());
      }
      
      //$("#save_status").val("0");
    });


    $("#btnReset").click(function(){
   	location.href="?action=t_advance_payment";
	});
     
    $("#grid").tableScroll({height:355});
    $("#tgrid").tableScroll({height:355});
    $("#qno").val($('#id').val());
    $("#cus_id").val($('#customer').val());


    $("#customer").autocomplete('index.php/main/load_data/m_customer/auto_com', {
    width: 350,
    multiple: false,
    matchContains: true,
    formatItem: formatItems,
    formatResult: formatItemsResult
    });

    $("#customer").keydown(function(e){
        if(e.keyCode == 13){
            set_cus_values($(this));
        }
    });

    $("#customer").blur(function(){
        set_cus_values($(this));
    });


    $("#btnDelete").click(function(){

       if($("#hid").val()!=0) {
            set_delete($("#hid").val());
       }else{
            set_msg("Please load the record","error");
       }
    });

    $("#customer").keydown(function(e){ 
        if(e.keyCode==112){
          $("#pop_search").val($("#customer").val());
          load_customer();
          $("#serch_pop").center();
          $("#blocker").css("display", "block");
          setTimeout("$('#pop_search').focus()", 100);   
        }
        $("#pop_search").keyup(function(e){
          if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
            load_customer();
          }
        });
        if(e.keyCode==46){
           $("#customer").val("");
           $("#customer_id").val("");
        }  
    });

    $("#officer").keydown(function(e){ 
        if(e.keyCode==112){
          $("#pop_search14").val($("#officer").val());
          load_employee();
          $("#serch_pop14").center();
          $("#blocker").css("display", "block");
          setTimeout("$('#pop_search14').focus()", 100);   
        }
        $("#pop_search14").keyup(function(e){
          if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
            load_employee();
          }
        });
        if(e.keyCode==46){
           $("#officer").val("");
           $("#officer_des").val("");
        }  
    });


});

function load_employee(){
  $.post("index.php/main/load_data/utility/f1_selection_list", {
      data_tbl:"m_employee",
      field:"code",
      field2:"name",
      preview2:"Employee Name",
      search : $("#pop_search14").val() 
  }, function(r){
      $("#sr14").html(r);
      settings_emp();      
 }, "text");
}

function settings_emp(){
    $("#item_list .cl").click(function(){        
        $("#officer").val($(this).children().eq(0).html());
        $("#officer_des").val($(this).children().eq(1).html());
        $("#pop_close14").click();                
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
        $("#customer").val($(this).children().eq(0).html());
        $("#customer_id").val($(this).children().eq(1).html());
        $("#pop_close").click();                
    })    
}


function set_delete(no){
    if(confirm("Are you sure delete "+no+"?")){
        loding();
        $.post("index.php/main/delete/t_advance_payment", {
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


function check_code(){

     loding();
     var code = $("#code").val();
     $.post("index.php/main/load_data/t_advance_payment/check_code", {
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

function set_cus_values(f){
    var v = f.val();
    v = v.split("-");
        
    if(v.length == 2){
        f.val(v[0]);
        $("#customer_id").val(v[1]);
        var cus=$("#customer").val();
        $.post("index.php/main/load_data/m_customer/load",
        {
        code:cus,
        },function(rs){
             $("#address").val(rs.data.address1+", "+rs.data.address2+", "+rs.data.address3); 
             input_active();
             },"json");
    }
}

function formatItems(row){
    return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
}

function formatItemsResult(row){
    return row[0]+"-"+row[1];
}


function save(){
    $("#dt").val($("#edate").val());
    $("#qno").val($('#id').val());
    $("#reciviedAmount").val($("#net").val());
    $("#cus_id").val($("#customer").val());
    $("#salesp_id").val($("#officer").val());
    $("#description").val();
    $("#org_print").val(2);


    $('#form_').attr('action',$('#form_id').val()+"t_advance_payment");
    var frm = $('#form_');
    loding();
    $.ajax({
        type: frm.attr('method'),
        url: frm.attr('action'),
        data: frm.serialize(),
        success: function (pid){
         var sid=pid.split('@');

            if(sid[0]==1){
                loding();
                $("#btnSave").attr("disabled",true);
                $("#showPayments").attr("disabled",true);
                
                //$("#btnSavee").css("display","inline");

                if(confirm("Save Completed, Do You Want A print?")){
                if($("#is_prnt").val()==1){
                  $("#qno").val(sid[1]);
                    $("#print_pdf").submit();
                }
                  setTimeout(function(){
                    location.href="";
                  }, 100);
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

  

function load_data(id){

loding();
$.post("index.php/main/get_data/t_advance_payment/", {
    id: id
}, function(r){
        if(r=="2"){
           alert("No records");
        }else{
        $("#hid").val(id); 
        $("#date").val(r.det[0].ddate); 
        $("#ref_no").val(r.det[0].ref_no);
        $("#cn_no").val(r.det[0].cn_no);  
        $("#customer").val(r.det[0].acc_code);
        $("#description").val(r.det[0].description);
        $("#edate").val(r.det[0].expire_date);
        $("#net").val(r.det[0].total_amount);
        $("#id").attr("readonly","readonly")  
        $("#dt").val(r.det[0].ddate);
        $("#reciviedAmount").val(r.det[0].total_amount);
        $("#cus_id").val(r.det[0].acc_code);
        $("#customer_id").val(r.det[0].name);

        $("#officer").val(r.det[0].employee);
        $("#officer_des").val(r.det[0].emp_name);
        $("#salesp_id").val(r.det[0].employee);

        $("#cash").val(r.det[0].cash_amount);
        $("#cheque_recieve").val(r.det[0].cheque_amount);
        $("#credit_card").val(r.det[0].card_amount);

        
        // $("#btnSave").css("display" , "none");


        if(r.det[0].is_cancel=="1") {
        	set_msg("Advance payment canceled","error");
        	$("#mframe").css("background-image", "url('img/cancel.png')");
        	$("#btnDelete").attr("disabled", true);
        	$("#btnPrint").attr("disabled", true);
        	$("#btnSave").attr("disabled", true);
        }         
        input_active();
       
        }
    loding();
    }, "json");
}


function validate(){

    var v = true;
   
    if($("#id").val() == ""){
        alert("Please enter No.");
        $("#id").focus();
        return false;
    }else if($("#date").val() == ""){
        alert("Please select date");
        $("#date").focus();
        return false;
    }else if($("#customer_id").val()=="" || $("#customer_id").val()==$("#customer_id").attr("title")){
        alert("Please select a customer.");
        $("#customer_id").focus();
        return false;
    }else if(v == false){
        alert("Please use minimum one item.");
    }else{
        return true;
    }
}

