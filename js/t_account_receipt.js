var is_edit=0;
var branch_list = new Array();var auto_fill;var sroot = sarea = 0;  
$(document).ready(function(){
    $("#tgrid").tableScroll({height:250});
    $('#tabs').tabs();
    $(".tamo").removeAttr("readonly","readonly");
    $("#amt").removeAttr("readonly");
    $("#id").keydown(function(e){
        if(e.keyCode == 13){
            $(this).blur();
            load_data($(this).val());
        }
    });
    $("#table_data").on("keydown",".tamo",function(e){
         if(e.keyCode==13)
         {    
             set_cid($(this).attr("id"));
             if(parseFloat($("#2_"+scid).val())<parseFloat($("#3_"+scid).val()))
             {
                 alert("Paid amount exceed the balance amount");
                 $("#3_"+scid).val($("#2_"+scid).val());
             }
             set_total();                 
         }
    });
 
    
   $("#table_data").on("blur",".tamo",function(e){
             set_cid($(this).attr("id"));
             if(parseFloat($("#2_"+scid).val())<parseFloat($("#3_"+scid).val()))
             {
                 alert("Paid amount exceed the balance amount");
                 $("#3_"+scid).val($("#2_"+scid).val());
             }
             set_total();
    });
    
    
    function clear_value()
    {
         $("#table_data").on("each",".tamo",function(e){
            $(this).val("");
            $(this).parent().parent().css("background-color", "transparent");
             empty_grid2();
            $("#settle,#cash,#cheque,#total,#advance").val('');
    });
    }
 
    $("#btnDelete").click(function(){
        
        check_cheque_status_del($("#hid").val());
		
    });
    
	
    function check_cheque_status_del(id)
    {
        $.post("index.php/main/load_data/loder/check_cheque_status",{
           
            id :id,
            trans :"RECEIPT"
            
        },function(data)
        {
            if(data==='1')
            {
                alert("you cannot delete this record.This is deposit cheque");
            }
            else if(data==='2')
            {
                
                alert("you cannot delete this record.This is returned cheque");
            }    
            else
            {
               
                 check_delete_permission();   
            }    
            
        },"text");  
    }
    
	function check_delete_permission(){

        set_delete();
    }
    
    $("#btnDelete, #btnSave, #btnReset").removeAttr("disabled");
    
    $("#btnPrint").click(function(){
        var id = $("#hid").val()
        if(id != 0){
            window.open('index.php/prints/trance_forms/t_customer_receipt/?id='+id);
        }else{
            alert("Please load record. Or save first.");
        }
    });
 
    
    $("#bank").change(function(){
        set_bank_branch();
    });
    
    enter_setup_trance();get_bank_branch();
    
  $("#table_data").on("keyup",".tamo",function(e){

        set_total();
    });
  
    $("#total").keyup(function(){
        if(auto_fill == true){
            auto_filling();
            var chq = parseFloat($("#cheque").val());if(isNaN(chq)){chq = 0;}
            var cash = parseFloat($("#cash").val());if(isNaN(cash)){cash = 0;}
            $("#settle").val(parseFloat($(this).val()) - (chq + cash));
        }
    });
    
    $("#saccount").autocomplete('index.php/main/load_data/m_account/auto_com', {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatAccount,
        formatResult: formatAccountResult
    });
    
      $("#saccount").blur(function(){
	set_account_values($(this));
    });
    
    $("#saccount").keydown(function(e){
	if(e.keyCode == 13){
	    set_account_values($(this));
	}
    });
    
    $("#pay_credit_label").html("Settle Balance");
    $("#settle").attr("title", "Amount");
    input_reset();
    
    $(".ttt").keyup(function(){
	set_cheque_total();
        set_bank_total();
        set_cash_total();
	check_ch_detail($(this).attr("id"));
    });
        
    $(".tttb").keyup(function(){
	set_cheque_total();
	set_bank_total();
        set_cash_total();
	
    });
        
    $("#amt").keyup(function(){
	set_cash_total();
    });
    
    $("#btnSavePay").click(function(){
        
         check_cheque_status($("#hid").val());
        
    });
    
       function check_cheque_status(id)
    {
        $.post("index.php/main/load_data/loder/check_cheque_status",{
           
            id :id,
            trans :"25"
            
        },function(data)
        {
            if(data=='1')
            {
                alert("you cannot edit this record.This is deposit cheque");
            }
            else if(data=='2')
            {
                
                alert("you cannot edit this record.This is returned cheque");
            }    
            else
            {
		check_permission();
				 
            }    
            
        },"text");
  
    }	
	
    function check_permission(){
       save();
    }
    
    $(".account_no, .cheque_no").blur(function(){
	check_cheque_exist($(this).attr("id"));
    });
    
    
    $("#amount_lable").html(" ");
    $("#cash").css("display","none");
    
});

function check_ch_detail(id){
	set_cid(id)
	
	var y=$("#q1_"+scid).val();
	var z=$("#qn_"+scid).val();
	var m=$("#q2_"+scid).val();
	var n=$("#q4_"+scid).val();
	
	if(y==''|| z==''|| m==''|| n==''){
		alert('Please fill each column in cheque details');
		$("#q3_"+scid).val(" ");
		$("#cheque").val(" ");
		$("#settle").val($("#total").val());
		}
	
	}

function set_cheque_total(){
    var t = ttt = 0;
    $(".ttt").each(function(){
							
	t = parseFloat($(this).val());
	if(! isNaN(t)){
	    ttt += t;
	}
    });

    var ct = parseFloat($("#total").val());
    if(isNaN(ct)){
	ct = 0;
    }

   $("#cheque").val(ttt);
    $("#settle").val(ct - ttt);

    input_active();
}

function set_bank_total(){
    var tb = tttb = 0;
    $(".tttb").each(function(){
							
	tb = parseFloat($(this).val());
	if(! isNaN(tb)){
	    tttb += tb;
	}
    });
    
	

    var ct = parseFloat($("#total").val());
    if(isNaN(ct)){
	ct = 0;
    }

    $("#bank").val(tttb);
    $("#settle").val(ct - tttb);

    input_active();
}


function set_cash_total(){

 var a= parseFloat($("#advance").val());
 var c= parseFloat($("#cheque").val());
 var b= parseFloat($("#bank").val());
 
 var d= parseFloat($("#amt").val());
 var e= parseFloat($("#total").val());
 
 var t=c+d+b;
 var s=e-t;
 $("#advance").val("");
  $("#settle").val(s);  
  
 if(parseFloat($("#total").val())<parseFloat(t))
    {
        
        var adv=parseFloat(t)-parseFloat($("#total").val());
        $("#advance").val(adv);
        $("#settle").val('0');
    }
  
    input_active();
}


function check_cheque_exist(id){
    set_cid(id);
    var c_no = $("#q1_"+scid).val();
    var a_no = $("#q2_"+scid).val();
    
    if(c_no != "" && a_no != ""){
	$.post("index.php/main/load_data/t_receipt/check_cheque_no", {
	    c_no : c_no,
	    a_no : a_no
	}, function(r){
	    if(r == "1"){
		alert("The Cheque No already exist.");
		set_cid(id);
		$("#q1_"+scid).val("");$("#q2_"+scid).val("");
	    }
	}, "text");
    }
}

function set_account_values(f){
    var v = f.val();
    v = v.split("~");
    if(v.length == 2){
	f.val(v[0]);
	$("#account").val(v[0]);
	$("#account_des").val(v[1]);
	$("#account_des").attr("class", "input_txt_f");
        load_receivable_invoice_data(v[0]);
    }
}
function formatAccount(row){
    return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
}

function formatAccountResult(row){
    return row[0]+"~"+row[1];
}

function get_total(){
    var t = tt = 0; 
    $(".tamo").each(function(){
        tt = parseFloat($(this).val());
        if(isNaN(tt)){tt = 0;}
        t += tt;
    });
    
    return t;
}

function set_total(){
    var t = get_total();
    $("#total").val(m_round(t));
    var chq = parseFloat($("#cheque").val());if(isNaN(chq)){chq = 0;}
    var cash = parseFloat($("#cash").val());if(isNaN(cash)){cash = 0;}
    t = t - (chq + cash);
    $("#settle").val(m_round(t));
    
    input_active();
}

function empty_grid(){
    for(var i=0; i<25; i++){
        $("#0_"+i).val("");
        $("#1_"+i).val("");
	$("#2_"+i).val("");
        $("#3_"+i).val("");
        $("#4_"+i).val("");
        $("#5_"+i).val("");
    }

}   

function load_receivable_invoice_data(cus_acc){
    empty_grid();
        $.post("index.php/main/load_data/t_receipt/load_receivable_invoice_data", {
            cus_acc : cus_acc
        }, function(r){
           if(r.rec!=0)
              { 
                for(var i=0; i<r.rec.length; i++){
                    
             var payable_data='';

             payable_data=payable_data+"<tr>\n\
             <td><input type='text' class='g_input_txt' style='width:100px;width:100%;background-color:#F7F8E0;' id='4_"+i+"' readonly='readonly' name='4_"+i+"' title="+r.rec[i]['dr_transe_no']+" value="+r.rec[i]['dr_transe_no']+"></td>\n\
             <td><input type='text' class='g_input_txt' style='width:100px;width:100%;background-color:#F7F8E0;' id='0_"+i+"' readonly='readonly' name='0_"+i+"' title="+r.rec[i]['date']+" value="+r.rec[i]['date']+"></td>\n\
             <td><input type='text' class='g_input_amo' style='width:100px;width:100%;background-color:#F7F8E0;' id='1_"+i+"' readonly='readonly' name='1_"+i+"' title="+r.rec[i]['cr_amount']+" value="+r.rec[i]['cr_amount']+"></td>\n\
             <td><input type='text' class='g_input_amo' style='width:100px;width:100%;background-color:#F7F8E0;' id='2_"+i+"' readonly='readonly' name='2_"+i+"' title="+r.rec[i]['balance']+" value="+r.rec[i]['balance']+"></td>\n\
             <td><input type='text' class='g_input_amo tamo' style='width:100px;width:100%;background-color:#F8E0EC;' id='3_"+i+"'  name='3_"+i+"' ><input type='hidden' class='g_input_amo' style='width:110px;width:100%' id='5_"+i+"' readonly='readonly' name='5_"+i+"' title="+r.rec[i]['account_code']+" value="+r.rec[i]['account_code']+"></td>\n\
             </tr>"; 
   
             $("#payable_grid").val(r.rec.length);  
             $("#table_data").html(payable_data);    
                }
              }
              else
              {
                  alert("No Recivable details found for this account");
                  $(".tablescroll_wrapper").css("background-image", "url('img/cancel.png')");
                  $(".tablescroll_wrapper").css("background-repeat", "repeat-x");
                  $(".tablescroll_wrapper").css("background-position", "center");
                  $(".tamo").attr("readonly","readonly");
              }    
              
              input_active();
        }, "json");
    }

$(document).keydown(function(e){
    if(e.keyCode == 112){
        $("#0_0").focus();
    }
});

function get_bank_branch(){
    $.post("index.php/main/load_data/m_bank_branch/bank_branch_list", {
        
    }, function(r){
        branch_list = r;
    }, "json");
}

function set_bank_branch(){
    var m = $("#bank option:selected").val();
    var txt  = "<option value='0'>---</option>";
    if(branch_list[m] != undefined){
        for(var i=0; i<branch_list[m].length; i++){
            txt += "<option value='"+branch_list[m][i][0]+"'>"+branch_list[m][i][1]+"</option>";
        }
    }
    
    $("#bank_branch").html(txt);
}

function set_delete(){
    var id = $("#hid").val();
    if(id != 0){
        if(confirm("Are you sure ? ")){
            $.post("index.php/main/delete/t_customer_receipt", {
                id : id
            }, function(r){
                if(r != 1){
                    alert("Record cancel fail.");
                }else{
                    $("#btnReset").click();
                }
            }, "text");
        }
    }else{
        alert("Please load record");
    }
}


function load_data(id){
    empty_grid();
    $.post("index.php/main/get_data/t_customer_receipt/", {
        id : id
    }, function(r){
        if(r.sum.id != undefined){
            $("#date").val(r.sum.date);
            $("#customer").val(r.sum.customer);
            $("#scustomers").val(r.sum.customer);
            $("#cus_des").val(r.sum.outlet_name+" ("+r.sum.name+")");
            $("#memo").val(r.sum.memo);
            $("#ref_no").val(r.sum.ref_no);
            $("#id").attr("readonly", "readonly");
            $("#hid").val(r.sum.id);
            $("#cheque").val(r.sum.cheque_amount);
            $("#amt").val(r.sum.cash_amount);
            $("#advance").val(r.sum.advance_settlement);
            $("#settle").val(0);
            $("#total").val(m_round(parseFloat(r.sum.cash_amount) + parseFloat(r.sum.cheque_amount)+parseFloat(r.sum.advance_settlement)));
            $("#balance").val(r.sum.balance);
            
            for(var i=0; i<r.det.length; i++){
                $("#0_"+i).val(r.det[i].receipt_no);
                $("#1_"+i).val(r.det[i].total);
                $("#2_"+i).val(r.det[i].balance);
                $("#3_"+i).val(r.det[i].paid);
                $("#4_"+i).val(r.det[i].trance_code);
                $("#5_"+i).val(r.det[i].description);
            }
            
            for(var i=0; i<r.cheque.length; i++){
                $("#qbh_"+i).val(r.cheque[i].bank);
                $("#qbbh_"+i).val(r.cheque[i].bank_branch);
                $("#q0_"+i).val(r.cheque[i].bank_name);
                $("#qn_"+i).val(r.cheque[i].description);
                $("#q4_"+i).val(r.cheque[i].realize_date);
                $("#q1_"+i).val(r.cheque[i].cheque_no);
                $("#q2_"+i).val(r.cheque[i].account_no);
                $("#q3_"+i).val(r.cheque[i].cheque_amount);
            }
            
           for(var i=0; i<r.adv2.length; i++){
		$("#p1_"+i).val(r.adv2[i].receipt_no);
		$("#p2_"+i).val(r.adv2[i].total);
		$("#p3_"+i).val(r.adv2[i].balance);
		$("#p4_"+i).val(r.adv2[i].settle_amount);
	    }
        
            if(r.sum.is_cancel > 0){
                alert("This record canceled.");
                $("#btnDelete").attr("disabled", "disabled");
                $("#btnSave, #btnSavePay").attr("disabled", "disabled");
                $("#mframe").css("background-image", "url('img/cancel.png')");
                $("#mframe").css("background-repeat", "repeat-x");
                $("#mframe").css("background-position", "center");
            }else if(r.sum.posting > 0){
                $("#btnDelete").attr("disabled", "disabled");
                $("#btnSave").attr("disabled", "disabled");
                $("#mframe").css("background-image", "url('img/posted.png')");
                $("#mframe").css("background-repeat", "repeat-x");
                $("#mframe").css("background-position", "center");
            }else if(r.sum.sale_recipt){
		$("#btnDelete").attr("disabled", "disabled");
                $("#btnSave").attr("disabled", "disabled");
                $("#mframe").css("background-image", "url('img/sale_rep.png')");
                $("#mframe").css("background-repeat", "repeat-x");
                $("#mframe").css("background-position", "center");
		alert("Auto made by sale");
	    }
            
            input_active();
			var is_edit=1;
        }else{
            alert("No records");
        }
    }, "json");
}

function select_search(){
    $("#pop_search").focus();
    $("#pop_search").val("");
}

function validate(){
    var v = false;
    $(".tamo").each(function(){
        if($(this).val() != "" && $(this).val() != 0){
            v = true;
        }
    });
    
    var c = parseFloat($("#settle").val());
    if(v == false){
        alert("Please use minimum one item.");
    }else if($("#customer").val() == 0){
        alert("Please select customer");
        v = false;
    }
      else if($("#cheque").val()>0)
    {
        for(var i=0;i<=10;i++)
            {
                if($("#q0_"+i).val()!='' || $("#qn_"+i).val()!='' || $("#q1_"+i).val()!=''|| $("#q2_"+i).val()!=''|| $("#q3_"+i).val()!=''|| $("#q4_"+i).val()!='')
                { 
                     if($("#q0_"+i).val()=='')
                     {
                         alert('Please Select a Bank');
                         $("#q0_"+i).focus();
                          v = false;
                     }
                     else if($("#qn_"+i).val()=='')
                     {
                         alert("Please select a Bank branch");
                         $("#qn_"+i).focus();
                          v = false;
                     }     
                     else if($("#q1_"+i).val()=='')
                     {
                         alert("Please enter valid cheque number");
                         $("#q1_"+i).focus();
                          v = false;
                     }     
                     else if($("#q2_"+i).val()=='')
                     {
                         alert("Please enter Account number");
                         $("#q2_"+i).focus();
                          v = false;
                     }     
                     else if($("#q4_"+i).val()=='')
                     {
                         alert("Please enter Realized Date");
                         $("#q4_"+i).focus();
                          v = false;
                     }  
                     else if($("#q3_"+i).val()=='')
                     {
                         alert("Please enter Amount");
                         $("#q3_"+i).focus();
                          v = false;
                     }  
                     
                }
                
            }
    }    
    var c = parseFloat($("#settle").val());
    if(c != 0){
        alert("Please make settle balance is 0.");
        $("#btnPayments").click();
        v = false;
    }
    
    return v;
}

function save(){
    $("#form_").submit();
	var is_edit=0;
}