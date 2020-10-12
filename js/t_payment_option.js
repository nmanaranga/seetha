$(document).ready(function(){

    $("#tabs").tabs();


    $("#installment").attr("disabled","disabled");
    $("#installment").addClass("hid_value");
    $("#installment").removeClass("input_txt");
    $("#installment").attr("disabled","disabled");
    $("#installment").val("");

    $(document).on('focus', '#pc', function(){
        if($("#type8_0").val()==""){
            alert("Please enter privilege card");
        }else{
            $("#chck_cus_card").click();   
        } 
    });

    // $(".bank1").autocomplete("index.php/main/load_data/t_payment_option/auto_com_bank", {
    //     width: 350,
    //     multiple: false,
    //     matchContains: true,
    //     formatItem: formatItems,
    //     formatResult: formatItemsResult
    // });
    $(document).on('blur', '.chq9', function(){
        set_cid($(this).attr("id"));
        if($("#bank9_"+scid).val()!="" && $("#acc9_"+scid).val()!="" && $("#cheque9_"+scid).val()!="" ){
            check_cheque_is_duplicate(scid);
        }
    });

    $(document).on('blur', '.chqno7', function(){
        set_cid($(this).attr("id"));
        if($("#bank7_"+scid).val()!="" && $("#chqu7_"+scid).val()!=""){
            check_issue_cheque_is_duplicate(scid);
        }
    });

    $(document).on('keydown', '.bank1', function(e){
        set_cid($(this).attr("id"));
        if(e.keyCode == 112){
            if($("#amount1_"+scid).val()!=""){
                $("#pop_search4").val();
                load_bank();
                $("#serch_pop4").center();
                $("#blocker4").css("display", "block");
                setTimeout("select_search()", 100);                
            }else{
                alert("Please type amount");
            }
        }

        $("#pop_search4").keyup(function(e){            
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
             load_bank();
         }
     }); 

        if(e.keyCode == 46){
            $("#bank1_"+scid).val("");
            $("#1bank1_"+scid).val("");
            $("#month1_"+scid).val("");
            $("#acc1_"+scid).val("");
            $("#rate1_"+scid).val("");          
        }
    });


    $(document).on('keydown', '.chq7', function(e){
        set_cid($(this).attr("id"));
        if(e.keyCode == 112){
            $("#pop_search14").val();
            load_chq_book();
            $("#serch_pop14").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search14').focus()", 100);                
        }

        $("#pop_search14").keyup(function(e){            
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
             load_chq_book();
         }
     }); 

        if(e.keyCode == 46){
            $("#chq7_"+scid).val("");
            $("#bank7_"+scid).val("");
            $("#des7_"+scid).val("");
            $("#chqu7_"+scid).val("");
            $("#amount7_"+scid).val("");    
            $("#cdate7_"+scid).val("");      
        }
    }); 



    $(document).on('keydown', '.chqno7', function(e){
        set_cid($(this).attr("id"));
        if(e.keyCode == 112){
            if($("#chq7_"+scid).val()!=""){
                $("#pop_search11").val();
                load_chqs($("#chq7_"+scid).val());
                $("#serch_pop11").center();
                $("#blocker").css("display", "block");
                setTimeout("$('#pop_search11').focus()", 100);                
            }else{
                set_msg("Please select cheque Book.");
            }
        }

        $("#pop_search11").keyup(function(e){            
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                if($("#chq7_"+scid).val()!=""){
                    load_chqs($("#chq7_"+scid).val());
                }else{
                    set_msg("Please select cheque Book.");
                }
            }
        }); 

        if(e.keyCode == 46){
            $("#chqu7_"+scid).val("");
        }
    }); 


    $(document).on('keydown', '.gft', function(e){
        set_cid($(this).attr("id"));
        if(e.keyCode == 112){
            $("#pop_search15").val($("#type5_"+scid).val());
            load_gift();
            $("#serch_pop15").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search15').focus()", 100);                
        }

        $("#pop_search15").keyup(function(e){            
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
             load_gift();
         }
     }); 

        if(e.keyCode == 46){
            $("#type5_"+scid).val("");
            $("#no5_"+scid).val("");
            $("#amount5_"+scid).val("");
            $("#des5_"+scid).val("");
            $("#gcode5_"+scid).val("");
            $("#amount5_"+scid).blur();
        }
    });


    $(document).on('keydown', '.pdbank9', function(e){
        set_cid($(this).attr("id"));
        if(e.keyCode == 112){
            $("#pop_search12").val($("#type5_"+scid).val());
            load_pd_cheques();
            $("#serch_pop12").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search12').focus()", 100);                
        }

        $("#pop_search12").keyup(function(e){            
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
             load_pd_cheques();
         }
     }); 
        
        if(e.keyCode == 46){
            $("#pdbank9_"+scid).val("");
            $("#pdbranch9_"+scid).val("");
            $("#pdacc9_"+scid).val("");
            $("#pdcheque9_"+scid).val("");
            $("#pdamount9_"+scid).val("");
            $("#pddate9_"+scid).val("");
            total_pd_values();
        }
    });

    $(document).on('keydown', '.bank7', function(e){
        set_cid($(this).attr("id"));
        if(e.keyCode == 112){
            $("#pop_search12").val();
            load_issue_chk();
            $("#serch_pop12").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search12').focus()", 100);                

        }

        $("#pop_search12").keyup(function(e){            
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
             load_issue_chk();
         }
     }); 

        if(e.keyCode == 46){
            $("#bank7_"+scid).val("");
            $("#des7_"+scid).val("");
            $("#chqu7_"+scid).val("");
            $("#amount7_"+scid).val(""); 
            $("#cdate7_"+scid).val("");          
        }
    }); 

    $(document).on('keydown', '.bank9', function(e){
        set_cid($(this).attr("id"));
        if(e.keyCode == 112){
            $("#pop_search13").val();
            load_receive_chk();
            $("#serch_pop13").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search13').focus()", 100);                

        }

        $("#pop_search13").keyup(function(e){            
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
             load_receive_chk();
         }
     }); 
    }); 

    $(document).on('keydown', '.type1', function(e){
        if(e.keyCode==112){
            $("#pop_search10").val();
            load_crd_type();
            $("#serch_pop10").center();
            $("#blocker4").css("display", "block");
            setTimeout("$('#pop_search10').focus()", 100);     
        }
        $("#pop_search10").keyup(function(e){            
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
             load_crd_type();
         }
     }); 
        if(e.keyCode=='46'){
            var id=($(this).attr('id')).split("_")[1];
            
            $("#type1_"+id).val("");
            $("#no1_"+id).val("");
            $("#amount1_"+id).val("");
            $("#1bank1_"+id).val("");
            
            $("#bank1_"+id).val("");   
            $("#month1_"+id).val("");   
            $("#rate1_"+id).val("");
            $("#amount_rate1_"+id).val("");  
            $("#credit_card").val(""); 

            tot_credit_card();       
        }
    });

    $(document).on('click','#ici',function(){
        $("#installment").val($("#net").val());
    });

    $(document).on('keydown', '.bank1', function(a){
        if (13 == a.keyCode) set_cus_values88($(this));
    });

    $(document).on('blur', '.bank1', function(){
        set_cus_values88($(this));
    });


    $(document).on('keyup', '.ci_amount', function(){
        var total=0;
        $(".ci_amount").each(function(e){
            if(!isNaN(parseFloat($("#amount7_"+e).val()))){
                total+=parseFloat($("#amount7_"+e).val());
            }
        });
        $("#cheque_issue").val(m_round(total));
        set_opt_balance();
    });

    $(document).on('keydown', '.type1', function(e){
        if(e.keyCode=='46')
        {
            var id=($(this).attr('id')).split("_")[1];
            
            $("#type1_"+id).val("");
            $("#no1_"+id).val("");
            $("#amount1_"+id).val("");
            $("#1bank1_"+id).val("");
            
            $("#bank1_"+id).val("");   
            $("#month1_"+id).val("");   
            $("#rate1_"+id).val("");
            $("#amount_rate1_"+id).val("");  
            $("#credit_card").val(""); 

            tot_credit_card();       
        }
    });


    $(document).on('keydown', '.gft', function(e){
        if(e.keyCode=='46')
        {
            var id=($(this).attr('id')).split("_")[1];
            
            $("#type5_"+id).val("");
            $("#no5_"+id).val("");
            $("#amount5_"+id).val("");           
        }
    });

    $(document).on('keydown', '.bank7', function(e){
        if(e.keyCode=='46')
        {
            var id=($(this).attr('id')).split("_")[1];
            
            $("#bank7_"+id).val("");
            $("#des7_"+id).val("");
            $("#chqu7_"+id).val("");   
            $("#amount7_"+id).val("");
            $("#cdate7_"+id).val("");         
        }
    });


    $(document).on('keydown', '.bank9', function(e){
        if(e.keyCode=='46')
        {
            var id=($(this).attr('id')).split("_")[1];            
            $("#bank9_"+id).val("");
            $("#branch9_"+id).val("");
            $("#acc9_"+id).val("");   
            $("#cheque9_"+id).val("");
            $("#amount9_"+id).val("");
            $("#date9_"+id).val("");         
        }
    });

    $(document).on('keydown', '.bank1', function(e){
        if(e.keyCode=='46')
        {

            var id=($(this).attr('id')).split("_")[1];
            
            $("#bank1_"+id).val(""); 
            $("#1bank1_"+id).val("");  
            $("#month1_"+id).val("");   
            $("#rate1_"+id).val("");
            $("#amount_rate1_"+id).val("");            
        }
    });



    $(document).on('keyup', '.cr_amount', function(){
        var total=0;
        $(".cr_amount").each(function(e){
            if(!isNaN(parseFloat($("#amount9_"+e).val()))){
                total+=parseFloat($("#amount9_"+e).val());
            }
        });
        $("#cheque_recieve").val(m_round(total));
        set_opt_balance();
    });

    $(document).on('keyup', '.pd_amount', function(){
        total_pd_values();
    });

    $(document).on('keyup', '.cc_amount', function(){
        var total=0;

        //$(".ci_amount").each(function(e){
            $(".cc_amount").each(function(e){
                if(!isNaN(parseFloat($("#amount1_"+e).val()))){
                   total+=parseFloat($("#amount1_"+e).val());
          // credit_card_rate_amount(e);
      }
  });

            $("#credit_card").val(m_round(total));
            set_opt_balance();
        });

    $(document).on('keyup', '.cn_settle', function(){
        var total=0;

        $(".cn_settle").each(function(e){
            if(!isNaN(parseFloat($("#settle2_"+e).val()))){
                total+=parseFloat($("#settle2_"+e).val());
            }
        });
        $("#credit_note").val(m_round(total));
        set_opt_balance();
    });

    $(document).on('keyup', '.dn_settle', function(){
        var total=0;
        $(".dn_settle").each(function(e){
            if(!isNaN(parseFloat($("#settle3_"+e).val()))){
                total+=parseFloat($("#settle3_"+e).val());
            }
        });
        $("#debit_note").val(m_round(total));
        set_opt_balance();
    });

    $(document).on('keyup', '.bd_amount', function(){
        var total=0;
        $(".bd_amount").each(function(e){
            if(!isNaN(parseFloat($("#amount4_"+e).val()))){
                total+=parseFloat($("#amount4_"+e).val());
            }
        });
        $("#bank_debit").val(m_round(total));
        set_opt_balance();
    });

    $(document).on('keyup', '.a_settle', function(){
        var total=0;
        $(".a_settle").each(function(e){
            if(!isNaN(parseFloat($("#cdate6_"+e).val()))){
                total+=parseFloat($("#cdate6_"+e).val());
            }
        });
        $("#advance").val(m_round(total));
        set_opt_balance();
    });

    $(document).on('keyup', '.gv_amount', function(){
        var total=0;
        $(".gv_amount").each(function(e){
            if(!isNaN(parseFloat($("#amount5_"+e).val()))){
                total+=parseFloat($("#amount5_"+e).val());
            }
        });
        $("#gv").val(m_round(total));
        set_opt_balance();
    });

    $(document).on('click', '#btnExit2', function(){
        document.getElementById('light2').style.display='none';
        document.getElementById('fade2').style.display='none'; 
        set_opt_balance(); 
    });


    $(document).on('blur', '.ci_amount', function(){
        var total=0;
        $(".ci_amount").each(function(e){
            if(!isNaN(parseFloat($("#amount7_"+e).val()))){
                total+=parseFloat($("#amount7_"+e).val());
            }
        });
        $("#cheque_issue").val(m_round(total));
        set_opt_balance();
    });

    $(document).on('blur', '.cr_amount', function(){
        var total=0;
        $(".cr_amount").each(function(e){
            if(!isNaN(parseFloat($("#amount9_"+e).val()))){
                total+=parseFloat($("#amount9_"+e).val());
            }
        });
        $("#cheque_recieve").val(m_round(total));
        set_opt_balance();
    });

    $(document).on('blur', '.cc_amount', function(){
        var total=0;
        $(".cc_amount").each(function(e){
            if(!isNaN(parseFloat($("#amount1_"+e).val()))){
                total+=parseFloat($("#amount1_"+e).val());
            }
        });
        $("#credit_card").val(m_round(total));

        set_opt_balance();
    });


    $(document).on('blur', '.cn_settle', function(){
        var total=0;
        $(".cn_settle").each(function(e){
            if(!isNaN(parseFloat($("#settle2_"+e).val()))){
                total+=parseFloat($("#settle2_"+e).val());

            }
        });
        $("#credit_note").val(m_round(total));
        set_opt_balance();
    });


    $(document).on('blur', '.dn_settle', function(){
        var total=0;
        $(".dn_settle").each(function(e){
            if(!isNaN(parseFloat($("#settle3_"+e).val()))){
                total+=parseFloat($("#settle3_"+e).val());
            }
        });
        $("#debit_note").val(m_round(total));
        set_opt_balance();
    });

    $(document).on('blur', '.bd_amount', function(){
        var total=0;
        $(".bd_amount").each(function(e){
            if(!isNaN(parseFloat($("#amount4_"+e).val()))){
                total+=parseFloat($("#amount4_"+e).val());
            }
        });
        $("#bank_debit").val(m_round(total));
        set_opt_balance();
    });


    $(document).on('blur', '.a_settle', function(){
        var total=0;
        $(".a_settle").each(function(e){
            if(!isNaN(parseFloat($("#cdate6_"+e).val()))){
                total+=parseFloat($("#cdate6_"+e).val());
            }
        });
        $("#advance").val(m_round(total));
        set_opt_balance();
    });


    $(document).on('blur', '.gv_amount', function(){
        var total=0;
        $(".gv_amount").each(function(e){
            if(!isNaN(parseFloat($("#amount5_"+e).val()))){
                total+=parseFloat($("#amount5_"+e).val());
            }
        });
        $("#gv").val(m_round(total));
        set_opt_balance();
    });


    // $(document).on('blur', '#installment', function(){
    //     var installment=parseFloat($(this).val());
    //     var ttl_amount=parseFloat($("#ttl_amount").val());
    //     if(isNaN(installment)){installment=0;}
    //     if(isNaN(ttl_amount)){ttl_amount=0;}
    //     $("#down_payment").val(m_round(ttl_amount-installment));
    // });

    $("#cash,#credit,#cheque_issue,#cheque_recieve,#credit_card,#credit_note,#debit_note,#bank_debit,#discount,#advance,#gv,#pc,#installment").change(function(){
        set_opt_balance();
    });

    $("#cash,#credit,#discount,#pc,#installment").keyup(function(){
       set_opt_balance(); 
   });


    $(document).on('blur', '#privi_card', function(){
        if($("#privi_card").val()!=""){
         $.post("index.php/main/load_data/t_payment_option/check_pv_no",{
           privi_card:$("#privi_card").val()
       },function(res){          
        if(res==0){
            alert("Incorrect privilege card");
            $("#privi_card").val("");
            $("#privi_card").focus();
        }else{
            privilege_calculation();
        }
    },"text");
     }
 });




    //$("#showPayments").click(function(){
        $(document).on('click', '#showPayments', function(){

            if($("#hid").val()=="0" || $("#hid").val()=="")
            {
                var pass_code="";

                if($("#customer").val() ==undefined)
                {
                    pass_code=$("#supplier_id").val();
                }
                else
                {
                    pass_code=$("#customer").val();
                }

                $.post("index.php/main/load_data/t_payment_option/load_credit_note",{
               //customer:$("#customer").val()
               customer:pass_code
           },function(res){  
            if($("#transtype").val()!="CREDIT"){        
                $("#tabs-2").html(res);
            }
        },"text");

                $.post("index.php/main/load_data/t_payment_option/load_debit_note",{
               //customer:$("#customer").val(),
               customer:pass_code
           },function(res){ 
            if($("#transtype").val()!="CREDIT"){         
                $("#tabs-3").html(res);
            }
        },"text");
            }
            setTimeout(function(){
                load_cr_total();
                load_dr_total();
            }, 200);

        });

        $(document).on('click', '#tabs-10', function(){
            $("#credit").addClass("hid_value");
            $("#credit").removeClass("input_txt");
            //$("#credit").attr("disabled","disabled");
            $("#credit").val("");  
            $("#ici").prop("checked", true); 
            $("#cic").prop("checked", false); 

            $("#installment").addClass("input_txt");
            $("#installment").removeClass("hid_value");
            $("#installment").attr("disabled","disabled");
            $("#installment").removeAttr("disabled");

        });



        $(document).on('click', '.ci', function(){
            if($(this).val()=="i"){
                $("#credit").addClass("hid_value");
                $("#credit").removeClass("input_txt");
                $("#credit").attr("readonly","readonly");
                $("#credit").val("");

                $("#installment").addClass("input_txt");
                $("#installment").removeClass("hid_value");
                $("#installment").attr("readonly","readonly");
                $("#installment").removeAttr("disabled");

                $("#tabs").tabs("select", "tabs-10" );   


            }else if($(this).val()=="c"){
                $("#credit").addClass("input_txt");
                $("#credit").removeClass("hid_value");
                $("#credit").attr("readonly","readonly");
                $("#credit").removeAttr("disabled");

                $("#installment").addClass("hid_value");
                $("#installment").removeClass("input_txt");
                $("#installment").attr("readonly","readonly");
                $("#installment").val("");

            }
        });


        $('#credit_tab').click(function(){
            load_cr_total();
        });

        $("#credit_note").click(function(){
            load_cr_total();
        });

        $("#debit_note").click(function(){
            load_dr_total();
        });

        $('#debit_tab').click(function(){
            load_dr_total();
        });





        $(document).on('click', '#installment_calc', function(){
            if($("#down_payment").val()==""){$("#down_payment").val(0);}
            var get_downpay_date=$("#date").val();
            var total_amount=parseFloat($("#ttl_amount").val());
            var down_payment=m_round(parseFloat($("#down_payment").val()));
            var int_rate=parseFloat($("#rate_per_month").val())/100;
            var period=parseFloat($("#period").val());
            var balance_amount=parseFloat(total_amount-down_payment);
            var num_of_install=parseInt($("#num_of_installment").val());
            var total_interest_amount=parseFloat((balance_amount*int_rate)*num_of_install);
            var total_installment_amount=parseInt((total_interest_amount+balance_amount)/period);
            var capital_amount=parseFloat((balance_amount/num_of_install));
            var interest_amount=parseFloat(balance_amount)*parseFloat(int_rate);
            var installment_amount=parseFloat(interest_amount)+parseFloat(capital_amount);
            var ddate;

        // if(period==0){alert("abc");period="";}
        if(!isNaN(period) && period!=0){
            var dd = "";
            var num_of_rows=1+(parseInt($("#num_of_installment").val()));
            var add_installment_grid='<thead> <th class="tb_head_th">NO</th> <th class="tb_head_th">Due Date</th> <th class="tb_head_th">Installment</th>  <th class="tb_head_th">Capital</th>  <th class="tb_head_th">Interest</th> </thead>';
            
            for(x=0;x<num_of_rows;x++){
             if(x==0){
              add_installment_grid +='<tbody> <tr><td><input type="text" readonly="readonly" id="insno_'+x+'" name="no_'+x+'" value="'+x+'" style="width:50px"></td> <td><input type="text" id="duedate_'+x+'" name="duedate_'+x+'" value="'+get_downpay_date+'" class="input_date_down_future"  style="width:100px"></td> <td><input type="text" class="g_input_amo" readonly="readonly" id="installment_'+x+'" name="installment_'+x+'" value="'+down_payment+'" style="width:100px;"></td> <td><input type="text" class="g_input_amo" readonly="readonly" id="capital_'+x+'" name="capital_'+x+'" value="'+down_payment+'" style="width:100px" ></td> <td><input type="text" class="g_input_amo" readonly="readonly" id="interest_'+x+'" name="interest_'+x+'" value="0" style="width:100px"></td></tr></tbody>';  
          }else{
           dd = dd + x + "~~";
           add_installment_grid +='<tbody> <tr><td><input type="text" readonly="readonly" id="insno_'+x+'" name="no_'+x+'" value="'+x+'" style="width:50px"></td> <td><input type="text" id="duedate_'+x+'" name="duedate_'+x+'" value="" class="input_date_down_future"  style="width:100px"></td> <td><input type="text" class="g_input_amo" readonly="readonly" id="installment_'+x+'" name="installment_'+x+'" value="'+m_round(installment_amount)+'" style="width:100px;"></td> <td><input type="text" class="g_input_amo" readonly="readonly" id="capital_'+x+'" name="capital_'+x+'" value="'+m_round(capital_amount)+'" style="width:100px" ></td> <td><input type="text" class="g_input_amo" readonly="readonly" id="interest_'+x+'" name="interest_'+x+'" value="'+m_round(interest_amount)+'" style="width:100px"></td></tr></tbody>'; 
       }
   }
   due_date2(dd);
   $("#installment_det").html(add_installment_grid);

}else{
    var dd = "";
    var a=b=c=d= 0;
    var num_of_rows=1+(parseInt($("#num_of_installment").val()));
    var add_installment_grid='<thead> <th class="tb_head_th">NO</th> <th class="tb_head_th">Due Date</th> <th class="tb_head_th">Installment</th>  <th class="tb_head_th">Capital</th>  <th class="tb_head_th">Interest</th> </thead>';
    for(x=0;x<num_of_rows;x++){
        if(x==0){
          add_installment_grid +='<tbody> <tr><td><input type="text" readonly="readonly" id="insno_'+x+'" name="no_'+x+'" value="'+x+'" style="width:50px"></td> <td><input type="text" id="duedate_'+x+'" name="duedate_'+x+'" value="'+get_downpay_date+'" class="input_date_down_future"  style="width:100px"></td> <td><input type="text" class="g_input_amo" readonly="readonly" id="installment_'+x+'" name="installment_'+x+'" value="'+down_payment+'" style="width:100px;"></td> <td><input type="text" class="g_input_amo" readonly="readonly" id="capital_'+x+'" name="capital_'+x+'" value="'+down_payment+'" style="width:100px" ></td> <td><input type="text" class="g_input_amo" readonly="readonly" id="interest_'+x+'" name="interest_'+x+'" value="0" style="width:100px"></td></tr></tbody>';  
      }else{
         dd = dd + x + "~~";
         add_installment_grid +='<tbody> <tr><td><input type="text" readonly="readonly" id="insno_'+x+'" name="no_'+x+'" value="'+x+'" style="width:50px"></td> <td><input type="text" id="duedate_'+x+'" name="duedate_'+x+'" value="" class="input_date_down_future"  style="width:100px"></td> <td><input type="text" class="g_input_amo" readonly="readonly" id="installment_'+x+'" name="installment_'+x+'" value="'+m_round(installment_amount)+'" style="width:100px;"></td> <td><input type="text" class="g_input_amo" readonly="readonly" id="capital_'+x+'" name="capital_'+x+'" value="'+capital_amount+'" style="width:100px" ></td> <td><input type="text" class="g_input_amo" readonly="readonly" id="interest_'+x+'" name="interest_'+x+'" value="'+m_round(interest_amount)+'" style="width:100px"></td></tr></tbody>';
         a += installment_amount;
         b = b + capital_amount;
         c += interest_amount;
     }


 }
 due_date(dd);

 $("#installment_det").html(add_installment_grid);

 $("#installment_det").append('<tbody> <tr><td></td> <td>Total</td> <td><input type="text" class="g_input_amo" readonly="readonly" id="tot_installment" name="tot_installment" value="'+m_round(a)+'" style="width:100px;"></td> <td><input type="text" class="g_input_amo" readonly="readonly"    id="tot_capital" name="tot_capital" value="'+m_round(b)+'" style="width:100px" ></td> <td><input type="text" class="g_input_amo" readonly="readonly" id="tot_interest" name="tot_interest" value="'+m_round(c)+'" style="width:100px"></td></tr></tbody');

}
$("#total_interest_amount").val(total_interest_amount);
});

$(document).on('keyup', '#amount8_0', function(){

    available=parseFloat($("#no8_0").val());
    redim=parseFloat($("#amount8_0").val());
    if(redim>available){
        set_msg("Settle amount should be less than or available points");
        $("#amount8_0").val('');
    }
    set_opt_balance();

});

$(document).on('blur', '#pc', function(){
    var get= $(this).val();
    $("#amount8_0").val(get);
    set_opt_balance();
});

$(document).on('keyup', '#pc', function(){
    var get= $(this).val();
    $("#amount8_0").val(get);
    set_opt_balance();
});

$(document).on('blur', '#amount8_0', function(){
    var get= $(this).val();
    $("#pc").val(get);
    set_opt_balance();
});


function tot_credit_card(){
    var values =parseFloat(0);
    $(".cc_amount").each(function(e){

        if(!isNaN($("#amount1_"+e).val()) && $("#amount1_"+e).val()!=""){
            values = values+ parseFloat($("#amount1_"+e).val());
        }
    });

    $("#credit_card").val(values);
}
function due_date(x){

    $.post("index.php/main/load_data/t_payment_option/get_due_date",{
      trans_date:$("#date").val(),
      num_of_days:$("#period").val(),
      num_of_installment:$("#num_of_installment").val(),
      installment_num:x
  },function(res){                
      for (n = 0 ; n < res.a.length ; n++){
          $("#duedate_" + (n+1)).val(res.a[n]);
      }
  },"json");  
}

function due_date2(x){
    $.post("index.php/main/load_data/t_payment_option/get_due_date2",{
      trans_date:$("#date").val(),
      num_of_days:$("#period").val(),
      num_of_installment:$("#num_of_installment").val(),
      installment_num:x
  },function(res){                
      for (n = 0 ; n < res.a.length ; n++){
          $("#duedate_" + (n+1)).val(res.a[n]);
      }
  },"json");  
}

$("#installment_grid").css("width","250px");

$(document).on('focus', '#cheque_issue', function(){
    $("#tabs").tabs("select", "tabs-7" );   
});

$(document).on('focus', '#credit_note', function(){
    $("#tabs").tabs("select", "tabs-2" );   
});

$(document).on('focus', '#credit_card', function(){
 $("#tabs").tabs("select", "tabs-1" );   
});

$(document).on('focus', '#debit_note', function(){
 $("#tabs").tabs("select", "tabs-3" );   
});

$(document).on('focus', '#bank_debit', function(){
 $("#tabs").tabs("select", "tabs-4" );   
});

$(document).on('focus', '#advance', function(){
 $("#tabs").tabs("select", "tabs-6" );   
});

$(document).on('focus', '#gv', function(){
 $("#tabs").tabs("select", "tabs-5" );   
});

$(document).on('focus', '#pc', function(){
 $("#tabs").tabs("select", "tabs-8" );   
});

$(document).on('focus', '#cheque_recieve', function(){
 $("#tabs").tabs("select", "tabs-9" );   
});

$(document).on('focus', '#installment', function(){
 $("#tabs").tabs("select", "tabs-10" );   
});

$(document).on('focus', '#pdchq', function(){
 $("#tabs").tabs("select", "tabs-11" );   
});

$(document).on('click', '#btnSave2', function(){

    var get_amount=parseFloat($("#amount8_0").val());
    var get_point=parseFloat($("#no8_0").val());
    var get_pc=parseFloat($("#pc").val());
    if(isNaN(get_pc)){get_pc=0;}
    if(isNaN(get_amount)){get_amount=0;}
    if(isNaN(get_point)){get_point=0;}

    if(!tally_cheque_issue()){
        alert(" Cheque issues amount is not tally with the total amount ");
    }else if(!tally_cheque_recieve()){
        alert(" Cheque recieves amount is not tally with the total amount ");
        /*}else if(!tally_credit_card()){
            alert(" Credit card amount is not tally with the total amount ");*/
        }else if(!tally_credit_note()){
            alert(" Credit note amount is not tally with the total amount ");
        }else if(!tally_debit_note()){
         alert(" Debit note amount is not tally with the total amount ");
     }else if(!tally_bank_debit()){
        alert(" Bank debit amount is not tally with the total amount ");
    }else if(!tally_advance()){
        alert(" Advance amount is not tally with the total amount ");   
    }else if(!tally_gv()){
     alert(" Gift Voucher amount is not tally with the total amount ");
 }else if(!tally_pd_chq()){
     alert(" Post Dated Cheques amount is not tally with the total amount ");
 }else if(!tally_payment()){           
     alert(" Payment, not tally for the net amount ");            
 }else if(!tally_pc()){
     alert("Please check the points.");
 }else{
    $("#load_opt").val("1");    
    $("#hid_cash").val($("#cash").val());
    $("#hid_cheque_issue").val($("#cheque_issue").val());
    $("#hid_cheque_recieve").val($("#cheque_recieve").val());
    $("#hid_credit_card").val($("#credit_card").val());
    $("#hid_credit_note").val($("#credit_note").val());
    $("#hid_debit_note").val($("#debit_note").val());
    $("#hid_bank_debit").val($("#bank_debit").val());
    $("#hid_discount").val($("#discount").val());
    $("#hid_advance").val($("#advance").val());
    $("#hid_gv").val($("#gv").val());
    $("#hid_pd_chq").val($("#pdchq").val());
    $("#hid_credit").val($("#credit").val());
    $("#hid_pc").val($("#pc").val());
    $("#hid_installment").val($("#installment").val());
    $("#hid_pc_type").val($("#type8_0").val());
    $("#credit_amount").val(set_credit_amount());


    $("#hid_ins_period_by_days").val($("#period").val());
    $("#hid_ins_down_payment").val($("#down_payment").val());
    $("#hid_ins_rate_per_month").val($("#rate_per_month").val());
    $("#hid_num_of_installment").val($("#num_of_installment").val());


    var num_of_rows=parseInt($("#num_of_installment").val())+1;
    var install_payment = [];
    for(x=0;num_of_rows>x;x++){
      install_payment.push($("#insno_"+x).val()+"~"+$("#duedate_"+x).val()+"~"+$("#installment_"+x).val()+"~"+$("#capital_"+x).val()+"~"+$("#interest_"+x).val());
  }

  $("#install_pay").val(install_payment);
  $("#btnSave").removeAttr("disabled");
  $("#save_status").val("1");
  document.getElementById('light2').style.display='none';
  document.getElementById('fade2').style.display='none';
}

});

$(document).on('click', '#chck_cus_card', function(){
    if($("#hid").val()!=0){           
       get_points2();
   }else if($("#type8_0").val()!=""){
    get_points();
}else{
 $.post('index.php/main/load_data/t_payment_option/get_points',{
    customer:$("#customer").val()
},function(res){   
    $("#type8_0").val(res.points_res.loyalty_card_no);
    $("#no8_0").val(res.points_res.loyalty_balance);

},"json");
}    
});

$(document).on('keydown', '#type8_0', function(e){
    if(e.keyCode==13){
     if($("#hid").val()!=0){
     }else{
        get_points();
    }
}

});


function get_points(){
    $.post('index.php/main/load_data/t_payment_option/get_points3/',{
        type:$("#type8_0").val()
    },function(res){   
        if(res.points_res==""){
            alert("Invalid card number"); 
        }
        $("#no8_0").val(res.points_res.loyalty_balance);
    },"json");
}

$(".type1").autocomplete('index.php/main/load_data/t_payment_option/load_credit_card_type', {
    width: 350,
    multiple: false,
    matchContains: true,
});

$(document).on('keydown', '.type1', function(e){
    set_cid($(this).attr("id"));      
    if(e.keyCode == 13){
        set_cus_values6(scid);
    }
});

$(document).on('blur', '.type1', function(){
    set_cid($(this).attr("id"));
    set_cus_values6(scid);
});


$(".bank9").autocomplete('index.php/main/load_data/m_bank/auto_com', {
    width: 350,
    multiple: false,
    matchContains: true,
    formatItem: formatItems2,
    formatResult: formatItemsResult2
});

$(document).on('keydown', '.bank9', function(e){
    if(e.keyCode == 13){
        set_cid($(this).attr("id"));
        set_cus_values3(scid);
    }
});

$(document).on('blur', '.bank9', function(){
    set_cid($(this).attr("id"));
    set_cus_values3(scid);

});


$(".bank1").autocomplete('index.php/main/load_data/m_bank/auto_com3', {
    width: 350,
    multiple: false,
    matchContains: true,
    formatItem: formatItems2,
    formatResult: formatItemsResult2
});

$(document).on('keydown', '.bank1', function(e){
    if(e.keyCode == 13){
        set_cid($(this).attr("id"));
        set_cus_values8(scid);
    }
});

$(document).on('blur', '.bank1', function(){
    set_cid($(this).attr("id"));
    set_cus_values8(scid);
});


$(".bank_deb").autocomplete('index.php/main/load_data/m_account/auto_com', {
    width: 350,
    multiple: false,
    matchContains: true,
    formatItem: formatItems,
    formatResult: formatItemsResult
});

$(document).on('keydown', '.bank_deb', function(e){
    set_cid($(this).attr("id"));      
    if(e.keyCode == 13){
        set_cus_values5(scid);
    }
});

$(document).on('blur', '.bank_deb', function(){
    set_cid($(this).attr("id"));
    set_cus_values5(scid);
});


$(".bank7").autocomplete('index.php/main/load_data/m_bank/auto_com22', {
    width: 350,
    multiple: false,
    matchContains: true,
    formatItem: formatItems,
    formatResult: formatItemsResult
});



$(document).on('keyup', '.cc_amount', function(e){
    set_cid($(this).attr("id"));
    if($("#rate1_"+scid).val()!=""){
        cal_amnt($(this).val(),$("#rate1_"+scid).val(),"#amount_rate1_");
    }    
});

$(document).on('keydown', '.bank7', function(e){
    set_cid($(this).attr("id"));      
    if(e.keyCode == 13){
        set_cus_values7(scid);
    }
});

$(document).on('blur', '.bank7', function(){
    set_cid($(this).attr("id"));
           // set_cus_values7(scid);
       });
});

function load_receive_chk(){
    $.post("index.php/main/load_data/t_payment_option/receive_chku", {
        search : $("#pop_search13").val() 
    }, function(r){
        $("#sr13").html(r);
        settings_receive_chk();        
    }, "text");
}

function settings_receive_chk(){ 
 $("#item_list .cl").click(function(){  
    var bank = $(this).children().eq(1).html().split("-")[0];
    $("#bank9_"+scid).val($(this).children().eq(0).html());
    $("#branch9_"+scid).val($(this).children().eq(2).html());
    $("#pop_close13").click();
});
}

function load_pd_cheques(){
    $.post("index.php/main/load_data/t_payment_option/post_dated_cheques", {
        search : $("#pop_search12").val(),
        date: $("#date").val() 
    }, function(r){
        $("#sr12").html(r);
        settings_pd_chk();        
    }, "text");
}


function settings_pd_chk(){ 
 $("#item_list .cl").click(function(){  
    $("#pdbank9_"+scid).val($(this).children().eq(0).html());
    $("#pdbranch9_"+scid).val($(this).children().eq(1).html());
    $("#pdacc9_"+scid).val($(this).children().eq(2).html());
    $("#pdcheque9_"+scid).val($(this).children().eq(3).html());
    $("#pdamount9_"+scid).val($(this).children().eq(4).html());
    $("#pddate9_"+scid).val($(this).children().eq(5).html());
    total_pd_values();
    $("#pop_close12").click();
});
}

function total_pd_values(){
    var total=0;
    $(".pd_amount").each(function(e){
        if(!isNaN(parseFloat($("#pdamount9_"+e).val()))){
            total+=parseFloat($("#pdamount9_"+e).val());
        }
    });
    $("#pdchq").val(m_round(total));
    set_opt_balance();
}

function load_issue_chk(){
    $.post("index.php/main/load_data/t_payment_option/issue_chku", {
        search : $("#pop_search12").val() 
    }, function(r){
        $("#sr12").html(r);
        settings_issue_chk();        
    }, "text");
}

function settings_issue_chk(){ 
 $("#item_list .cl").click(function(){  
    var bank = $(this).children().eq(1).html().split("-")[0];
    $("#bank7_"+scid).val($(this).children().eq(0).html());
    $("#des7_"+scid).val(bank);
    $("#pop_close12").click();
});
}


function load_crd_type(){
    $.post("index.php/main/load_data/t_payment_option/card_type", {
        search : $("#pop_search10").val() 
    }, function(r){
        $("#sr10").html(r);
        settings_crd_type();        
    }, "text");
}

function settings_crd_type(){ 
 $("#item_list .cl").click(function(){  
    $("#type1_"+scid).val($(this).children().eq(0).html());
    $("#blocker4").css("display", "none");
    $("#pop_close10").click();
});
}

function load_gift(){
    $.post("index.php/main/load_data/t_payment_option/load_gift", {
        search : $("#pop_search15").val() 
    }, function(r){
        $("#sr15").html(r);
        settings_gift();        
    }, "text");
}

function settings_gift(){ 
 $("#item_list .cl").click(function(){  
    if(check_gift_exist($(this).children().eq(0).html())){
        $("#type5_"+scid).val($(this).children().eq(0).html());
        $("#des5_"+scid).val($(this).children().eq(1).html());
        $("#no5_"+scid).val($(this).children().eq(2).html());
        $("#amount5_"+scid).val($(this).children().eq(3).html());
        $("#gcode5_"+scid).val($(this).children().eq(4).html());
        $("#amount5_"+scid).keyup();
        set_opt_balance(); 
    }else{
        set_msg("Gift Voucher "+$(this).children().eq(0).html()+" Already Added");
    }
    $("#pop_close15").click();
});
}

function check_gift_exist(id){
    var v = true;
    $(".gft").each(function(){
        if($(this).val() == id){
            v = false;
        }
    });    
    return v;
}



function load_chqs(chq_b_no){
    $.post("index.php/main/load_data/t_payment_option/pending_chqs", {
        search : $("#pop_search11").val(),
        chq_book_no: chq_b_no
    }, function(r){
        $("#sr11").html(r);
        settings_chqs();        
    }, "text");
}

function settings_chqs(){ 
 $("#item_list .cl").click(function(){  
    $("#chqu7_"+scid).val($(this).children().eq(0).html()); 
    $("#pop_close11").click();
});
}


function load_chq_book(){
    $.post("index.php/main/load_data/t_payment_option/chq_book", {
        search : $("#pop_search14").val() 
    }, function(r){
        $("#sr14").html(r);
        settings_chq_book();        
    }, "text");
}

function settings_chq_book(){ 
 $("#item_list .cl").click(function(){  
    $("#chq7_"+scid).val($(this).children().eq(0).html());
    $("#bank7_"+scid).val($(this).children().eq(1).html());
    $("#des7_"+scid).val($(this).children().eq(2).html());
    $("#chqu7_"+scid).val($(this).children().eq(3).html()); 
    $("#pop_close14").click();
});
}


function load_bank(){
    $.post("index.php/main/load_data/t_payment_option/bank_rate", {
        search : $("#pop_search4").val() 
    }, function(r){
        $("#sr4").html(r);
        settings_bank();        
    }, "text");
}

function settings_bank(){ 
 $("#item_list .cl").click(function(){  
    $("#bank1_"+scid).val($(this).children().eq(0).html());
    $("#1bank1_"+scid).val($(this).children().eq(1).html());
    $("#month1_"+scid).val($(this).children().eq(3).html());
    $("#rate1_"+scid).val($(this).children().eq(4).html()); 
    $("#acc1_"+scid).val($(this).children().eq(2).html());
    $("#merchant1_"+scid).val($(this).children().eq(5).html());

    cal_amnt($("#amount1_"+scid).val(),$(this).children().eq(4).html(),"#amount_rate1_");

    $("#pop_close4").click();
});
}

function cal_amnt(amount,rate,total_txt){

    var amount = parseFloat(amount);
    var rate = parseFloat(rate);

    var total =(amount*rate/100);

    //alert(total_txt+scid);
    $(total_txt+scid).val(total);   
    $(total_txt+scid).blur();

}


function set_cus_values88(a) {
 var b = a.val();
 b = b.split("-");
 var b_id =$(a).attr('id');
 if (2 == b.length) {

     a.val(b[0]);
     $("#1"+b_id).val(b[1]);
 }
}

function load_cr_total(){
   var i="0";
   var amount_c_tot=parseFloat("0");
   var amount_c_bal=parseFloat("0");
   $('.cn_settle').each(function(){
    if($("#amount2_"+i).val()!="" || $("#balance2_"+i).val()!="")
    {
        amount_c_tot=amount_c_tot+parseFloat($("#amount2_"+i).val());
        amount_c_bal=amount_c_bal+parseFloat($("#balance2_"+i).val());      
    }
    i++;
})
   $("#total").val(amount_c_tot);
   $("#total_bal").val(amount_c_bal);
}

function load_dr_total(){
 var i="0";
 var amount_d_tot=parseFloat("0");
 var amount_d_bal=parseFloat("0");
 $('.dn_settle').each(function(){
    if($("#amount3_"+i).val()!="" || $("#balance3_"+i).val()!="")
    {
        amount_d_tot=amount_d_tot+parseFloat($("#amount3_"+i).val());
        amount_d_bal=amount_d_bal+parseFloat($("#balance3_"+i).val());   
    }
    i++;
})
 $("#total_d").val(amount_d_tot);
 $("#total_d_bal").val(amount_d_bal);
}

function set_cus_values3(f){
    var v = $("#bank9_"+f).val();
    v = v.split("-");    
    if(v.length == 5){ // this change accourding to bank branch code          
     $("#bank9_"+f).val(v[0]);
     $("#branch9_"+f).val(v[2]+"-"+v[3]);
 }
}

function set_cus_values8(f){
    var v = $("#bank1_"+f).val();
    v = v.split("-");
    if(v.length == 4){
     $("#bank1_"+f).val(v[0]);
     $("#1bank1_"+f).val(v[1]);
     $("#month1_"+f).val(v[2]);
     $("#rate1_"+f).val(v[3]);
       //  credit_card_rate_amount(f);
   }
}

function credit_card_rate_amount(f){

    var amount=parseFloat($("#amount1_"+f).val());
    if(isNaN(amount)){amount=0;}
    var rate=parseFloat($("#rate1_"+f).val());
    if(isNaN(rate)){rate=0;}
    var amount_rate=amount*(rate/100);
    $("#amount_rate1_"+f).val(m_round(amount_rate));
}

function set_cus_values4(f){
    var v = $("#branch9_"+f).val();
    v = v.split("-");
    if(v.length == 2){
        $("#branch9_"+f).val(v[0]);
    }
}

function set_cus_values6(scid){     
   var v = $("#type1_"+scid).val();
}

function set_cus_values5(scid){     
    var v = $("#code4_"+scid).val();
    v = v.split("-");
    if(v.length == 2){
     $("#code4_"+scid).val(v[0]);
     $("#name4_"+scid).val(v[1]);
 }
}


function set_cus_values7(scid){     
    var v = $("#bank7_"+scid).val();
    v = v.split("-");
    //if(v.length == 2){
     $("#bank7_"+scid).val(v[0]);
     $("#des7_"+scid).val(v[1]);
    //}
}

function payment_opt(type,net_value){
    $("#btnSave").attr("disabled","disabled");   

    $("#opt_net_value").val(net_value);


    var current_balance=set_credit_amount();
    if(isNaN(current_balance)){
        current_balance=0;
    }
    $("#opt_balance").val(m_round(parseFloat(net_value)-current_balance));

    $("#ttl_amount").val(net_value);
    $("#installment").blur();
    document.getElementById('light2').style.display='block';
    document.getElementById('fade2').style.display='block';

    if($("#hid").val()!=0){           
       get_points2();
   }

}


function get_points2(){
    $.post('index.php/main/load_data/t_payment_option/get_points3',{ 
        trans_no:$("#id").val(),
        trans_type:$("#type").val(),
        card_no: $("#hid_priv_card").val()
    },function(res){   
      $("#type8_0").val(res.points_res.card_no);
      $("#no8_0").val(res.points_res.points);
  },"json");

}


function formatItems2(row){
    return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong> | <strong> " +row[2] + "</strong> | <strong> " +row[3] + "</strong>";
}

function formatItemsResult2(row){
    return row[0]+"-"+row[1]+"-"+row[2]+"-"+row[3];;
}



function formatItems3(row){
    return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong> | <strong> " +row[2] + "</strong>";
}

function formatItemsResult3(row){
    return row[0]+"-"+row[1]+"-"+row[2];
}


function formatItems(row){
    return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
}

function formatItemsResult(row){
    return row[0]+"-"+row[1];
}


function tally_payment(){
    var cash=parseFloat($("#cash").val());
    var cheque_issue=parseFloat($("#cheque_issue").val());
    var cheque_recieve=parseFloat($("#cheque_recieve").val());  
    var credit_card=parseFloat($("#credit_card").val());
    var credit_note=parseFloat($("#credit_note").val());
    var debit_note=parseFloat($("#debit_note").val());
    var bank_debit=parseFloat($("#bank_debit").val());
    var discount=parseFloat($("#discount").val());
    var advance=parseFloat($("#advance").val());
    var gv=parseFloat($("#gv").val());
    var credit=parseFloat($("#credit").val());
    var pc=parseFloat($("#pc").val());
    var installment=parseFloat($("#installment").val());    
    var net_amount=parseFloat($("#net").val());
    var pd_chq =parseFloat($("#pdchq").val());

    if(isNaN(cash)){cash=0;}
    if(isNaN(cheque_issue)){cheque_issue=0;}
    if(isNaN(cheque_recieve)){cheque_recieve=0;}
    if(isNaN(credit_card)){credit_card=0;}
    if(isNaN(credit_note)){credit_note=0;}
    if(isNaN(debit_note)){debit_note=0;}
    if(isNaN(bank_debit)){bank_debit=0;}
    if(isNaN(discount)){discount=0;}
    if(isNaN(advance)){advance=0;}
    if(isNaN(gv)){gv=0;}
    if(isNaN(credit)){credit=0;}
    if(isNaN(pc)){pc=0;}
    if(isNaN(installment)){installment=0;}
    if(isNaN(pd_chq)){pd_chq=0;}
    if(isNaN(net_amount)){net_amount=0;}
    
   /* alert(cash);
    alert(cheque_issue);
    alert(cheque_recieve);
    alert(credit_card);
    alert(credit_note);
    alert(debit_note);
    alert(bank_debit);
    alert(discount);

    alert(advance);
    alert(credit);
    alert(gv);
    alert(pc);
    alert(installment);
    alert(pd_chq);
    alert(net_amount);*/

    var total=cash+cheque_issue+cheque_recieve+credit_card+credit_note+debit_note+bank_debit+discount+advance+gv+credit+pc+installment+pd_chq;

    if(m_round(total,2)!=net_amount){    
        return false;
    }else{
        return true;
    }

}


function tally_credit_card(){
    var credit_card=parseFloat($("#credit_card").val());
    if(isNaN(credit_card)){credit_card=0;}
    var total=0;
    $(".cc_amount").each(function(e){
        if(!isNaN(parseFloat($("#amount1_"+e).val()))){
            total+=parseFloat($("#amount1_"+e).val());
        }
    });
    if(total!=credit_card){

        return false;
    }else{
        return true;
    }
}

function tally_credit_note(){
    var credit_note=parseFloat($("#credit_note").val());
    if(isNaN(credit_note)){credit_note=0;}
    var total=0;
    $(".cn_amount").each(function(e){
        if(!isNaN(parseFloat($("#settle2_"+e).val()))){
            total+=parseFloat($("#settle2_"+e).val());
        }
    });
    if(total!=credit_note){

        return false;
    }else{
       return true;
   }
}


function tally_cheque_issue(){
    var cheque_issue=parseFloat($("#cheque_issue").val());
    if(isNaN(cheque_issue)){cheque_issue=0;}
    var total=0;
    $(".ci_amount").each(function(e){
        if(!isNaN(parseFloat($("#amount7_"+e).val()))){
            total+=parseFloat($("#amount7_"+e).val());
        }
    });
    if(total!=cheque_issue){

        return false;
    }else{
       return true;
   }
}



function tally_cheque_recieve(){
    var cheque_recieve=parseFloat($("#cheque_recieve").val());
    if(isNaN(cheque_recieve)){cheque_recieve=0;}
    var total=0;
    $(".cr_amount").each(function(e){
        if(!isNaN(parseFloat($("#amount9_"+e).val()))){
            total+=parseFloat($("#amount9_"+e).val());
        }
    });
    if(total!=cheque_recieve){

        return false;
    }else{
       return true;
   }
}



function tally_debit_note(){
    var debit_note=parseFloat($("#debit_note").val());
    if(isNaN(debit_note)){debit_note=0;}
    var total=0;
    $(".dn_amount").each(function(e){
        if(!isNaN(parseFloat($("#settle3_"+e).val()))){
            total+=parseFloat($("#settle3_"+e).val());
        }
    });
    if(total!=debit_note){

        return false;
    }else{
       return true;
   }
}



function tally_bank_debit(){
    var bank_debit=parseFloat($("#bank_debit").val());
    if(isNaN(bank_debit)){bank_debit=0;}
    var total=0;
    $(".bd_amount").each(function(e){
        if(!isNaN(parseFloat($("#amount4_"+e).val()))){
            total+=parseFloat($("#amount4_"+e).val());
        }
    });
    if(total!=bank_debit){

        return false;
    }else{
        return true;
    }
}


function tally_advance(){
    var advance=parseFloat($("#advance").val());
    if(isNaN(advance)){advance=0;}
    var total=0;
    $(".a_amount").each(function(e){
        if(!isNaN(parseFloat($("#cdate6_"+e).val()))){
            total+=parseFloat($("#cdate6_"+e).val());
        }
    });
    if(total!=advance){
        return false;
    }else{
        return true;
    }
}


function tally_pd_chq(){
    var pd=parseFloat($("#pdchq").val());
    if(isNaN(pd)){pd=0;}
    var total=0;
    $(".pdbank9").each(function(e){
        if(!isNaN(parseFloat($("#pdamount9_"+e).val()))){
            total+=parseFloat($("#pdamount9_"+e).val());
        }
    });
    if(total!=pd){
        return false;
    }else{
        return true;
    }
}

function tally_gv(){
    var gv=parseFloat($("#gv").val());
    if(isNaN(gv)){gv=0;}
    var total=0;
    $(".gv_amount").each(function(e){
        if(!isNaN(parseFloat($("#amount5_"+e).val()))){
            total+=parseFloat($("#amount5_"+e).val());
        }
    });
    if(total!=gv){
        return false;
    }else{
        return true;
    }
}

function tally_pc(){
    alert("Successfully Calculated");
    var pc=parseFloat($("#pc").val());
    if(isNaN(pc)){pc=0;}
    var total=0;
    if(!isNaN(parseFloat($("#amount8_0").val()))){
        total=parseFloat($("#amount8_0").val());
    }
    if(total!=pc){
        return false;
    }else{
        return true;
    }
}


function privilege_calculation(){
    if($("#privi_card").val()!=""){
        var total=rate=result=0;
        $(".amount").each(function(e){
            if($("#8_"+e).val()!="" && $("#0_"+e).val()!=""){
                if(isNaN($("#8_"+e).val())){$("#8_"+e).val()=0;}
                total=parseFloat($("#8_"+e).val());

                privilege_card_rate_cal(total,$("#privi_card").val())
            }else{
             $("#points").val("");
         }
     });
    }

}

function privilege_card_rate_cal(total,code){
   var rate=result=0;
   $.post("index.php/main/load_data/t_payment_option/get_department_pv_rate",{
       code:code,
       total:total
   },function(res){
      if(isNaN(parseFloat(res))){res=0;}
      $("#points").val(m_round(res));
  },"text");


}




function set_credit_amount(){
    var net_value=parseFloat($("#opt_net_value").val());
    var cash=parseFloat($("#cash").val());
    var credit=parseFloat($("#credit").val());
    var cheque_issue=parseFloat($("#cheque_issue").val());
    var cheque_recieve=parseFloat($("#cheque_recieve").val());
    var credit_card=parseFloat($("#credit_card").val());
    var credit_note=parseFloat($("#credit_note").val());
    var debit_note=parseFloat($("#debit_note").val());
    var bank_debit=parseFloat($("#bank_debit").val());
    var discount=parseFloat($("#discount").val());
    var advance=parseFloat($("#advance").val());
    var gv=parseFloat($("#gv").val());
    var pc=parseFloat($("#pc").val());
    var installment=parseFloat($("#installment").val());
    
    if(isNaN(cash)){cash=0;}
    if(isNaN(cheque_issue)){cheque_issue=0;}
    if(isNaN(cheque_recieve)){cheque_recieve=0;}
    if(isNaN(credit_card)){credit_card=0;}
    if(isNaN(credit_note)){credit_note=0;}
    if(isNaN(debit_note)){debit_note=0;}
    if(isNaN(bank_debit)){bank_debit=0;}
    if(isNaN(discount)){discount=0;}
    if(isNaN(advance)){advance=0;}
    if(isNaN(gv)){gv=0;}
    if(isNaN(credit)){credit=0;}
    if(isNaN(pc)){pc=0;}
    if(isNaN(installment)){installment=0;}

    var total=cash+credit+cheque_issue+cheque_recieve+credit_card+credit_note+debit_note+bank_debit+discount+advance+gv+pc+installment;

    return total;
}


function load_payment_option_data(id,trans_code){
    loding();
    $.post("index.php/main/get_data/t_payment_option/", {
        id: id,
        trans_code:trans_code
    }, function(r){

        if(r=="2"){
           alert("No records");
       }else{


        if(r.ins_schedule!=2){
            var add_installment_grid='<thead> <th class="tb_head_th">NO</th> <th class="tb_head_th">Due Date</th> <th class="tb_head_th">Installment</th>  <th class="tb_head_th">Capital</th>  <th class="tb_head_th">Interest</th> </thead>';
            for(var i=0;i<r.ins_schedule.length;i++){
                add_installment_grid +='<tbody> <tr><td><input type="text" readonly="readonly" id="insno_'+i+'" name="no_'+i+'" value="'+ r.ins_schedule[i].ins_no+'" style="width:50px"></td> <td><input type="text" id="duedate_'+i+'" name="duedate_'+i+'" value="'+r.ins_schedule[i].due_date+'" class="input_date_down_future"  style="width:100px"></td> <td><input type="text" class="g_input_amo" readonly="readonly" id="installment_'+i+'" name="installment_'+i+'" value="'+ r.ins_schedule[i].ins_amount+'" style="width:100px;"></td> <td><input type="text" class="g_input_amo" readonly="readonly" id="capital_'+i+'" name="capital_'+i+'" value="'+r.ins_schedule[i].capital_amount+'" style="width:100px" ></td> <td><input type="text" class="g_input_amo" readonly="readonly" id="interest_'+i+'" name="interest_'+i+'" value="'+ r.ins_schedule[i].int_amount+'" style="width:100px"></td></tr></tbody>';
            }
            $("#installment_det").html(add_installment_grid);
        }


        if(r.opt_credit_note_det!=2){

            for(var i=0; i<r.opt_credit_note_det.length;i++){
              $("#cl_"+i).val(r.opt_credit_note_det[i].cl);
              $("#bc_"+i).val(r.opt_credit_note_det[i].bc);
              $("#no2_"+i).val(r.opt_credit_note_det[i].cn_no);
              $("#date2_"+i).val(r.opt_credit_note_det[i].ddate);
              $("#amount2_"+i).val(r.opt_credit_note_det[i].amount);
              $("#balance2_"+i).val(r.opt_credit_note_det[i].balance);
              $("#settle2_"+i).val(r.opt_credit_note_det[i].settled);
              $("#des_"+i).val(r.opt_credit_note_det[i].memo);
              $("#type_"+i).val(r.opt_credit_note_det[i].description);
              $("#t_code_"+i).val(r.opt_credit_note_det[i].trans_code);
          }

      }


      if(r.opt_advance_pay_det!=2){
        for(var i=0; i<r.opt_advance_pay_det.length;i++){
          $("#no6_"+i).val(r.opt_advance_pay_det[i].advance_no);
          $("#date6_"+i).val(r.opt_advance_pay_det[i].ddate);
          $("#amount6_"+i).val(r.opt_advance_pay_det[i].amount);
          $("#balance6_"+i).val(r.opt_advance_pay_det[i].balance);
          $("#cdate6_"+i).val(r.opt_advance_pay_det[i].settle);
      }
  }


  if(r.opt_bank_debit_det!=2){
    for(var i=0; i<r.opt_bank_debit_det.length;i++){
      $("#code4_"+i).val(r.opt_bank_debit_det[i].code);
      $("#name4_"+i).val(r.opt_bank_debit_det[i].name);
      $("#amount4_"+i).val(r.opt_bank_debit_det[i].amount);
  }
}


if(r.opt_credit_card_det!=2){
    for(var i=0; i<r.opt_credit_card_det.length;i++){
      $("#type1_"+i).val(r.opt_credit_card_det[i].card_type);
      $("#no1_"+i).val(r.opt_credit_card_det[i].card_no);
      $("#amount1_"+i).val(r.opt_credit_card_det[i].amount);
      $("#exdate_"+i).val(r.opt_credit_card_det[i].expire_date);
      $("#1bank1_"+i).val(r.opt_credit_card_det[i].description);
      $("#bank1_"+i).val(r.opt_credit_card_det[i].bank_id);
      $("#month1_"+i).val(r.opt_credit_card_det[i].month);
      $("#rate1_"+i).val(r.opt_credit_card_det[i].rate);
      $("#amount_rate1_"+i).val(r.opt_credit_card_det[i].int_amount);
      $("#merchant1_"+i).val(r.opt_credit_card_det[i].merchant_id);
      $("#acc1_"+i).val(r.opt_credit_card_det[i].acc_code);
              //cal_amnt($("#amount1_"+i).val(),r.opt_credit_card_det[i].rate,"#amount_rate1_");
          }
      }





      if(r.opt_debit_note_det!=2){
        for(var i=0; i<r.opt_debit_note_det.length;i++){
          $("#no3_"+i).val(r.opt_debit_note_det[i].dn_no);
          $("#date3_"+i).val(r.opt_debit_note_det[i].ddate);
          $("#amount3_"+i).val(r.opt_debit_note_det[i].amount);
          $("#balance3_"+i).val(r.opt_debit_note_det[i].balance);
          $("#settle3_"+i).val(r.opt_debit_note_det[i].settled);
          $("#des3_"+i).val(r.opt_debit_note_det[i].memo);
          $("#type3_"+i).val(r.opt_debit_note_det[i].description);
          $("#cl3_"+i).val(r.opt_debit_note_det[i].cl);
          $("#bc3_"+i).val(r.opt_debit_note_det[i].bc);
          $("#t_code3_"+i).val(r.opt_debit_note_det[i].trans_code);
      }
  }

  if(r.opt_gift_voucher_det!=2){
    for(var i=0; i<r.opt_gift_voucher_det.length;i++){
      $("#type5_"+i).val(r.opt_gift_voucher_det[i].vou_no);
      $("#des5_"+i).val(r.opt_gift_voucher_det[i].description);
      $("#gcode5_"+i).val(r.opt_gift_voucher_det[i].code);
      $("#no5_"+i).val(r.opt_gift_voucher_det[i].issued_date);
      $("#amount5_"+i).val(r.opt_gift_voucher_det[i].amount);
  }
}

if(r.opt_issue_cheque_det!=2){
    for(var i=0; i<r.opt_issue_cheque_det.length;i++){
      $("#bank7_"+i).val(r.opt_issue_cheque_det[i].bank);
      $("#des7_"+i).val(r.opt_issue_cheque_det[i].description);
      $("#chqu7_"+i).val(r.opt_issue_cheque_det[i].cheque_no);
      $("#amount7_"+i).val(r.opt_issue_cheque_det[i].amount);
      $("#chq7_"+i).val(r.opt_issue_cheque_det[i].cheque_book);
      $("#cdate7_"+i).val(r.opt_issue_cheque_det[i].chq_date);
  }
}


if(r.opt_receive_cheque_det!=2){
    for(var i=0; i<r.opt_receive_cheque_det.length;i++){
      $("#bank9_"+i).val(r.opt_receive_cheque_det[i].bank);
      $("#branch9_"+i).val(r.opt_receive_cheque_det[i].branch);
      $("#acc9_"+i).val(r.opt_receive_cheque_det[i].account_no);
      $("#cheque9_"+i).val(r.opt_receive_cheque_det[i].cheque_no);
      $("#amount9_"+i).val(r.opt_receive_cheque_det[i].amount);
      $("#date9_"+i).val(r.opt_receive_cheque_det[i].cheque_date);
  }
}

if(r.opt_post_dated_cheque_det!=2){
    for(var i=0; i<r.opt_post_dated_cheque_det.length;i++){
      $("#pdbank9_"+i).val(r.opt_post_dated_cheque_det[i].bank);
      $("#pdbranch9_"+i).val(r.opt_post_dated_cheque_det[i].branch);
      $("#pdacc9_"+i).val(r.opt_post_dated_cheque_det[i].account_no);
      $("#pdcheque9_"+i).val(r.opt_post_dated_cheque_det[i].cheque_no);
      $("#pdamount9_"+i).val(r.opt_post_dated_cheque_det[i].amount);
      $("#pddate9_"+i).val(r.opt_post_dated_cheque_det[i].cheque_date);
  }
}

if(r.opt_privilege_card_det!=2){
    for(var i=0; i<r.opt_privilege_card_det.length;i++){
      $("#type8_"+i).val(r.opt_privilege_card_det[i].card_no);
      $("#no8_"+i).val(r.opt_privilege_card_det[i].available);
      $("#amount8_"+i).val(r.opt_privilege_card_det[i].redeem);
  }
}

input_active();
}  
loding();
}, "json");
}


function set_opt_balance(){
    var net_value=parseFloat($("#opt_net_value").val());
    var cash=parseFloat($("#cash").val());
    var credit=parseFloat($("#credit").val());
    var cheque_issue=parseFloat($("#cheque_issue").val());
    var cheque_recieve=parseFloat($("#cheque_recieve").val());
    var credit_card=parseFloat($("#credit_card").val());
    var credit_note=parseFloat($("#credit_note").val());
    var debit_note=parseFloat($("#debit_note").val());
    var bank_debit=parseFloat($("#bank_debit").val());
    var discount=parseFloat($("#discount").val());
    var advance=parseFloat($("#advance").val());
    var gv=parseFloat($("#gv").val());
    var pd_chq=parseFloat($("#pdchq").val());
    var pc=parseFloat($("#pc").val());
    var installment=parseFloat($("#installment").val());
    
    if(isNaN(cash)){cash=0;}
    if(isNaN(cheque_issue)){cheque_issue=0;}
    if(isNaN(cheque_recieve)){cheque_recieve=0;}
    if(isNaN(credit_card)){credit_card=0;}
    if(isNaN(credit_note)){credit_note=0;}
    if(isNaN(debit_note)){debit_note=0;}
    if(isNaN(bank_debit)){bank_debit=0;}
    if(isNaN(discount)){discount=0;}
    if(isNaN(advance)){advance=0;}
    if(isNaN(gv)){gv=0;}
    if(isNaN(credit)){credit=0;}
    if(isNaN(pc)){pc=0;}
    if(isNaN(pd_chq)){pd_chq=0;}
    if(isNaN(installment)){installment=0;}

    var total=cash+credit+cheque_issue+cheque_recieve+credit_card+credit_note+debit_note+bank_debit+discount+advance+gv+pc+installment+pd_chq;

    if($("#type").val()=="4"){
        var b = parseFloat(net_value)-parseFloat(total);
        var c = parseFloat($("#cash").val()); 
        $("#cash").val(m_round(c+b));      
    }else if($("#type").val()=="5"){
        var b = parseFloat(net_value)-parseFloat(total);
        var c = parseFloat($("#credit").val()); 
        $("#credit").val(m_round(c+b));      
    }else{
        var b = parseFloat(net_value)-parseFloat(total);
        var c = parseFloat($("#cash").val()); 
        if(isNaN(c)){
            c=parseFloat(0);
        }
        $("#cash").val(m_round(c+b));   
    }


    //if($("#type").val()=="19"){

    //}

    $("#opt_balance").val(m_round(net_value-total));

}

function check_cheque_is_duplicate(scid){    
    $.post("index.php/main/load_data/t_payment_option/check_cheque_no",{
       chq:$("#cheque9_"+scid).val(),
       bank:$("#bank9_"+scid).val(),
       branch:$("#branch9_"+scid).val()
   },function(res){
      if(res!=1){
        set_msg(res);
        $("#cheque9_"+scid).val("");
        $("#amount9_"+scid).val("")
    }
},"text");
    
}

function check_issue_cheque_is_duplicate(scid){
    $.post("index.php/main/load_data/t_payment_option/check_issue_cheque_no",{
       bank:$("#bank7_"+scid).val(),
       chq:$("#chqu7_"+scid).val()
   },function(res){
      if(res!=1){
        set_msg(res);
        $("#chqu7_"+scid).val("");
        $("#amount7_"+scid).val("");
    }
},"text");
}