$(document).ready(function(){
    $("#is_chk").click(function(event) {
        if($("#is_chk").is(':checked')){
            $("#is_check").val('1');
        }else{
            $("#is_check").val('0');
        }
    });
    $("#code").blur(function(){
        check_code();
    });


    $(".chk").click(function(){
        set_cid($(this).attr("id"));
        if($("#sel_"+scid).is(":checked")){
            $("#sel_"+scid).val(1);
            $("#di_"+scid).removeAttr("readonly");
            cal_amodis();
            cal_totdis();
            tot_amt();

        }else{
            $("#sel_"+scid).val(0);
            $("#di_"+scid).prop('readonly','true');
            $("#di_"+scid).val("");

        }
    });
    
    var wh=$("#mframe").width()-20;
    $("#tgrid").tableScroll({height:300, width:wh});

    $(".amo").bind('keyup blur' , function() {
        set_cid($(this).attr("id"));
        tot_amt();
    });

    $("#showPayments").click(function(){

        if(!validate_amt()){
            return false;
        }

        payment_opt('JOB_ISSUE',$("#net").val());
        if($("#is_chk").is(":checked")){
            empty_grid3();

        }
        
        $("#cash").val($("#net").val());
        
    });

    $(".dis").keyup(function(){
        set_cid($(this).attr("id"));
        cal_amodis();
        cal_totdis();
        tot_amt();
    });

    $("#btnPrint").click(function(){
        if($("#qno").val()=="0" || $("#qno").val()==""){
            set_msg("Please Load a record");
        }else{
            $("#print_pdf").submit();
        }   
    });
    $("#search").keyup(function(){
        load_services();
    });

    $("#btnDelete").click(function(){
        set_delete();
    });
    
    $("#btnReset").click(function(){
        $("input[type='checkbox']").each(function(){
            $(this).removeAttr("checked");
        });
        $("input[type='button']").removeAttr("disabled");
    });

    $("#id").keydown(function(e){
        if(e.keyCode== 13){
            $("#hid").val($(this).val());
            load_data();
            load_payment_option_data($(this).val(),"102");
            $("#btnSave").attr("disabled","disabled");
        }
    });

    $("#customer").keydown(function(e){
        if(e.keyCode==112){
            $("#pop_search").val();
            select_cus()
            $("#serch_pop").center();
            $("#blocker").css("display","block");
            setTimeout("$('#pop_search').focus()", 100); 
        }
        if(e.keyCode == 46){
            $("#customer").val("");
            $("#cus_name").val("");
        }
        $("#pop_search").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ){ 
                select_cus();
            }
        })
    })
});
function save(){

    $('#form_').attr('action',$('#form_id').val()+"t_job_issue");
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
    $.post("/index.php/main/load_data/t_job_issue/get_data_table", {

    }, function(r){
        $("#grid_body").html(r);
    }, "text");
}


function validate(){

    if($("#customer").val()==""){
        set_msg("Please select a customer ");
        return false;
    }else if(!$(".chk").is(":checked")){
        set_msg("Please Select a job");
        return false;
    }else{
        return true;
    }
}



function select_cus(){
    $.post("index.php/main/load_data/t_job_issue/select_customer",{
        search:$("#pop_search").val()
    },function(r){
        $("#sr").html(r);
        cus_settings();
    },"text");
}
function cus_settings(){
    $("#item_list .cl").click(function(){
        $("#customer").val($(this).children().eq(0).html()); 
        $("#cus_name").val($(this).children().eq(1).html());
        load_services();
        $("#pop_close").click();
    });
}

function load_services(){
    var tot =0;
    $.post("index.php/main/load_data/t_job_issue/load_services",{
        customer:$("#customer").val(),
        search:$("#search").val()
    },function(res){
        empty_grid();
        if(res.a !=2){
            for(var i=0;i<res.a.length;i++){
                $("#0_"+i).val(res.a[i].job_no);
                $("#n_"+i).val(res.a[i].receive_dt);
                $("#1_"+i).val(res.a[i].item_code + " - " + res.a[i].Item_name);
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
                $("#6_"+i).val(res.a[i].sup_amt);
               // var adv_amt = parseFloat(res.a[i].advance_amount);
                /*if(isNaN(adv_amt)){
                    adv_amt=0;
                }
                tot+=adv_amt;*/
            }
           // $("#adv_amount").val(m_round(tot));
       }
   },"json");
}

function tot_amt(){

    var tot =0;
    var bal_amt=0;
    $(".tot").each(function(e){
        if($("#sel_"+e).val()=='1'){
            var amount = parseFloat($("#6_"+e).val());
            
            //var adv_amt =parseFloat($("#adv_amount").val());
            var dis_amt =parseFloat($("#dis_amount").val());
            if(isNaN(amount)){ amount=0; }
            //if(isNaN(adv_amt)){ adv_amt =0; }
            if(isNaN(dis_amt)){ dis_amt =0; }
            tot = parseFloat(tot)+amount;
           // if(tot>adv_amt){
            bal_amt = tot-dis_amt;
            //}
        }
    });
    $("#net").val(m_round(bal_amt));
    $("#amount").val(m_round(tot));
}


function load_data(){
    $.post("index.php/main/load_data/t_job_issue/load",{
        id:$("#hid").val()

    },function(res){
        empty_grid();
        if(res.a==2){
            set_msg("NO records");
        }else{
            if(res.a[0].is_cancel == 1){
                $("#mframe").css("background-image", "url('img/cancel.png')");
                $("#btnDelete").attr("disabled", "disabled");
                $("#btnSave").attr("disabled", "disabled");
            }
            if(res.a[0].is_debit_note == 1){
                $("#is_chk").attr("checked","checked");
            }
            $("#id").attr("readonly",true);
            $("#qno").val($("#id").val());
            $("#is_check").val(res.a[0].is_debit_note);
            $("#is_chk").attr("disabled","disabled");
            $("#customer").val(res.a[0].customer);
            $("#cus_name").val(res.a[0].name);
            $("#comment").val(res.a[0].memo);
            $("#date").val(res.a[0].ddate);
            $("#ref_no").val(res.a[0].ref_no);
            $("#drn").val(res.a[0].drn_no);
            $("#amount").val(res.a[0].amount);
            //$("#adv_amount").val(res.a[0].advance);
            $("#net").val(res.a[0].balance);
            $("#cash").val(res.a[0].pay_cash);
            $("#cheque_recieve").val(res.a[0].pay_receive_chq);
            $("#credit_card").val(res.a[0].pay_ccard);
            $("#credit_note").val(res.a[0].pay_cnote);
            $("#debit_note").val(res.a[0].pay_dnote);
            $("#dis_amount").val(res.a[0].discount);

            $("#id").attr("readonly","readonly");
            for(var i=0;i<res.b.length;i++){
                $("#sel_"+i).attr("checked","checked");
                if($("#sel_"+i).is(":checked")){
                    $("#6_"+i).removeAttr("readonly");
                }
                $("#0_"+i).val(res.b[i].job_no); 
                $("#n_"+i).val(res.b[i].r_date); 
                $("#1_"+i).val(res.b[i].item_code + " - " +res.b[i].Item_name); 
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
                $("#6_"+i).val(res.b[i].price); 
                $("#di_"+i).val(res.b[i].discount); 
                $("#7_"+i).val(res.b[i].amount); 
            }
        }
    },"json");
}
function empty_grid(){
    $("#tgrid input").each(function(){
        $(this).val("");
    });
}

function empty_grid3(){
    $("#tbl1 input[type='text']").each(function(){
        $(this).val("");
    });
}


function set_delete(){
    var id = $("#hid").val();
    if(id!=0){
        if(confirm("Are you sure, You Want to Cancel the record ?")){
            loding();
            $.post("index.php/main/load_data/t_job_issue/delete",{
                id: $("#hid").val()
            }, function(res){
                if(res == 1){
                    reload_form();
                }else if(res == 2){
                    alert("No permission to delete data.");
                }else{
                    alert("Item deleting fail.");
                }
                loding();
            }, "text");
        }
    }else{
        alert("Please Load a record");
    }
}

function reload_form(){
    setTimeout(function(){
        location.href= '';
    },50); 
}


function validate_amt(){

  return true;

}

function cal_amodis(){

    var price=parseFloat($("#6_"+scid).val());
    var dis=parseFloat($("#di_"+scid).val());
    var tot=0;
    if(isNaN(dis)){
        dis=0;
    }

    tot=price-dis;

    $("#7_"+scid).val(tot);
}

function cal_totdis(){

   var tot =0;
   var distot=0;
   $(".dis").each(function(){
    var disc = parseFloat($(this).val());
    if(isNaN(disc)){ disc=0; }
    tot = parseFloat(tot)+disc;
    distot = tot ;
});

   $("#dis_amount").val(distot);

}

