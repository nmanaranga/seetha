$(document).ready(function(){
	
	bank_entry();
	cash_tot()

	$("#btnPrint").click(function(){
		if($("#hid").val()!=0){
			$("#print_pdf").submit();
		}else{
			set_msg("please load recor before print");
		}
	});



	$("#cash_m").keyup(function(){
		cash_tot();
	});

	$("#receipt,#receipt_t,#receipt_a,#receipt_o").keyup(function(){
		receipt_tot();
	});

	$("#receipt_cancel,#cash_voucher,#receipt_manual").keyup(function(){
		receipt_cancel_tot();
	});

	$('input[type=text],select').keyup(function(){
		money_tot();
	});

	$("#id").keydown(function(e){
		if(e.keyCode == 13){
			$("#id").attr("readonly","readonly");
			setTimeout(load_data($(this).val()),1100);
		}
	});

	$("#btnDelete").click(function(){
		
		load_delete($("#id").val());

	});



	setTimeout("calculate_totals();cal_cash_and_receipt_tot();receipt_tot();f_part_tot();",1000);

});

function cal_cash_and_receipt_tot(){
	var cash = parseFloat($("#cash_tot").val());
	var receipt = parseFloat($("#receipt_tot").val());
	var bnk_entry = parseFloat($("#opb").val())-parseFloat($("#bank_tot").val());

	$("#receipt_f_tot").val(m_round(cash+receipt));
	f_part_tot();
}

function f_part_tot(){
	var cash = parseFloat($("#cash_tot").val());
	var receipt = parseFloat($("#receipt_tot").val());
	var op_bal = parseFloat($("#opb").val());
	var f_tot = cash+receipt+op_bal;
	var bank_tot = parseFloat($("#bank_tot").val());
	var cancel_tot = parseFloat($("#payment_tot").val());
	$("#close_bal").val(m_round(f_tot-(cancel_tot+bank_tot)));
}

function calculate_totals(){
	var opt = $("#opb").val();
	var cash_f = $("#cash_f").val();
	var opt_bal = $("#opt_bal").val( opt - cash_f );
	$("#opt_bal").focus().blur();
}


function bank_entry(){
	$.post("index.php/main/load_data/m_dialy_summery/bank_entry", {
	}, function(res){
		$("#bank_entries").html(res.table);
		$("#bank_tot").val(res.tot);
		$("#bank_tot").blur();
	},"json");
}

function cash_tot(){
	var system = $("#cash").val();
	var manual = $("#cash_m").val();
	var total = parseFloat(0);

	total = parseFloat(system) + parseFloat(manual);

	$("#cash_tot").val(total);
	$("#cash_tot").blur();
	cal_cash_and_receipt_tot();
	f_part_tot();

}

function receipt_tot(){
	var receipt = $("#receipt").val();
	var receipt_t = $("#receipt_t").val();
	var receipt_a = $("#receipt_a").val();
	var receipt_o = $("#receipt_o").val();
	var total = parseFloat(0);
	//if($("#receipt").val()=="")
	total = parseFloat(receipt)+ parseFloat(receipt_t)+parseFloat(receipt_a)+parseFloat(receipt_o);

	$("#receipt_tot").val(total);
	$("#receipt_tot").val(total);
	$("#receipt_tot").blur();
	cal_cash_and_receipt_tot();

}

function receipt_cancel_tot(){
	var receipt_cancel = $("#receipt_cancel").val();
	var cash_payment_voucher = $("#cash_voucher").val();
	var manual_receipts = $("#receipt_manual").val();
	
	var total = parseFloat(0);

	total = parseFloat(receipt_cancel)+ parseFloat(cash_payment_voucher)+parseFloat(manual_receipts);

	$("#payment_tot").val(total);
	$("#payment_tot").blur();

	f_part_tot();
}



function receipt_voucher_tot(){
	var receipt_c = $("#receipt_cancel").val();
	var cash_v = $("#cash_voucher").val();
	var receipt_manual = $("#receipt_manual").val();
	var total = parseFloat(0);

	total = parseFloat(receipt_c)+ parseFloat(cash_v)+parseFloat(receipt_manual);

	$("#payment_tot").val(total);
	$("#payment_tot").blur();	

}

function money_tot(){
	var s5000_qty = parseInt($("#5000_").val() * 5000);
	var s2000_qty = parseInt($("#2000_").val() * 2000);
	var s1000_qty = parseInt($("#1000_").val() * 1000);
	var s500_qty  = parseInt($("#500_").val() * 500);
	var s100_qty  = parseInt($("#100_").val() * 100);
	var s50_qty   = parseInt($("#50_").val() * 50);
	var s20_qty   = parseInt($("#20_").val() * 20);
	var s10_qty   = parseInt($("#10_").val() * 10);
	var scoints_qty = parseInt($("#coints_").val());
	var tot = parseFloat(0);

	$("#5000_tot").val(s5000_qty);
	$("#2000_tot").val(s2000_qty);
	$("#1000_tot").val(s1000_qty);
	$("#500_tot").val(s500_qty);
	$("#100_tot").val(s100_qty);
	$("#50_tot").val(s50_qty);
	$("#20_tot").val(s20_qty);
	$("#10_tot").val(s10_qty);
	$("#coints_tot").val(scoints_qty);

	tot = parseFloat($("#5000_tot").val())+ parseFloat($("#2000_tot").val())+parseFloat($("#1000_tot").val())+parseFloat($("#500_tot").val())+ parseFloat($("#100_tot").val())+parseFloat($("#50_tot").val())+parseFloat($("#20_tot").val())+ parseFloat($("#10_tot").val())+parseFloat($("#coints_tot").val());


	$("#h_").val(m_round(tot));
	var shot = parseFloat(tot) - parseFloat($("#close_bal").val());
	$("#ttt").val(m_round(shot));

	
}

function validate(){
	return true; 
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
				$("#btnSave").attr("disabled",true);
				loding();
				sucess_msg();
			}else{
				loding();
				set_msg(pid,"error");
			}
			
		}
	});

	
}


function load_data(id){
	loding();
   // empty_grid();
   $.post("index.php/main/load_data/m_dialy_summery/get_display", {
   	id: id
   }, function(r){
   	if(r=="2"){
   		set_msg("No records");
   	}else{
   		$("#hid").val(id);  
   		$("#qno").val(id);   

   		$("#ddate").val(r.sum[0].ddate);
   		$("#cash_acc").val(r.sum[0].cash_acc);   
   		$("#opb").val(r.sum[0].opb);
   		$("#cash_f").val(r.sum[0].cash_float);
   		$("#cash").val(r.sum[0].cash_sales_system);
   		$("#cash_m").val(r.sum[0].cash_sales_manual);
   		$("#receipt_t").val(r.sum[0].rcp_transport);
   		$("#receipt_a").val(r.sum[0].rcp_advance);
   		$("#receipt_o").val(r.sum[0].rcp_others);   
   		$("#receipt_cancel").val(r.sum[0].rcp_cancel);
   		$("#cash_voucher").val(r.sum[0].cash_voucher);
   		$("#receipt_manual").val(r.sum[0].rcp_manual);
   		$("#c_sales").val(r.sum[0].cash_sale_tot);
   		$("#g_sales").val(r.sum[0].gift_sale_tot);

   		if(r.sum[0].is_cancel==1)
   		{
   			$("#form_").css("background-image", "url('img/cancel.png')");
   			$("#btnDelete").attr("disabled", true);
   			$("#btnSave").attr("disabled", true);
   		}

   		var m_total = parseFloat(0);
   		m_total = parseFloat(r.sum[0].dn_5000)+parseFloat(r.sum[0].dn_2000)+parseFloat(r.sum[0].dn_1000)+parseFloat(r.sum[0].dn_500)+parseFloat(r.sum[0].dn_100)+parseFloat(r.sum[0].dn_50)+parseFloat(r.sum[0].dn_20)+parseFloat(r.sum[0].dn_10)+parseFloat(r.sum[0].dn_coints);
   		$("#h_").val(m_total);

   		$("#5000_").val(r.sum[0].dn_5000/5000);
   		$("#2000_").val(r.sum[0].dn_2000/2000);
   		$("#1000_").val(r.sum[0].dn_1000/1000);
   		$("#500_").val(r.sum[0].dn_500/500);   
   		$("#100_").val(r.sum[0].dn_100/100);
   		$("#50_").val(r.sum[0].dn_50/50);
   		$("#20_").val(r.sum[0].dn_20/20);
   		$("#10_").val(r.sum[0].dn_10/10);
   		$("#coints_").val(r.sum[0].dn_coints);

   		$("#5000_tot").val(r.sum[0].dn_5000);
   		$("#2000_tot").val(r.sum[0].dn_2000);
   		$("#1000_tot").val(r.sum[0].dn_1000);
   		$("#500_tot").val(r.sum[0].dn_500);   
   		$("#100_tot").val(r.sum[0].dn_100);
   		$("#50_tot").val(r.sum[0].dn_50);
   		$("#20_tot").val(r.sum[0].dn_20);
   		$("#10_tot").val(r.sum[0].dn_10);
   		$("#coints_tot").val(r.sum[0].dn_coints);

   		$("#i_cash_sale").val(r.sum[0].inv_cash);
   		$("#i_credit_sale").val(r.sum[0].inv_credit);
   		$("#finance").val(r.sum[0].inv_finance);
   		$("#ccard_sales").val(r.sum[0].inv_card);
   		$("#s_return").val(r.sum[0].inv_return);
   		$("#ttt").val(m_round(r.sum[0].shortage));

   		var i_tot = (parseFloat(r.sum[0].inv_cash)+parseFloat(r.sum[0].inv_credit)+parseFloat(r.sum[0].inv_card))-parseFloat(r.sum[0].inv_return);

   		$("#n_sale").val(m_round(i_tot));

   		$("#j_cash").val(r.sum[0].rcp_cash);
   		$("#j_credit").val(r.sum[0].rcp_card);
   		$("#j_cheque").val(r.sum[0].rcp_cheque);

   		var j_tot = parseFloat(r.sum[0].rcp_cash)+parseFloat(r.sum[0].rcp_card)+parseFloat(r.sum[0].rcp_cheque);

   		$("#j_tot").val(m_round(j_tot));
   		
   		$("#receipt").val(m_round(r.sum[0].rcp));
   		$("#note").val(r.sum[0].note);

   		
   		var total = parseFloat(0);
   		var a ="";
   		for(var x=0; x<r.det.length;x++){
   			a+="<tr>";
   			a+="<td><input type='text' class='input_active g_input_num' readonly='readonly' name='nno_"+x+"' id='nno_"+x+"' value='"+r.det[x].entry_no+"' title='"+r.det[x].entry_no+"'> </td>";
   			a+="<td><input type='text' class='input_active g_input_num' readonly='readonly' name='acc_"+x+"' id='acc_"+x+"' value='"+r.det[x].bank_acc+"' title='"+r.det[x].bank_acc+"'> </td>";
   			a+="<td><input type='text' class='input_active g_input_num' readonly='readonly' name='amount_"+x+"' id='amount_"+x+"' value='"+r.det[x].amount+"' title='"+r.det[x].amount+"'> </td>";
   			a+="</tr>";
   			total = total + parseFloat(r.det[x].amount);
   		}
   		$("#bank_entries").html(a);
   		$("#bank_tot").val(total);
   		calculate_totals();
   		cash_tot();
   		receipt_tot();
   		receipt_cancel_tot();
   		receipt_voucher_tot();
   		money_tot();
   		
   		
   		
   	}
   	loding();
   }, "json");
}

function load_delete(no){
	if (confirm("Are you sure delete " + no + "?")) {
		loding();

		$.post("index.php/main/delete/m_dialy_summery/", {
			id: no,
		},
		function (r) {
			if(r==1)
			{
				loding();
				delete_msg();
			}
			else if(r==2)
			{
				set_msg("Not enough quatity to cancel");
			}
			else if(r==3)
			{
				set_msg("Serial not available");
			}
			else
			{
				loding();
				set_msg(r);
			}
			
		}, "text")
	}
}