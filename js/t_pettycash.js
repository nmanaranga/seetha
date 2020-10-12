var storse = 0;var is_edit=0;
$(document).ready(function(){
    
   // default_load_acc();
load_pettycash_account();
     $("#btnPrint").click(function () {
        if ($("#hid").val() == "0") {
            alert("Please load data before print");
            return false;
        }
        if ($("#qno").val() == "0") {
            alert("Please load data before print");
            return false;
        }
        if ($("#pblt").val() == "0") {
            alert("Please load data before print");
            return false;
        }
        else
        {
            $("#print_pdf").submit();
        }
    });

    load_accounts();
    
    $("#tgrid").tableScroll({height:280});
    
    $(".fo").keydown(function(e){
        set_cid($(this).attr("id"));
        if(e.keyCode==112){
            set_cid($(this).attr("id"));
            $("#serch_pop").center();
            $("#blocker").css("display", "block");
            setTimeout("select_search()", 100);
            load_accounts();  
        }

        if(e.keyCode==13){
            $.post("index.php/main/load_data/t_pettycash/get_account", {
                code:$("#0_"+scid).val()
            }, function(res){
                if(res.a!=2){
                  $("#0_"+scid).val(res.a[0].code);
                  if(check_item_exist($("#0_"+scid).val())){

                    $("#h_"+scid).val(res.a[0].code);
                    $("#0_"+scid).val(res.a[0].code);
                    $("#n_"+scid).val(res.a[0].description);
                   
                    $("#1_"+scid).focus();
                  }else{
                    alert("Account "+$("#0_"+scid).val()+" is already added.");
                  }
                }else{
                  set_msg($("#0_"+scid).val()+" Account not available in account list","error");
                  $("#0_"+scid).val("");
                }
            }, "json");
        }

        if(e.keyCode==46){
            $("#h_"+scid).val("");
            $("#0_"+scid).val("");
            $("#n_"+scid).val("");
            $("#1_"+scid).val("");
            $("#2_"+scid).val("");
        }
    });
    
    load_pettycash_account();
        
    $("#btnSave1").click(function(){
      if(validate())
        {
            check_permission();
        }
    });
        
    $("#pop_search").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { load_accounts();}
    });
    
    $("#pop_search").gselect();
        
    $(".cr").keyup(function(){
        set_cid($(this).attr("id"));
        set_cr_total();
    });

     $("#saccount").autocomplete('index.php/main/load_data/t_pettycash/auto_com_pettycash', {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatjtype,
        formatResult: formatjtypeResult
    });
    
    $("#saccount").blur(function(){
    set_acc_values($(this));
    });


    
    $("#id").keydown(function(e){
        if(e.keyCode == 13){
            $(this).blur();
            load_data($(this).val());
        }
    });
        
    $("#btnDelete1").click(function(){
        if($("#hid").val()>0)
        {    
         check_delete_permission();
        }
        else
        {
            alert("Please load a record");
        }
    });
    
    $("#btnDelete1, #btnSave1, #btnPrint").removeAttr("disabled");

    $("#sales_category").change(function() {
       get_group();
    });


    $("#saccount").keydown(function(e){
        /*if(e.keyCode == 112){
            $("#pop_search11").val($("#saccount").val());
            load_data9();
            $("#serch_pop11").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search11').focus()", 100);
        }

       $("#pop_search11").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                 load_data9();
            }
        }); */

        if(e.keyCode == 46){
            $("#saccount").val("");
            $("#account").val("");
            $("#account_des").val("");
        }
    });

  
});

function load_data9(){
    $.post("index.php/main/load_data/utility/f1_selection_list", {
        data_tbl:"m_account",
        field:"code",
        field2:"description",
        preview2:"Account Name",
        search : $("#pop_search11").val() 
    }, function(r){
        $("#sr11").html(r);
        settings9();            
    }, "text");
}

function settings9(){
    $("#item_list .cl").click(function(){        
        $("#saccount").val($(this).children().eq(0).html());
        $("#account").val($(this).children().eq(0).html());
        $("#account_des").val($(this).children().eq(1).html());
        $("#pop_close11").click();                
    })    
}


function default_load_acc(){
    $.post("index.php/main/load_data/t_pettycash/default_load_acc", {       
    }, function(r){
        if(r!="2"){
            $("#saccount").val(r[0].code);
            $("#account").val(r[0].code);
            $("#account_des").val(r[0].description);
        }
        //alert(r[0].code);            
    }, "json");
}




function load_pettycash_account()
{
    $.post("index.php/main/load_data/t_pettycash/load_pettycash_account",{
          
    },function(r)    
    {    
            $("#saccount").val(r.sum.acc_code);
            $("#account").val(r.sum.acc_code);
            $("#account_des").val(r.sum.description); 
        
    },"json");
    
}

function formatjtype(row){
    return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
}

function formatjtypeResult(row){
    return row[0]+"~"+row[1];
}

function set_acc_values(f){
    var v = f.val();
    v = v.split("~");
    if(v.length == 2){
    f.val(v[0]);
    $("#saccount").val(v[0]);
    $("#account").val(v[0]);
    $("#account_des").val(v[1]);
    //load_je_type(v[0]);
    }
}

function check_delete_permission(){
  set_delete();
}

function check_permission(){
     save();
}



/*$(document).keydown(function(e){
    if(e.keyCode == 112){
        $("#0_0").focus();
    }
});*/



function set_delete(){
    var id = $("#hid").val();
    if(id != 0){
        if(confirm("Are you sure ? ")){
            $.post("index.php/main/delete/t_pettycash", {
                id : id
            }, function(r){
                if(r != 1){
                    set_msg(r,"error");
                }else{
                    delete_msg();
                }
            }, "text");
        }
    }else{
        alert("Please load record");
    }
}

function empty_grid(){
    for(var i=0; i<$("#grid_row").val(); i++){
        $("#h_"+i).val(0);
        $("#0_"+i).val("");
        $("#n_"+i).val("");
        $("#1_"+i).val("");
        $("#2_"+i).val("");
        $("#3_"+i).val("");
    }
}

function load_data(id){
    empty_grid();
    $.post("index.php/main/get_data/t_pettycash/", {
        id : id
    }, function(r){
        if(r.sum.no != undefined){

            $("#date").val(r.sum.date);
	        $("#description").val(r.sum.pdes);
            $("#ref_no").val(r.sum.ref_no);
            $("#narration").val(r.sum.narration);
            $("#saccount").val(r.sum.pettycash_account);
            $("#account").val(r.sum.pettycash_account);
            $("#account_des").val(r.sum.pettycase_des); 
            $("#groups").val(r.sum.group_sales_id); 
            $("#sales_category").val(r.sum.category_id); 
            $("#po_dt").val(r.sum.date);

            
            load_accounts();
            
            for(var i=0; i<r.det.length; i++){
                $("#h_"+i).val(r.det[i].account_code);
                $("#0_"+i).val(r.det[i].account_code);
                $("#n_"+i).val(r.det[i].acc_des);
                $("#1_"+i).val(r.det[i].amount);
                $("#2_"+i).val(r.det[i].description);
            }
            set_cr_total();
            if(r.sum.is_cancel > 0){
                set_msg("This record canceled.","error");
                
                $("#btnDelete1").attr("disabled", "disabled");
                $("#btnSave1").attr("disabled", "disabled");
                $("#btnPrint").attr("disabled", "disabled");
                $("#mframe").css("background-image", "url('img/cancel.png')");
                //$("#mframe").css("background-repeat", "repeat-x");
                //$("#mframe").css("background-position", "center");
            }
            is_edit=1;
            $("#hid").val(r.sum.no);
            input_active();
            $("#hid").val(r.sum.no);
            $("#qno").val(r.sum.no);
            $("#pblt").val(r.sum.receivable_type);
            
            
        }else{
            alert("No records");
        }
    }, "json");
}

function select_search(){
    $("#pop_search").focus();
    $("#pop_search").val("");
}
function load_accounts(){
    $.post("index.php/main/load_data/m_account/account_list", {
        search : $("#pop_search").val()
       
    }, function(r){
        $("#sr").html(r);
        settings();
    }, "text");
}

function settings(){
    $("#item_list tr").click(function(){
        if($(this).children().eq(0).html() != "&nbsp;"){
            if(check_item_exist($(this).children().eq(0).html())){
                $("#0_"+scid).val($(this).children().eq(0).html());
                $("#h_"+scid).val($(this).children().eq(0).html());
                $("#n_"+scid).val($(this).children().eq(1).html());
                $("#1_"+scid).val('');
                $("#2_"+scid).val('');
                $("#2_"+scid).val($(this).children().eq(7).html());
                $("#1_"+scid).focus();$("#pop_close").click();
            }else{
                alert("Account code "+$(this).children().eq(1).html()+" is already added.");
            }
        }else{
            $("#n_"+scid).val("");
            $("#0_"+scid).val("");
            $("#1_"+scid).val("");
            $("#2_"+scid).val("");
            $("#3_"+scid).val("");
            set_total();$("#pop_close").click();
        }
    });
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

function set_cr_total(){

    var t = tt = 0; 
    $(".cr").each(function(e){
        if($("#0_"+e).val()!=""){
            tt = parseFloat($(this).val());
            if(isNaN(tt)){ tt = 0;}
            t += tt;
        }
    });
    
    $("#total").val(m_round(t));
    $("#price").val(m_round(t));

}

function validate(){
    var v = false;
    $("input[type='hidden']").each(function(){
        if($(this).val() != "" && $(this).val() != 0){
            v = true;
        }
    });
    if($("#sales_category").val()=="0"){
        set_msg("Please select category");
        v = false;
    }else{
        v = true;
    }
//    if(v == false){
//        alert("Please use minimum one item.");
//    }else if($("#description").val() == ''){
//        alert("Please enter description");
//        v = false;
//    }
//    else if($("#account").val() == 0){
//        alert("Please select payable account");
//        v = false;
//    }
//    else if($("#description").val() == ""){
//        alert("Please enter description");
//        v = false;
//    }
//    else if(parseFloat($("#total").val()==0))
//    {
//        alert("Please enter expense amount");
//        v = false;
//    }    
    
    return v;
}

function save()
{
    $("#po_dt").val($("#date").val());
    //$("#jtype").val($("#total").val());
    $("#qno").val($("#id").val());
    $("#price").val($("#total").val());

  
    var frm = $('#form_');
	loding();
	$.ajax({
	    type: frm.attr('method'),
	    url: frm.attr('action'),
	    data: frm.serialize(),
	    success: function (pid) {
                
            loding();
            if(pid==1)
            {    
                //sucess_msg();   
                $("#btnSave1").attr("disabled",true);    
                if(confirm("Save Completed, Do You Want A print?")){
                    if($("#is_prnt").val()==1){
                        $("#print_pdf").submit();
                    }
                    location.href="";
                }else{
                    location.href="";
              }    
	        }else{          
                set_msg(pid,"error");
            } 
        }
	});
        is_edit=0;
}

function closeMsgBox(){
    $(".msgBox").fadeOut(500);
    load_pettycash_account();
    empty_grid();
    input_reset();
    get_max();
    clear(); 
}



function get_max(){
    $.post("/index.php/main/load_data/t_pettycash/get_max", {
    }, function(r){
       $("#id").val(r.max_no);
    }, "json");
}

function get_group(){
    $.post("index.php/main/load_data/r_groups/select_by_category", {
        category_id : $("#sales_category").val()
    }, function(r){
       $("#groups").html(r);
    }, "text");

}
