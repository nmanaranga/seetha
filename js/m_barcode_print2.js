$(document).ready(function(){
	empty_det();
	empty_sum();

	$("#pop_search").gselect();
	$("#pop_search2").gselect();
	$("#pop_search3").gselect();
	$("#pop_search4").gselect();	
	$("#pop_search6").gselect();
	$(".qunsb").css("display","none");
	$("#ptype").val('l');

	$(".print_type").click(function(){
		if($(this).val()=='l'){
			$("#ptype").val('l');
		}else{
			$("#ptype").val('s');
		}
	});



	$("#btnPrint").click(function(){
		var cnt=0;
		$(".chkSerial:checked").each(function(index, el) {
			set_cid($(this).attr('id'));
			if($("#all_serial_"+scid).val()=='0' || $("#all_serial_"+scid).val()==''){
				set_msg("Please Check serials in "+$("#itmNm_"+scid).val()+"("+$("#0_"+scid).val()+")");
				cnt++;
				return true;
			}

		});

		if (cnt==0) {
			$("#print_pdf").submit();
		}
	});

	$("#btnResett").click(function(){
		if(confirm("Are You Sure, You Want To Reset")){
			location.reload();
		};
	});

	$("#btnDelete").click(function(){
		if(confirm("Are You Sure, You Want To Delete")){
			deleteFrm();
		};
	});

	$("#btnClrHis").click(function(){
		if(confirm("Are You Sure, You Want To Clear History")){
			deleteFrm('ClHis');
		};
	});

	var wh=$("#mframe").width()-20;
	$("#tgrid").tableScroll({height:300, width:wh});

	$("#hno").keypress(function(e){
		var id=$(this).val();
		if(e.keyCode == 13){
			load_data(id,'hno')
		}		

		if(e.keyCode == 46){
			$("#code").val("");
			$("#itmNm").val("");
			$("#Mdl").val("");
			$("#mxSlPris").val("");
		}
	});

	$("#lGrnNo").keypress(function(e){
		var id=$(this).val();
		if(e.keyCode == 13){
			load_data(id,'grn')
		}		

		if(e.keyCode == 46){
			$("#lGrnNo").val("");
			
		}
	});

	// $("#code").keypress(function(e){
	// 	if(e.keyCode == 112){
	// 		$("#pop_search4").val($("#main_category_id").val());
	// 		load_data_itm();
	// 		$("#serch_pop4").center();
	// 		$("#blocker").css("display", "block");
	// 		setTimeout("$('#pop_search4').focus()", 100);
	// 	}
	// 	$("#pop_search4").keyup(function(e){
	// 		if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
	// 			load_data_itm();
	// 		}
	// 	}); 
	// 	if(e.keyCode == 46){
	// 		$("#code").val("");
	// 		$("#itmNm").val("");
	// 		$("#Mdl").val("");
	// 		$("#mxSlPris").val("");
	// 	}
	// });

	$("#supCd").change(function(){
		empty_det();
	});

	$(".qty").bind('blur change', function(event) {
		set_cid($(this).attr('id'));
		conut_itms_qty();
		//check_max_qty(scid);
	});

	$("#supCd").keypress(function(e){ 
		if(e.keyCode==112){
			$("#pop_search2").val($("#supCd").val());
			load_sup();
			$("#serch_pop2").center();
			$("#blocker2").css("display", "block");
			setTimeout("$('#pop_search2').focus()", 100);   
		}
		$("#pop_search2").keyup(function(e){
			if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
				load_sup();
			}
		});
		if(e.keyCode==46){
			$("#supCd").val("");
			$("#sup_des").val("");
			empty_det();
		}  
	}); 

	$(".com").keypress(function(e) {
		set_cid($(this).attr('id'));
		if(e.keyCode==112){
			$("#pop_search12").val();
			load_company_name();
			$("#serch_pop12").center();
			$("#blocker").css("display", "block");
			setTimeout("$('#pop_search12').focus()", 100);
		}
	});

	$("#lGrnNo").keypress(function(e){ 
		if(e.keyCode==112){
			$("#pop_search").val($("#lGrnNo").val());
			load_grnNo();
			$("#serch_pop").center();
			$("#blocker").css("display", "block");
			setTimeout("$('#pop_search').focus()", 100);   
		}
		$("#pop_search").keyup(function(e){
			if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
				load_grnNo();
			}
		});
		if(e.keyCode==46){
			$("#lGrnNo").val("");
			$("#supCd").val("");
			$("#sup_des").val("");
			empty_det();
			// $("#sup_des").val("");
		}  
	});

	$(".fo").keypress(function(e){ 
		set_cid($(this).attr('id'));
		if(e.keyCode==112){
			$("#pop_search6").val($(this).val());
			load_GItems(scid);
			$("#serch_pop6").center();
			$("#blocker").css("display", "block");
			setTimeout("$('#pop_search6').focus()", 100);   
		}
		$("#pop_search6").keyup(function(e){
			if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
				load_GItems(scid);
			}
		});
		if(e.keyCode==46){
			empty_grid_Sel_row(scid);
			// $("#lGrnNo").val("");
			// $("#sup_des").val("");
		}  
	});

	$(".btcno").keypress(function(e){ 
		set_cid($(this).attr('id'));
		if(e.keyCode==112){
			$("#pop_search14").val($(this).val());
			selBatchNo(scid);
			$("#serch_pop14").center();
			$("#blocker").css("display", "block");
			setTimeout("$('#pop_search14').focus()", 100);   
		}
		$("#pop_search14").keyup(function(e){
			if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
				selBatchNo(scid);
			}
		});
		if(e.keyCode==46){
			empty_aft_bdg(scid);

		}  
	});

	$(".qty").bind('keyup keydown keypress blur', function(event) {
		set_cid($(this).attr('id'));

		if ($("#chkSerial_"+scid).is(':checked') && $("#btcno_"+scid).val()!="") {
			if(parseInt($("#mxserials_"+scid).val())<parseInt($(this).val())){
				$("#qty_"+scid).val($("#mxserials_"+scid).val());
				set_msg("Max Qty "+$("#mxserials_"+scid).val());
			}
		}	
	});

	$(".chkSerial").click(function(event) {
		set_cid($(this).attr('id'));
		$("#btn_"+scid).prop('disabled', 'disabled');
		if ($(this).is(':checked') && $("#btcno_"+scid).val()!="") {
			$("#btn_"+scid).removeAttr('disabled');
		}
		if($("#btcno_"+scid).val()==""){
			$("#chkSerial_"+scid).removeAttr('checked');
			set_msg("Please select Batch");

		}
		$(".qty").keyup();
	});


	$("#btnExit1").click(function(){
		document.getElementById('light').style.display='none';
		document.getElementById('fade').style.display='none';  
		$("#3_"+scid).focus();
	});

	$(".qunsb").click(function(){
		set_cid($(this).attr("id"));
		check_is_batch_item(scid);
	});

});

function load_sup(){
	$.post("index.php/main/load_data/utility/f1_selection_list", {
		data_tbl:"m_supplier",
		field:"code",
		field2:"name",
		preview2:"Supplier Name",
		search : $("#pop_search2").val() 
	}, function(r){
		$("#sr2").html("Loding...");
		$("#sr2").html(r);		
		settings_sup();      
	}, "text");
}

function settings_sup(){
	$("#item_list .cl").click(function(){     
		$("#lGrnNo").val('');
		empty_det();   
		$("#supCd").val($(this).children().eq(0).html());
		$("#sup_des").val($(this).children().eq(1).html());
		$("#pop_close2").click();                
	})    
}

function load_grnNo(){
	$.post("index.php/main/load_data/m_barcode_print/f1_selection_list_load_grn", {
		data_tbl:"t_grn_sum",
		field:"nno",
		field2:"ddate",
		preview1:"GRN No",
		preview2:"Date",
		chkClBc:true,
		add_query:"ORDER BY `nno`  DESC",
		search : $("#pop_search").val() 
	}, function(r){
		$("#sr").html("Loding...");		
		$("#sr").html(r);
		settings_grnNo();      
	}, "text");
}

function settings_grnNo(){
	$("#item_list .cl").click(function(){  
		$("#supCd").val('');
		empty_det();        
		$("#lGrnNo").val($(this).children().eq(0).html());
		// $("#sup_des").val($(this).children().eq(1).html());
		load_data($(this).children().eq(0).html(),'grn')
		$("#pop_close").click();  

	})    
}



function load_GItems(scid){

	$.post("index.php/main/load_data/m_barcode_print/item_selection_list", {	
		search : $("#pop_search6").val(),
		supCd : $("#supCd").val() 		
	}, function(r){
		$("#sr6").html("Loding...");
		$("#sr6").html(r);		
		settings_GItems(scid);      
	}, "text");
}

function settings_GItems(scid){
	$("#item_list .cl").click(function(){  
		if ($(this).children().eq(0).html()!='&nbsp;') {
			check_is_batch_item2($(this).children().eq(0).html(),scid);
			$("#0_"+scid).val($(this).children().eq(0).html());
			$("#itmNm_"+scid).val($(this).children().eq(1).html());
			$("#itmMod_"+scid).val($(this).children().eq(2).html());
			$("#btcno_"+scid).val($(this).children().eq(3).html());
			$("#cost_"+scid).val($(this).children().eq(6).html());
			$("#selPr_"+scid).val($(this).children().eq(4).html());
			$("#qty_"+scid).val($(this).children().eq(5).html());
			$("#curqty_"+scid).val($(this).children().eq(5).html());
			$("#pop_close6").click();
			check_is_batch_item(scid);
			check_is_serial_item2($(this).children().eq(0).html(),scid)
			conut_itms();  
			conut_itms_qty()	
		}             
	})

}

function check_is_batch_item2(x,scid){
	var store=$("#stores").val();
	$.post("index.php/main/load_data/m_barcode_print/is_batch_item",{
		code:x,
	},function(res){
		$("#btnb_"+scid).css("display","none"); 
		if(res==1){
			$("#btnb_"+scid).css("display","block");
		}
	},'text');
}


function empty_det() {
	$("#tgrid").find('input.g_input_txt').val("");
	$(".chkSerial").attr('disabled', 'disabled').removeAttr('checked');	
	conut_itms();  
	conut_itms_qty()	
}

function empty_sum() {
	$("#supCd, #sup_des, #lGrnNo").val("");
	$("#pr_name ,#pr_icode ,#pr_price ,#pr_coscode,#pr_comlogo,#model").attr('checked', 'checked');

}

function empty_grid_Sel_row(scid) {
	$("#0_"+scid).closest('tr').find('input').val("");
	$("#btn_"+scid).css('display', 'none');
	empty_aft_bdg(scid);
	conut_itms();
	conut_itms_qty();
}

function empty_aft_bdg(scid) {
	$("#btcno_"+scid).val("");
	$("#selPr_"+scid).val("");
	$("#qty_"+scid).val("");
	$("#setserial_"+scid).val("");	
	$("#curqty_"+scid).val("");
	$("#all_serial_"+scid).val("");
	$("#numofserial_"+scid).val("");
	$("#mxserials_"+scid).val("");	
	$("#chkSerial_"+scid).removeAttr('checked');	
	$("#chkSerial_"+scid).prop('disabled', 'disabled');	
	$("#btn_"+scid).prop('disabled', 'disabled');	
	// $("#btn_"+scid).css('display', 'none');

}
function conut_itms() {
	var a=0;
	$('.fo').each(function(index, el) {
		if ($(this).val()!="") {
			a++;
		}
	});
	$("#totItms").val(a);

}

function conut_itms_qty() {

	var a=0;
	$('.qty').each(function(index, el) {
		if ($(this).val()!="") {
			a+=parseInt($(this).val());
		}
	});
	$("#totQty").val(a);
}

// function load_data_itm(){
// 	$.post("index.php/main/load_data/m_barcode_print/f1_selection_list", {
// 		search : $("#pop_search4").val() 
// 	}, 
// 	function(r){
// 		$("#sr4").html("Loding...");
// 		$("#sr4").html(r);
// 		settings_itm();            
// 	}, "text");
// }

// function settings_itm(){
// 	$("#item_list .cl").click(
// 		function(){        
// 			$("#code").val($(this).children().eq(0).html());
// 			$("#itmNm").val($(this).children().eq(1).html());
// 			$("#Mdl").val($(this).children().eq(2).html());
// 			$("#mxSlPris").val($(this).children().eq(3).html());
// 			$("#pop_close4").click();                
// 		})    
// }

function load_data(id,typ){
	// alert(id);
	empty_det();
	loding();
	$.post("index.php/main/get_data/m_barcode_print/", {
		id: id,
		typ:typ
	}, function(r){

	//var fre =parseFloat(0);
	//
	
	// console.log(r);

	if(r.er=="2"){
		set_msg("No records");
	}else{

		for(var i=0; i<r.det.length;i++){
			$("#supCd").val(r.det[i].sup_id);			
			$("#sup_des").val(r.det[i].sup_des);			
			$("#0_"+i).val(r.det[i].itmId);
			check_is_serial_item2(r.det[i].itmId,i);
			$("#itmNm_"+i).val(r.det[i].itmNm);
			$("#itmMod_"+i).val(r.det[i].itmMod);
			$("#cost_"+i).val((r.det[i].cost));
			$("#coPrCod_"+i).val(r.det[i].coPrCod);
			$("#btcno_"+i).val(r.det[i].batch_no);			
			$("#selPr_"+i).val(r.det[i].selPr);
			$("#cosPr_"+i).val(r.det[i].cos_code);
			$("#qty_"+i).val(r.det[i].qty);
			$("#curqty_"+i).val(r.det[i].qty);
			$("#comp_"+i).val(r.det[i].company);

			$("#all_serial_"+i).val(r.det[i].serials);

			if (r.det[i].batch_no=="") {$("#chkSerial_"+i).attr('disabled', 'disabled');}
			if (r.det[i].serials !="") {$("#btn_"+i).removeAttr('disabled');}
			
			(r.det[i].pr_name == '0')?$("#pr_name").removeAttr('checked'):$("#pr_name").attr('checked', 'checked');
			(r.det[i].pr_price == '0')?$("#pr_price").removeAttr('checked'):$("#pr_price").attr('checked', 'checked');
			(r.det[i].pr_coscode == '0')?$("#pr_coscode").removeAttr('checked'):$("#pr_coscode").attr('checked', 'checked');
			(r.det[i].pr_btcno == '0')?$("#pr_btcno").removeAttr('checked'):$("#pr_btcno").attr('checked', 'checked');
			(r.det[i].pr_comlogo == '0')?$("#pr_comlogo").removeAttr('checked'):$("#pr_comlogo").attr('checked', 'checked');
			(r.det[i].pr_icode == '0')?$("#pr_icode").removeAttr('checked'):$("#pr_icode").attr('checked', 'checked');
			(r.det[i].model == '0')?$("#model").removeAttr('checked'):$("#model").attr('checked', 'checked');
		}
		conut_itms();
		conut_itms_qty();


	}
	loding();

}, "json");
}


function deleteFrm(dlWht){

	$.post("index.php/main/load_data/m_barcode_print/DelOrClearHis/", {
		dlWht: dlWht,
		id:$("#hno").val()
	}, function(r){
		alert(r);
		location.reload();
	});

}

function check_is_batch_item(scid){
	$.post("index.php/main/load_data/m_barcode_print/is_batch_item",{
		code:$("#0_"+scid).val(),
	},function(res){
		
		if(res==1){
			
			$("#serch_pop3").center();
			$("#blocker3").css("display", "block");
			setTimeout("select_search3()", 100);
			load_items3($("#0_"+scid).val());
		}else if(res=='0'){

			$("#btcno_"+scid).val("0");
			$("#btcno_"+scid).attr("readonly","readonly");
		}else{
			$("#btcno_"+scid).val(res.split("-")[0]);
			$("#btcno_"+scid).attr("readonly","readonly");
		}
	},'text');
}



function load_items3(x){
	$.post("index.php/main/load_data/m_barcode_print/batch_item", {
		search : x,
	}, function(r){
		$("#sr3").html(r);
		settings3();
	}, "text");
}

function select_search3(){
	$("#pop_search3").focus();
}


function settings3(){
	$("#batch_item_list .cl").click(function(){
		if($(this).children().eq(0).html() != "&nbsp;"){
			if(check_item_exist3($(this).children().eq(0).html())){
				$("#btcno_"+scid).val($(this).children().eq(0).html());
				$("#qty_"+scid).val($(this).children().eq(1).html());
				$("#curqty_"+scid).val($(this).children().eq(1).html());
				$("#selPr_"+scid).val($(this).children().eq(2).html());
				$("#btcno_"+scid).attr("readonly","readonly");

				$("#pop_close3").click();
			}else{
				set_msg ("Item "+$(this).children().eq(1).html()+" is already added.");
			}
		}else{
			$("#btcno_"+scid).val("");
			$("#selPr_"+scid).val("");
			
			$("#pop_close3").click();
		}
	});
}

function check_item_exist3(id){
	var v = true;
	return v;
}

function check_max_qty(scid){
	var cur_qty=$("#curqty_"+scid).val();
	var qty=$("#qty_"+scid).val();

	if(qty>cur_qty){
		set_msg("Qty Should be Less Than or Equal Current Qty");
		$("#qty_"+scid).val("");
		return false;
	}
}
// function is_this_serial(scid){
// alert($("#btn_"+scid).is(':visible'));

// }
