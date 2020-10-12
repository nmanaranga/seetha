  var serial_items=[];
  var get_id;
  var serialWind;
  var current_serial_no;
  
  $(document).ready(function(){

  	$("#tgrid").tableScroll({height:200});
	
	$("#tgrid1").tableScroll({height:200, width:875});

  	$("#btnPrint").click(function(){
  		if($("#hid").val()=="0"){
  				alert("Please load data before print");
        	return false;
  		}
  		else{
  		 $("#print_pdf").submit();
  		}
  	})

       $("#pop_search44").keyup(function(e){
          if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { 
            seriall_items();
          }
        });

          $("#free_fix,#pst").blur(function(){
            var get_code=$(this).val();
            $(this).val(get_code.toUpperCase());
          });

       $("#pop_search44").gselect();

       $("#set_serial").delegate(".srl_count","keydown",function(e){

            if(e.keyCode==112){

               $("#pop_search4").select(); 
               $("#serch_pop4").center();
               $("#blocker4").css("display", "block");
               current_serial_no=this;
               seriall_items();
               setTimeout("select_search4()", 100);
               
            }

         });

       	$(document).on('keyup','.vali', function(){
       	//$(".vali").keyup(function(){
			set_cid($(this).attr("id"));
         	if($("#2_"+scid).val() !="" )
         	{
         		if(parseFloat($("#2_"+scid).val())>parseFloat($("#rmax_"+scid).val()))
         		{
         			alert("Maximum available quntity is " +$("#rmax_"+scid).val());	
         			$(this).val("0");
         		}
	        } 
    	 });



        $(".fo").blur(function(){
         var id=$(this).attr("id").split("_")[1];
         if($(this).val()=="" || $(this).val()=="0"){
         }else if($(this).val()!=$("#itemcode_"+id).val()){
            deleteSerial(id);
         }
        });

      $("#btnExit1").click(function(){
        document.getElementById('light').style.display='none';
        document.getElementById('fade').style.display='none';  
        $("#2_"+get_id).focus();
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
              results.push(sorted_arr[i],i);
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
          results.sort();
          alert("Duplicated serial number - "+results[1]+" at Line "+(results[0]+1));
      }

      }//close if condition
    });

        
        $("#tgrid").tableScroll({height:200});
        $("#tgrid2").tableScroll({height:100});

         $("#id").keyup(function(){
            this.value = this.value.replace(/[^0-9\.a-z,',A-Z]/g,'');
        });
        

        $("#id").keydown(function(e){
            if(e.keyCode == 13){
                $(this).blur();
                load_data($(this).val());
            }
        });


         $("#stores").change(function(){
          set_select('stores','store_id');
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


         $(".fo").keydown(function(e){
            set_cid($(this).attr("id"));
            if(e.keyCode==112){

              if($("#stores").val()==0)
              {
                alert("Please select store");
                return false;
              }
              else
              {
                $("#pop_search").val($("#0_"+scid).val());
                load_items();    
                $("#serch_pop").center();
                $("#blocker").css("display", "block");
                setTimeout("select_search()", 100);
              }
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

     $(".qun").keydown(function(e){
        set_cid($(this).attr("id"));
         if(e.keyCode == 13){
           if(parseInt($(this).val())>0){
              check_is_serial_item(scid);

                $.post("index.php/main/load_data/t_pur_ret_sum/check_last_serial", {
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
           $.post("index.php/main/load_data/t_pur_ret_sum/check_last_serial", {
                   item:$("#0_"+scid).val()
                        }, function(res){
                              $("#last_serial").val(res);
                }, "text");
        }      
      
    }) ;    


     $(".foo").focus(function(){
        set_cid($(this).attr("id"));
        $("#serch_pop2").center();
        $("#blocker2").css("display", "block");
        setTimeout("select_search2()", 100);
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
     
      load_items();
      load_items2();

    $("#pop_search").gselect();
    $("#pop_search2").gselect(); 
    $("#pop_search3").gselect(); 

    $("#pop_search").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { load_items();
        }
    });




   $("#grnno").keydown(function(e){
 
    if(e.keyCode == 13){
            empty_grid2();
			empty_grid()

           
            $.post("index.php/main/load_data/t_pur_ret_sum/get_invoice", {
                code:$("#grnno").val()
            }, function(r)
            {
            	if(r.det==2){
            		alert("No records");
            	}
            	else{
              
            	$("#supplier_id").val(r.sum[0].supp_id);
            	$("#supplier").val(r.sum[0].name);
            	$("#stores").val(r.sum[0].store);
            	$("#store_id").val(r.sum[0].store_name);

              $("#supplier_id").attr("readonly", "readonly");
              $("#supplier").attr("readonly", "readonly");
              $("#stores").attr("readonly", "readonly");
              $("#store_id").attr("readonly", "readonly");

               for(var i=0; i<r.det.length;i++){
               
                $("#numofserial_"+i).val(r.det[i].qty);
                $("#setserial_"+i).removeAttr("title");
                $("#setserial_"+i).removeAttr("value");
                $("#setserial_"+i).attr("title",1);
                $("#setserial_"+i).attr("value",1);   
                $("#h1_"+i).val(r.det[i].code);
                $("#01_"+i).val(r.det[i].code);
                $("#n1_"+i).val(r.det[i].iName);
                $("#101_"+i).val(r.det[i].model);
                $("#21_"+i).val(r.det[i].qty);
                $("#31_"+i).val(r.det[i].batch_no);
                $("#41_"+i).val(r.det[i].price);
                $("#51_"+i).val(r.det[i].discountp);
                $("#61_"+i).val(r.det[i].discount);
               // $("#t1_"+i).val(r.det[i].amount);
                $("#rq_"+i).val(r.det[i].return_qty);
                $("#qno").val($("#inv_no").val()); 
                $(".dis").blur();
               

                $("#0_"+i).attr("readonly", "readonly");
				        $("#n_"+i).attr("readonly", "readonly");
                $("#1_"+i).attr("readonly", "readonly");
                $("#3_"+i).attr("readonly", "readonly");
                $("#4_"+i).attr("readonly", "readonly");
                $("#t_"+i).attr("readonly", "readonly");
                }

                for(var i=0; i<r.max.length;i++){
                  $("#rmax1_"+i).val(r.max[i].return_qty);
                }


              serial_items.splice(0);
                for(var i=0;i<r.serial.length;i++){
                  serial_items.push(r.serial[i].item+"-"+r.serial[i].serial_no);
                }
              serial_items.sort();
            }

        			settings1();
            }, 
            "json");
        }
    });



























    $(".batch").focus(function(){
        set_cid($(this).attr("id"));
                $.post("index.php/main/load_data/t_pur_ret_sum/is_batch_item",{
            code:$("#0_"+scid).val(),
           
         },function(res){
            
           if(res=='a'){
            $("#serch_pop3").center();
            $("#blocker3").css("display", "block");
            setTimeout("select_search3()", 100);

            load_items3($("#0_"+scid).val());

            $("#pop_search3").keyup(function(e){
                if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { 
                    load_items3($("#0_"+scid).val());
                }
            });

            $("#pop_search3").gselect();
            
            }else if(res=='b'){
                $("#3_"+scid).val("0");
                $("#3_"+scid).attr("readonly","readonly");
            } else {
                $("#3_"+scid).val(res.split("-")[0]);
                $("#2_"+scid).val(res.split("-")[1]);
                $("#3_"+scid).attr("readonly","readonly");
           }

        },'text');
    });

});


function settings1(){
    $("#tgrid .cl1").dblclick(function(){

        if($(this).children().eq(0).html() != "&nbsp;"){


       		var row_value1=$(this).children().find('input').eq(0).val();
       		var row_value2=$(this).children().find('input').eq(2).val();
       		var row_value3=$(this).children().find('input').eq(3).val();
       		var row_value4=$(this).children().find('input').eq(5).val();
       		var row_value5=$(this).children().find('input').eq(4).val();
       		var row_value6=$(this).children().find('input').eq(6).val();
       		var row_value7=$(this).children().find('input').eq(7).val();
       		var row_value8=$(this).children().find('input').eq(8).val();
       		var row_value9=$(this).children().find('input').eq(9).val();
       		var row_value10=$(this).children().find('input').eq(10).val();
       		var row_value11=$(this).children().find('input').eq(11).val();
       		var row_value12=$(this).children().find('input').eq(12).val();
       		
       		
            	for(var i=0; i<25 ;i++){

            		if(row_value1 == "0" || row_value1 =="")
            		{
            		}
            		else
            		{
	            		if($("#0_"+i).val()==row_value1)
	            		{
	            			//alert("Item "+row_value1+" is already added.");
	            			return false;
	            		}
		            	else if($("#0_"+i).val()=="")
		            	{
		            	 	$("#0_"+i).val(row_value1);
		            	 	$("#n_"+i).val(row_value2);
		            	 	$("#1_"+i).val(row_value3);
		            	 	$("#2_"+i).val(row_value6);
		            	    $("#3_"+i).val(row_value4);

		            	   // $("#3_"+i).val(row_value5);

		            	 	$("#4_"+i).val(row_value7);
		            	 	$("#5_"+i).val(row_value8);
		            	 	$("#6_"+i).val(row_value9);
		            	 	$("#t_"+i).val(row_value11);
		            	 	$("#7_"+i).val(row_value12);

		            	 $("#rmax_"+i).val(row_value10);


		            	 	break;		

		            	}

	            	}
            	}

               //if($(this).children().eq(4).html() == 1){
                //    $("#1_"+scid).autoNumeric({mDec:2});
                //}else{
               //    $("#1_"+scid).autoNumeric({mDec:0});
               // }
               // $("#1_"+scid).removeAttr("disabled"); $("#2_"+scid).removeAttr("disabled"); $("#3_"+scid).removeAttr("disabled");
               // $("#1_"+scid).focus();$("#pop_close").click();
            
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
            
            //$("#pop_close").click();
        }
    });
}



function seriall_items(){
      $.post("index.php/main/load_data/t_pur_ret_sum/serial_item", {
          search : $("#pop_search44").val(),
          item:$("#item_code").val(),
          stores :$("#stores").val(),
          batch_no :$("#3_"+get_id).val()
      }, function(r){
           $("#sr44").html(r);
           $("#serial_item_list tr td input").focus();
          settings4();
      }, "text");
}

function settings4(){
    $("#serial_item_list .cl").dblclick(function(){

       var q =parseInt($("#qty").val());

        if($(this).children().eq(0).html() != "&nbsp;"){
            if(check_item_exist4($(this).children().eq(2).html())){
                var get=$(this).children().eq(2).html();
                //current_serial_no.value=get;

                for(var i=0; i<q ;i++){

                    if($("#serial_"+i).val()==get)
                      {
                        //alert("Item "+row_value1+" is already added.");
                        return false;
                      }
                    else if($("#serial_"+i).val()=="")
                      {
                        $("#serial_"+i).val(get);
                        break;    
                      }
                  }


                //$("#pop_close4").click();
           }else{
               alert("Serial "+$(this).children().eq(2).html()+" is already added.");
           }
        }else{
            // current_serial_no.value="";
            //$("#pop_close4").click();

        }
    });
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


function emptyElement(element) {
    if (element == null || element == 0 || element.toString().toLowerCase() == 'false' || element == '')
       return false;
       else return true;
}

function load_serial_form(scid){
     get_id=scid;
     var serial_table="";
     var serial_table2="";
     $("#item_code").val($("#0_"+scid).val());
     $("#item").val($("#n_"+scid).val());
     $("#no").val($("#id").val());   
     $("#qty,#quantity").val($("#2_"+scid).val()); 
     //$("#type").val("10");    //t_trans_code 1
     $("#type").val("PURCHASE RETURN");
     
     var count=parseInt($("#2_"+scid).val());
     if($("#setserial_"+scid).val()=="1"){
        loading_serial_items(scid);
     }else{
        var serial_table=serial_table2="";
        for(x=0;x<count;x++){
            if(x<10){var row_count="0"+x;}else{ var row_count=x;}
            serial_table=serial_table+"<tr><td> "+row_count+" <input type='text' id='serial_"+x+"' class='g_input_txt srl_count' style='border:1px solid #000; width:150px;'/></td></tr>";
            serial_table2=serial_table2+"<tr><td> "+row_count+" <input type='text' class='g_input_txt srl_count2' id='srl_"+x+"' style='border:1px solid #000; width:150px;'/></td></tr>";
        }

     $("#set_serial").html(serial_table);
     $("#set_serial2").html(serial_table2);
    }
    seriall_items();
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
                if(y<10){var row_count="0"+y;}else{ var row_count=y;}
                serial_table=serial_table+"<tr><td> "+row_count+" <input type='text' id='serial_"+y+"' title='"+serial_code+"' value='"+serial_code+"' class='g_input_txt srl_count' style='border:1px solid #000; width:150px;'/></td></tr>";
                serial_table2=serial_table2+"<tr><td> "+row_count+" <input type='text' class='g_input_txt srl_count2' id='srl_"+y+"' style='border:1px solid #000; width:150px;'/></td></tr>";
                y++;
                }
            }

            var count=parseInt($("#2_"+scid).val())-parseInt($("#numofserial_"+scid).val());
             
             for(x=0;count>x;x++){
                if(y<10){var row_count="0"+y;}else{ var row_count=y;}
               serial_table=serial_table+"<tr><td> "+row_count+" <input type='text' id='serial_"+y+"' class='g_input_txt srl_count' style='border:1px solid #000; width:150px;'/></td></tr>";
               serial_table2=serial_table2+"<tr><td> "+row_count+" <input type='text' class='g_input_txt srl_count2' id='srl_"+y+"' style='border:1px solid #000; width:150px;'/></td></tr>"; 
               y++;
            }

       }else{
        serial_items.sort();
        for(x=0;serial_items.length>x;x++){
                var get_code=serial_items[x].split("-")[0];
                if(get_code==item_code){
                var serial_code=serial_items[x].split("-")[1];
                  if(x<10){var row_count="0"+x;}else{ var row_count=x;}
                serial_table=serial_table+"<tr><td> "+row_count+" <input type='text' id='serial_"+x+"' title='"+serial_code+"' value='"+serial_code+"' class='g_input_txt srlcd srl_count' style='border:1px solid #000; width:150px;'/><input type='button' title='Remove' value='Remove' style='width:75px;height:22px;border:1px solid #000;border-radius:0' class='removeSerial' id='removeSeri_"+x+"' /></td></tr>";
                serial_table2=serial_table2+"<tr><td> "+row_count+" <input type='text' class='g_input_txt srl_count2' id='srl_"+x+"' style='border:1px solid #000; width:150px;'/></td></tr>";
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
     $.post("index.php/main/load_data/t_pur_ret_sum/check_is_serial_item",{
            code:item_code,
         },function(r){
            if(r==1){

              load_serial_form(scid);
            }
         },"text"); 
   }
}


function insertSerial(x){   
    var serial=x;
    var check=$("#hid").val();
   
    if(check=='0'){
        $.post("index.php/main/load_data/t_pur_ret_sum/is_serial_available", {
               serial:serial,
               item:$("#item_code").val()
            }, function(res){
                  serial_window(res,x);
        }, "text");

    }else{
        $.post("index.php/main/load_data/t_pur_ret_sum/is_serial_available", {
               nno:$("#id").val(), 
               serial:serial,
               item:$("#item_code").val()
            }, function(res){
                    
                  serial_window(res,x);
        }, "text");
    }  
  

}

   function empty_grid2(){
	    for(var i=0; i<25; i++){
	        $("#h1_"+i).val(0);
	        $("#01_"+i).val("");
	        $("#n1_"+i).val("");
	       
	        $("#101_"+i).val("");
	        $("#21_"+i).val("");
	        $("#31_"+i).val("");
	        $("#41_"+i).val("");
	        $("#51_"+i).val("");
	        $("#61_"+i).val("");
	        $("#71_"+i).val("");
	        $("#t1_"+i).val("");
	        $("#rq_"+i).val("");
	        $("#7_"+i).val("");

	       
	        }
	        $("#gross_amount").val("");
	        $("#total2").val("");
	        $("#net_amount").val("");
	        $("#customer").val("");
	        $("#customer_id").val("");
	        $("#sales_rep").val("");
	        $("#sales_rep2").val("");
    }


function serial_window(res,x){
      
       var item_code=$("#item_code").val();
       var count=$("#qty").val();
       var serial=x; 
       


        if(res==0){
         alert("This serial number ["+serial+"] is not in current stock."); 
         serialWind=0;   
         
        }else if(x==""){
           alert("Please check the serial number 1");
           
        }else if($(".srl_count").length !=count){
           alert("Please check the serial number 2");
          
        }else{
           serial_items.push($("#item_code").val()+"-"+serial);
           serialWind=1;
          
        }

         if($(".srl_count").length ==count && res==1){
                   $("#clear").click();
                   document.getElementById('light').style.display='none';
                   document.getElementById('fade').style.display='none';
                   $("#btnExit1").removeAttr("disabled");
                  globalZ=0;
        }

         if(serialWind==0){
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
                      if($("#serial_"+x).val()!=""){
                          serial_items.push(item_code+"-"+$(this).val());
                      } 
                  }
               }else{  
                alert("Please check the serial number");
              }
            });
          load_serial_form(get_id);         
         }

}


function select_search(){
    $("#pop_search").focus();
}

function select_search2(){
    $("#pop_search2").focus();
}


function select_search3(){
    $("#pop_search3").focus();
}

function select_search4(){
    $("#pop_search4").focus();
}


function load_items3(x){
    $.post("index.php/main/load_data/t_pur_ret_sum/batch_item", {
        search : x,
        stores : $("#stores").val()
    }, function(r){
        $("#sr3").html(r);
        settings3();
    }, "text");
}

function settings3(){
    $("#batch_item_list .cl").click(function(){
        if($(this).children().eq(0).html() != "&nbsp;"){
            if(check_item_exist3($(this).children().eq(0).html())){
                $("#3_"+scid).val($(this).children().eq(0).html());
                $("#2_"+scid).val($(this).children().eq(1).html());
                $("#3_"+scid).attr("readonly","readonly");
                $("#5_"+scid).focus();
                discount();
                amount();
                gross_amount();
                all_rate_amount();
                net_amount();
                $("#pop_close3").click();
            }else{
                alert("Item "+$(this).children().eq(1).html()+" is already added.");
            }
        }else{
            $("#3_"+scid).val("");
            $("#2_"+scid).val("");
            $("#5_"+scid).val("");
                discount();
                amount();
        
                gross_amount();
                all_rate_amount();
                net_amount();
            $("#pop_close3").click();
        }
    });
}


      function load_items(){
          $.post("index.php/main/load_data/m_items/item_list_all", {
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
                $("#4_"+scid).val($(this).children().eq(3).html());
                
                if($(this).children().eq(4).html() == 1){
                   // $("#1_"+scid).autoNumeric({mDec:2});
                }else{
                   // $("#1_"+scid).autoNumeric({mDec:2});
                }
                $("#1_"+scid).removeAttr("disabled"); 
                $("#2_"+scid).removeAttr("disabled"); 
                $("#3_"+scid).removeAttr("disabled");
                $("#3_"+scid).focus();
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
            $("#3_"+scid).focus();
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

    function check_item_exist3(id){
        var v = true;
        return v;
    }


    function load_items(){
         $.post("index.php/main/load_data/t_pur_ret_sum/item_list_all", {
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


     function select_search(){
        $("#pop_search").focus();
       
    }


    function select_search2(){
        $("#pop_search2").focus();
       
    }

    function set_cus_values(f){
            var v = f.val();
            v = v.split("|");
             if(v.length == 2){
                f.val(v[0]);
                $("#supplier").val(v[1]);
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
        $("#dt").val($("#date").val());
        $("#qno").val($("#id").val());
        $("#drn").val($("#drn_no").val());
        $("#v_id").val($("#supplier_id").val());
        $("#netAmnt").val($("#net_amount").val());


        $("#srls").attr("title",serial_items);
        $("#srls").val(serial_items);
        var frm = $('#form_');
        loding();
        $.ajax({
      	type: frm.attr('method'),
      	url: frm.attr('action'),
      	data: frm.serialize(),
      	success: function (pid){
       
                if(pid==5){
                  alert('Please check the serial numbers');
                }else if(pid == 0){
                  alert("Transaction is NOT completed");
                  location.href="";
                }else if(pid == 2){
                    alert("No permission to add data.");
                }else if(pid == 3){
                    alert("No permission to edit data.");
                }else if(pid==1){
                   $("#print_pdf").submit();
                   serial_items.splice(0);
                   reload_form();
                                      
                }
          
                loding();

            }
        });

          
    }


function reload_form(){
  setTimeout(function(){
    window.location = '';
  },1); 
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

        var v = false;


        for(var i=0; i<25 ;i++){
         if($("#2_"+i).val() !="" || $("#2_"+i).val() !="0") {
         	if(parseFloat($("#2_"+i).val())>parseFloat($("#rmax_"+i).val())){
         	alert("Maximum available quntity is " +$("#rmax_"+i).val());	
         	//$(this).val("0");
         	return false;
         	}
          } 
      }

            $("input[type='hidden']").each(function(){
                if($(this).val() != "" && $(this).val() != 0){
                    v = true;
                }
            });

        total_discount();
            if($("#id").val().length==0 || $("#id").val().length<0){
                alert("Please enter no");
                return false;
             }else if($("#supplier").val() === $("#supplier").attr('title') || $("#supplier").val() == ""){
                alert("Please Select Supplier");
                $("#supplier_id").focus();
                return false;
             }else if($("#stores").val() == 0 ){
                alert("Please Select Store");
                $("#stores").focus();
                return false;
             }else if(v == false){
                alert("Please use minimum one item.");
                return false;
             }else{
                return true;

            //checking with grn number and supplier.        

            //      var supplier=$("#supplier_id").val();
            //      var grn_no=$("#grnno").val();
            //  $.post("index.php/main/load_data/t_pur_ret_sum/check_grn_no/",{
            //         supplier_id:supplier,
            //         grn_no:grn_no
            //  },function(res){
            //         check_grn_result(res);
            // },"text");
           }
    }

function check_grn_result(res){   
            if(res==1){  
               save();
            }else{  
                alert("Please insert correct GRN no");
                return false;
            }
}

    
    function set_delete(code){
        if(confirm("Are you sure delete "+code+"?")){
            loding();
            $.post("index.php/main/delete/t_grn_sum", {
                code : code
            }, function(res){
                if(res == 1){
                    get_data_table();
                }else if(res == 2){
                    alert("No permission to delete data.");
                }else{
                    alert("Item deleting fail.");
                }
                loding();
            }, "text");
        }
    }


    
    function set_edit(code){
        loding();
        $.post("index.php/main/get_data/t_grn_sum", {
            code : code
        }, function(res){
            $("#code_").val(res.code);
            $("#code").val(res.code);
    	      $("#code").attr("readonly", true);
            $("#description").val(res.description);
            
               if(res.is_vehical == 1){
                $("#is_vehical").attr("checked", "checked");
              }else{
                  $("#is_vehical").removeAttr("checked");
              }
            
            
            
           // is_edit('010');
            loding(); input_active();
        }, "json");
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

function total_discount(){
    var td=0;
    set_cid($(".dis").attr("id"));
    $(".dis").each(function(){
        if(!isNaN($("#6_"+scid).val())){
            td=td+parseFloat($("#6_"+scid).val());
        }
    });

    $("#total_discount").val(td);
}

function load_data(id){
 
    empty_grid();
    loding();
    $.post("index.php/main/get_data/t_pur_ret_sum/", {
        id: id
    }, function(r){

            if(r=="2"){
               alert("No records");
            }else{
            $("#hid").val(id);   
            $("#id").val(id);

            $("#supplier_id").val(r.sum[0].supplier_id);
            $("#date").val(r.sum[0].ddate);
            $("#type").val(r.sum[0].type);
            $("#ref_no").val(r.sum[0].ref_no);
            $("#supplier_id").val(r.sum[0].supp_id);
            $("#supplier").val(r.sum[0].supplier_name);
            $("#sales_rep2").val(r.sum[0].rep_name);
            $("#date").val(r.sum[0].ddate); 
            $("#ref_no").val(r.sum[0].ref_no);
            
            $("#grnno").val(r.sum[0].grn_no);
            $("#drn_no").val(r.sum[0].drn_no);
            $("#memo").val(r.sum[0].memo);
           
            $("#stores").val(r.sum[0].store);
            set_select("stores","store_id");

            $("#gross_amount").val(r.sum[0].gross_amount);
            $("#total2").val(r.sum[0].other);
            $("#net_amount").val(r.sum[0].net_amount);
            $("#dt").val(r.sum[0].ddate);
            $("#qno").val(id);
            $("#drn").val(r.sum[0].drn_no);
            $("#v_id").val(r.sum[0].supp_id);
            $("#netAmnt").val(r.sum[0].net_amount);

            for(var i=0; i<r.det.length;i++){
                $("#itemcode_"+i).val(r.det[i].icode);
                $("#numofserial_"+i).val(r.det[i].qty);
                $("#setserial_"+i).removeAttr("title");
                $("#setserial_"+i).removeAttr("value");
                $("#setserial_"+i).attr("title",1);
                $("#setserial_"+i).attr("value",1); 
                $("#h_"+i).val(r.det[i].icode);
                $("#0_"+i).val(r.det[i].icode);
                $("#n_"+i).val(r.det[i].idesc);
                $("#1_"+i).val(r.det[i].model);
                $("#2_"+i).val(r.det[i].qty);
                $("#3_"+i).val(r.det[i].batch_no);
                $("#4_"+i).val(r.det[i].price);
                $("#5_"+i).val(r.det[i].discountp);
                $("#6_"+i).val(r.det[i].discount);
                $("#7_"+i).val(r.det[i].reason);
                 scid=i; 
                 amount();   
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
             if(r.serial!=2){
                for(var i=0;i<r.serial.length;i++){
                  serial_items.push(r.serial[i].item+"-"+r.serial[i].serial_no);
                }
            }
            serial_items.sort();

            input_active();
           }

            loding();
        }, "json");

  
}

  function empty_grid(){
    for(var i=0; i<25; i++){
        $("#h_"+i).val(0);
        $("#0_"+i).val("");
        $("#n_"+i).val("");
        $("#1_"+i).val("");
        $("#2_"+i).val("");
        $("#3_"+i).val("");
        $("#4_"+i).val("");
        $("#5_"+i).val("");
        $("#6_"+i).val("");
        $("#t_"+i).val("");
        $("#hhh_"+i).val("");
        $("#hh_"+i).val("");
        $("#00_"+i).val("");
        $("#nn_"+i).val("");
        $("#11_"+i).val("");
        $("#22_"+i).val("");
        }
    }

    function get_sales_type(i){
        $.post("index.php/main/load_data/r_additional_items/get_type",{
             id:$("#00_"+i).val()
            },function(res){      
              $("#hh_"+i).val(res);     
         },"text");
     
    }
