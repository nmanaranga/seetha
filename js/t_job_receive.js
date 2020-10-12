$(document).ready(function(){

	var wh=$("#mframe").width()-20;
	$("#tgrid").tableScroll({height:355, width:wh});

	$(".amt").bind('keyup blur' , function() {
		tot_amt();
	});
	
	$("#search_job").keyup(function(){
		load_services();
	});

	$("#btnPrint").click(function(){
		if($("#hid").val()=="0" || $("#hid").val()==" "){
			set_msg("Please Load a record");
		}else{
			$("#print_pdf").submit();
		}	
	});

	$("#btnReset").click(function(){
		$("input[type='checkbox']").each(function(){
			$(this).removeAttr("checked");
		});
		$("input[type='button']").removeAttr("disabled");
	});

	$(".chk").click(function(){
		set_cid($(this).attr("id"));
		if($("#sel_"+scid).is(":checked")){
			$("#6_"+scid).removeAttr("readonly");
		}else{
			
			$("#6_"+scid).prop('readonly','true');
			$("#6_"+scid).val("");
			tot_amt();	
		}
	});

	$("#id").keydown(function(e){
		if(e.keyCode == 13){
			$("#hid").val($(this).val());
			load_data();
		}
	});

	$("#btnDelete").click(function(){
		set_delete();
	});
	$("#supplier").keydown(function(e){
		if(e.keyCode==112){
			$("#pop_search").val();
			select_sup();
			$("#serch_pop").center();
			$("#blocker").css("display","block");
			setTimeout("$('#pop_search').focus()", 100); 
		}
		if(e.keyCode == 46){
			$("#supplier").val("");
			$("#sup_name").val("");
		}
		$("#pop_search").keyup(function(e){
			if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ){ 
				select_sup();
			}
		})
	})
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
				sucess_msg();
			}else if(pid == 2){
				set_msg("No permission to add data.");
			}else if(pid== 3){
				set_msg("No permission to edit data.");
			}else{
				set_msg(pid);
			}

		}
	});
}

function get_data_table(){
	$.post("/index.php/main/load_data/t_job_receive/get_data_table", {

	}, function(r){
		$("#grid_body").html(r);
	}, "text");
}


/*function check_code(){
	loding();
	var code = $("#code").val();
	$.post("index.php/main/load_data/t_job_receive/check_code", {
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
}*/

function validate(){
	if($("#supplier").val() == ""){
		set_msg("Please select supplier");
		$("#supplier").focus();
		return false;
	}else if(!$("#sel_"+scid).is(":checked")){
		set_msg("Please select a job");
		return false;
	}
	else{
		return true;
	}

}

function set_delete(){
	if(confirm("Are you sure, You Want to Cancel the record ")){
		loding();
		$.post("index.php/main/load_data/t_job_receive/delete",{
			id:$("#hid").val()
		}, function(res){
			if(res == 1){
				reload_form();
			}else if(res == 2){
				alert("No permission to delete data.");
			}else{
				alert("Record Cancelling fail.");
			}
			loding();
		}, "text");
	}
}

/*function is_edit($mod)
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
}*/

/*function set_edit(code){
	loding();
	$.post("index.php/main/get_data/t_job_receive", {
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
		

		loding(); input_active();
	}, "json");
}*/

function select_sup(){
	$.post("index.php/main/load_data/t_job_receive/select_supplier",{
		search:$("#pop_search").val()
	},function(r){
		$("#sr").html(r);
		supplier_settings();
	},"text");
}
function supplier_settings(){
	$("#item_list .cl").click(function(){
		$("#supplier").val($(this).children().eq(0).html()); 
		$("#sup_name").val($(this).children().eq(1).html());
		load_services();
		$("#pop_close").click();
	});
}
function load_services(){
	$.post("index.php/main/load_data/t_job_receive/load_services",{
		supplier:$("#supplier").val(),
		search : $("#search_job").val()
	},function(res){
		empty_grid();
		if(res.a==2){
		
		}else{
			for(var i=0;i<res.a.length;i++){
				$("#0_"+i).val(res.a[i].job_no);
				$("#n_"+i).val(res.a[i].receive_dt);
				$("#1_"+i).val(res.a[i].item_code + " - " + res.a[i].Item_name);
				$("#2_"+i).val(res.a[i].send_date);
				$("#3_"+i).val(res.a[i].fault);
				if(res.a[i].w_start_date == "0000-00-00"){
					$("#4_"+i).val("");
				}else{
					$("#4_"+i).val(res.a[i].w_start_date);
				}
				if(res.a[i].w_end_date == "0000-00-00"){
					$("#5_"+i).val("");
				}else{
					$("#5_"+i).val(res.a[i].w_end_date); 
				}

			}
		}

	},"json");
}

function empty_grid(){
	$("#tgrid input").each(function(){
		$(this).val("");
	});
}

function load_data(){
	$.post("index.php/main/load_data/t_job_receive/load",{
		id:$("#id").val()

	},function(res){
		empty_grid();
		if(res.a==2){
			set_msg("No Records");
		}else{
			if(res.a[0].is_cancel == 1){
				$("#mframe").css("background-image", "url('img/cancel.png')");
				$("#btnDelete").attr("disabled", "disabled");
				$("#btnSave").attr("disabled", "disabled");
			}
			$("#supplier").val(res.a[0].supplier);
			$("#sup_name").val(res.a[0].name);
			$("#comment").val(res.a[0].memo);
			$("#date").val(res.a[0].ddate);
			$("#ref_no").val(res.a[0].ref_no);
			$("#crn").val(res.a[0].crn_no);
			$("#amount").val(res.a[0].amount);
			$("#qno").val($("#id").val());

			for(var i=0;i<res.b.length;i++){
				$("#sel_"+i).attr("checked","checked");
				$("#0_"+i).val(res.b[i].job_no); 
				$("#n_"+i).val(res.b[i].service_receive_dt); 
				$("#1_"+i).val(res.b[i].item_code + " - " +res.b[i].Item_name); 
				$("#2_"+i).val(res.b[i].send_sup_dt); 
				$("#3_"+i).val(res.b[i].fault); 
				if(res.b[i].w_start_date == "0000-00-00"){
					$("#4_"+i).val(""); 
				}else{
					$("#4_"+i).val(res.b[i].w_start_date); 
				}
				if(res.b[i].w_end_date == "0000-00-00"){
					$("#5_"+i).val(""); 
				}else{
					$("#5_"+i).val(res.b[i].w_end_date); 
				}
				$("#6_"+i).val(res.b[i].item_amt); 
			}
		}
	},"json");
}
function reload_form(){
	setTimeout(function(){
		location.href= '';
	},50); 
}

function tot_amt(){
	var tot =0;
	$(".amt").each(function(){
		var amount = parseFloat($(this).val());
		if(isNaN(amount)){ amount=0; }
		tot = parseFloat(tot)+amount;
	});
	$("#amount").val(m_round(tot));
}
