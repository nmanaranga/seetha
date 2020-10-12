var is_edit=0;
var l1 = l2 = l3 = mr = rmr = def = pro = 0; var inv_t = lno = 0; var online_check; var bb=tt=total = 0;
var request_id = 0; var count = 100; var root; var sroot = sarea = sales_ref = storse = 0; 
$(document).ready(function(){
    enter_setup_trance('tgrid2');
    
    $("#btnSave").removeAttr("disabled","disabled");
    
    $("#root").css("display", "none");
    
    
    $("#tgrid").tableScroll({height:280});
    //$("#tgrid2").tableScroll({height:100});
    //$("#tgrid_new").tableScroll({height:100});
    load_items(); 

    
    $("#pop_search").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { load_items();}
    });
    
    //$("#pop_search").gselect();
    
    $(".amo, .qun, .dis").keyup(function(){
        set_cid($(this).attr("id"));
        set_discount('dis');
        set_sub_total();
    });
    
    $(".dis_pre").keyup(function(){
        set_cid($(this).attr("id"));
        set_discount('pre');
        set_sub_total();
       
    });
    
    $("#id").keydown(function(e){
        if(e.keyCode == 13){
           
	    $(this).blur();
            load_data($(this).val());
        }
    });
    
    $("#scustomers").keydown(function(e){
        if(e.keyCode == 13){
         
         load_advance($(this).val());
	 
        }
    });
    
    $("#advance").change(function(e){
	
	$("#ad_balance").val("");
	$("#amount").val("");
        load_ad_bal($("#advance option:selected ").val());
	
    });
    
   $("#btnSave").click(function(){
	if(validate())
	{    
		check_permission();
		
						
	}
	});
	
	function check_permission()
{

    $.post("index.php/main/load_data/user_permissions/get_permission",{
    
        module_id : '031',
        is_edit   : is_edit
        
    },function(r){
    
    
    
    if(r=='1')
    {
       save();
	  
       
    }    
    else if(r=='2')
    {
        alert("You have no permission to edit this record");
    }
    else
    {
     save();
	 
    }    
});
}
    
   
    
    $("#btnDelete").click(function(){
       // set_delete();
		check_delete_permission();
    });
    
	function check_delete_permission()
    {
    $.post("index.php/main/load_data/user_permissions/get_delete_permission",{
        module_id : '031'
    },function(r){
    
    alert(r);
    
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

  //  $("#btnDelete, #btnSave, #btnReset").removeAttr("disabled");
    
    $("#btnPrint").click(function(){
        var id = $("#hid").val()
        if(id != 0){
            window.open('index.php/prints/trance_forms/t_sales/?id='+id);
        }else{
            alert("Please load record. Or save first.");
        }
    });
    
    $("#btnPrint2").click(function(){
        var id = $("#hid").val()
        if(id != 0){
            window.open('index.php/prints/trance/t_sales/?id='+id);
        }else{
            alert("Please load record. Or save first.");
        }
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
	//set_credit_total();
    });
    
    $(".ppp").keyup(function(){
	set_advance_total();
	//set_credit_total();
    });
    
    $("#amt").keyup(function(){
	
	//set_credit_total();
    });
    
    $("#cash").keyup(function(){
        set_cheque_total();
    });
    
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
    
    //$("#btnSavePay").click(function(){
    //    set_save_with_pay();
    //});
    
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
    
    $("#cash_amount").keyup(function(){

    calculate_total();    

    });
    
    $("#cheque,.ttt").keyup(function(){

        calculate_total();    

    });
    
    $("#scustomers").keydown(function(e){
	if(e.keyCode == 13){
	    set_cus_values($(this));
	}
    });
    
       function calculate_total()
    {

        
        if(isNaN(tt)){
            tt = 0;
        }
        else  if(isNaN(bb)){
            bb=0;
        }
        else
        {
            if(parseFloat($("#total").val())>parseFloat($("#amount").val()) || parseFloat($("#total").val())<0)
            {
                alert("Total amount should less than advance amount");
                $("#total,#cash_amount,#cheque").val('0');
                input_active();
                
            }
            else
            {    
            tt=parseFloat($("#cash_amount").val())+parseFloat($("#cheque").val());
            bb=parseFloat($("#amount").val())-(parseFloat($("#cash_amount").val())+parseFloat($("#cheque").val()));
            $("#total").val(parseFloat(tt));
            $("#ad_balance").val(parseFloat(bb));
            input_active();
            }
        }    
    
        
    }
    
    
    
    $(".account_no, .cheque_no").blur(function(){
	check_cheque_exist($(this).attr("id"));
    });
    

});


 function load_advance($id){
        $.post("index.php/main/load_data/t_ad_refund/select", {
	    cus:$id
        }, function(res){
            $("#advance").html(res);
            }, "text");
    }
    
    function load_ad_bal($id){
        $.post("index.php/main/load_data/t_ad_refund/select2", {
	    no:$id
        }, function(res){
	    
            $("#amount").val(res.ad['AdvanceBalance']);
	    $("#ad_balance").val(res.ad['AdvanceBalance']);
	    input_active();
            }, "json");
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
        area : $("#area option:selected").val(),
	customer: $("scustomers").val
    }, function(r){
        $("#root_area").css("display", "none");
        $("#blocker").css("display", "none");
        location.reload();
    }, "text");
}

//function set_root(){
//    var txt = "<option value='0'>---</option>";
//    var area = $("#area option:selected").val();
//    
//    if(root[area] != undefined){
//        for(var i=0; i<root[area].length; i++){
//            txt += "<option value='"+root[area][i][0]+"'>"+root[area][i][1]+"</option>";
//        }
//    }
//    
//    $("#route").html(txt);
//}
//
//function get_route_list(){
//    $.post("index.php/main/load_data/m_root/select_area_wise", {
//        
//    }, function(r){
//        root = r;
//    }, "json");
//}

function set_cheque_total(){
    var t = ttt = 0;
    $(".ttt").each(function(){
	t = parseFloat($(this).val());
	if(! isNaN(t)){
	    ttt += t;
	}
    });
    
//    var ct = parseFloat($("#cash").val());
//    if(isNaN(ct)){
//	ct = 0;
//        
//    }


    var ct = parseFloat($("#net_amount").val());
    if(isNaN(ct)){
	ct = 0;
    }
    
    $("#cash").val(ct);
    
    
    $("#cheque").val(ttt);
    ttt += ct;
    
    //$("#credit").val(crt - ttt);
    
    input_active();
}

function set_advance_total(){
    var t = ttt = 0;
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
    
    
    $("#advance").val(ttt);
    ttt += ct;
    
       
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


//    var k=t-$("#credit").val();
//    
//    $(".ppp").each(function(){
//	t = parseFloat($(this).val());
//	if(! isNaN(t)){
//	    ttt += t;
//	}
//    });
           
    input_active();
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
	$.post("index.php/main/load_data/t_sales/check_cheque_no", {
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
            $.post("index.php/main/delete/t_ad_refund", {
                
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
    
    set_total();
}

function load_data(id){
    empty_grid();
    $.post("index.php/main/get_data/t_ad_refund/", {
        id : id
    }, function(r){
        if(r.sum.id != undefined){
            $("#date").val(r.sum.date);
            $("#customer").val(r.sum.customer);
            $("#scustomers").val(r.sum.customer);
	    $("#cus_des").val(r.sum.name);
            $("#ref_no").val(r.sum.ref_no);
	    $("#id").attr("readonly", "readonly");
            $("#ad_balance").val(r.sum.balance);
            $("#amount").val(r.sum.amount);
            $("#ad").html(r.inv);

            $("#cash_amount").val(r.sum.cash);
            $("#cheque").val(r.sum.cheque);
            $("#total").val(parseFloat(r.sum.cash)+parseFloat(r.sum.cheque));
            
            $("#ad").html(r.inv);
            $("#ad").val(r.sum.advance_pay_no);
            
            input_active()
            
             for(var i=0; i<r.cheque.length; i++){
                $("#qbbh1_"+i).val(r.cheque[i].b_bank);
                $("#q0_"+i).val(r.cheque[i].bank_name);
                $("#qn1_"+i).val(r.cheque[i].description);
                $("#q41_"+i).val(r.cheque[i].r_date);
                $("#q11_"+i).val(r.cheque[i].cheque_no);
                $("#q21_"+i).val(r.cheque[i].acc_no);
                $("#q31_"+i).val(r.cheque[i].cheque_amount);
            }
            
            if(r.sum.is_cancel > 0){
                alert("This record canceled.");
                $("#btnDelete").attr("disabled", "disabled");
                $("#btnSave").attr("disabled", "disabled");
                $("#mframe").css("background-image", "url('img/cancel.png')");
                $("#mframe").css("background-repeat", "repeat-x");
                $("#mframe").css("background-position", "center");
            }
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
                    
                    load_subitem($(this).children().eq(0).html(),scid)
                    
                    
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
            set_total();$("#pop_close").click();
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
    //load_subitem();
    
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
                $("#n_"+scid).val(r.det[i].description);
                $("#5_"+scid).val(r.det[i].foc);
                $("#2_"+scid).val(r.det[i].cost_price);

           }

    }, "json");
}

function set_auto_fill(){
    if($("#auto_fill").attr("checked") == undefined){
        $(".tamo").removeAttr("readonly");
        $("#total").attr("readonly", "readonly");
        auto_fill = false;
    }else{
        $("#total").removeAttr("readonly");
        $(".tamo").attr("readonly", "readonly");
        auto_fill = true; auto_filling();
    }
}

function auto_filling(){
    var total = parseFloat($("#total").val());
    if(isNaN(total)){ total = 0; }
    var t = 0;
    $(".tamo").each(function(){
        t = parseFloat($(this).parent().parent().children().eq(2).children().val());
        
        if(total > 0){
            if(! isNaN(t)){
                if(t <= total){
                    $(this).val(t);
                    total -= t;
                }else if(t > total){
                    $(this).val(total);
                    total = 0;
                }
            }
        }else{
            $(this).val("");
        }
    });
    
    input_active();
}

//function load_balance(){
//    empty_grid();
//    var code = $("#customer").val();
//    if(code != "0"){
//        loding();
//        $.post("index.php/main/load_data/m_customer/get_balance", {
//            code : code
//        }, function(r){
//            if(r.length == 0){
//                alert("No crdited invoice for this customer.");
//                $("#customer").val(0);
//                set_select("customer", "cus_des");
//            }else{
//                var total_balance = 0;
//                for(var i=0; i<r.length; i++){
//                    //$("#p1_"+i).val(r[i].no);
//                    //$("#p2_"+i).val(r[i].total);
//                    //$("#p3_"+i).val(r[i].balance);
//                                      
//                                      
//                    total_balance += parseFloat(r[i].balance);                    
//                }
//                if(auto_fill == true){ auto_filling();}
//                $("#balance").val(total_balance.toFixed(2));
//                input_active();
//            } loding();
//        }, "json");
//    }
//}

function load_balance(){
    empty_grid();
    var code = $("#customer").val();
    if(code != "0"){
        loding();
        $.post("index.php/main/load_data/m_customer/get_balance", {
            code : code
        }, function(r){
            
            $("#balance").val(r.balance);
            //$("#rm").val(r.margin);
            //l1 = r.l1; l2 = r.l2; l3 = r.l3; mr = r.margin;
            input_active();
        
        }, "json");
    }
}



function load_balance2(){
    empty_grid();
    var code = $("#customer").val();
    if(code != "0"){
        loding();
        $.post("index.php/main/load_data/t_sales_ad/balance", {
            code : code
        }, function(r){
            //if(r.length == 0){
            //    alert("No invoice for this customer.");
            //    $("#customer").val(0);
            //    set_select("customer", "cus_des");
            //}else{
                var total_balance = 0;
                for(var i=0; i<r.length; i++){
                    $("#p1_"+i).val(r[i].no);
                    $("#p2_"+i).val(r[i].total);
                    $("#p3_"+i).val(r[i].balance);
                                      
                                      
                    //total_balance += parseFloat(r[i].balance);                    
                //}
                //if(auto_fill == true){ auto_filling();}
                //$("#balance").val(total_balance.toFixed(2));
                input_active();
            } //loding();
        }, "json");
    }
}

function validate(){
    var v = false;
   $("input[type='hidden']").each(function(){
        if($(this).val() != "" && $(this).val() != 0){
            v = true;
        }
    });
    
    if($("#cheque").val()>0)
    {
        
        
        for(var i=0;i<=10;i++)
            {
                 if($("#qn1_"+i).val()!='' || $("#q11_"+i).val()!=''|| $("#q21_"+i).val()!=''|| $("#q31_"+i).val()!=''|| $("#q41_"+i).val()!='')
                {
                 if($("#qbbh1_"+i).val()=='' || $("#qbbh1_"+i).val()=='0')
                     {
                         alert("Please select a Bank");
                         $("#qbbh1_"+i).focus();
                          v = false;
                     }     
                     else if($("#q11_"+i).val()==='')
                     {
                         alert("Please enter valid cheque number");
                         $("#q11_"+i).focus();
                          v = false;
                     }     
                     else if($("#q21_"+i).val()==='')
                     {
                         alert("Please enter Account number");
                         $("#q21_"+i).focus();
                          v = false;
                     }     
                     else if($("#q41_"+i).val()==='')
                     {
                         alert("Please enter Realized Date");
                         $("#q41_"+i).focus();
                          v = false;
                     }  
                     else if($("#q31_"+i).val()==='')
                     {
                         alert("Please enter Amount");
                         $("#q31_"+i).focus();
                          v = false;
                     }  
                     
                }
            } 
            } 
        
   else if($("#total").val() <= '0' || $("#total").val() === '' || $("#total").val() === '0' || $("#total").val() == $("#total").attr("title")){
        alert("Please enter cash or cheque amount");
        v = false;
    }
    else if($("#customer").val() == 0){
        alert("Please select customer");
        v = false;
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
    $("#form_").submit();
	var is_edit=0;
}

function set_save_with_pay(){
    var ctotal = parseFloat($("#credit").val());
    var qtotal = parseFloat($("#cheque").val());
    if(isNaN(ctotal)){ ctotal = 0; }
    if(isNaN(qtotal)){ qtotal = 0; }
    var balance = parseFloat($("#balance").val());
    if(isNaN(balance)){ balance = 0; }
    total = balance+ctotal;
    
    if(! isNaN(inv_t)){ total -= inv_t; }
    var l = "";
//    if(total > l1 && (ctotal > 0 || qtotal > 0)){
//	if(total >= l1 && total < l2){
//	    l = "Level 02"; lno = 2;
//	}else if(total >= l2 && total < l3){
//	    l = "Level 03"; lno = 3;
//	}
//	
//	if(total < l3){
//	    load_online_users();
//	    $("#perid").html(l);
//	    $("#permission").center();
//	    $("#blocker").css("display", "block");
//	    $("#btnRequest, #btnRefresh, #btnCancel").removeAttr("disabled", "disabled");
//	}else{
//	    alert("Cann't make credit invoice for this customer.");
//	}
//    }else{
	$("#form_").submit();
    //}
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