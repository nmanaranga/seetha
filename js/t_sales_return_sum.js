var serial_items=[];
var get_id;
var serialWind;
var current_serial_no;
var sub_items=[];

$(document).ready(function(){

	var p_code = window.location.search.split('=')[2];

	if(p_code != undefined){
		load_data(p_code);
	}

	$(".quns").css("display","none");
	$("#btnSavee").css("display","none");
	$("#btnApprove").attr("disabled", "disabled");

	$("#tgrid2").tableScroll({height:200});

	$("#inv_no").keyup(function(){
		this.value = this.value.replace(/[^0-9\.-_a-z,',A-Z]/g,'');
	});

	$("#tgrid1").tableScroll({height:200, width:895});


	$(document).on('keyup','.vali', function(){
		set_cid($(this).attr("id"));
		if($("#1_"+scid).val() !="" ) {
			if(parseFloat($("#1_"+scid).val())>parseFloat($("#rmax_"+scid).val())){
				set_msg("Maximum available quntity is " +$("#rmax_"+scid).val());	
       	  //$(this).val($("#rmax_"+scid).val());
       	}
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

	$("#sales_rep").keydown(function(e){ 
		if(e.keyCode==112){
			$("#pop_search4").val($("#sales_rep").val());
			load_emp();
			$("#serch_pop4").center();
			$("#blocker").css("display", "block");
			setTimeout("$('#pop_search4').focus()", 100);   
		}
		$("#pop_search4").keyup(function(e){
			if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
				load_emp();
			}
		});
		if(e.keyCode==46){
			$("#sales_rep").val("");
			$("#sales_rep2").val("");
		}  
	});

	$("#btnApprove").click(function(){
		$("#app_status").val("2");
		if(validate()){
			save();    
		}
	});

 /* $("#btnReset").click(function(){
    alert();
    location.href="";
  });
  */
  $("#btnPrint").click(function(){
  	if($("#hid").val()=="0"){
  		set_msg ("Please load data before print");
  		return false;
  	}else{
  		$("#print_pdf").submit();
  	}
  });

  $("#btnDelete5").click(function(){
  	set_delete();
  });



  $("#tgrid").tableScroll({height:355});


  $("#stores").change(function(){
  	set_select('stores','store_id');
  });



  $("#btnExit1").click(function(){
  	document.getElementById('light').style.display='none';
  	document.getElementById('fade').style.display='none';  
  	$("#1_"+get_id).focus();
  });


  $("#free_fix,#pst").blur(function(){
  	var get_code=$(this).val();
  	$(this).val(get_code.toUpperCase());
  });


  $("#id").keydown(function(e){
  	if(e.keyCode == 13){
  		$(this).blur();
  		load_data($(this).val());
  	}
  });


  $("#type").change(function(){
  	if($("#type").val()=="4"){ 			
  	}
  	else if($("#type").val()=="5"){
  	}
  });


  $("#inv_no").keydown(function(e){
  	var paths;
  	var cashret = "index.php/main/load_data/t_sales_return_sum/get_invoice";
  	var creditret = "index.php/main/load_data/t_sales_return_sum/get_invoice1";

  	if(e.keyCode == 13){

  		empty_grid2();
  		empty_grid();

  		if($("#types").val()=="4"){
  			paths = cashret;
  		}else if($("#types").val()=="5"){
  			paths =creditret;
  		}else{
  			set_msg
  			("No records");
  			return false;
  		}

  		$.post(paths, {
  			code:$("#inv_no").val(),
  			type:$("#types").val()
  		}, function(r){
  			if(r.det==2){
  				set_msg("No records");
  			}else{

  				if(r.status!=1){
  					set_msg(r.status+"","error");
  				}


          //if ($("#types :selected").val() != "4"){            
          	$("#customer").val(r.det[0].cus_id);
          	$("#customer_id").val(r.det[0].cus_name);
          //}
          
          $("#inv_store").val(r.det[0].store);

          $("#sales_rep").val(r.det[0].rep);
          $("#sales_rep2").val(r.det[0].rep_name);
          $("#stores").val(r.det[0].store);
          set_select("stores","store_id");
          for(var i=0; i<r.det.length;i++){
          //$("#0_"+i).val(r.det[i].code);
          if($("#df_is_serial").val()=='1'){
          	$("#numofserial_"+i).val(r.det[i].qty);
          	$("#setserial_"+i).removeAttr("title");
          	$("#setserial_"+i).removeAttr("value");
          	$("#setserial_"+i).attr("title",1);
          	$("#setserial_"+i).attr("value",1); 
          }

          $("#h1_"+i).val(r.det[i].iCode);
          $("#01_"+i).val(r.det[i].iCode);
          $("#n1_"+i).val(r.det[i].iName);
          $("#11_"+i).val(r.det[i].qty);
          $("#21_"+i).val(r.det[i].price);
          $("#31_"+i).val(r.det[i].discountp);
          $("#41_"+i).val(r.det[i].discount);
          $("#51_"+i).val(r.det[i].amount);
          $("#rq_"+i).val(r.det[i].return_qty);
          $("#bt1_"+i).val(r.det[i].batch_no);
          $("#is_frees_"+i).val(r.det[i].is_free);
          $("#qno").val($("#inv_no").val()); 

          $("#customer").attr("readonly", "readonly");
          $("#customer_id").attr("readonly", "readonly");
          $("#sales_rep").attr("readonly", "readonly");
          $("#sales_rep2").attr("readonly", "readonly");

          $("#0_"+i).attr("readonly", "readonly");
          $("#n_"+i).attr("readonly", "readonly");
            //$("#1_"+i).attr("readonly", "readonly");
            $("#2_"+i).attr("readonly", "readonly");
            $("#3_"+i).attr("readonly", "readonly");
            //$("#4_"+i).attr("readonly", "readonly");
            $("#5_"+i).attr("readonly", "readonly");
        }

        for(var i=0; i<r.max.length;i++){
        	$("#rmax1_"+i).val(r.max[i].return_qty);
        }

        if($("#df_is_serial").val()=='1'){
        	serial_items.splice(0);
        	for(var i=0;i<r.serial.length;i++){
        		serial_items.push(r.serial[i].item+"-"+r.serial[i].serial_no);
        	}
        	serial_items.sort();
        }  

        if(r.addi!=2){
        	for(var i=0; i<r.addi.length;i++){  
        		$("#hh_"+i).val(r.addi[i].is_add);
        		$("#hhh_"+i).val(r.addi[i].type);
        		$("#000_"+i).val(r.addi[i].type);
        		$("#nnn_"+i).val(r.addi[i].description);
        		$("#111_"+i).val(r.addi[i].rate_p);
        		$("#222_"+i).val(r.addi[i].amount);
        	}
        }  
    }
    additional_amount();
    settings1();
    
}, 
"json");
  	}
  });



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


  $("#sales_rep").autocomplete('index.php/main/load_data/m_employee/auto_com', {
  	width: 350,
  	multiple: false,
  	matchContains: true,
  	formatItem: formatItems,
  	formatResult: formatItemsResult
  });

  $("#sales_rep").keydown(function(e){
  	if(e.keyCode == 13){
  		set_cus_values2($(this));
  	}
  });

  $("#sales_rep").blur(function(){
  	set_cus_values2($(this));
  });

  $(".rate, .aa").blur(function(){
  	additional_amount();
  	net_amount();
  });

  $(".price, .qty, .dis_pre").blur(function(){
  	set_cid($(this).attr("id"));
    //discount();
    additional_amount();
    dis_prec();
    amount();
    gross_amount();
    total_discount();
    net_amount();
});

  $(".dis").blur(function(){
  	set_cid($(this).attr("id"));
  	dis_prec();
  	additional_amount();
  	amount();
  	gross_amount();
  	total_discount();
  	net_amount();
  });

  $(".return_reason").keydown(function(e){
  	set_cid($(this).attr("id"));
   // alert(set_cid($(this).attr("id")));
   if(e.keyCode == 112){
   	$("#pop_search11").val($("6_"+scid).val());
   	load_data_reason(scid);
   	$("#serch_pop11").center();
   	$("#blocker").css("display", "block");
   	setTimeout("$('#pop_search11').focus()", 100);
   }

   $("#pop_search11").keyup(function(e){
   	if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
   		load_data_reason(scid);
   	}
   }); 
   if(e.keyCode == 46){
   	$("#6_"+scid).val(""); 
   	$("#ret_"+scid).val(""); 
   }
});

  $(".fo").keydown(function(e){  
  	set_cid($(this).attr("id"));

  	if(e.keyCode==112){
  		if($("#inv_no").val()=="")
  		{
  			$("#pop_search").val($("#0_"+scid).val());
  			load_items();
  			$("#serch_pop").center();
  			$("#blocker").css("display", "block");
  			setTimeout("select_search()", 100);
  		}
  	}

  	if(e.keyCode==13){
  		$.post("index.php/main/load_data/t_sales_return_sum/get_item", {
  			code:$("#0_"+scid).val()
  		}, function(res){
  			if(res.a!=2){
  				$("#0_"+scid).val(res.a[0].code);

  				if(check_item_exist($("#0_"+scid).val())){
  					if($("#df_is_serial").val()=='1'){
  						check_is_serial_item2(res.a[0].code,scid);
  					}
  					$("#h_"+scid).val(res.a[0].code);
  					$("#n_"+scid).val(res.a[0].description);
  					$("#0_"+scid).val(res.a[0].code);

  					$("#2_"+scid).val(res.a[0].max_price);

  					$("#1_"+scid).focus();
  				}else{
  					set_msg("Item "+$("#0_"+scid).val()+" is already added.");
  				}
  			}
  		}, "json");
  	}

  	if(e.keyCode==46){
  		if($("#df_is_serial").val()=='1'){
        //deleteSerial(scid);
        $("#all_serial_"+scid).val("");
    }
    $("#h_"+scid).val("");
    $("#0_"+scid).val("");
    $("#n_"+scid).val("");
    $("#bt_"+scid).val(""); 
    $("#1_"+scid).val(""); 
    $("#2_"+scid).val(""); 
    $("#3_"+scid).val(""); 
    $("#4_"+scid).val(""); 
    $("#rmax_"+scid).val(""); 
    $("#5_"+scid).val("");
    $("#21h_"+scid).val("");
    $("#6_"+scid).val("");
    $("#ret_"+scid).val("");
    $("#subcode_"+scid).val(""); 
    $("#7_"+scid).val(""); 
    $("#8_"+scid).val(""); 
    $("#9_"+scid).val(""); 
    $("#subcode_"+scid).removeAttr("data-is_click");

    $("#btn_"+scid).css("display","none");

    dis_prec();
    amount();
    additional_amount();
    gross_amount();
    total_discount();
    net_amount(); 
}
});


  load_items();
  // $("#pop_search").keyup(function(e){
  //   if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { load_items();
  //   }
  // });

  $("#pop_search").gselect(); 


  $(".fo").blur(function(){
  	var id=$(this).attr("id").split("_")[1];
  	if($(this).val()=="" || $(this).val()=="0"){
  	}else if($(this).val()!=$("#itemcode_"+id).val()){
  		if($("#df_is_serial").val()=='1'){
  			deleteSerial(id);
  		}
  	}
  });


  $(".return_reason").autocomplete("index.php/main/load_data/return_reason/auto_com2", {
  	width: 350,
  	multiple: false,
  	matchContains: true,
  	formatItem: formatItems,
  	formatResult: formatItemsResult
  });
  $(".return_reason").keydown(function(a) {
  	if (13 == a.keyCode) set_cus_values5($(this));
  });
  $(".return_reason").blur(function() {
  	$(".price").blur();
  	set_cus_values5($(this));
  }); 

  $(".qty").blur(function(){
  	is_sub_item(scid);
  });

  $(".del_item").keydown(function(e){
  	set_cid($(this).attr("id"));
  	if(e.keyCode==46){
  		if($("#df_is_serial").val()=='1'){
  			$("#all_serial_"+scid).val("");
  		}
  		$("#h_"+scid).val("");
  		$("#0_"+scid).val("");
  		$("#n_"+scid).val("");
  		$("#bt_"+scid).val(""); 
  		$("#1_"+scid).val(""); 
  		$("#2_"+scid).val(""); 
  		$("#3_"+scid).val(""); 
  		$("#4_"+scid).val(""); 
  		$("#rmax_"+scid).val(""); 
  		$("#5_"+scid).val("");
  		$("#21h_"+scid).val("");
  		$("#6_"+scid).val("");
  		$("#ret_"+scid).val("");
  		$("#subcode_"+scid).val(""); 
  		$("#7_"+scid).val(""); 
  		$("#8_"+scid).val(""); 
  		$("#9_"+scid).val("");
  		$("#subcode_"+i).removeAttr("data-is_click"); 
  	}
  });


});


function load_emp(){
	$.post("index.php/main/load_data/utility/f1_selection_list_emp", {
		filter_emp_cat:"salesman",
		search : $("#pop_search4").val() 
	}, function(r){
		$("#sr4").html(r);
		settings_emp();      
	}, "text");
}

function settings_emp(){
	$("#item_list .cl").click(function(){        
		$("#sales_rep").val($(this).children().eq(0).html());
		$("#sales_rep2").val($(this).children().eq(1).html());
		$("#pop_close4").click();                
	})    
}

function load_customer(){
	$.post("index.php/main/load_data/t_cash_sales_sum/customer_list", {
		search : $("#pop_search").val() 
	}, function(r){
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

function is_sub_item(x){
	sub_items=[];
	$("#subcode_"+x).val("");
	$.post("index.php/main/load_data/utility/is_sub_items_sales_return", {
		code:$("#0_"+x).val(),
	}, function(r){
		if(r!=2){

			for(var i=0; i<r.sub.length;i++){
				sub_items.push(r.sub[i].sub_item+"-"+r.sub[i].qty);         
				var p = parseInt(r.sub[i].qty) * parseInt($("#1_"+x).val());
				var rr = parseInt(r.sub[i].qty) * parseInt($("#rmax_"+x).val());

				if (($("#inv_no").length > 0)){
					if(rr<p){
						set_msg("Not enough quantity in item ("+$("#0_"+x).val()+")","error");
						$("#subcode_"+x).val("");
					}else{
						$("#subcode_"+x).val(sub_items); 
					}
				}else{
					$("#subcode_"+x).val(sub_items);
				}
			}
			$("#subcode_"+x).attr("data-is_click","1");
		}else{
          //set_msg("Not enough quantity in this sub item ("+$("#0_"+x).val()+")","error");
          $("#subcode_"+x).val("");
      }

  },"json");
}



// add(x,r.sub[i].sub_item,r.sub[i].qty);
// function add(x,items,qty){
//   $.post("index.php/main/load_data/utility/is_sub_items_available", {
//       code:items,
//       qty:qty,
//       grid_qty:$("#1_"+x).val(),
//       batch:$("#bt_"+x).val(),
//       hid:$("#hid").val(),
//       trans_type:"8",
//       store:$("#stores").val()
//     }, function(res){ 
//         if(res!=2){
//           var r = parseInt(res.sub[0].qty) * parseInt($("#rmax_"+x).val())
//           var q = parseInt(res.sub[0].qty) * parseInt($("#1_"+x).val())
//           if(r<q){
//             set_msg("Not enough quantity in item ("+$("#0_"+x).val()+")","error");
//             $("#subcode_"+x).val("");
//           }else{
//             sub_items.push(res.sub[0].sub_item+"-"+res.sub[0].qty);
//             $("#subcode_"+x).val(sub_items);         
//           }
//         }else{
//           set_msg("Not enough quantity in this sub item ("+items+")","error");
//           $("#subcode_"+x).val("");
//         }
//   },"json");
// }



function set_cus_values5(a) {
	var g = $(a).attr("id").split("_")[1];
	var b = a.val();
	b = b.split("-");
	if(2 == b.length){
		a.val(b[1]);
		$("#ret_"+g).val(b[0]);
	}
}


function select_search(){
	$("#pop_search").focus();
}

function load_items(){
	$.post("index.php/main/load_data/t_sales_return_sum/item_list_all", {
		search : $("#pop_search").val(),
		stores : false
	}, function(r){
		$("#sr").html(r);
		settings();
	}, "text");
}

function settings(){
	$("#item_list .cl").click(function(){
		if($(this).children().eq(0).html() != "&nbsp;"){
			if(check_item_exist($(this).children().eq(0).html())){
				if($("#df_is_serial").val()=='1'){
					check_is_serial_item2($(this).children().eq(0).html(),scid);
				}
				$("#h_"+scid).val($(this).children().eq(0).html());
				$("#0_"+scid).val($(this).children().eq(0).html());
				$("#n_"+scid).val($(this).children().eq(1).html());
				$("#2_"+scid).val($(this).children().eq(2).html());
				$("#3_"+scid).val($(this).children().eq(3).html());

				if($(this).children().eq(4).html() == 1){
					$("#1_"+scid).autoNumeric({mDec:2});
				}else{
					$("#1_"+scid).autoNumeric({mDec:0});
				}
				$("#1_"+scid).removeAttr("disabled"); $("#2_"+scid).removeAttr("disabled"); $("#3_"+scid).removeAttr("disabled");
				$("#1_"+scid).focus();$("#pop_close").click();
			}else{
				set_msg
				("Item "+$(this).children().eq(1).html()+" is already added.");
			}
		}else{
			$("#h_"+scid).val("");
			$("#0_"+scid).val("");
			$("#n_"+scid).val("");
			$("#1_"+scid).val(""); 
			$("#2_"+scid).val(""); 
			$("#3_"+scid).val(""); 
			$("#t_"+scid).html("&nbsp;");
			$("#1_"+scid).attr("disabled", "disabled"); 
			$("#2_"+scid).attr("disabled", "disabled");
			$("#3_"+scid).attr("disabled", "disabled");
			$("#pop_close").click();
		}
	});
}

function settings1(){
	$("#tgrid2 .cl1").dblclick(function(){

		if($(this).children().eq(0).html() != "&nbsp;"){
			var row_value1=$(this).children().find('input').eq(0).val();
			var row_value2=$(this).children().find('input').eq(2).val();
			var row_value3=$(this).children().find('input').eq(3).val();
			var row_value4=$(this).children().find('input').eq(4).val();
			var row_value5=$(this).children().find('input').eq(5).val();
			var row_value6=$(this).children().find('input').eq(6).val();
			var row_value7=$(this).children().find('input').eq(7).val();
			var row_value8=$(this).children().find('input').eq(8).val();
			var row_value9=$(this).find('.fe').val();
			var row_value10=$(this).children().find('input').eq(10).val();
			var row_value11=$(this).children().find('input').eq(11).val();


			for(var i=0; i<25 ;i++){      		
				if(row_value1 == "0" || row_value1 ==""){
				}else{
					if($("#0_"+i).val()==row_value1 && $("#bt_"+i).val()==row_value4){

						return false;
					}else if($("#0_"+i).val()==""){
						if($("#df_is_serial").val()=='1'){
							check_is_serial_item2($(this).children().find('input').eq(0).val(),i);
						}
          //get_qty(row_value1,row_value4,$("#inv_store").val(),i)

          $("#0_"+i).val(row_value1);
          $("#n_"+i).val(row_value2);
          $("#bt_"+i).val(row_value4);

          free_item(row_value1);
          $("#1_"+i).val(row_value9);
          $("#2_"+i).val(row_value6);
          $("#3_"+i).val(row_value7);
          $("#4_"+i).val(row_value8);
          $("#rmax_"+i).val(row_value9);
          $("#5_"+i).val(row_value10);
          $("#itemcode_"+i).val(row_value1);
          $("#is_free_"+i).val(row_value11);

          break;		
      }
  }
}



}else{
	$("#h_"+scid).val("");
	$("#0_"+scid).val("");
	$("#n_"+scid).val("");
      //$("#1_"+scid).val(""); 
      $("#2_"+scid).val(""); 
      $("#3_"+scid).val(""); 
      $("#t_"+scid).html("&nbsp;");
      $("#1_"+scid).attr("disabled", "disabled"); 
      $("#2_"+scid).attr("disabled", "disabled");
      $("#3_"+scid).attr("disabled", "disabled");
      
      //$("#pop_close").click();
  }
  gross_amount();
  total_discount();
  net_amount();
});
}

function free_item(item){ 
	$.post("index.php/main/load_data/t_sales_return_sum/get_free_item/",{
		item_code:item,
		b_date:$("#buy_date").val()  
	},function(res){  
		for(var y=1; y<26; y++){
			for(var x=0; x<res.free.length; x++){

				if(res.free[x].item==$("#01_"+y).val()){       
					if($("#0_"+y).val() == "0" || $("#0_"+y).val() ==""){            
						$("#0_"+y).val($("#01_"+y).val());
						$("#n_"+y).val($("#n1_"+y).val());
						$("#bt_"+y).val($("#bt1_"+y).val());
						$("#1_"+y).val($("#11_"+y).val());
						$("#2_"+y).val($("#21_"+y).val());
						$("#3_"+y).val($("#31_"+y).val());
						$("#4_"+y).val($("#41_"+y).val());  
						$("#5_"+y).val($("#51_"+y).val()); 
						if($("#df_is_serial").val()=='1'){
							check_is_serial_item2($("#01_"+y).val(),y);
						}
					}
				}
			}
		}          
	},"json");
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




function set_cus_values(f){
	var v = f.val();
	v = v.split("-");

	if(v.length == 2){
		f.val(v[0]);
		$("#customer_id").val(v[1]);
               // $("#customer_id").attr("class", "input_txt_f");
           }
       }

       function set_cus_values2(f){
       	var v = f.val();
       	v = v.split("-");
       	
       	if(v.length == 2){
       		f.val(v[0]);
       		$("#sales_rep2").val(v[1]);
               // $("#sales_rep2").attr("class", "input_txt_f");


           }
       }

       function formatItems(row){
       	return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
       }

       function formatItemsResult(row){
       	return row[0]+"-"+row[1];
       }


       function save(){
       	$("#btnApprove").attr('disabled','disabled');
       	$("#btnSave").attr('disabled','disabled');
       	if($("#df_is_serial").val()=='1'){
       		serial_items.sort();
       		$("#srls").attr("title",serial_items);
       		$("#srls").val(serial_items);
       	}
       	$("#is_duplicate").val("1");
       	$("#dt").val($("#date").val());
       	$("#qno").val($("#id").val());
       	$("#cus_id").val($("#customer").val());
       	$("#salesp_id").val($("#sales_rep").val());

       	var frm = $('#form_');
       	loding();
       	$.ajax({
       		type: frm.attr('method'),
       		url: frm.attr('action'),
       		data: frm.serialize(),
       		success: function (pid){
       			var sid=pid.split('@');
       			if(pid==5){
       				set_msg('Please check the serial numbers','error');
       			}else if(pid == 0){
       				set_msg("Transaction is not completed",'error');
       				location.href="";
       			}else if(pid == 2){
       				set_msg("No permission to add data.",'error');
       			}else if(pid == 3){
       				set_msg("No permission to edit data.",'error');
       			}else if(sid[0]==1){
       				$("#btnSave").attr("disabled",true);
                //$("#btnSavee").css("display","inline");
                if(confirm("Save Completed, Do You Want A print?")){
                	if($("#is_prnt").val()==1){
                		$("#qno").val(sid[1]);
                		$("#print_pdf").submit();
                	}
                	reload_form();
                }else{
                	location.href="";
                }
            }
            else{
            	set_msg(pid,'error');
            }
            loding();
            //  location.href="";
        }
    });
      //serial_items.splice(0);
  }


  function reload_form(){
  	setTimeout(function(){
  		location.href = '';
  	},100); 
  }


  function validate(){

  	for(var t=0; t<25; t++){
  		if($("#subcode_"+t).data("is_click")==1 && $("#subcode_"+t).val()==""){
  			set_msg("Not enough sub items in ("+$("#0_"+t).val()+")" ,"error");
  			$("#btnApprove").removeAttr('disabled');
  			$("#btnSave").removeAttr('disabled');
  			return false;
  		}
  	}

  	var v = false;
  	var z = true;

  	for(var i=0; i<25 ;i++){
  		if($("#1_"+i).val() !="" || $("#1_"+i).val() !="0") {
  			if(parseFloat($("#1_"+i).val())>parseFloat($("#rmax_"+i).val())){
  				set_msg("Maximum available quntity is " +$("#rmax_"+i).val());	
  				$(this).val("0");
     	//return false;
     }
 } 
}

$("input[type='hidden']").each(function(){
	if($(this).val() != "" && $(this).val() != 0){
		v = true;
	}
});

$(".return_reason").each(function(e){
	if($("#6_"+e).val()=="" && $("#0_"+e).val()!=""){  
		z= false;
		$("#btnApprove").removeAttr('disabled');
		$("#btnSave").removeAttr('disabled');
	}
});

if( $("#customer").val() == "" || $("#customer_id").val() == "" || $("#customer_id").val() == $("#customer_id").attr("title") ){
	set_msg("Please enter customer.","error");
	$("#btnApprove").removeAttr('disabled');
	$("#btnSave").removeAttr('disabled');
	return false;
}else if($("#inv_no").val() == ""){
	set_msg("Please enter invoice no.","error");
	$("#btnApprove").removeAttr('disabled');
	$("#btnSave").removeAttr('disabled');
	return false;
}else if($("#sales_rep").val() == ""){
	set_msg("Please enter sales rep.","error");
	$("#btnApprove").removeAttr('disabled');
	$("#btnSave").removeAttr('disabled');
	return false;
}else if($("#store_id").val() == "0"){
	set_msg("Please select store.","error");
	$("#btnApprove").removeAttr('disabled');
	$("#btnSave").removeAttr('disabled');
	return false;
}else if(v == false){
	set_msg("Please use minimum one item.","error");
	$("#btnApprove").removeAttr('disabled');
	$("#btnSave").removeAttr('disabled');
	return false;
}else if(z==false){
	set_msg("Please enter return reason","error");
	$("#btnApprove").removeAttr('disabled');
	$("#btnSave").removeAttr('disabled');
}else{

	var customer=$("#customer").val();
	var type=$("#types").val();
	var inv_no=$("#inv_no").val();

	if(inv_no!=0){

		$.post("index.php/main/load_data/t_sales_return_sum/check_invoice_no/",{
			cus_id:customer,
			type:type,
			inv_no:inv_no
		},function(res){   
			check_invoice_result(res);
		},"text");
	}else{
		return true;
	}
} 

}


function check_invoice_result(res){
  /*if(res==1){  
    save();
  }else{  
  set_msg
("Please check the invoice no with customer");
    return false;
}*/
save();
}


function discount(){
	var qty=parseFloat($("#1_"+scid).val());
	var price=parseFloat($("#2_"+scid).val());
	var dis_pre=parseFloat($("#3_"+scid).val());
	var discount="";
	if(isNaN(qty)){qty=0;}
	if(isNaN(price)){price=0;}
	if(isNaN(dis_pre)){dis_pre=0;}

	if(!isNaN(qty)&& !isNaN(price) && !isNaN(dis_pre)){
		discount=(qty*price*dis_pre)/100;

		if(discount!=0){
			$("#4_"+scid).val(m_round(discount));
		}else{
			$("#4_"+scid).val("");
		}
	}

}

function dis_prec(){
	var qty=parseFloat($("#1_"+scid).val());
	var price=parseFloat($("#2_"+scid).val());
	var discount=parseFloat($("#4_"+scid).val());

	var dis_pre="";

	if(isNaN(qty)){qty=0;}
	if(isNaN(price)){price=0;}
	if(isNaN(discount)){discount=0;}

	if(!isNaN(qty)&& !isNaN(price) && !isNaN(discount)){
		dis_pre=(discount*100)/(qty*price);
		if(isNaN(dis_pre) || !isFinite(dis_pre)){ 
			$("#3_"+scid).val("");
		}else{
			$("#3_"+scid).val(m_round(dis_pre));
		}
	}

}

function amount(){

	var qty=parseFloat($("#1_"+scid).val());
	var price=parseFloat($("#2_"+scid).val());
  //var discount=parseFloat($("#4_"+scid).val());
  var discount=parseFloat($("#4_"+scid).val()) * parseFloat($("#1_"+scid).val());
  var amount="";
  if(isNaN(qty)){qty=0;}
  if(isNaN(price)){price=0;}
  if(isNaN(discount)){discount=0;}

  //alert(discount);
  if(!isNaN(qty)&& !isNaN(price) && !isNaN(discount)){
  	amount=qty*price;
  	amount=amount-discount;

  	if(amount!=0){
  		$("#5_"+scid).val(m_round(amount)); 
  	}else{
  		$("#5_"+scid).val(""); 
  	}
  	
  }else if(!isNaN(qty)&& !isNaN(price)){
  	amount=(qty*price);

  	if(amount!=0){
  		$("#5_"+scid).val(m_round(amount)); 
  	}else{
  		$("#5_"+scid).val(""); 
  	}
  }
}


function gross_amount(){
	var gross=loop=0;
	$(".amount").each(function(){
		var gs=parseFloat($("#1_"+loop).val())*parseFloat($("#2_"+loop).val());
		var dis=parseFloat($("#4_"+loop).val()) *parseFloat($("#2_"+loop).val()) ;
		if(!isNaN(gs)){   
        //alert($("#is_free_0").val()); 
        if($("#is_free_"+loop).val()=="0" || $("#is_free_"+loop).val()==""){
        	gross+=gs;
        }
        
    }    
    loop++;
});
	$("#total2").val(m_round(gross));
}    


function total_discount(){

	var discount=loop=0;

	$(".dis").each(function(){

		var gs=parseFloat($("#4_"+loop).val()) * parseInt($("#1_"+loop).val());
		if(!isNaN(gs)){   
			discount=discount+gs;
		}    
		loop++;
	});
	$("#discount").val(m_round(discount));
}


function net_amount(){
	var gross_amount=parseFloat($("#total2").val());
	var discount=parseFloat($("#discount").val());
	var addi = parseFloat($("#addi_amount").val());
	if(isNaN(addi)){
		addi=0;
	}
	var net_amount=0;
	if(!isNaN(gross_amount)&& !isNaN(discount)){
		net_amount=gross_amount-discount;
		var n = parseFloat(net_amount)+parseFloat(addi);
		$("#net").val(m_round(n));
	}else{
		var n = parseFloat(net_amount)+parseFloat(addi);
		$("#net").val(m_round(n));
	}

}


function load_data(id){
	var g=[];
	empty_grid();
	loding();
	$.post("index.php/main/get_data/t_sales_return_sum/", {
		id: id
	}, function(r){

		if(r=="2"){
			set_msg("No records");
		}else{
			if(r.sum[0].return_type ==0){
				$("#hid").val(id);   
				$("#id").val(id); 
				$("#customer_id").val(r.sum[0].customer_name);
				$("#customer").val(r.sum[0].cus_id);
				$("#cus_id").val(r.sum[0].cus_id);
				$("#types").val(r.sum[0].sales_type);
				$("#inv_no").val(r.sum[0].inv_no);
				$("#crn_no").val(r.sum[0].crn_no);
				$("#sales_rep").val(r.sum[0].rep);
				$("#salesp_id").val(r.sum[0].rep);
				$("#sales_rep2").val(r.sum[0].rep_name);
				$("#date").val(r.sum[0].ddate); 
				$("#ref_no").val(r.sum[0].ref_no);gross_amount
				$("#total2").val(r.sum[0].gross_amount);
				$("#discount").val(r.sum[0].discount); 
				$("#net").val(r.sum[0].net_amount);
				$("#memo").val(r.sum[0].memo);
				$("#stores").val(r.sum[0].store);
				$("#dt").val(r.sum[0].ddate);

				$("#additional_add").val(r.sum[0].addi_add);
				$("#additional_deduct").val(r.sum[0].addi_deduct);
				
				$("#inv_no").attr("readonly", "readonly");
				set_select("stores","store_id");


				for(var i=0; i<r.det.length;i++){
					$("#itemcode_"+i).val(r.det[i].code);

					if($("#df_is_serial").val()=='1'){
						check_is_serial_item2(r.det[i].code,i);
						$("#numofserial_"+i).val(r.det[i].qty);
						for(var a=0;a<r.serial.length;a++){
							if(r.det[i].code==r.serial[a].item){
								g.push(r.serial[a].serial_no);
								$("#all_serial_"+i).val(g);
							}   
						}
						g=[];                 
					}

					$("#h_"+i).val(r.det[i].code);
					$("#0_"+i).val(r.det[i].code);
					$("#n_"+i).val(r.det[i].item_des);
					$("#1_"+i).val(r.det[i].qty);
					$("#bt_"+i).val(r.det[i].batch_no);
					$("#2_"+i).val(r.det[i].price);
					$("#3_"+i).val(r.det[i].discountp);
					$("#4_"+i).val(r.det[i].discount);
					$("#5_"+i).val(r.det[i].amount);
					$("#6_"+i).val(r.det[i].description);
					$("#ret_"+i).val(r.det[i].reason);
					$("#is_free_"+i).val(r.det[i].is_free);
					$("#qno").val(id);

					is_sub_item(i);

				}

				$("#btnApprove").attr("disabled", false);  

				if(r.sum[0].is_approve==1){
					$("#btnDelete5").attr("disabled", "disabled");
					$("#btnSave").attr("disabled", "disabled");
					$("#btnApprove").attr("disabled", "disabled");
					$("#mframe").css("background-image", "url('img/approved1.png')");
				}  

				if(r.sum[0].is_cancel==1){
					$("#btnDelete5").attr("disabled", "disabled");
					$("#btnSave").attr("disabled", "disabled");
					$("#btnApprove").attr("disabled", "disabled");
					$("#mframe").css("background-image", "url('img/cancel.png')");
				}

				if(r.add!=2){
					for(var i=0; i<r.add.length;i++){      
						$("#hhh_"+i).val(r.add[i].type);
						$("#000_"+i).val(r.add[i].type);
						$("#nnn_"+i).val(r.add[i].description);
						$("#111_"+i).val(r.add[i].rate_p);
						$("#222_"+i).val(r.add[i].amount);
						$("#hh_"+i).val(r.add[i].is_add);
					}
				}
				additional_amount();
				input_active();
			}else{
				alert("This Sales Return Without Sales Invoice");
				location.href="";
			}
		}
		loding();            
	}, "json");


}
function get_sales_type(i){
	$.post("index.php/main/load_data/r_additional_items/get_type",{
		id:$("#00_"+i).val()
	},function(res){      
		$("#hh_"+i).val(res);     
	},"text");
}

function additional_amount(){
	var additional=loop=add_plus=add_deduct=0;
	$(".foo").each(function(){
		var add=parseFloat($("#222_"+loop).val());
		var f= $("#hh_"+loop).val();
		if(!isNaN(add)){
			if(f==1){
				additional=additional+add;      
				add_plus+=add;
			}else{
				additional=additional-add;           
				add_deduct+=add;
			}
		}    
		loop++;
	});
	$("#addi_amount").val(m_round(additional));
	$("#additional_add").val(m_round(add_plus));
	$("#additional_deduct").val(m_round(add_deduct));
}

function empty_grid(){
	for(var i=0; i<25; i++){
		$("#h_"+i).val("");
		$("#0_"+i).val("");
		$("#n_"+i).val("");
		$("#bt_"+i).val(""); 
		$("#1_"+i).val(""); 
		$("#2_"+i).val(""); 
		$("#3_"+i).val(""); 
		$("#4_"+i).val(""); 
		$("#rmax_"+i).val(""); 
		$("#5_"+i).val("");
		$("#21h_"+i).val("");
		$("#6_"+i).val("");
		$("#ret_"+i).val("");
		$("#subcode_"+i).val(""); 
		$("#7_"+i).val(""); 
		$("#8_"+i).val(""); 
		$("#9_"+i).val("");
		$("#subcode_"+i).removeAttr("data-is_click"); 

		$("#btn_"+i).css("display","none");    
	}
	$(".quns").css("display","none");
}

function empty_grid2(){
	for(var i=0; i<25; i++){
		$("#h1_"+i).val(0);
		$("#01_"+i).val("");
		$("#n1_"+i).val("");

		$("#rq_"+i).val("");
		$("#bt1_"+i).val("");
		$("#bt_"+i).val("");
		$("#11_"+i).val("");
		$("#21_"+i).val("");
		$("#31_"+i).val("");
		$("#41_"+i).val("");
		$("#51_"+i).val("");

	}

	if ($("#customer").val() == ""){
		$("#customer").val("");
		$("#customer_id").val("");
	}


	$("#sales_rep").val("");
	$("#sales_rep2").val("");
	$("#total2").val("");
	$("#discount").val("");
	$("#net").val("");
}



function insertSerial(x){   
	serial_window(x);
}

function load_data_reason(scid){
	$.post("index.php/main/load_data/utility/f1_selection_list", {
		data_tbl:"r_return_reason",
		field:"code",
		field2:"description",
		preview2:"Return Reason Name",
		add_query: " AND type='2'",
		search : $("#pop_search11").val() 
	}, 
	function(r){ 
		$("#sr11").html(r);
		settings_reasonf1(scid);            
	}, "text");
}

function settings_reasonf1(scid){
	$("#item_list .cl").click(function(){     
		$("#ret_"+scid).val($(this).children().eq(0).html());
		$("#6_"+scid).val($(this).children().eq(1).html());
          //alert(scid);
          $("#pop_close11").click();                
      })    
}

function serial_window(x){ //if remove
	var item_code=$("#item_code").val();
	var count=$("#qty").val();
	var serial=x; 
       serial_items.push($("#item_code").val()+"-"+serial);// if remove
       document.getElementById('light').style.display='none';
       document.getElementById('fade').style.display='none';
       $("#btnExit1").removeAttr("disabled");

   }



   function check_item_exist4(id){
   	var v = true;
   	$(".srl_count").each(function(){
   		if($(this).val() == id){
   			v = false;
   		}
   	});
   	
   	return v;
   }

   function select_search4(){
   	$("#pop_search4").focus();
   }

   function set_delete(){
   	var id = $("#hid").val();
   	if(id != 0){
   		if(confirm("Are you sure to delete this sales return ["+$("#hid").val()+"]? ")){
   			loding();
   			$.post("index.php/main/delete/t_sales_return_sum", {
   				trans_no:id,
   				inv_no:$("#inv_no").val(),
   				type:$("#types").val()

   			},function(r){
   				loding();
   				if(r != 1){
   					set_msg(r);
   				}else{
   					delete_msg();
   				}
   			}, "text");
   		}
   	}else{
   		set_msg
   		("Please load record");
   	}
   }

