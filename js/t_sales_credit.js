var is_edit=0;
var l1 = l2 = l3 = mr = rmr = def = pro = 0; var inv_t = lno = 0; var online_check; var total = 0;
var request_id = 0; var count = 100; var root; var sroot = sarea = sales_ref = storse = 0; 
$(document).ready(function(){
    enter_setup_trance('tgrid2');
    
    $("#sales_ref").val(sales_ref); $("#stores").val(storse);
    set_select('sales_ref', 'ref_des'); set_select('stores', 'sto_des');
    
    $("#tgrid").tableScroll({height:280});
    load_items();
    $("#stores").removeAttr("disabled");
    $("#amt").attr("disabled","disabled");
    
    $(".fo").focus(function(){
        if($("#customer").val() != 0 && $("#stores option:selected").val() != 0){
            set_cid($(this).attr("id"));
            $("#serch_pop").center();
            $("#blocker").css("display", "block");
            setTimeout("select_search()", 100);
        }else{
            alert("Please Select Customer and Stores");
        }
    });
    
    $("#pop_search").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { load_items();}
    });
    
    $("#pop_search").gselect();
    
    $(".amo, .qun, .dis").keyup(function(){
        set_cid($(this).attr("id"));
        check_current_stock(scid);
        set_discount('dis');
        set_sub_total();
    });

       function check_current_stock(scid)
    {
        var qty = parseFloat($("#1_"+scid).val());
        var item_code = $("#0_"+scid).val();

        $.post("index.php/main/load_data/m_items/check_current_stock",{
            
        item_code :item_code,    
        scid      : scid,    
        store     : $("#stores option:selected").val()
            
        },function(r)
        {
            if(r<qty)
                { 
                    alert("Input quantity exceed current stock quantity "+r);
                    $("#1_"+scid).val('');
                    $("#1_0").val(r);

                    set_discount('dis');
                    set_sub_total();
                }
                {
                     set_discount('dis');
                     set_sub_total();
                }

        },"text");
    }


    $("#btnreject").hide();
    $("#btnapprove").hide();
    $("#reject").val('0');    
    $("#response").val('0');
    $('#print,#btnReset,#btnDelete').show();
    
    $("#btnreject").click(function(){
    $("#reject").val('1');    
    $("#response").val('0');    
    set_save_with_pay();
    });
    
    $("#btnapprove").click(function(){
    $("#reject").val('0');    
    $("#response").val('1');    
    set_save_with_pay();
    });


    function set_cash_total(){
 
    //var t=0;
    var a= parseFloat($("#advance").val());
    var c= parseFloat($("#cheque").val());
    var d= parseFloat($("#amt").val());
    var e= parseFloat($("#cash").val());
    //alert($("#amt").val());   

     //t += a+c;
     var t=a+c;
     var s=e-t;
     $("#amt").val(s);  



       input_active();
}
    
    $(".dis_pre").keyup(function(){
        set_cid($(this).attr("id"));
        check_current_stock(scid);
        set_discount('pre');
        set_sub_total();
    });
    
    $("#id").keydown(function(e){
        if(e.keyCode == 13){
            $(this).blur();
            load_data($(this).val());
        }
    });
    
    $("#btnDelete").click(function(){
        is_process_del();
	   
    });
    
	
	
      function check_is_return_del(id)
    {
        
      $.post("/index.php/main/load_data/t_sales_credit/is_return", {
        
        id : id
        
    }, function(r){

        if(r>0)
            {
                alert('You cannot delete this record.This is returned sale')
            }
       else
           {
               check_cheque_status_del($("#hid").val()); 
           }
      
    }, "text");
  
    }
    
        function check_cheque_status_del(id)
    {
        $.post("index.php/main/load_data/loder/check_cheque_status",{
           
            id :id,
            trans :"CR_SALES"
            
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
    
	function check_delete_permission()
    {
    $.post("index.php/main/load_data/user_permissions/get_delete_permission",{
        module_id : '024'
    },function(r){
    
    if(r>0)
    {
        set_delete();
 
    }    
    else
    {
         alert("You have no permission to delete this record");
    }    
    
    });  
    }
    
    $("#btnDelete, #btnSave, #btnReset").removeAttr("disabled");
    
    $("#btnPrint").click(function(){
        var id = $("#hid").val()
        if(id != 0){
            window.open('index.php/prints/trance_forms/t_sales_credit/?id='+id);
        }else{
            alert("Please load record. Or save first.");
        }
    });
    
    $("#sales_ref").change(function(){
        set_select('sales_ref', 'ref_des');
    });
    
    $("#discount").keyup(function(){
        set_total();
    });
    
    $("#stores").change(function(){
        set_select('stores', 'sto_des');
        load_items();
    });
    
    enter_setup_trance();
    
    $("#btnCancel").click(function(){
        if(request_id != 0){
            $.post("index.php/main/load_data/loder/cancel_request", {
                request_id : request_id
            }, function(r){
                if(r == 1){
                    request_id = 0;
                }$("#btnReset").click();
            }, "text");
        }else{
            $("#btnReset").click();
        }
    });
    
    $("#btnRefresh").click(function(){
        load_online_users();
    });
    
    $("#btnRequest").click(function(){
        send_request();
    });
    
    $(".ttt").keyup(function(){
	set_cheque_total();
	set_credit_total();
	check_ch_detail($(this).attr("id"),$(this).val());
    });
    
    $(".ppp").keyup(function(){
	set_advance_total();
	set_credit_total();
	chk_ad_details($(this).attr("id"));
	
    });
    
    $("#amt").keyup(function(){
	
	set_credit_total();
    });
    
    //$("#cash").val($("#net_amount").val());
    
    //$("#cash").keyup(function(){
    //    set_cheque_total();
    //});
    
    $("#btnCloseRequest").click(function(){
        if(request_id != 0){
            $.post("index.php/main/load_data/loder/cancel_request", {
                request_id : request_id
            }, function(r){
                if(r == 1){
                    request_id = 0;
                }$("#btnReset").click();
            }, "text");
        }else{
            $("#permission").css("display", "none");
            $("#blocker").css("display", "none");
        }
        
    });
    
    $("#btnSavePay").click(function(){
        
        check_is_process();

    });
    
       function check_is_process()
    {
        $.post("index.php/main/load_data/t_interest_calculation/is_pross",{
           id : $("#id").val() 
        },function(r)    
        {    
            if(r>0)
            {
                alert("Interest calculation process already effected to this invoice.You cannot edit this record");
            }    
            else
            {
               if(validate())
                {    
                    check_is_return($("#id").val())   
                }      
            }    
            
        },"text");
        
        
    }
    
       function is_process_del()
    {
        $.post("index.php/main/load_data/t_interest_calculation/is_pross",{
           id : $("#id").val() 
        },function(r)    
        {    
            if(r>0)
            {
                alert("Interest calculation process already effected to this invoice.You cannot delete this record");
            }    
            else
            {
              	check_is_return_del($("#id").val());     
            }    
            
        },"text");
        
        
    }
	

    function check_is_return(id)
    {
        
      $.post("/index.php/main/load_data/t_sales_credit/is_return", {
        
        id : id
        
    }, function(r){

        if(r>0)
            {
                alert('You cannot edit this record.This is returned sale')
            }
       else
           {
                check_cheque_status($("#hid").val());
			
           }
      
    }, "text");
  
    }
    
       function check_cheque_status(id)
    {
        $.post("index.php/main/load_data/loder/check_cheque_status",{
           
            id :id,
            trans :"CR_SALES"
            
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
    
	function check_permission()
	{
    	$.post("index.php/main/load_data/user_permissions/get_permission",{
    
        module_id : '024',
        is_edit   : is_edit
        
    },function(r){
    
    if(r=='1')
    {
       //save();
	   set_save_with_pay(); 
       
    }    
    else if(r=='2')
    {
        alert("You have no permission to edit this record");
    }
    else
    {
      //save();
	  //set_save_with_pay(); 
          if($("#response").val()>0)
          {
          set_save_with_pay(); 
          }   
          else
          {    
          check_need_special_approval();
          }
    }    
});
    
}


function check_need_special_approval()
{
      var re=true;  

    
      for(var x=0; x<30; x++){
       
       if($("#0_"+x).val()!='')
           {

                 if(parseFloat($("#4_"+x).val()) == parseFloat($("#44_"+x).val()) &&  parseFloat($("#44_"+x).val())>0)
                  {

                 $("#0_"+x).css("background-color", "transparent");
                 $("#n_"+x).css("background-color", "transparent");
                 $("#2_"+x).css("background-color", "transparent");
                 $("#3_"+x).css("background-color", "transparent");
                 $("#4_"+x).css("background-color", "transparent");
                 $("#t_"+x).css("background-color", "transparent");
               

                 $("#0_"+x).css("background-color", "#dde458");
                 $("#n_"+x).css("background-color", "#dde458");
                 $("#2_"+x).css("background-color", "#dde458");
                 $("#3_"+x).css("background-color", "#dde458");
                 $("#4_"+x).css("background-color", "#dde458");
                 $("#t_"+x).css("background-color", "#dde458");
                 alert("You need Special approval for these item discount(s)");
                 re=false;
                 }      
           }
           else
             {
             if(re!=false)   
             {
             re=true;    
             }
             }  
         }
         
         if(re==true)
         {
            $("#po").val('0'); 
            set_save_with_pay();
            
         }
         else
         {
              $("#btnClosePay").click();
              if(confirm("Do you want to continue?")){
                   $("#po").val('1');
                   set_save_with_pay();
              }
              else
              {
                  $("#po").val('0');
                 
              }    
         }    
    
}

    $("#scustomers").autocomplete('index.php/main/load_data/m_customer/auto_com', {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatCustomer,
        formatResult: formatCustomerResult
    });
    
    $("#scustomers").blur(function(){
	set_cus_values($(this));
    });
    
    $("#scustomers").keydown(function(e){
	if(e.keyCode == 13){
	    set_cus_values($(this));
	}
    });
    
    $(".account_no, .cheque_no").blur(function(){
	check_cheque_exist($(this).attr("id"));
    });
    
    $(":radio").change(function(){
	if($(this).val() == 2){
	    $("#btnPayments").removeAttr("disabled");
	}else{
	    $("#btnPayments").attr("disabled", "disabled");
	}
    });
	
	$("#cash_lable").html(" ");
	$("#amt").css("display","none");
	
	//$("#ch_grd").html(" ");
});

/*function set_discount(m){
    var x = parseFloat($("#1_"+scid).val());
    var y = parseFloat($("#2_"+scid).val());
    var z = x*y;
    if(m == "pre"){
	var d = parseFloat($("#4_"+scid).val()); if(isNaN(d)){ d = 0; }
	d = z*(d/100);
	$("#3_"+scid).val(m_round(d));
    }else if(m == "dis"){
	var d = parseFloat($("#3_"+scid).val()); if(isNaN(d)){ d = 0; }
	d = (d/z)*100;
	$("#4_"+scid).val(m_round(d));
    }
}*/

//function set_discount(m){
//    var x = parseFloat($("#1_"+scid).val());
//    var y = parseFloat($("#2_"+scid).val());
//    var z = x*y;
//
//    if(m == "pre"){
//   
//	var d = parseFloat($("#4_"+scid).val()); if(isNaN(d)){ d = 0; }
//	d = z*(d/100);
//	$("#3_"+scid).val(m_round(d));
//        
//         if($($("#4_"+scid)).val()=='0' || $("#4_"+scid).val()=='')
//        {
//        $("#3_"+scid).removeAttr("disabled","disabled");    
//        }   
//        else
//        {    
//        $("#3_"+scid).attr("disabled","disabled");
//        }
// 
//    }
//    if(m == "dis"){
// 
//	var d = parseFloat($("#3_"+scid).val()); if(isNaN(d)){ d = 0; }
//	d = (d/z)*100;
//	$("#4_"+scid).val(m_round(d));
//        
//        if($("#3_"+scid).val()=='0' || $("#3_"+scid).val()=='')
//        {
//        $("#4_"+scid).removeAttr("disabled","disabled");    
//        }   
//        else
//        {    
//        $("#4_"+scid).attr("disabled","disabled");
//        }
//        
//    }
//}

function set_discount(m){
    var x = parseFloat($("#1_"+scid).val());
    var y = parseFloat($("#2_"+scid).val());
    var z = x*y;

    if(m == "pre"){
   
	var d = parseFloat($("#4_"+scid).val()); if(isNaN(d)){ d = 0; }
	d = z*(d/100);
	$("#3_"+scid).val(m_round(d));
        
         if($($("#4_"+scid)).val()=='0' || $("#4_"+scid).val()=='')
        {
        $("#3_"+scid).removeAttr("disabled","disabled");    
        }   
        else
        {    
        $("#3_"+scid).attr("disabled","disabled");
        }
 
    }
    if(m == "dis"){
 
	var d = parseFloat($("#3_"+scid).val()); if(isNaN(d)){ d = 0; }
	d = (d/z)*100;
	$("#4_"+scid).val(m_round(d));
        
        if($("#3_"+scid).val()=='0' || $("#3_"+scid).val()=='')
        {
        $("#4_"+scid).removeAttr("disabled","disabled");    
        }   
        else
        {    
        $("#4_"+scid).attr("disabled","disabled");
        }
        
    }
    
    find_discount_rate($("#h_"+scid).val(),$("#4_"+scid).val(),scid);
}

    function find_discount_rate(item,dis_pre,scid)
{
    
    $.post("index.php/main/load_data/m_items/discount_rate",{
        
    item :item    
        
    },function(r){   


    if(r.rate.special_discount!=undefined)
       { 
               if(parseFloat(dis_pre) > parseFloat(r.rate.special_discount) || parseFloat(r.rate.normal_discount) < parseFloat(dis_pre) && parseFloat($("#4_"+scid).val())!== parseFloat($("#44_"+scid).val()))
               {
                   
                   alert("Input Discount(%) should less than Special discount rate "+r.rate.special_discount+" AND Normal discount rate "+parseFloat(r.rate.normal_discount));
                   $("#btnPayments").attr("disabled","disabled");
               }
          else
              {
                       $("#44_"+scid).val(r.rate.special_discount);
                       $("#btnPayments").removeAttr("disabled","disabled");
                       

              }
       }
    },"json");    
}

 function find_minimum_sale_price(item,total,qty)
{
    
    $.post("index.php/main/load_data/m_items/minimum_sale_price",{
        
    item :item    
        
    },function(r){   


    if(r.min.cost_price!=undefined)
       { 
         if((parseFloat(total)/parseFloat(qty))<parseFloat(r.min.cost_price))
           {
               alert("Per item sales amount should higher than minimum Sales price "+r.min.cost_price);
               $("#btnPayments").attr("disabled","disabled");
           }
         else
          {
               $("#btnPayments").removeAttr("disabled","disabled");
               
          }
       }
    },"json");    
}

function set_cus_values(f){
    
    var v = f.val();
    v = v.split("~");
    if(v.length == 2){
	f.val(v[0]);
	$("#customer").val(v[0]);
	$("#cus_des").val(v[1]);
	$("#cus_des").attr("class", "input_txt_f");
        load_balance();
	load_balance2();
    }
}

function formatCustomer(row){
    return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
}

function formatCustomerResult(row){
    return row[0]+"~"+row[1];
}

function set_session(){
    $.post("/index.php/main/load_data/loder/report_session", {
        route : $("#route option:selected").val(),
        area : $("#area option:selected").val()
    }, function(r){
        $("#root_area").css("display", "none");
        $("#blocker").css("display", "none");
        location.reload();
    }, "text");
}

function set_root(){
    var txt = "<option value='0'>---</option>";
    var area = $("#area option:selected").val();
    
    if(root[area] != undefined){
        for(var i=0; i<root[area].length; i++){
            txt += "<option value='"+root[area][i][0]+"'>"+root[area][i][1]+"</option>";
        }
    }
    
    $("#route").html(txt);
}

function get_route_list(){
    $.post("index.php/main/load_data/m_root/select_area_wise", {
        
    }, function(r){
        root = r;
    }, "json");
}

function chk_ad_details(id){
    set_cid(id)
	
	var y=$("#p3_"+scid).val();
	var z=parseFloat($("#p4_"+scid).val());
	//alert(y+z);
	if(y<z){
		alert('Invalid advance settle amount');
		$("#p4_"+scid).val(" ");
		$("#advance").val(" ");
		
		
		 //var a= parseFloat($("#advance").val());
		 //var c= parseFloat($("#cheque").val());
		 //var d= parseFloat($("#amt").val());
		 var e= parseFloat($("#cash").val());
		
 
		var t=0;
		var s=e-t;
		$("#credit").val(s); 		
		
		
		}
    
}

function set_advance_total(){
    var t = ttt =sss= s= 0;
    
//    $(".k").each(function(){
//	
//	s= parseFloat($(this).val());
//	if(! isNaN(s)){
//	    sss += s;
//	}
//	})    
//    
    $(".ppp").each(function(){
	t = parseFloat($(this).val());
	if(! isNaN(t)){
	    ttt += t;
	}
    });
    

    var ct = parseFloat($("#net_amount").val());
    if(isNaN(ct)){
	ct = 0;
    }
    
    $("#cash").val(ct);
    
    
    //$("#advance").val(ttt);
    //ttt += ct;
    
   
	$("#advance").val(ttt);
    
       
    input_active();
}

function set_credit_total(){
 
 //var t=0;
 var a= parseFloat($("#advance").val());
 var c= parseFloat($("#cheque").val());
 var d= parseFloat($("#amt").val());
 var e= parseFloat($("#cash").val());
 //alert($("#amt").val());   
    
  //t += a+c;
  var t=a+c+d;
  var s=e-t;
  $("#credit").val(s);  


           
    input_active();
}

function set_cheque_total(){
    var t = ttt = 0;
    $(".ttt").each(function(){
	t = parseFloat($(this).val());
	if(! isNaN(t)){
	    ttt += t;
	}
    });
    

    var ct = parseFloat($("#net_amount").val());
    if(isNaN(ct)){
	ct = 0;
    }
    
    $("#cash").val(ct);
    
    
    $("#cheque").val(ttt);
    //ttt += ct;
    
    $("#credit").val(ct - ttt);
    
    input_active();
}

//function set_cheque_total(){
//    var t = ttt = 0;
//    $(".ttt").each(function(){
//	t = parseFloat($(this).val());
//	if(! isNaN(t)){
//	    ttt += t;
//	}
//    });
//    
//    var ct = parseFloat($("#cash").val());
//    if(isNaN(ct)){
//	ct = 0;
//    }
//    var crt = parseFloat($("#net_amount").val());
//    if(isNaN(crt)){
//	crt = 0;
//    }
//    
//    var a= parseFloat($("#advance").val());
//    var d= parseFloat($("#amt").val());
//    
//    $("#cheque").val(ttt);
//    ttt += ct;
//    $("#credit").val(crt - (ttt+a+d));
//    
//    input_active();
//}

function check_ch_detail(id,v){
	set_cid(id)
	
	var y=$("#q1_"+scid).val();
	var z=$("#qn_"+scid).val();
	var m=$("#q2_"+scid).val();
	var n=$("#q4_"+scid).val();
	
	if(y==''|| z==''|| m==''|| n==''){
		alert('Please fill each column in cheque details');
		$("#q3_"+scid).val(" ");
		$("#cheque").val(" ");
		$("#credit").val($("#cash").val());
		}
	
	}

$(document).keydown(function(e){
    if(e.keyCode == 112){
        $("#0_0").focus();
    }
});

function check_cheque_exist(id){
    set_cid(id);
    var c_no = $("#q1_"+scid).val();
    var a_no = $("#q2_"+scid).val();
    
    if(c_no != "" && a_no != ""){
	$.post("index.php/main/load_data/t_sales_credit/check_cheque_no", {
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

function set_delete(){
    var id = $("#hid").val();
    if(id != 0){
        if(confirm("Are you sure ? ")){
            $.post("index.php/main/delete/t_sales_credit", {
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

function empty_grid(){
    for(var i=0; i<25; i++){
        $("#h_"+i).val(0);
        $("#0_"+i).val("");
        $("#n_"+i).val("");
        $("#t_"+i).html("&nbsp;");
        $("#1_"+i).val("");
        $("#2_"+i).val("");
        $("#3_"+i).val("");
    }
}

function set_sub_total(){
    var x = parseFloat($("#1_"+scid).val());
    var y = parseFloat($("#2_"+scid).val());
    var item = $("#h_"+scid).val();
    var d = $("#3_"+scid).val();
    var pre;
    if(d.match('%') != null){
        d = d.replace('%', '');
        d = parseFloat(d); pre = true;
        if(isNaN(d)){ d = 0; }
    }else{
        d = parseFloat(d); pre = false;
    }
    
    if(isNaN(d)){ d = 0; }
    
    var z;
    if(! isNaN(x) && ! isNaN(y)){
        z = x*y;
        if(pre){
            z *= ((100-d)/100);
        }else{
            z -= d;
        }
        $("#t_"+scid).html(m_round(z));
    }else{
        $("#t_"+scid).html("0.00");
    }
    
    find_minimum_sale_price(item,m_round(z),x);
    set_total();
}

function load_data(id){
    empty_grid();
    $.post("index.php/main/get_data/t_sales_credit/", {
        id : id
    }, function(r){
        if(r.sum.id != undefined){
            $("#date").val(r.sum.date);
            $("#customer").val(r.sum.customer);
            $("#scustomers").val(r.sum.customer);
            $("#cus_des").val(r.sum.outlet_name+" ("+r.sum.name+")");
            $("#sales_ref").val(r.sum.sales_ref);
            set_select('sales_ref', 'ref_des');
            $("#memo").val(r.sum.memo);
            $("#ref_no").val(r.sum.ref_no);
            $("#discount").val(r.sum.discount);
            $("#id").attr("readonly", "readonly");
            $("#so_no").val(r.sum.so_no);
            $("#stores").val(r.sum.stores);
            $("#balance").val(r.sum.balance);
            $("#cash").val(r.sum.pay_amount);
            $("#rm").val(r.sum.r_margin);
            set_select('stores', 'sto_des');
               if(r.sum.is_reject>0)
            {    
            alert("Sales details rejected by the authorizer.");
	    $("#po").val(r.sum.is_reject);
            }
            else
            {
            $("#po").val(r.sum.is_request);    
            }
            $("#response").val(r.sum.feed_back);
            
	    $("#amt").val(r.sum.cash);
	    $("#advance").val(r.sum.advance);
	    $("#cheque").val(r.sum.cheque);
	    $("#credit").val(r.sum.credit);
	    
            load_items();
            
            for(var i=0; i<r.det.length; i++){
                $("#h_"+i).val(r.det[i].item_code);
                $("#0_"+i).val(r.det[i].item_code);
                $("#n_"+i).val(r.det[i].description);
                
                if(r.det[i].is_measure == 1){
                    $("#1_"+i).val(r.det[i].quantity);
                    $("#1_"+i).autoNumeric({mDec:2});
                }else{
                    $("#1_"+i).val(parseInt(r.det[i].quantity));
                    $("#1_"+i).autoNumeric({mDec:0});
                }
                
                $("#2_"+i).val(r.det[i].cost);
                $("#3_"+i).val(r.det[i].discount);
                $("#4_"+i).val(r.det[i].discount_pre);
                $("#44_"+i).val(r.det[i].special_discount);
                $("#1_"+i).removeAttr("disabled");
                $("#2_"+i).removeAttr("disabled");
                $("#3_"+i).removeAttr("disabled");
                
                set_cid("1_"+i);
		
		///////////////////////////////
	    
	    var x = parseFloat($("#1_"+scid).val());
	    var y = parseFloat($("#2_"+scid).val());
	    var d = $("#3_"+scid).val();
	    var pre;
	    if(d.match('%') != null){
		d = d.replace('%', '');
		d = parseFloat(d); pre = true;
		if(isNaN(d)){ d = 0; }
	    }else{
		d = parseFloat(d); pre = false;
	    }
	    
	    if(isNaN(d)){ d = 0; }
	    
	    var z;
	    if(! isNaN(x) && ! isNaN(y)){
		z = x*y;
		if(pre){
		    z *= ((100-d)/100);
		}else{
		    z -= d;
		}
		$("#t_"+scid).html(m_round(z));
	    }else{
		$("#t_"+scid).html("0.00");
	    }
	    
	    
	    
	    
	    var t = tt = ct = ctt = qun = 0; 
	    $(".tf").each(function(){
		set_cid($(this).attr("id"));
		ctt = parseFloat($("#hc_"+scid).val());
		qun = parseFloat($("#1_"+scid).val());
		tt = parseFloat($(this).html());
		if(isNaN(tt)){ tt = 0;}if(isNaN(ctt)){ ctt = 0;}if(isNaN(qun)){ qun = 0;}
		t += tt; ct += ctt*qun;
	    });
	    
	    $("#total2").val(m_round(t));
	    
	    var dis = parseFloat($("#discount").val());
	    if(! isNaN(dis)){
		t -= dis;
	    }
	    
	    var ret = parseFloat($("#ret_amount").val());
	    if(! isNaN(ret)){
		t -= ret;
	    }
	    
	    pro = m_round(((t-ct)/(ct))*100);
	    $("#cm").val(pro);
	    
	    if(mr > pro){
		def = mr - pro;
	    }else{
		rmr = 0;
	    }
	    
	    $("#net_amount").val(m_round(t));
	    
            
            
            //////////////////////////////
            set_sub_total();
            
            
            }
            
            for(var i=0; i<r.chq.length; i++){
                $("#qbh_"+i).val(r.chq[i].bank);
                $("#qbbh_"+i).val(r.chq[i].bank_branch);
                $("#q0_"+i).val(r.chq[i].bank_name);
                $("#qn_"+i).val(r.chq[i].description);
                $("#q1_"+i).val(r.chq[i].cheque_no);
                $("#q2_"+i).val(r.chq[i].account_no);
                $("#q3_"+i).val(r.chq[i].cheque_amount);
                $("#q4_"+i).val(r.chq[i].realize_date);
		
            }
            
            set_cheque_total();
            
            for(var i=0; i<r.adv2.length; i++){
		$("#p1_"+i).val(r.adv2[i].receipt_no);
		$("#p2_"+i).val(r.adv2[i].total);
		$("#p3_"+i).val(r.adv2[i].balance);
		$("#p4_"+i).val(r.adv2[i].settle_amount);
		
	    }
	    
	    
            inv_t = parseFloat($("#net_amount").val());
            
            $("#cm").val(r.sum.c_margin);
            
            if(r.sum.is_cancel > 0){
                alert("This record canceled.");
                
                $("#btnDelete").attr("disabled", "disabled");
                $("#btnSave").attr("disabled", "disabled");
                $("#mframe").css("background-image", "url('img/cancel.png')");
                $("#mframe").css("background-repeat", "repeat-x");
                $("#mframe").css("background-position", "center");
            }else if(r.sum.posting > 0){
                $("#btnDelete").attr("disabled", "disabled");
                $("#btnSave").attr("disabled", "disabled");
                $("#mframe").css("background-image", "url('img/posted.png')");
                $("#mframe").css("background-repeat", "repeat-x");
                $("#mframe").css("background-position", "center");
            }
            
            l1 = r.levels.l1;
            l2 = r.levels.l2;
            l3 = r.levels.l3;
            
            $("#hid").val(r.sum.id);
            input_active();
            
            
        }else{
            alert("No records");
        }
    }, "json");
}

function load_data_by_id(id){
    empty_grid();
    $.post("index.php/main/get_data/t_sales_conform_cr/", {
        id : id
    }, function(r){
        if(r.sum.id != undefined){
   
            $("#id").val(r.sum.no);
            $("#hid").val(r.sum.id);
            $("#date").val(r.sum.date);
            $("#customer").val(r.sum.customer);
            $("#scustomers").val(r.sum.customer);
            $("#cus_des").val(r.sum.outlet_name+" ("+r.sum.name+")");
            $("#sales_ref").val(r.sum.sales_ref);
            set_select('sales_ref', 'ref_des');
            $("#memo").val(r.sum.memo);
            $("#ref_no").val(r.sum.ref_no);
            $("#discount").val(r.sum.discount);
            $("#id").attr("readonly", "readonly");
            $("#so_no").val(r.sum.so_no);
            $("#stores").val(r.sum.stores);
            $("#balance").val(r.sum.balance);
            $("#cash").val(r.sum.pay_amount);
            $("#rm").val(r.sum.r_margin);
            set_select('stores', 'sto_des');
            if(r.sum.is_reject>0)
            {    
            alert("Sales details rejected by the authorizer.");
	    $("#po").val(r.sum.is_reject);
            }
            else
            {
            $("#po").val(r.sum.is_request);    
            }
            
            $("#response").val(r.sum.feed_back);
            
	    $("#amt").val(r.sum.cash);
	    $("#advance").val(r.sum.advance);
	    $("#cheque").val(r.sum.cheque);
	    $("#credit").val(r.sum.credit);
	    
            load_items();
            
            for(var i=0; i<r.det.length; i++){
    
                $("#h_"+i).val(r.det[i].item_code);
                $("#0_"+i).val(r.det[i].item_code);
                $("#n_"+i).val(r.det[i].description);
                
                if(r.det[i].is_measure == 1){
                    $("#1_"+i).val(r.det[i].quantity);
                    $("#1_"+i).autoNumeric({mDec:2});
                }else{
                    $("#1_"+i).val(parseInt(r.det[i].quantity));
                    $("#1_"+i).autoNumeric({mDec:0});
                }
                
                $("#2_"+i).val(r.det[i].cost);
                $("#3_"+i).val(r.det[i].discount);
                $("#4_"+i).val(r.det[i].discount_pre);
                $("#44_"+i).val(r.det[i].special_discount);
                $("#1_"+i).removeAttr("disabled");
                $("#2_"+i).removeAttr("disabled");
                $("#3_"+i).removeAttr("disabled");
                
                set_cid("1_"+i);
		
		///////////////////////////////
	    
	    var x = parseFloat($("#1_"+scid).val());
	    var y = parseFloat($("#2_"+scid).val());
	    var d = $("#3_"+scid).val();
	    var pre;
	    if(d.match('%') != null){
		d = d.replace('%', '');
		d = parseFloat(d); pre = true;
		if(isNaN(d)){ d = 0; }
	    }else{
		d = parseFloat(d); pre = false;
	    }
	    
	    if(isNaN(d)){ d = 0; }
	    
	    var z;
	    if(! isNaN(x) && ! isNaN(y)){
		z = x*y;
		if(pre){
		    z *= ((100-d)/100);
		}else{
		    z -= d;
		}
		$("#t_"+scid).html(m_round(z));
	    }else{
		$("#t_"+scid).html("0.00");
	    }
	    
	    
	    
	    
	    var t = tt = ct = ctt = qun = 0; 
	    $(".tf").each(function(){
		set_cid($(this).attr("id"));
		ctt = parseFloat($("#hc_"+scid).val());
		qun = parseFloat($("#1_"+scid).val());
		tt = parseFloat($(this).html());
		if(isNaN(tt)){ tt = 0;}if(isNaN(ctt)){ ctt = 0;}if(isNaN(qun)){ qun = 0;}
		t += tt; ct += ctt*qun;
	    });
	    
	    $("#total2").val(m_round(t));
	    
	    var dis = parseFloat($("#discount").val());
	    if(! isNaN(dis)){
		t -= dis;
	    }
	    
	    var ret = parseFloat($("#ret_amount").val());
	    if(! isNaN(ret)){
		t -= ret;
	    }
	    
	    pro = m_round(((t-ct)/(ct))*100);
	    $("#cm").val(pro);
	    
	    if(mr > pro){
		def = mr - pro;
	    }else{
		rmr = 0;
	    }
	    
	    $("#net_amount").val(m_round(t));
	    
            
            
            //////////////////////////////
            set_sub_total();
            
            
            }
            
            for(var i=0; i<r.chq.length; i++){
                $("#qbh_"+i).val(r.chq[i].bank);
                $("#qbbh_"+i).val(r.chq[i].bank_branch);
                $("#q0_"+i).val(r.chq[i].bank_name);
                $("#qn_"+i).val(r.chq[i].description);
                $("#q1_"+i).val(r.chq[i].cheque_no);
                $("#q2_"+i).val(r.chq[i].account_no);
                $("#q3_"+i).val(r.chq[i].cheque_amount);
                $("#q4_"+i).val(r.chq[i].realize_date);
		
            }
            
            set_cheque_total();
            
            for(var i=0; i<r.adv2.length; i++){
		$("#p1_"+i).val(r.adv2[i].receipt_no);
		$("#p2_"+i).val(r.adv2[i].total);
		$("#p3_"+i).val(r.adv2[i].balance);
		$("#p4_"+i).val(r.adv2[i].settle_amount);
		
	    }
	    
	    
            inv_t = parseFloat($("#net_amount").val());
            
            $("#cm").val(r.sum.c_margin);
            
            if(r.sum.is_cancel > 0){
                alert("This record canceled.");
                
                $("#btnDelete").attr("disabled", "disabled");
                $("#btnSave").attr("disabled", "disabled");
                $("#mframe").css("background-image", "url('img/cancel.png')");
                $("#mframe").css("background-repeat", "repeat-x");
                $("#mframe").css("background-position", "center");
            }else if(r.sum.posting > 0){
                $("#btnDelete").attr("disabled", "disabled");
                $("#btnSave").attr("disabled", "disabled");
                $("#mframe").css("background-image", "url('img/posted.png')");
                $("#mframe").css("background-repeat", "repeat-x");
                $("#mframe").css("background-position", "center");
            }
            
            l1 = r.levels.l1;
            l2 = r.levels.l2;
            l3 = r.levels.l3;
            
            $("#hid").val(r.sum.id);
            input_active();
            
            
        }else{
            alert("No records");
        }
    }, "json");
}

function select_search(){
    $("#pop_search").focus();
    $("#pop_search").val("");
}

function load_items(){
    $.post("index.php/main/load_data/m_items/item_list", {
        search : $("#pop_search").val(),
        stores : $("#stores option:selected").val(),
        date : $("#date").val()
    }, function(r){
        $("#sr").html(r);
        settings();
    }, "text");
}

function settings(){
    $("#item_list tr").click(function(){
        if($(this).children().eq(0).html() != "&nbsp;"){
            if(check_item_exist($(this).children().eq(0).html())){
                if(parseFloat($(this).children().eq(5).html()) > 0){
                    $("#h_"+scid).val($(this).children().eq(0).html());
                    $("#0_"+scid).val($(this).children().eq(0).html());
                    $("#n_"+scid).val($(this).children().eq(1).html());
                    $("#2_"+scid).val($(this).children().eq(3).html());
                    $("#hc_"+scid).val($(this).children().eq(2).html());
                    
                    if($(this).children().eq(4).html() == 1){
                        $("#1_"+scid).autoNumeric({mDec:2});
                    }else{
                        $("#1_"+scid).autoNumeric({mDec:0});
                    }
                    $("#1_"+scid).removeAttr("disabled"); $("#2_"+scid).removeAttr("disabled"); $("#3_"+scid).removeAttr("disabled");
                    $("#1_"+scid).focus();$("#pop_close").click();
		    
		    load_subitem($(this).children().eq(0).html(),scid);
                }else{
                    alert("Selected item is empty");
                }
            }else{
                alert("Item "+$(this).children().eq(1).html()+" is already added.");
            }
        }else{
            $("#h_"+scid).val("");
            $("#0_"+scid).val("");
            $("#n_"+scid).val("");
            $("#1_"+scid).val(""); $("#2_"+scid).val(""); $("#3_"+scid).val(""); $("#t_"+scid).html("&nbsp;");
            $("#1_"+scid).attr("disabled", "disabled"); $("#2_"+scid).attr("disabled", "disabled"); $("#3_"+scid).attr("disabled", "disabled");
            //set_total();
	    $("#pop_close").click();
        }
    });
}

function check_item_exist(id){
    var v = true;
    $(".item_h").each(function(){
        if($(this).val() == id){
            v = false;
        }
    });
    
    return v;
}

function set_total(){
    var t = tt = ct = ctt = qun = 0; 
    $(".tf").each(function(){
        set_cid($(this).attr("id"));
        ctt = parseFloat($("#hc_"+scid).val());
        qun = parseFloat($("#1_"+scid).val());
        tt = parseFloat($(this).html());
        if(isNaN(tt)){ tt = 0;}if(isNaN(ctt)){ ctt = 0;}if(isNaN(qun)){ qun = 0;}
        t += tt; ct += ctt*qun;
    });
    
    $("#total2").val(m_round(t));
    
    var dis = parseFloat($("#discount").val());
    if(! isNaN(dis)){
        t -= dis;
    }
    
    pro = m_round(((t-ct)/(ct))*100);
    $("#cm").val(pro);
    
    if(mr > pro){
        def = mr - pro;
    }else{
        rmr = 0;
    }
    
    $("#net_amount").val(m_round(t));
    set_cheque_total();
    input_active();
    
    
}

function load_balance(){
    empty_grid();
    var code = $("#customer").val();
    
    if(code != "0"){
        $.post("index.php/main/load_data/m_customer/get_balance", {
            code : code
        }, function(r){
            $("#balance").val(r.balance);
            $("#rm").val(r.margin);
            l1 = r.l1; l2 = r.l2; l3 = r.l3; mr = r.margin;
            input_active();
        }, "json");
    }
}

function load_balance2(){
    empty_grid();
    var code = $("#customer").val();
    if(code != "0"){
      //  loding();
        $.post("index.php/main/load_data/t_sales_ad/balance", {
            code : code
        }, function(r){
            
                var total_balance = 0;
                for(var i=0; i<r.length; i++){
                    $("#p1_"+i).val(r[i].no);
                    $("#p2_"+i).val(r[i].total);
                    $("#p3_"+i).val(r[i].balance);
                                      
                                      
                    
                input_active();
            } //loding();
        }, "json");
    }
}


function load_subitem(id,scid){
   
    $.post("index.php/main/get_subitem_data/t_sales/", {
        id : id
    }, function(r){
            load_items();
            
            for(var i=0; i<r.det.length; i++){
                scid=parseInt(scid)+1;   
                $("#h_"+scid).val(r.det[i].sub_item_code);
                $("#0_"+scid).val(r.det[i].sub_item_code);
		$("#1_"+scid).val(r.det[i].foc);
                $("#n_"+scid).val(r.det[i].description);
                $("#5_"+scid).val(r.det[i].foc);
                $("#2_"+scid).val("0.00");

           }

    }, "json");
}


function validate(){
    var v = false;
    $("input[type='hidden']").each(function(){
        if($(this).val() != "" && $(this).val() != 0){
            v = true;
        }
    });
    
    if(v == false){
        alert("Please use minimum one item.");
    }else if($("#customer").val() == 0){
        alert("Please select customer");
        v = false;
    }else if($("#sales_ref option:selected").val() == 0){
        alert("Please select sales ref");
        v = false;
    }else if($("#stores option:selected").val() == 0){
        alert("Please select stores");
        v = false;
    }else if(check_cheques() == false){
        $("#btnPayments").click();
        v = false;
    }else if(mr > pro){
        alert("Current Profit Margin (CM : "+pro+") Lower Than Request Profited Margin (RM : "+mr+")");
        v = false;
    }
    else if ($("#net_amount").val()==0 || $("#net_amount").val()==" ")
    {
        alert("Please enter minimum one item");
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
    else if($("#advance").val()>0)
    {
         if($("#p1_"+i).val()!='' || $("#p2_"+i).val()!='' || $("#p3_"+i).val()!=''|| $("#p4_"+i).val()!='')
           {
                  if($("#p1_"+i).val()=='')
                     {
                         alert('Please enter receipt number');
                         $("#p1_"+i).focus();
                          v = false;
                     }   
           }
  
    }    
        

    return v;
}

function check_cheques(){
    var v = true;
    
    $(".ttt").each(function(){
        if(!isNaN(parseFloat($(this).val()))){
            set_cid($(this).attr('id'));
            if($("#qbh_"+scid).val() == 0){
                alert("Please select Bank Branch.");
                v = false;
            }else if($("#q1_"+scid).val() == ""){
                alert("Please Enter Correct Cheques No.");
                v = false;
            }else if($("#q2_"+scid).val() == ""){
                alert("Please Enter Correct Cheques Account.");
                v = false;
            }
        }
    });
    
    return v;
}

function save(){
    if($(":radio")[1].checked == false){
	var ctotal = parseFloat($("#credit").val());
	var qtotal = parseFloat($("#cheque").val());
	if(isNaN(ctotal)){ ctotal = 0; }
	if(isNaN(qtotal)){ qtotal = 0; }
	var balance = parseFloat($("#balance").val());
	if(isNaN(balance)){ balance = 0; }
	total = balance+ctotal;
	
	if(! isNaN(inv_t)){ total -= inv_t; }
	var l = "";
	if(total > l1 && (ctotal > 0 || qtotal > 0)){
	    if(total >= l1 && total < l2){
		l = "Level 02"; lno = 2;
	    }else if(total >= l2 && total < l3){
		l = "Level 03"; lno = 3;
	    }
	    
	    if(total < l3){
		load_online_users();
		$("#perid").html(l);
		$("#permission").center();
		$("#blocker").css("display", "block");
		$("#btnRequest, #btnRefresh, #btnCancel").removeAttr("disabled", "disabled");
	    }else{
		alert("Cann't make credit invoice for this customer.");
	    }
	}else{
	    $("#form_").submit();
	}
    }else{
	$("#btnPayments").click();
    }
}

function set_save_with_pay(){
    
     $(".dis,.dis_pre").removeAttr("disabled","disabled");
    
    var ctotal = parseFloat($("#credit").val());
    var qtotal = parseFloat($("#cheque").val());
    if(isNaN(ctotal)){ ctotal = 0; }
    if(isNaN(qtotal)){ qtotal = 0; }
    var balance = parseFloat($("#balance").val());
    if(isNaN(balance)){ balance = 0; }
    total = balance+ctotal;
    
    if(! isNaN(inv_t)){ total -= inv_t; }
    var l = "";
    if(total > l1 && (ctotal > 0 || qtotal > 0)){
	if(total >= l1 && total < l2){
	    l = "Level 02"; lno = 2;
	}else if(total >= l2 && total < l3){
	    l = "Level 03"; lno = 3;
	}
	
	if(total < l3){
	    load_online_users();
	    $("#perid").html(l);
	    $("#permission").center();
	    $("#blocker").css("display", "block");
	    $("#btnRequest, #btnRefresh, #btnCancel").removeAttr("disabled", "disabled");
	}else{
	    alert("Cann't make credit invoice for this customer.");
	}
    }else{
	$("#form_").submit();
    }
}

function load_online_users(){
    $.post("index.php/main/load_data/loder/get_online_users", {
        user_level : lno
    }, function(r){
        if(r.length == 0){
            $("#online_users").html("<span style='color : red;'>No Online Users.</span>")
        }else{
            var txt = "<table style='width: 100%;'><tr>"; var x = 0;
            for(var i=0; i<r.length; i++){
                txt += "<td><input type='checkbox' id='"+r[i].code+"' /> "+r[i].discription+"<td>";
                
                if(x > 2){
                    txt += "</tr><tr>";
                    x = 0;
                }else{
                    x++;
                }
            }
            txt += "</tr></table>";
            
            $("#online_users").html(txt);
        }
    }, "json");
}

function send_request(){
    var users = new Array(); var i = 0;
    $("input[type='checkbox']").each(function(){
        if($(this).attr("checked") == "checked"){
            users[i] = $(this).attr("id"); i++;
        }
    });
    if(i > 0){
        $.post("index.php/main/load_data/loder/save_request", {
            users : users,
            customer : $("#customer").val(),
            l1 : l1,
            l2 : l2,
            l3 : l3,
            total : total
        }, function(r){
            if(r.lid != undefined){
                $("#massage").css("color", "blue");
                $("#massage").html("Successfully Send Permission Request. <br /> Request ID: "+r.lid+"</br ></br >Wait For Get Permission...");
                $("#request_body").css("text-align", "center");
                $("#request_body").css("font-size", "24px");
                $("#request_body").css("padding", "24px");
                $("#request_body").css("font-weight", "bold");
                request_id = r.lid;
                check_permission_respons(); 
            }else{
                alert("Error");
            }
        }, "json");
    }
}

function check_permission_respons(){
    $("#request_body").html(count);
    $("#btnRequest, #btnRefresh").attr("disabled", "disabled");
    $.post("index.php/main/load_data/loder/cheque_permission", {
        request_id : request_id
    }, function(r){
        if(r.conform == 0){
            if(count > 0){
                count -= 2;
                setTimeout("check_permission_respons()", 2000);
            }else{
                if(confirm("Are you continue waiting ?")){
                    count = 100;
                    setTimeout("check_permission_respons()", 2000);
                }else{
                    $("#btnCancel").click();
                }
            }
        }else if(r.conform == 1){
            alert("Request Conform By "+r.discription);
            $("#form_").submit();
        }else if(r.conform == 2){
            alert("Request Reject By "+r.discription);
            $("#btnReset").click();
        }
    }, "json");
}