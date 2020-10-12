var storse = 0;
var is_edit=0;
 var array_serial="";
//{serial}
 var serial_items=Array();

//{/serial}

$(document).ready(function(){

   



     $("#btnPrint").click(function(){
     loading_serial_items();
     document.getElementById('light').style.display='block';
     document.getElementById('fade').style.display='block';
     });

//{serial}
   $(".fo").blur(function(){
     var id=$(this).attr("id").split("_")[1];
     if($(this).val()=="" || $(this).val()=="0"){
     }else if($(this).val()!=$("#itemcode_"+id).val()){
        deleteSerial(id);
     }
   });


    // $(".srl_count").keyup(function(){
    //     this.value = this.value.replace(/[^0-9\.a-z,',A-Z]/g,'');
    // });


    // $("#set_serial").delegate(".srl_count", "keyup", function(){
    //    this.value = this.value.replace(/[^0-9\.a-z,',A-Z]/g,'');
    // });

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


       if(result==0){
         alert("Please check the serial number");
       }else{

       $("#numofserial_"+scid).removeAttr("title");
       $("#numofserial_"+scid).removeAttr("value");
       $("#numofserial_"+scid).attr("title",$("#qty").val());
       $("#numofserial_"+scid).attr("value",$("#qty").val());

       $("#itemcode_"+scid).removeAttr("title");
       $("#itemcode_"+scid).removeAttr("value");
       $("#itemcode_"+scid).attr("title",$("#item_code").val());
       $("#itemcode_"+scid).attr("value",$("#item_code").val());


       

        if($("#setserial_"+scid).val()==0){ // $("#setserial_"+scid).val() is 0 means adding new items
                var count=$("#qty").val();
                var item_code=$("#item_code").val();

           for(x=0;x<count;x++){
            
             serial_items.push($("#item_code").val()+"-"+$("#serial_"+x).val());
           }

       $("#setserial_"+scid).removeAttr("title");
       $("#setserial_"+scid).removeAttr("value");
       $("#setserial_"+scid).attr("title",1);
       $("#setserial_"+scid).attr("value",1);

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
                    // break;
                  }else{
                    var ser=$(this).val();
                   // insertSerial2(ser);
                    serial_items.push(item_code+"-"+$(this).val());
                  }
                }else{  
                  alert("Please check the serial number");
                 // break;
                }           
            });


        }
      
        $("#clear").click();
        $("#2_"+scid).focus();
        $("#btnExit1").removeAttr("disabled");
        document.getElementById('fade').style.display='none';
        document.getElementById('light').style.display='none';
      
       
       

         }//close if condition



        
        
       

    });//close save1 
   
//{/serial}

    $("#item_list").tableScroll({height:200});
    $("#stores").val(storse);
    set_select('stores', 'sto_des');

    $(".fo").keydown(function(e){


        set_cid($(this).attr("id"));
        if(e.keyCode==112){
            $("#pop_search").val($("#0_"+scid).val());
            load_items();
            $("#pop_search").select();       
            $("#serch_pop").center();
            $("#blocker").css("display", "block");
            setTimeout("select_search()", 100);
        }

        if(e.keyCode==46){

            deleteSerial(scid);
           
            $("#h_"+scid).val("");
            $("#0_"+scid).val("");
            $("#n_"+scid).val("");
            $("#1_"+scid).val(""); 
            $("#2_"+scid).val(""); 
            $("#3_"+scid).val(""); 
            $("#t_"+scid).html("&nbsp;"); 
             set_sub_total();
        }


        if(e.keyCode==13){
            $.post("/index.php/main/load_data/t_open_stock/get_item", {
                code:$("#0_"+scid).val()
            }, function(res){
                if(res.a!=2){
                    $("#0_"+scid).val(res.a[0].code);

                        if(check_item_exist($("#0_"+scid).val())){
                            $("#h_"+scid).val(res.a[0].code);
                            $("#n_"+scid).val(res.a[0].description);
                            $("#0_"+scid).val(res.a[0].code);
                            $("#2_"+scid).val(res.a[0].purchase_price);
                            
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
    
    $("#tgrid").tableScroll({height:300});

    $("#pop_search").gselect();

   
    $("#nno").keydown(function(e){
        if(e.keyCode == 13){
            $(this).blur();
            load_data($(this).val());
        }
    });





   function load_data(id){
    empty_grid();
    $.post("index.php/main/get_data/t_open_stock/", {
        nno: id
    }, function(r){
            
            if(r=="2"){
               alert("No records");
            }else{
             
            $("#code_").val(r.det[0].nno);
            $("#nno").attr("readonly",'readonly');
            $("#stores").val(r.det[0].store);
            set_select('stores','sto_des');

            $("#ddate").val(r.det[0].ddate);
            $("#ref_no").val(r.det[0].ref_no);
            $("#total2").val(r.det[0].net_amount);

            for(var i=0; i<r.det.length;i++){
                $("#h_"+i).val(r.det[i].item);
                $("#0_"+i).val(r.det[i].item);
                $("#itemcode_"+i).val(r.det[i].item);
                $("#n_"+i).val(r.det[i].item_desc);
                $("#1_"+i).val(r.det[i].qty_in);
                 $("#numofserial_"+i).val(r.det[i].qty_in);
                 $("#setserial_"+i).removeAttr("title");
                 $("#setserial_"+i).removeAttr("value");
                 $("#setserial_"+i).attr("title",1);
                 $("#setserial_"+i).attr("value",1);

               
                
                $("#2_"+i).val(r.det[i].cost);
                    var x = parseFloat($("#1_"+i).val());
                    var y = parseFloat($("#2_"+i).val());
                    var z;
                    if(! isNaN(x) && ! isNaN(y)){
                        z = x*y;
                        $("#t_"+i).html(m_round(z));
                    }else{
                        $("#t_"+i).html("0.00");
                    }
            }

            serial_items.splice(0);
            for(var i=0;i<r.serial.length;i++){
              serial_items.push(r.serial[i].item+"-"+r.serial[i].serial_no);

            }
            alert(serial_items);

            input_active();
    }}, "json");
}


    
   $("#btnDelete5").click(function(){
        if(nno==""){
            alert('Please enter no');
        }else{      
        var nno=$("#nno").val();
         $.post("index.php/main/load_data/t_open_stock/check_code",{
            nno:nno,
         },function(r){
            if(r==1){
                set_delete(nno);
            }else{
               alert("There is no data "); 
            }
         });      
        }
    });
    
    $("#pop_search").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { load_items();}
    });
    

       $(".amo, .qun, .dis").blur(function(){
        set_cid($(this).attr("id"));    
        set_sub_total();  
       });

       $(".qun").blur(function(){
        check_is_serial_item(scid);
       });

//{serial}
    // $(".qun").keydown(function(e){
    //     set_cid($(this).attr("id"));
    //      if(e.keyCode == 13){
    //       check_is_serial_item(scid);
    //     }
    // }) 
//{/serial}
    
    $("#id").keydown(function(e){
        if(e.keyCode == 13){
            $(this).blur();
            load_data($(this).val());
        }
    });
    

    
    $("#stores").change(function(){
        set_select('stores', 'sto_des');
        load_items();
    });


    //serial coding

    $("#btnExit1").click(function(){
        
         document.getElementById('light').style.display='none';
         document.getElementById('fade').style.display='none';
    });    

});

    $(document).keydown(function(e){
        if(e.keyCode == 112){
            $("#0_0").focus();
        }
    });

function set_delete(id){
    if(confirm("Are your sure, you want to delete no"+id)){
        $.post("index.php/main/delete/t_open_stock",{
            id:id
        },function(r){

            alert(r)
        });
    }else{
       $("#btnReset").click();
    }
}

function empty_grid(){
    for(var i=0; i<25; i++){
        $("#h_"+i).val(0);
        $("#is_ser_"+i).val(0);
        $("#is_ser_upt_"+i).val(0);
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
    var z;
    if(! isNaN(x) && ! isNaN(y)){
        z = x*y;
        $("#t_"+scid).html(m_round(z));
    }else{
        $("#t_"+scid).html("0.00");
    }
    
    set_total();
}





function select_search(){
    $("#pop_search").focus();
  
}


function load_items(){
    $.post("index.php/main/load_data/t_open_stock/item_list_all", {
        search : $("#pop_search").val(),
        stores : false
    }, function(r){
        $("#sr").html(r);
        settings();
        //$("#pop_search").select();
      
    }, "text");
}

function settings(){
    $("#item_list .cl").click(function(){
        if($(this).children().eq(0).html() != "&nbsp;"){
            if(check_item_exist($(this).children().eq(0).html())){
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
                $("#1_"+scid).removeAttr("disabled"); 
                $("#2_"+scid).removeAttr("disabled");
                $("#3_"+scid).removeAttr("disabled");
                $("#1_"+scid).focus();$("#pop_close").click();
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
            set_total();
            $("#pop_close").click();
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


function set_total(){
    var t = tt = 0; 
    $(".tf").each(function(){
        tt = parseFloat($(this).html());
        if(isNaN(tt)){ tt = 0;}
        t += tt;
    });
    
    $("#total2").val(m_round(t));
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
    }else if($("#stores option:selected").val() == 0){
        alert("Please select stores");
        v = false;
    }
    
    return v;

}

function save(){

    $("#srls").attr("title",serial_items);
    $("#srls").val(serial_items);
    alert( $("#srls").val());
    var frm = $('#form_');
    
    $.ajax({
    type: frm.attr('method'),
    url: frm.attr('action'),
    data:frm.serialize(),
    success: function (pid){
            if(pid == 1){
                 location.reload();
            }else if(pid == 2){
                alert("No permission to add data.");
            }else if(pid == 3){
                alert("No permission to edit data.");
            }else{
              //alert("Errorrrrrrrrrrrrrrr : \n"+pid);
            }
    //loding();
        }

    });

    //{serial}

    serial_items.splice(0);

    //{/serial}

}

//{serial}

function load_serial_form(scid){
     $("#item_code").val($("#0_"+scid).val());
     $("#item").val($("#n_"+scid).val());
     $("#no").val($("#nno").val());   
     $("#qty,#quantity").val($("#1_"+scid).val()); 
     $("#type").val("1");    //t_trans_code 1
     
     var count=parseInt($("#1_"+scid).val());
     if($("#setserial_"+scid).val()=="1"){
        loading_serial_items();
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


function loading_serial_items(){
    
    var serial_table="";
    var serial_table2="";
    var item_code=$("#0_"+scid).val();


        if(parseInt($("#numofserial_"+scid).val())<=parseInt($("#1_"+scid).val())){
             var y=0;
             var num_of_qty=parseInt($("#1_"+scid).val());
            for(x=0;serial_items.length>x;x++){
             var get_code=serial_items[x].split("-")[0];
             if(get_code==item_code){
                var serial_code=serial_items[x].split("-")[1];
                serial_table=serial_table+"<tr><td><input type='text' id='serial_"+y+"' title='"+serial_code+"' value='"+serial_code+"' class='g_input_txt srl_count' style='border:1px solid #000; width:150px;'/></td></tr>";
                serial_table2=serial_table2+"<tr><td><input type='text' class='g_input_txt srl_count2' id='srl_"+y+"' style='border:1px solid #000; width:150px;'/></td></tr>";
                y++;
             }
           }

            var count=parseInt($("#1_"+scid).val())-parseInt($("#numofserial_"+scid).val());
             
             for(x=0;count>x;x++){
               serial_table=serial_table+"<tr><td><input type='text' id='serial_"+y+"' class='g_input_txt srl_count' style='border:1px solid #000; width:150px;'/></td></tr>";
               serial_table2=serial_table2+"<tr><td><input type='text' class='g_input_txt srl_count2' id='srl_"+y+"' style='border:1px solid #000; width:150px;'/></td></tr>"; 
               y++;
            }

       }else{

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


function insertSerial(x){

    var serial=$("#serial_"+x).val();
    var serial_window=0;
         $.post("/index.php/main/load_data/t_open_stock/is_serial_available", {
               nno:$("#nno").val(), 
               serial:serial,
               item:$("#item_code").val()
            }, function(res){
              
                   if(res=="0"){
                    serial_items.push($("#item_code").val()+"-"+serial);
                  }else{
                    serial_window=1;
                    alert("This "+serial+" no is already in stock.");
                  }
               //serial_items.push($("#item_code").val()+"-"+serial);
    }, "text");

    // if(serial_window==0){
    
   
    // }else{
    //   alert("This "+serial+" no is already in stock.");
    // }     
 
}


function insertSerial2(v){
 // alert(v);
        var serial=v;
        var serial_window=0;
         $.post("/index.php/main/load_data/t_open_stock/is_serial_available", {
               nno:$("#nno").val(), 
               serial:serial,
               item:$("#item_code").val()
            }, function(res){
              
                   if(res=="0"){
                    serial_items.push($("#item_code").val()+"-"+v);
                  }else{
                    serial_window=1;
                    alert("This "+serial+" no is already in stock.");
                  }
               //serial_items.push($("#item_code").val()+"-"+serial);
    }, "text");

}

//{/serial}


function check_is_serial_item(scid){
    var item_code=$("#0_"+scid).val();
    if(item_code!=""){
     $.post("index.php/main/load_data/t_open_stock/check_is_serial_item",{
            code:item_code,
         },function(r){
            if(r==1){
              load_serial_form(scid);
            }
         },"text"); 
   }
}

