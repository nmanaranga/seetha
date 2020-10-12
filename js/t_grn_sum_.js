  var serial_items=[];
  var get_id;
  var serialWind;
  


$(document).ready(function(){
    $("#code").blur(function(){
        check_code();
    });

     $("#id").keydown(function(e){
         if(e.keyCode == 13){
            $(this).blur();
            load_data($(this).val());
         }

    });

     $("#id").keyup(function(){
        this.value = this.value.replace(/[^0-9\.a-z,',A-Z]/g,'');
    });
    
    $(".price, .qty, .dis_pre").blur(function(){
        set_cid($(this).attr("id"));
        discount();
        amount();
        gross_amount();
        all_rate_amount();
        additional_amount();
        net_amount();

    });

    $(".dis").blur(function(){
        set_cid($(this).attr("id"));
        dis_prec();
        amount();
        gross_amount();
        all_rate_amount();
        additional_amount();
        net_amount();
    });

    $(".rate").blur(function(){
        set_cid($(this).attr("id"));
        rate_amount();
        additional_amount();
        net_amount();
    });

    $(".aa").blur(function(){
        set_cid($(this).attr("id"));
        rate_pre();
        additional_amount();
        net_amount();
    });



    $("#set_serial").delegate(".srl_count", "keyup", function(){
       this.value = this.value.replace(/[^0-9\.a-z,',A-Z]/g,'');
    });


    $("#set_serial").delegate(".removeSerial", "click", function(){
        var dis=$(this).attr("id").split("_")[1];
        var serial_code=$("#item_code").val()+"-"+$("#serial_"+dis).val();
        var qty=parseInt($("#qty").val());

         for(x=serial_items.length-1;x>=0;x--){
            if(serial_code==serial_items[x]){
                var del=serial_items.indexOf(serial_items[x]);
                serial_items.splice(del, 1);
            }
         }


        // globalZ=globalZ-1; 
        $("#serial_"+dis).remove();
        $(this).remove();
        $("#btnExit1").attr("disabled","disabled");
        var n = $( ".removeSerial" ).length;
        if(n==qty){
            $(".removeSerial").attr("disabled","disabled");
        }
    });

        $("#gen").click(function(){
        var free_fix=$("#free_fix").val();
        var post_fix=$("#pst").val();
        var start_no=parseInt($("#abc").val());
        var quantity=parseInt($("#quantity").val());
          
        for(x=0;x<quantity;x++){
            start_no=start_no+1;
            var code_gen=free_fix+start_no.toString()+post_fix;
            $("#srl_"+x).val(code_gen);
        }
     });


     $("#add").click(function(){
            var quantity=parseInt($("#quantity").val());
             for(x=0;x<quantity;x++){
                $("#serial_"+x).val($("#srl_"+x).val());
             }
     });


     $("#clear").click(function(){
        var quantity=parseInt($("#quantity").val());
        for(x=0;x<quantity;x++){
        $("#srl_"+x).val("");
        }

        $("#free_fix").val("");
        $("#pst").val("");
        $("#abc").val("");
        $("#quantity").val("");
     });


     $("#btnSave1").click(function(){
      var validateCount=parseInt($("#qty").val());
      var result=1;
        for(r=validateCount;r>0;r--){
            if($("#serial_"+r).val()==""){    
             result=0;
            }
        } 

      var z=[];  
      var q=0;
      $(".srl_count").each(function(e){
        if($("#serial_"+e).val()!=="" || $("#serial_"+e).val()!== undefined){
          z.push($("#serial_"+e).val());
        }
        
      });  

        z = z.filter(emptyElement);
      var sorted_arr = z.sort();                            
      var results = [];
      for (var i = 0; i < z.length - 1; i++) {
          if (sorted_arr[i + 1] == sorted_arr[i]) {
              results.push(sorted_arr[i]);
          }
      }


       if(result==0){
         alert("Please check the serial number");
       }else{
          if(results.length==0){
       $("#numofserial_"+get_id).removeAttr("title");
       $("#numofserial_"+get_id).removeAttr("value");
       $("#numofserial_"+get_id).attr("title",$("#qty").val());
       $("#numofserial_"+get_id).attr("value",$("#qty").val());
       $("#itemcode_"+get_id).removeAttr("title");
       $("#itemcode_"+get_id).removeAttr("value");
       $("#itemcode_"+get_id).attr("title",$("#item_code").val());
       $("#itemcode_"+get_id).attr("value",$("#item_code").val());


        var get_check=document.getElementById("setserial_"+get_id).value;
        var get_check2=$("#setserial_"+get_id).attr("title");
      
        if(get_check==0 || get_check2=="0"  ){ // $("#setserial_"+scid).val() is 0 means adding new items
                var count=$("#qty").val();
                var item_code=$("#item_code").val();
               
                 $("#setserial_"+get_id).removeAttr("title");
                 $("#setserial_"+get_id).removeAttr("value");
                 $("#setserial_"+get_id).attr("title",1);
                 $("#setserial_"+get_id).attr("value",1);
                 document.getElementById("setserial_"+get_id).value=1;

           for(x=0;x<count;x++){
                if($("#serial_"+x).val()!=""){
                  //serial_items.push(item_code+"-"+$("#serial_"+x).val());
                  insertSerial($("#serial_"+x).val());
                }  
           }

      

        }else{

            var count=$("#qty").val();
            var item_code=$("#item_code").val();

            for(x=serial_items.length-1;x>=0;x--){
                  var get_code=serial_items[x].split("-")[0];
                  var serial_code=serial_items[x].split("-")[1];
                  if(get_code==item_code){
                    var cd=get_code+"-"+serial_code;
                    var del=serial_items.indexOf(cd);
                    serial_items.splice(del, 1);                  
                  }
            }

            $(".srl_count").each(function(e){
              if(e<count){
                  if($("#serial_"+e).val()==""){
                     alert("Please check the serial number");
                  }else{
                   insertSerial($(this).val());
                  }
               }else{  
                alert("Please check the serial number");
              }
            });
           }



      }else{
         alert("Duplicated serial number - "+results);
      }

      }//close if condition

           $("#clear").click();    //if remove
    });


       $(".qun").keydown(function(e){
        set_cid($(this).attr("id"));
         if(e.keyCode == 13){
           if(parseInt($(this).val())>0){
              check_is_serial_item(scid);

                $.post("index.php/main/load_data/t_grn_sum/check_last_serial", {
                   item:$("#0_"+scid).val()
                        }, function(res){
                              $("#last_serial").val(res);
                }, "text");
          }
        
        }
    }); 

      $(".qun").blur(function(){
        set_cid($(this).attr("id"));
        if(parseInt($(this).val())>0){
         
          check_is_serial_item(scid);
           $.post("index.php/main/load_data/t_grn_sum/check_last_serial", {
                   item:$("#0_"+scid).val()
                        }, function(res){
                              $("#last_serial").val(res);
                }, "text");
        }      
      
    }) ;

    $(".fo").keydown(function(e){
        set_cid($(this).attr("id"));
         if(e.keyCode==112){
        $("#pop_search").val($("#0_"+scid).val());
          load_items();    
        $("#serch_pop").center();
        $("#blocker").css("display", "block");
        setTimeout("select_search()", 100);
        }

         if(e.keyCode==13){
            $.post("/index.php/main/load_data/t_grn_sum/get_item", {
                code:$("#0_"+scid).val()
            }, function(res){
                if(res.a!=2){
                    $("#0_"+scid).val(res.a[0].code);

                        if(check_item_exist($("#0_"+scid).val())){
                            $("#h_"+scid).val(res.a[0].code);
                            $("#n_"+scid).val(res.a[0].description);
                            $("#0_"+scid).val(res.a[0].code);
                            $("#1_"+scid).val(res.a[0].model);
                            $("#4_"+scid).val(res.a[0].purchase_price);
                           
                            $("#1_"+scid).focus();
                        }else{
                            alert("Item "+$("#0_"+scid).val()+" is already added.");
                        }

                }
            }, "json");

        }


        if(e.keyCode==46){
             deleteSerial(scid);
            $("#h_"+scid).val("");
            $("#0_"+scid).val("");
            $("#n_"+scid).val("");
            $("#1_"+scid).val(""); 
            $("#2_"+scid).val(""); 
            $("#3_"+scid).val(""); 
            $("#4_"+scid).val(""); 
            $("#5_"+scid).val(""); 
            $("#6_"+scid).val(""); 
            $("#t_"+scid).val(""); 

        discount();
        amount();
        
        gross_amount();
        all_rate_amount();
        additional_amount();
        net_amount();
        
        }

    });


    $(".fo").blur(function(){
     var id=$(this).attr("id").split("_")[1];
     if($(this).val()=="" || $(this).val()=="0"){
     }else if($(this).val()!=$("#itemcode_"+id).val()){
        deleteSerial(id);
     }
   });





     $(".foo").focus(function(){
        set_cid($(this).attr("id"));
        $("#serch_pop2").center();
        $("#blocker2").css("display", "block");
        setTimeout("select_search2()", 100);
    });
     
    $("#tgrid").tableScroll({height:200});
    $("#tgrid2").tableScroll({height:100});

    $("#id,#sub_no").keyup(function(){
        this.value = this.value.replace(/\D/g,'');
    });

    $("#ref_no").keyup(function(){
        this.value = this.value.replace(/[^0-9a-zA-Z]/g,'');
    });

    $("#stores").change(function(){
        set_select("stores","store_no");
    });


   
    $("#supplier_id").autocomplete('index.php/main/load_data/m_supplier/auto_com', {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatItems,
        formatResult: formatItemsResult
        });
   

        $("#supplier_id").keydown(function(e){
            if(e.keyCode == 13){
                set_cus_values($(this));
            }
        });
    
        $("#supplier_id").blur(function(){
            set_cus_values($(this));
        });

     load_items();
     load_items2();


     $("#pop_search").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { load_items();
        }
    });

    $("#pop_search2").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { load_items2();
        }
    });  

    $("#pop_search").gselect();
    $("#pop_search2").gselect(); 

});



function select_search(){
    $("#pop_search").focus();
   
}


 function emptyElement(element) {
    if (element == null || element == 0 || element.toString().toLowerCase() == 'false' || element == '')
       return false;
       else return true;
  }


function select_search2(){
    $("#pop_search2").focus();
   
}

function load_items(){
     $.post("index.php/main/load_data/t_grn_sum/item_list_all", {
        search : $("#pop_search").val(),
        stores : false
    }, function(r){
        $("#sr").html(r);
        settings();
    }, "text");
}

function load_items2(){
     $.post("index.php/main/load_data/r_additional_items/item_list_all", {
        search : $("#pop_search2").val(),
        stores : false
    }, function(r){
        $("#sr2").html(r);
        settings2();
    }, "text");
}

function settings(){
    $("#item_list .cl").click(function(){
        if($(this).children().eq(0).html() != "&nbsp;"){
            if(check_item_exist($(this).children().eq(0).html())){
                $("#h_"+scid).val($(this).children().eq(0).html());
                $("#0_"+scid).val($(this).children().eq(0).html());
                $("#n_"+scid).val($(this).children().eq(1).html());
                $("#1_"+scid).val($(this).children().eq(2).html());
                $("#4_"+scid).val($(this).children().eq(3).html());
              
              
                $("#2_"+scid).focus();
                $("#pop_close").click();
            }else{
                alert("Item "+$(this).children().eq(1).html()+" is already added.");
            }
        }else{
            $("#h_"+scid).val("");
            $("#0_"+scid).val("");
            $("#n_"+scid).val("");
            $("#1_"+scid).val(""); 
            $("#2_"+scid).val(""); 
            $("#3_"+scid).val(""); 
            $("#4_"+scid).val(""); 
            $("#5_"+scid).val(""); 
            $("#6_"+scid).val(""); 
            $("#t_"+scid).val(""); 
            $(".qty").blur();
            

            $("#pop_close").click();
        }
    });
}

function settings2(){
    $("#item_list2 .cl").click(function(){
        if($(this).children().eq(0).html() != "&nbsp;"){
            if(check_item_exist2($(this).children().eq(0).html())){
                
                $("#hh_"+scid).val($(this).children().eq(3).html());
                $("#00_"+scid).val($(this).children().eq(0).html());
                $("#nn_"+scid).val($(this).children().eq(1).html());
                $("#11_"+scid).val($(this).children().eq(2).html());
                $("#hhh_"+scid).val($(this).children().eq(0).html());
                
                if($(this).children().eq(4).html() == 1){
                    $("#11_"+scid).autoNumeric({mDec:2});
                }else{
                    $("#11_"+scid).autoNumeric({mDec:2});
                }
                // $("#1_"+scid).removeAttr("disabled"); 
                // $("#2_"+scid).removeAttr("disabled"); 
                // $("#3_"+scid).removeAttr("disabled");
                //$("#11_"+scid).focus();
                
                
                
               
                rate_amount();
                additional_amount();
                net_amount();
                 $("#pop_close2").click();

                
            }else{
                alert("Item "+$(this).children().eq(1).html()+" is already added.");
            }
        }else{
            $("#hh_"+scid).val("");
            $("#00_"+scid).val("");
            $("#nn_"+scid).val("");
            $("#11_"+scid).val(""); 
            $("#22_"+scid).val(""); 
            $("#hhh_"+scid).val("");
            // $("#3_"+scid).val(""); 
            // $("#t_"+scid).html("&nbsp;");
            // $("#1_"+scid).attr("disabled", "disabled"); 
            // $("#2_"+scid).attr("disabled", "disabled"); 
            // $("#3_"+scid).attr("disabled", "disabled");
            
           
            rate_amount();
            additional_amount();
            net_amount();
            $("#pop_close2").click();

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

function check_item_exist2(id){
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
            v = v.split("|");
             if(v.length == 2){
                f.val(v[0]);
                $("#supplier").val(v[1]);
               // $("#supplier").attr("class", "input_txt_f");

        }
    }


    function formatItems(row){
        return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
    }

    function formatItemsResult(row){
        return row[0]+"|"+row[1];
    }


function save(){
    serial_items.sort();
    $("#srls").attr("title",serial_items);
    $("#srls").val(serial_items);

    var frm = $('#form_');
    loding();
    $.ajax({
  	type: frm.attr('method'),
  	url: frm.attr('action'),
  	data: frm.serialize(),
  	success: function (pid){
              if(pid == 1){
                  input_active();
                  
              }else if(pid == 2){
                  alert("No permission to add data.");
              }else if(pid == 3){
                  alert("No permission to edit data.");
              }else{
                 // alert("Error : \n"+pid);
              }
              loding();
             // location.href="";
          }
    });

     serial_items.splice(0);

     



     
}




function check_code(){
        loding();
    var code = $("#code").val();
    $.post("index.php/main/load_data/t_grn_sum/check_code", {
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
}

function validate(){
    if($("#supplier").val() === $("#supplier").attr('title') || $("#supplier").val() == ""){
        alert("Please Select Supplier");
        $("#supplier_id").focus();
        return false;
    }else if($("#id").val() == ""){
        alert("Please Enter No");
        $("#id").focus();
        return false;
    }else if($("#stores").val() == 0 ){
        alert("Please Select Store");
        $("#stores").focus();
        return false;
    }else if($("#net_amount").val() == 0 ){
        alert("Please Check the Transaction");
        $("#stores").focus();
        return false;
    }else{
        return true;
    }
}
    

function discount(){
    var qty=parseFloat($("#2_"+scid).val());
    var price=parseFloat($("#4_"+scid).val());
    var dis_pre=parseFloat($("#5_"+scid).val());
    var discount="";

    if(!isNaN(qty)&& !isNaN(price) && !isNaN(dis_pre)){
    discount=(qty*price*dis_pre)/100;
    $("#6_"+scid).val(m_round(discount));
    }
    
}

function dis_prec(){
    var qty=parseFloat($("#2_"+scid).val());
    var price=parseFloat($("#4_"+scid).val());
    var discount=parseFloat($("#6_"+scid).val());
    var dis_pre="";

   if(!isNaN(qty)&& !isNaN(price) && !isNaN(discount)){
    dis_pre=(discount*100)/(qty*price);
    $("#5_"+scid).val(m_round(dis_pre));
    }

}

function amount(){
    var qty=parseFloat($("#2_"+scid).val());
    var price=parseFloat($("#4_"+scid).val());
    var discount=parseFloat($("#6_"+scid).val());
    var amount="";


    if(!isNaN(qty)&& !isNaN(price) && !isNaN(discount)){
    dis_pre=(qty*price)-discount;
    $("#t_"+scid).val(m_round(dis_pre));
    }else if(!isNaN(qty)&& !isNaN(price)){
    dis_pre=(qty*price);
    $("#t_"+scid).val(m_round(dis_pre));
    }
}

function gross_amount(){
    var gross=loop=0;

    $(".tf").each(function(){
        var gs=parseFloat($("#t_"+loop).val());
        if(!isNaN(gs)){    
        gross=gross+gs;
        }    
        loop++;
    });
    $("#gross_amount").val(m_round(gross));
}

function rate_amount(){
    var rate_pre=parseFloat($("#11_"+scid).val());
    var gross_amount=parseFloat($("#gross_amount").val());
    var rate_amount="";
    if(!isNaN(rate_pre)&& !isNaN(gross_amount)){
    rate_amount=(gross_amount*rate_pre)/100;
    $("#22_"+scid).val(m_round(rate_amount));
    }
}


function rate_pre(){
    var gross_amount=parseFloat($("#gross_amount").val());
    var rate=parseFloat($("#22_"+scid).val());
    var rate_amount_pre="";

    if(!isNaN(rate)&& !isNaN(gross_amount)){
    rate_amount_pre=(rate*100)/gross_amount;
    $("#11_"+scid).val(m_round(rate_amount_pre));
    }

}



function all_rate_amount(){
    var gross_amount=parseFloat($("#gross_amount").val());  
    var additional=loop=0;
    

    $(".rate").each(function(){
        var rate=parseFloat($("#11_"+loop).val());
        var rate_amount=0;
        if(!isNaN(rate) && !isNaN(rate_amount) ){ 
        rate_amount=(gross_amount*rate)/100;
        $("#22_"+loop).val(m_round(rate_amount));
        }    
        loop++;
    });

    
}

function additional_amount(){
    var additional=loop=0;

    $(".tf").each(function(){
        var add=parseFloat($("#22_"+loop).val());
        var f= $("#hh_"+loop).val();

        if(!isNaN(add)){
        if(f==1){
            additional=additional+add;
         
           //alert(additional+"-- f=1");
            }else{

            additional=additional-add; 
          
           //alert(additional+"-- f=0");   
        }
    }    
        loop++;
    });
    $("#total2").val(m_round(additional));

}

function net_amount(){
    var additional=parseFloat($("#total2").val());
    var gross_amount=parseFloat($("#gross_amount").val());
    var net_amount=0;

    if(!isNaN(additional)&& !isNaN(gross_amount)){
    net_amount=gross_amount+additional;
    $("#net_amount").val(m_round(net_amount));
    }else{
        $("#net_amount").val(net_amount);
    }

}

function load_data(id){
    empty_grid();
    $.post("index.php/main/get_data/t_grn_sum/", {
        id: id
    }, function(r){

            if(r=="2"){
               alert("No records");
            }else{
            $("#hid").val(id);    
            $("#supplier_id").val(r.sum[0].scode);
            $("#supplier").val(r.sum[0].name);
            $("#stores").val(r.sum[0].stcode);
            set_select("stores","store_no");
            $("#pono").val(r.sum[0].po_no);
            $("#credit_period").val(r.sum[0].credit_period);
            $("#pono2").val(r.sum[0].po_no2);
            $("#pono3").val(r.sum[0].po_no3);
            $("#date").val(r.sum[0].ddate);
            $("#inv_no").val(r.sum[0].inv_no);
            $("#ref_no").val(r.sum[0].ref_no);
            $("#ddate").val(r.sum[0].inv_date);
            $("#gross_amount").val(r.sum[0].gross_amount);
            $("#total2").val(r.sum[0].additional);
            $("#net_amount").val(r.sum[0].net_amount);
            $("#id").attr("readonly","readonly")            
           
            for(var i=0; i<r.det.length;i++){
                $("#h_"+i).val(r.det[i].icode);
                $("#0_"+i).val(r.det[i].icode);
                $("#n_"+i).val(r.det[i].idesc);
                $("#1_"+i).val(r.det[i].model);
                $("#2_"+i).val(r.det[i].qty);
                $("#3_"+i).val(r.det[i].foc);
                $("#4_"+i).val(r.det[i].price);
                $("#5_"+i).val(r.det[i].discountp);
                $("#6_"+i).val(r.det[i].discount);
                $("#t_"+i).html(r.det[i].amount);
                 // scid=i; 
                 // amount();


               $("#itemcode_"+i).val(r.det[i].icode);
               $("#2_"+i).val(r.det[i].qty);
               $("#numofserial_"+i).val(r.det[i].qty);
               $("#setserial_"+i).removeAttr("title");
               $("#setserial_"+i).removeAttr("value");
               $("#setserial_"+i).attr("title",1);
               $("#setserial_"+i).attr("value",1);   
            }
            gross_amount();


            if(r.add!=2){
                for(var i=0; i<r.add.length;i++){           
                $("#hhh_"+i).val(r.add[i].type);
                $("#00_"+i).val(r.add[i].type);
                $("#nn_"+i).val(r.add[i].description);
                $("#11_"+i).val(r.add[i].rate_p);
                $("#22_"+i).val(r.add[i].amount);
               
                get_sales_type(i);
                  
            }
        }

             serial_items.splice(0);
                for(var i=0;i<r.serial.length;i++){
                  serial_items.push(r.serial[i].item+"-"+r.serial[i].serial_no);
                }
            serial_items.sort();

            input_active();
    }

}, "json");
}


function get_sales_type(i){
        $.post("index.php/main/load_data/r_additional_items/get_type",{
             id:$("#00_"+i).val()
            },function(res){
               
              $("#hh_"+i).val(res);
              // alert($("#hh_"+i).val());
         },"text");
     
        // alert($("#hh_"+0).val());
        // alert($("#hh_"+1).val());
     
    
    }




function empty_grid(){
    for(var i=0; i<25; i++){
        $("#h_"+i).val("");
        $("#0_"+i).val("");
        $("#n_"+i).val(""); 
        $("#1_"+i).val("");
        $("#2_"+i).val("");
        $("#3_"+i).val("");
        $("#4_"+i).val("");
        $("#5_"+i).val("");
        $("#6_"+i).val("");
        $("#t_"+i).val("");

        $("#hh_"+i).val("");
        $("#hhh_"+i).val("");
        $("#00_"+i).val("");
        $("#nn_"+i).val("");
        $("#11_"+i).val("");
        $("#22_"+i).val("");
}

}



function load_serial_form(scid){
   get_id=scid;
    var serial_table="";
    var serial_table2="";
     $("#item_code").val($("#0_"+scid).val());
     $("#item").val($("#n_"+scid).val());
     $("#no").val($("#id").val());   
     $("#qty,#quantity").val($("#2_"+scid).val()); 
     $("#type").val("3");    //t_trans_code 1
     
     var count=parseInt($("#2_"+scid).val());
     if($("#setserial_"+scid).val()=="1"){
        loading_serial_items(scid);
     }else{
        var serial_table=serial_table2="";
        for(x=0;x<count;x++){
            serial_table=serial_table+"<tr><td><input type='text' id='serial_"+x+"' class='g_input_txt srl_count' style='border:1px solid #000; width:150px;'/></td></tr>";
            serial_table2=serial_table2+"<tr><td><input type='text' class='g_input_txt srl_count2' id='srl_"+x+"' style='border:1px solid #000; width:150px;'/></td></tr>";
        }

     $("#set_serial").html(serial_table);
     $("#set_serial2").html(serial_table2);
    }
      //display popup  
     document.getElementById('light').style.display='block';
     document.getElementById('fade').style.display='block';
}


function loading_serial_items(scid){
   
    var serial_table="";
    var serial_table2="";
    var item_code=$("#0_"+scid).val();


        if(parseInt($("#numofserial_"+scid).val())<=parseInt($("#2_"+scid).val())){
           
             var y=0;
             var num_of_qty=parseInt($("#2_"+scid).val());
             serial_items.sort();
             for(x=0;serial_items.length>x;x++){
                var get_code=serial_items[x].split("-")[0];
                if(get_code==item_code){
                var serial_code=serial_items[x].split("-")[1];
                serial_table=serial_table+"<tr><td><input type='text' id='serial_"+y+"' title='"+serial_code+"' value='"+serial_code+"' class='g_input_txt srl_count' style='border:1px solid #000; width:150px;'/></td></tr>";
                serial_table2=serial_table2+"<tr><td><input type='text' class='g_input_txt srl_count2' id='srl_"+y+"' style='border:1px solid #000; width:150px;'/></td></tr>";
                y++;
                }
            }

            var count=parseInt($("#2_"+scid).val())-parseInt($("#numofserial_"+scid).val());
             
             for(x=0;count>x;x++){
               serial_table=serial_table+"<tr><td><input type='text' id='serial_"+y+"' class='g_input_txt srl_count' style='border:1px solid #000; width:150px;'/></td></tr>";
               serial_table2=serial_table2+"<tr><td><input type='text' class='g_input_txt srl_count2' id='srl_"+y+"' style='border:1px solid #000; width:150px;'/></td></tr>"; 
               y++;
            }

       }else{
        serial_items.sort();
        for(x=0;serial_items.length>x;x++){
                var get_code=serial_items[x].split("-")[0];
                if(get_code==item_code){
                var serial_code=serial_items[x].split("-")[1];
                serial_table=serial_table+"<tr><td><input type='text' id='serial_"+x+"' title='"+serial_code+"' value='"+serial_code+"' class='g_input_txt srlcd srl_count' style='border:1px solid #000; width:150px;'/><input type='button' title='Remove' value='Remove' style='width:75px;height:22px;border:1px solid #000;border-radius:0' class='removeSerial' id='removeSeri_"+x+"' /></td></tr>";
                serial_table2=serial_table2+"<tr><td><input type='text' class='g_input_txt srl_count2' id='srl_"+x+"' style='border:1px solid #000; width:150px;'/></td></tr>";
                }
       }

    }

     $("#set_serial").html(serial_table);
     $("#set_serial2").html(serial_table2);
}


function deleteSerial(scid){
            var item_code= $("#itemcode_"+scid).val();
            for(x=serial_items.length-1;x>=0;x--){
                   var get_code=serial_items[x].split("-")[0];
                   var serial_code=serial_items[x].split("-")[1];
                  if(get_code==item_code){
                    var cd=get_code+"-"+serial_code;
                    var del=serial_items.indexOf(cd);
                    serial_items.splice(del, 1);
                  }
            }

            $("#numofserial_"+scid).removeAttr("title");
            $("#numofserial_"+scid).removeAttr("value");
            $("#setserial_"+scid).removeAttr("title");
            $("#setserial_"+scid).removeAttr("value");
            $("#itemcode_"+scid).removeAttr("value");
            $("#itemcode_"+scid).removeAttr("title");

}

//{/serial}


function check_is_serial_item(scid){
    var item_code=$("#0_"+scid).val();
    if(item_code!=""){
     $.post("index.php/main/load_data/t_grn_sum/check_is_serial_item",{
            code:item_code,
         },function(r){
            if(r==1){
              load_serial_form(scid);
            }
         },"text"); 
   }
}


function insertSerial(x){   
    //var serial=x;  
    //      $.post("index.php/main/load_data/t_grn_sum/is_serial_available", {
    //            nno:$("#id").val(), 
    //            serial:serial,
    //            item:$("#item_code").val()
    //         }, function(res){
    //               serial_window(res,x);
    // }, "text");

  serial_window(x);//if remove

}


//function serial_window(res,x){
function serial_window(x){ // if remove
       var item_code=$("#item_code").val();
       var count=$("#qty").val();
       var serial=x; 

       serial_items.push($("#item_code").val()+"-"+serial);// if remove

        // if(res=="1"){
        //  alert("This "+serial+" no is already in stock."); 
        //  serialWind=1;   
         
        // }else if(x==""){
        //    alert("Please check the serial number 1");
           
        // }else if($(".srl_count").length !=count){
        //    alert("Please check the serial number 2");
          
        // }else{
        //    serial_items.push($("#item_code").val()+"-"+serial);
        //    serialWind=0;
          
        // }

        //alert($(".srl_count").length +"===="+count);


         // if($(".srl_count").length ==count && res=="0"){
                  //$("#clear").click();
                   document.getElementById('light').style.display='none';
                   document.getElementById('fade').style.display='none';
                  // $("#btnExit1").removeAttr("disabled");
                  //  globalZ=0;
                  // alert(serial_items);
         // }

         // if(serialWind==1){
         //   for(x=serial_items.length-1;x>=0;x--){
         //          var get_code=serial_items[x].split("-")[0];
         //          var serial_code=serial_items[x].split("-")[1];
         //          if(get_code==item_code){
         //            var cd=get_code+"-"+serial_code;
         //            var del=serial_items.indexOf(cd);
         //            serial_items.splice(del, 1);                  
         //          }
         //    }

         //   $(".srl_count").each(function(e){
         //      if(e<count){
         //          if($("#serial_"+e).val()==""){
         //             alert("Please check the serial number");
         //          }else{

         //              if($("#serial_"+x).val()!=""){
         //                  serial_items.push(item_code+"-"+$(this).val());
         //              } 
         //          }
         //       }else{  
         //        alert("Please check the serial number");
         //      }
         //    });

         //  load_serial_form(get_id);         
         // }

}

