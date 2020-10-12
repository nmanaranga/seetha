$(document).ready(function(){
          $("#id").keypress(function(e){
            if(e.keyCode == 13){
                $(this).blur();
                load_data($(this).val());
                 $("#qno").val($('#id').val());

            }
        });
     
        $("#grid").tableScroll({height:355});
        $("#tgrid").tableScroll({height:355});
         $("#qno").val($('#id').val());
         $("#cus_id").val($('#customer').val());
   
        $("#click").click(function(){
            var x=0;
            $(".me").each(function(){
            alert(x);
            if($(this).val() != "" && $(this).val() != 0){
            v = true;
            }
            x++;
        });


       });

        $(".fo").keypress(function(e){
            set_cid($(this).attr("id"));
            if(e.keyCode==112){
                
                 $("#pop_search").val($("#0_"+scid).val());
                 load_items();
                 $("#serch_pop").center();
                 $("#blocker").css("display", "block");
                 setTimeout("select_search()", 100);
             }

              if(e.keyCode==46){
                
                
                $("#h_"+scid).val("");
                $("#0_"+scid).val("");
                $("#n_"+scid).val("");
                $("#1_"+scid).val(""); 
                $("#2_"+scid).val(""); 
                $("#3_"+scid).val(""); 
                $("#4_"+scid).val(""); 
                $("#5_"+scid).val("");
                $("#t_"+scid).html("&nbsp;"); 
                    set_discount();
                    total();
                    set_total();
            }
            

            if(e.keyCode==13){
            $.post("/index.php/main/load_data/t_quotation_sum/get_item", {
                code:$("#0_"+scid).val()
            }, function(res){
                if(res.a!=2){
                    $("#0_"+scid).val(res.a[0].code);

                        if(check_item_exist($("#0_"+scid).val())){
                            $("#h_"+scid).val(res.a[0].code);
                            $("#n_"+scid).val(res.a[0].description);
                            $("#0_"+scid).val(res.a[0].code);
                            $("#1_"+scid).val(res.a[0].model);
                            $("#3_"+scid).val(res.a[0].max_price);
                           
                        }else{
                            alert("Item "+$("#0_"+scid).val()+" is already added.");
                        }

                }
            }, "json");

        }
    

            });

        load_items();
        $("#pop_search").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { load_items();
            }
        });
    
        $(".dis, .qun, .dis_pre").blur(function(){
          set_cid($(this).attr("id"));
          set_discount();
          total();
          set_total();
        
        });

        $(".free_is").blur(function (){
            set_cid($(this).attr("id"));
            set_discount2();
            total();
            set_total();
        });

    
    
        $("#pop_search").gselect();


        $("#customer").autocomplete('index.php/main/load_data/m_customer/auto_com', {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatItems,
        formatResult: formatItemsResult
        });
    
        $("#customer").keypress(function(e){
            if(e.keyCode == 13){
                set_cus_values($(this));
            }
        });
    
        $("#customer").blur(function(){
            set_cus_values($(this));
        });


$("#btnDelete").click(function(){
    var id=$("#id").val();
        if(id==""){
            alert('Please enter no');
        }else{      
        var id=$("#id").val();
         $.post("index.php/main/load_data/t_quotation_sum/check_code",{
            id:id,
         },function(r){
           
            if(r==1){
                set_delete(id);
            }else{
               alert("There is no data "); 
            }
         });      
        }
    });


  $("#btnPrint").click(function(){
  	$("#cus_id").val($('#customer').val());
		$("#print_pdf").submit();
       });



});



    function set_cus_values(f){
            var v = f.val();
            v = v.split("-");
            
                if(v.length == 2){
                //$("#vehicle_no").val(v[0]);
                f.val(v[0]);
                $("#customer_id").val(v[1]);
               // $("#customer_id").attr("class", "input_txt_f");
                var cus=$("#customer").val();
                $.post("index.php/main/load_data/m_customer/load",
                {
                code:cus,
                },function(rs){
             
                 $("#address").val(rs.data.address1+", "+rs.data.address2+", "+rs.data.address3); 
                 input_active();
                 },"json");

        }
    }

    function formatItems(row){
        return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
    }

    function formatItemsResult(row){
        return row[0]+"-"+row[1];
    }


    function save(){
         $("#form_").submit();
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
        $("#4_"+i).val("");
        $("#5_"+i).val("");
        
    }
}



  function load_data(id){
    empty_grid();
    $.post("index.php/main/get_data/t_quotation_sum/", {
        id: id
    }, function(r){
            if(r=="2"){
               alert("No records");
            }else{
            $("#hid").val(id);    
            $("#customer").val(r.det[0].ccode);
            $("#customer_id").val(r.det[0].name);
            $("#address").val(r.det[0].address1+", "+r.det[0].address2+", "+r.det[0].address3);
            $("#date").val(r.det[0].ddate);
            $("#ref_no").val(r.det[0].ref_no);
            $("#memo").val(r.det[0].memo);
            $("#total2").val(r.det[0].gross_amount);
            $("#discount").val(r.det[0].dis);
            $("#net_amount").val(r.det[0].net_amount);
            $("#validity_period").val(r.det[0].validity_period);
            $("#id").attr("readonly","readonly")            
           
            for(var i=0; i<r.det.length;i++){
                $("#h_"+i).val(r.det[i].code);
                $("#0_"+i).val(r.det[i].code);
                $("#n_"+i).val(r.det[i].description);
                $("#1_"+i).val(r.det[i].model);
                $("#2_"+i).val(r.det[i].qty);
                $("#3_"+i).val(r.det[i].price);
                $("#4_"+i).val(r.det[i].discountp);
                $("#5_"+i).val(r.det[i].discount);
                    var x = parseFloat($("#3_"+i).val());
                    var y = parseFloat($("#2_"+i).val());
                    var z;
                    if(! isNaN(x) && ! isNaN(y)){
                        z = x*y;
                        $("#t_"+i).html(m_round(z));
                    }else{
                        $("#t_"+i).html("0.00");
                    }
            }
            input_active();
    }

}, "json");
}






function check_code(){
  //       loding();
  //   var code = $("#code").val();
  //   $.post("index.php/main/load_data/t_qutation_sum/check_code", {
  //       code : code
  //   }, function(res){
  //       if(res == 1){
  //           if(confirm("This code ("+code+") already added. \n\n Do you need edit it?")){
  //               set_edit(code);
  //           }else{
  //               $("#code").val('');
		// $("#code").attr("readonly", false);
  //           }
  //       }
  //       loding();
  //   }, "text");
}



function validate(){

    var v = true;
   
    for(x=0;x<24;x++){
        if($("#0_"+x).val()!="" || $("#0_"+x).val()){

        }
    }

    

    if($("#id").val() == ""){
        alert("Please enter No.");
        $("#id").focus();
        return false;
    }else if($("#date").val() == ""){
        alert("Please select date");
        $("#date").focus();
        return false;
    }else if($("#customer_id").val()=="" || $("#customer_id").val()==$("#customer_id").attr("title")){
        alert("Please select a customer.");
        $("#customer_id").focus();
        return false;
    }else if(v == false){
        alert("Please use minimum one item.");
    }else{
        return true;
    }
}


    
function set_delete(id){
    if(confirm("Are you sure delete "+id+"?")){
        loding();
        $.post("index.php/main/delete/t_quotation_sum", {
            id : id
        }, function(res){
          
            if(res == 1){
               
            }else{
                alert("Item deleting fail.");
            }
            loding();
        }, "text");
    }
}

function is_edit($mod)
{
    //$.post("index.php/main/is_edit/user_permissions/is_edit", {
    //     module : $mod
        
    // }, function(r){
    //    if(r==1)
    //        {
    //          $("#btnSave").removeAttr("disabled", "disabled");
    //        }
    //    else{
    //          $("#btnSave").attr("disabled", "disabled");
    //    }
       
    // }, "json");

}
    
function set_edit(code){
 //    loding();
 //    $.post("index.php/main/get_data/t_qutation_sum", {
 //        code : code
 //    }, function(res){
 //        $("#code_").val(res.code);
 //        $("#code").val(res.code);
	// $("#code").attr("readonly", true);
 //        $("#description").val(res.description);
        
 //           if(res.is_vehical == 1){
 //            $("#is_vehical").attr("checked", "checked");
 //        }else{
 //            $("#is_vehical").removeAttr("checked");
 //        }
        
 //        loding(); input_active();
 //    }, "json");
}


function select_search(){
    $("#pop_search").focus();
}

function load_items(){
    $.post("index.php/main/load_data/t_quotation_sum/item_list_all", {
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
                $("#h_"+scid).val($(this).children().eq(0).html());
                $("#0_"+scid).val($(this).children().eq(0).html());
                $("#n_"+scid).val($(this).children().eq(1).html());
                $("#1_"+scid).val($(this).children().eq(2).html());
                $("#3_"+scid).val($(this).children().eq(3).html());
                
                if($(this).children().eq(4).html() == 1){
                    $("#11_"+scid).autoNumeric({mDec:2});
                }else{
                    $("#11_"+scid).autoNumeric({mDec:0});
                }
                $("#1_"+scid).removeAttr("disabled"); 
                $("#2_"+scid).removeAttr("disabled"); 
                $("#3_"+scid).removeAttr("disabled");
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
            $("#t_"+scid).html("&nbsp;");
            $("#1_"+scid).attr("disabled", "disabled"); 
            $("#2_"+scid).attr("disabled", "disabled");
            $("#3_"+scid).attr("disabled", "disabled");
            $("#0_"+scid).focus();
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





function set_sub_total(){
    var x = parseFloat($("#1_"+scid).val());
    var y = parseFloat($("#2_"+scid).val());
    var z;
    if(! isNaN(x) && ! isNaN(y)){
        z = x*y;
        $("#t_"+scid).html(m_round(z));
    }else{
        $("#t_"+scid).html("0.00");
    }
    
    set_total();
}

function set_total2(){
    var t = tt = 0; 
    $(".tf").each(function(){;
        tt = parseFloat($(this).html());
        if(isNaN(tt)){ tt = 0;}
        t += tt;
    });
    
    $("#total2").val(m_round(t));
}

function set_total(){
    var gross = 0;
    var discount=0;
    var net=0;
    var t=0;
    $(".tf").each(function(){
        var x = parseFloat($("#2_"+t).val());
        var y = parseFloat($("#3_"+t).val());
        var z = parseFloat($("#5_"+t).val());
        if(isNaN(x)||isNaN(y)){ x=0; y=0;}
        gross += x*y;
        if(isNaN(z)){z=0;}
        discount +=z;
        net=gross-discount;
        t++;
    });
    
   $("#total2").val(m_round(gross));
   $("#discount").val(m_round(discount));
   $("#net_amount").val( m_round(net));
}


function total(){
    var w = parseFloat($("#4_"+scid).val());
    var x = parseFloat($("#2_"+scid).val());
    var y = parseFloat($("#3_"+scid).val());
    var z = parseFloat($("#5_"+scid).val());
    var a;
    var b;

    if(!isNaN(x) && !isNaN(y) && !isNaN(z) && !isNaN(w)){
         
         a=x*y;
         b=(a*w)/100;
         a=a-z;
         $("#t_"+scid).html(m_round(a));   
    }else if(!isNaN(x) && !isNaN(y) && !isNaN(z)){
        
         a=x*y-z;
         $("#t_"+scid).html(m_round(a));
    }else if(!isNaN(x) && !isNaN(y)){
        
         a=x*y;
         $("#t_"+scid).html(m_round(a));
    }else{
        
         $("#t_"+scid).html("0.00");
    }


}

function set_discount(){
    var x = parseFloat($("#3_"+scid).val());
    var y = parseFloat($("#2_"+scid).val());
    var z = x*y;
    var d = parseFloat($("#4_"+scid).val());
    if(isNaN(d)){ d = 0; }
    d = z*(d/100);
    if(isNaN(d)){d=0;}
    $("#5_"+scid).val(m_round(d));

    }

 function set_discount2(){
    var x = parseFloat($("#3_"+scid).val());
    var y = parseFloat($("#2_"+scid).val());
    var z = x*y;
    var d = parseFloat($("#5_"+scid).val());
    if(isNaN(d)){ d = 0; }
    d = z-d;

    d = parseFloat($("#5_"+scid).val());
    if(isNaN(d)){ d = 0; }


    $("#t_"+scid).val(m_round(d));
    var a=d*100/z;

    if(isNaN(a)){ a = 0; }
    $("#4_"+scid).val(a);
    $("#4_"+scid).click();

    }   
