$(document).ready(function () {

var p_code = window.location.search.split('=')[2];
if(p_code != undefined){
   load_data(p_code);
}
    $("#btnApprove").attr("disabled",true);
    
    $(".fo").dblclick(function(){
      set_cid($(this).attr("id"));  
      if($(this).val()!=""){
          $.post("index.php/main/load_data/utility/get_sub_item_detail2", {
              code:$(this).val(),
              store:$("#stores").val(),
              po:$("#pono").val(),
              qty:$("#2_"+scid).val(),
              date:$("#date").val()
          }, function(res){
              if(res!=0){
                  $("#msg_box_inner").html(res);
                  $("#msg_box").slideDown();
              }
          },"text");
         } 
    });


    $(document).on('click','#load_qty',function(){
          $.post("index.php/main/load_data/utility/previous_qty", {
              avg_from:$("#avg_from").val(),
              avg_to:$("#avg_to").val(), 
              item:$("#0_"+scid).val(),             
          }, function(res){
                  $("#grn_qty").val(res.grn);
                  $("#sale_qty").val(res.sales);
          
          },"json");
         
    });


    $(document).on('focus','.input_date_down_future',function(){
        $(".input_date_down_future").datepicker({
        showButtonPanel: false,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        beforeShow: function (input, inst) {
            var offset = $(input).offset();
            var height = $(input).height();
            window.setTimeout(function () {
                inst.dpDiv.css({ top: (offset.top + height + 4) + 'px', left: offset.left + 'px' })
            }, 1);
        }        
        });
    });

    $("#btnApprove").click(function(){
        if(validate()){
           save();    
        }
    });

    $("#btnSave1").click(function(){
        $("#app_status").attr("disabled","disabled");
        if(validate()){
           save();    
        }
    });

    $(".qunsb").click(function(){
        set_cid($(this).attr("id"));
        code = $("#0_"+scid).val();
        check_is_stock_item(code,scid)
    });

	$("#btnReset").click(function(){
        location.href="?action=t_req_sum";
	});

    $("#slide_arrow").click(function(){
        //$("#det_box").slideToggle(); 
    }); 

    $("#code").blur(function () {
        check_code();
    });

    $("#btnDelete").click(function(){
       if($("#hid").val()!=0) {
            set_delete($("#hid").val());
       }
     });

    load_supplier();


	$(document).on("mouseleave", "#btnSavee", function () {
		if($("#b_1").val()==""){
			$("#b_1").val(parseInt("0"));
		}
		if($("#cc_1").val()==""){
			$("#cc_1").val(parseInt("0"));
		}
		if($("#d_1").val()==""){
			$("#d_1").val(parseInt("0"));
		}
		if($("#e_1").val()==""){
			$("#e_1").val(parseInt("0"));
		}
		var a = parseInt($("#b_1").val());
		var b = parseInt($("#cc_1").val());
		var c = parseInt($("#d_1").val());
		var f = a+b;
		g =f-c;
		$("#e_1").val(g);
	 });


    $("#id").keydown(function (e) {
        if (e.keyCode == 13) {
            $(this).blur();
            set_cid($(this).attr("id"));
            load_data($(this).val());
        }
    });


    $(".tf").blur(function () {
        set_cid($(this).attr("id"));
        total(scid);
    });



    $("#price_btn").click(function(){
        //set_cid($(this).attr("id"));
        load_item_price_range($("#0_" + scid).val());
    });







    $("#tgrid").tableScroll({
        height: 300
    });

    $(document).on('keydown', ".supplier_id", function (e) {
        set_cid($(this).attr("id")); 
        if (e.keyCode == 112) {
            load_supplier();
            $("#serch_pop2").center();
            $("#blocker2").css("display", "block");
            setTimeout("select_search2()", 100);
        }
    });
   
    $(document).on('keydown',".supplier_id",function (e) {
        set_cid($(this).attr("id"));
        //alert(scid);
         if (e.keyCode == 46) {
            $("#supp_" + scid).val("");
            $("#supplier_" + scid).val("");
            $("#h_" + scid).val("");
            $("#0_" + scid).val("");
            $("#n_" + scid).val("");
            $("#1_" + scid).val("");
            $("#2_" + scid).val("");
            $("#btnb_"+scid).css("display","none");
            $("#3_" + scid).val("");
            $("#4_" + scid).val("");
            $("#5_" + scid).val("");
            $("#6_" + scid).val("");
            $("#7_" + scid).val("");
            $("#8_" + scid).val("");
            $("#9_" + scid).val("");
            $("#c_" + scid).val("");
            $("#sup_" + scid).val("");
            $("#roq_" + scid).val("");
           
            $("#t_" + scid).html("&nbsp;");
            //$(".tf").val("");
            total();
        }

     });

    $(document).on('keydown',".fo",function (e) {
        set_cid($(this).attr("id"));
        if (e.keyCode == 112) {
            $("#pop_search_find1").val("");
            $("#pop_search_find2").val("");
            $("#pop_search_find").val($("#0_" + scid).val());
            load_items($("#supp_"+scid).val());
            $("#serch_pop_find").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search_find').focus()", 100);
        }

            if(e.keyCode==13){
              $.post("index.php/main/load_data/t_req_sum/get_item", {
                  code:$("#0_"+scid).val(),
                  supplier:$("#supplier_id_"+scid).val()
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
                      $("#1_" + scid).val(res.a[0].model); 
                      $("#2_"+scid).val(res.a[0].qty);
                      $("#3_" + scid).val(res.a[0].rol);
                      $("#roq_" + scid).val(res.a[0].roq);
                      $("#4_"+scid).focus();

                    }else{
                      set_msg("Item "+$("#0_"+scid).val()+" is already added.");
                    }
                  }else{
                    set_msg($("#0_"+scid).val()+" Item not available in list","error");
                    $("#0_"+scid).val("");
                  }
                }, "json");
            }

        if (e.keyCode == 46) {
            $("#h_" + scid).val("");
            $("#0_" + scid).val("");
            $("#n_" + scid).val("");
            $("#1_" + scid).val("");
            $("#2_" + scid).val("");
            $("#btnb_"+scid).css("display","none");
            $("#3_" + scid).val("");
            $("#4_" + scid).val("");
            $("#5_" + scid).val("");
            $("#6_" + scid).val("");
            $("#7_" + scid).val("");
            $("#8_" + scid).val("");
            $("#9_" + scid).val("");
            $("#c_" + scid).val("");
            $("#sup_" + scid).val("");
            $("#roq_" + scid).val("");
           
            $("#t_" + scid).html("&nbsp;");
           // $(".tf").val("");
            total();
        }
    });

    $("#pop_search_find").keyup(function (e) {
        if (e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) {
            $("#pop_search_find1").val("");
            $("#pop_search_find2").val("");
            
            load_items($("#supp_"+scid).val());
        }
    });

    $("#pop_search2").keyup(function (e) {
        if (e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) {
            load_supplier();
        }
    });
    $("#pop_search").gselect();
    $("#pop_search2").gselect();
});


function load_item_price_range(sup){
    var type ="0";
    if($("#cost_chk").is(':checked')){
        type="1";
    }else if($("#min_chk").is(':checked')){
        type="2";
    }else if($("#max_chk").is(':checked')){
        type="3";
    }

    if(type=="0"){
        set_msg("Please select price type");
    }else{
        $.post("index.php/main/load_data/t_req_sum/item_list_all_range", {
            supplier: sup,
            category: $("#main_category").val(),
            type:type,
            price_from:$("#pop_search_find1").val(),
            price_to:$("#pop_search_find2").val(),
            search:""
        }, function (r) {
            $("#sr_find").html(r);
            settings();
        }, "text");
    }
}

function select_search() {
    $("#pop_search").focus();
}


function select_search2() {
    $("#pop_search2").focus();
}
function load_items(sup) {
    var item = $("input:checked").val();
    
    $.post("index.php/main/load_data/t_req_sum/item_list_all", {
        search: $("#pop_search_find").val(),
        supplier: sup,
        category: $("#main_category").val(),
        item: item
    }, function (r) {
        $("#sr_find").html(r);
        settings();

    }, "text");
}


function settings2(){
    $("#item_list .cl").click(function(){
        $("#supp_"+scid).val($(this).children().eq(0).html());
        $("#supplier_"+scid).val($(this).children().eq(1).html());

        $("#pop_close2").click();
    });
}


function settings() {
    $("#item_list .cl").click(function () {
        if ($(this).children().eq(0).html() != "&nbsp;") {
            if (check_item_exist($(this).children().eq(0).html())) {

                //check_is_stock_item($(this).children().eq(0).html(),scid);
                $("#h_" + scid).val($(this).children().eq(0).html());
                $("#0_" + scid).val($(this).children().eq(0).html());
                $("#n_" + scid).val($(this).children().eq(1).html());
                $("#1_" + scid).val($(this).children().eq(2).html());

                $("#lp_" + scid).val($(this).children().eq(4).html());
                $("#sp_" + scid).val($(this).children().eq(5).html());

                $("#btnb_"+scid).css("display","block");
                $("#2_" + scid).val($(this).children().eq(6).html());
                $("#3_" + scid).val($(this).children().eq(7).html());
                $("#roq_" + scid).val($(this).children().eq(8).html());
                $("#4_" + scid).focus();
                $("#pop_close").click();
                $("#pop_close_find").click();

               
            } else {
                set_msg("Item " + $(this).children().eq(1).html() + " is already added.");
            }
        } else {
            $("#h_" + scid).val("");
            $("#0_" + scid).val("");
            $("#n_" + scid).val("");
            $("#1_" + scid).val("");
            $("#2_" + scid).val("");
            $("#3_" + scid).val("");
            $("#t_" + scid).html("&nbsp;");
            //$("#1_" + scid).attr("disabled", "disabled");
            //$("#2_" + scid).attr("disabled", "disabled");
            //$("#3_" + scid).attr("disabled", "disabled");
            $("#0_" + scid).focus();
            total(scid);
            $("#pop_close").click();
        }
    });
}

function check_item_exist(id) {
    var v = true;
    $("input[type='hidden']").each(function () {
        if ($(this).val() == id) {
            v = false;
        }
    });

    return v;
}


function load_supplier(){
    $.post("index.php/main/load_data/utility/f1_selection_list_supplier", {
        search : $("#pop_search2").val(),
        field2 :'name',
        data_tbl:'m_supplier',
        add_query:" AND is_inactive='0' AND is_blacklist='0' "
    }, function(r){
        $("#sr2").html(r);
        settings2();
    }, "text"); 
}


function save() {
    var frm = $('#form_');
    loding();
    $.ajax({
        type: frm.attr('method'),
        url: frm.attr('action'),
        data: frm.serialize(),
        success: function (pid) {

            if (pid == 1) {
                $("#btnSave").attr("disabled",true);
                loding();
                sucess_msg();
            } else if (pid == 2) {
                set_msg("No permission to add data.");
            } else if (pid == 3) {
                set_msg("No permission to edit data.");
            } else {
                set_msg(pid);
            }
            
        }
    });
}




function check_code() {
    loding();
    var code = $("#code").val();
    $.post("index.php/main/load_data/sd/check_code", {
        code: code
    }, function (res) {
        if (res == 1) {
            if (confirm("This code (" + code + ") already added. \n\n Do you need edit it?")) {
                set_edit(code);
            } else {
                $("#code").val('');
                $("#code").attr("readonly", false);
            }
        }
        loding();
    }, "text");
}

function validate() {
    if($("#type").val()=="0"){
        set_msg("Please select type");
        return false;
    }
    for(x=0;x<25;x++){
        if($("#0_"+x).val()!=""){
            $("#sup_"+x).val($("#supplier_id").val());
            if($("#supp_"+x).val()==""){
                set_msg("Enter supplier code");
                return false;
            }
        } 
        //alert($("#8_"+x).val() +"&&"+ $("#0_"+x).val());
        if(($("#8_"+x).val()=="0" || $("#8_"+x).val()=="")&& $("#0_"+x).val()!=""){
            set_msg("Request quantity shouldn't be 0");
            return false;
        }
    }

    return true;
}

function set_delete(id) {
    if (confirm("Are you sure cancel no " + id + "?")) {
        loding();
        $.post("index.php/main/delete/t_req_sum", {
            id: id
        }, function (res) {
            if (res == 1) {
                loding();
                delete_msg();
            } else if (res == 2) {
                set_msg("No permission to delete data.");
            } else {
                set_msg("This Requisition cannot update or delete.");
            } 
        }, "text");
    }
}

function is_edit($mod) {
    $.post("index.php/main/is_edit/user_permissions/is_edit", {
        module: $mod

    }, function (r) {
        if (r == 1) {
            $("#btnSave").removeAttr("disabled", "disabled");
        } else {
            $("#btnSave").attr("disabled", "disabled");
        }

    }, "json");


}

function set_edit(code) {
    loding();
    $.post("index.php/main/get_data/t_req_sum", {
        code: code
    }, function (res) {
        $("#code_").val(res.code);
        $("#code").val(res.code);
        $("#code").attr("readonly", true);
        $("#description").val(res.description);

        if (res.is_vehical == 1) {
            $("#is_vehical").attr("checked", "checked");
        } else {
            $("#is_vehical").removeAttr("checked");
        }

        loding();
        input_active();
    }, "json");
}


function empty_grid() {
    $("#mframe1").css("background-image", "none");
    for(var i = 0; i < 25; i++) {
        $("#h_" + i).val("");
        $("#0_" + i).val("");
        $("#n_" + i).val("");
        $("#1_" + i).val("");
        $("#2_" + i).val("");
        $("#3_" + i).val("");
        $("#4_" + i).val("");
        $("#5_" + i).val("");
        $("#6_" + i).val("");
        $("#7_" + i).val("");
        $("#8_" + i).val("");
        $("#9_" + i).val("");
        $("#c_" + i).val("");
        $("#sup_" + i).val("");
        $("#roq_" + i).val("");
        $("#t_" + i).html("&nbsp;");
        $(".tf").val("");
    }
}



function load_data(id) {
    empty_grid();
    loding();
    $.post("index.php/main/get_data/t_req_sum/", {
        id: id
    }, function (r) {
        if (r == "2") {

            set_msg("No records");
        } else {
            $(".qunsb").css("display","none");
            $("#hid").val(id);
            $("#id").val(id);
            $("#branch_id").val(r.det[0].bc);
            $("#branch").val(r.det[0].bname);
            $("#supplier").val(r.det[0].name);
            $("#supplier_id").val(r.det[0].code);
            $("#date").val(r.det[0].ddate);
            $("#comment").val(r.det[0].comment);
            $("#type").val(r.det[0].type);
            $("#btnApprove").attr("disabled",false);
            $("#id").attr("readonly", "readonly")

            for (var i = 0; i < r.det.length; i++) {

                $("#supp_" + i).val(r.det[i].code);
                $("#supplier_" + i).val(r.det[i].name);


                $("#0_" + i).val(r.det[i].item);
                $("#n_" + i).val(r.det[i].description);
                $("#1_" + i).val(r.det[i].model);
                $("#lp_" + i).val(r.det[i].min_price);
                $("#sp_" + i).val(r.det[i].max_price);
                $("#2_" + i).val(r.det[i].cur_qty);
                $("#btnb_"+i).css("display","block");
                $("#3_" + i).val(r.det[i].rol);
                $("#4_" + i).val(r.det[i].week1);
                $("#5_" + i).val(r.det[i].week2);
                $("#6_" + i).val(r.det[i].week3);
                $("#7_" + i).val(r.det[i].week4);
                $("#sup_" + i).val(r.det[i].supplier);
                $("#c_" + i).val(r.det[i].cmnt);
                total(i);

            }

            if(r.det[0].is_level_1_approved==1){
                $("#mframe1").css("background-image", "url('img/approved1.jpg')");
                $("#btnApprove").attr("disabled",true);
                $("#btnSave1").attr("disabled",true);
                $("#btnDelete").attr("disabled",true);
               
            }
     
            if(r.det[0].is_cancel==1) {
                $("#mframe1").css("background-image", "url('img/cancel.png')");
                $("#btnApprove").attr("disabled",true);
                $("#btnSave1").attr("disabled",true);
                $("#btnDelete").attr("disabled",true);
            }
            
            input_active();
        }
        loding();
    }, "json");
}


function total(i) {
    var x = 0;
    var total_cr = 0;
    $(".tf" + i).each(function () {
        if ($(this).val() == "") {
            x = 0;
        } else {
            x = parseFloat($(this).val());
            total_cr += x;
        }
    });
    $("#8_" + i).val(total_cr);
}

function load_data_form(id){
    $("#id,#hid").val(id);
    $("#hid").attr("title",id);
    load_data(id);
    $("#btnSave").attr("disabled","disabled");
    $("#det_box").slideToggle(); 
}

function disable_form(){
    for(x=0;x<25;x++){
        $("#supplier_id_" + x).removeClass('supplier_id');
        $("#supplier_id_" + x).addClass('g_col_fixed');
        $("#supplier_id_" + x).attr('readonly','readonly');
        $("#0_"+x).removeClass('g_input_txt');
        $("#0_"+x).removeClass('fo');
        $("#0_"+x).addClass('g_col_fixed');
        $("#0_"+x).attr('readonly','readonly');
        $("#0_"+x).removeAttr('class');
        $("#0_"+x).attr('class','g_col_fixed');
    }
}


function check_is_stock_item(code,scid){

    var store=$("#stores").val();

    $.post("index.php/main/load_data/t_req_sum/get_stock",{
        code:code,
     },function(res){

        $("#blocker3").css("display", "block");
        $("#serch_pop3").center();
        $("#sr3").html(res);

    },'text');
}
