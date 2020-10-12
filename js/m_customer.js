var is_edit=0; 
$(document).ready(function(){

    $("#nic").blur(function(){
        check_code();
		//check_ID();
    });
    
    $("#cus_list").click(function(){
        $("#print_pdf").submit();
    });

    $("#btnPrint_1").click(function(){
        if($("#cus_code").val()== ""){
            set_msg("Please Load a record");   
       }else{
        $("#print_pdf2").submit();
       }
       
   });

    $("#btnSave1").click(function(){
    if(validate()){
        check_permission();
    }  
     
    });

    $(".fn").keyup(function(){
        if(!$("#edit_name").is(":checked")){
            genarate_name();
        }
    });

    $("#edit_name").click(function(){
        if($("#edit_name").is(":checked")){
            $("#name").removeClass("hid_value");
            $("#name").removeAttr("readonly");
        }else{
            $("#name").removeClass("input_txt");
            $("#name").addClass("hid_value");
            $("#name").addClass("input_txt");
            $("#name").attr("readonly","readonly");
        }
    });

    $("#btnReset").click(function(){
   	location.href="?action=m_customer";
	});

    $("#cus_type").change(function(){
        if($("#cus_type").val()!="1"){
            $("#nic").val($("#code").val());
        } else{
            $("#nic").val("");
        }
    })

    $("#btnPrint").click(function(){
        $("#print_pdf").submit();
    });

    $("#nic").blur(function(){
       // check_code();
    });

    $("#cus_status").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search4").val();
            cus_status();
            $("#serch_pop4").center();
            $("#blocker").css("display", "block");
            setTimeout("$('pop_search4').focus()",100);
        }

        $("#pop_search4").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112){
                cus_status();
            }      
        });
        if(e.keyCode == 46){
            $("#cus_status").val("");
            $("#hid_cus_status").val("");
            $("#hid_cus_status").css("background-color","");
            $("#hid_cus_status").addClass("hid_value");
            $("#hid_cus_status").removeClass("input_txt");
        }

    });
    $(".return_reason").keydown(function(e){
        set_cid($(this).attr("id"));
        if(e.keyCode == 112){
            $("#pop_search4").val();
            load_data_reason(scid);
            $("#serch_pop4").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search4').focus()", 100);
        }
        $("#pop_search4").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                 load_data_reason(scid);
            }
        }); 
        if(e.keyCode == 46){
            $("#type_"+scid).val(""); 
            $("#tcode_"+scid).val(""); 
            $("#ddate_"+scid).val(""); 
            $("#comment_"+scid).val(""); 
        }
    });
    $("#tgrid").tableScroll({height:200});
    $("#grid").tableScroll({height:355});
    
    
    $('#tabs').tabs();

    $('#cus_category').change(function(){
        set_select("cus_category","category");
    });

    
    $("#srchee").keyup(function(){  
	$.post("index.php/main/load_data/utility/get_data_table", {
                    code:$("#srchee").val(),
                    tbl:"m_customer",
                    tbl_fied_names:"Code-Name-NIC",
                    fied_names:"code-name-nic",
                    col4:"Y"
                 }, function(r){
            $("#grid_body").html(r);
        }, "text");
    });
	
    $("#btncategory").click(function(){
    location.href="?action=r_cus_category";
    });

     $("#btntown").click(function(){
    location.href="?action=r_town";
    });

    $("#btnarea").click(function(){
    location.href="?action=r_area";
    });

    $("#btnroot").click(function(){
    location.href="?action=r_root";
    });

    $("#btn_nationality").click(function(){
    location.href="?action=r_nationality";
    });


     $("#category_id").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search2").val($("#category_id").val());
            load_data9();
            $("#serch_pop2").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search2').focus()", 100);
        }

       $("#pop_search2").keyup(function(e){
            
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                 load_data9();
            }
        }); 

        if(e.keyCode == 46){
            $("#category_id").val("");
            $("#category").val("");
        }
    });

     $("#town_id").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search11").val($("#town_id").val());
            load_data10();
            $("#serch_pop11").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search11').focus()", 100);
        }

       $("#pop_search11").keyup(function(e){
            
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                 load_data10();
            }
        }); 

        if(e.keyCode == 46){
            $("#town_id").val("");
            $("#town_name").val("");
        }
    });

     $("#area_id").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search12").val($("#area_id").val());
            load_data11();
            $("#serch_pop12").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search12').focus()", 100);
        }

       $("#pop_search12").keyup(function(e){
            
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                 load_data11();
            }
        }); 

        if(e.keyCode == 46){
            $("#area_id").val("");
            $("#area_name").val("");
        }
    });


    $("#root_id").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search13").val($("#root_id").val());
            load_data12();
            $("#serch_pop13").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search13').focus()", 100);
        }

       $("#pop_search13").keyup(function(e){
            
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                 load_data12();
            }
        }); 

        if(e.keyCode == 46){
            $("#root_id").val("");
            $("#root_name").val("");
        }
    });


    $("#nationality_id").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search14").val($("#nationality_id").val());
            load_data13();
            $("#serch_pop14").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search14').focus()", 100);
        }

       $("#pop_search14").keyup(function(e){
            
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                 load_data13();
            }
        }); 

        if(e.keyCode == 46){
            $("#nationality_id").val("");
            $("#nationality_id").val("");
        }
    });


$(".contact").change(function(){
    set_cid($(this).attr("id"));
        $("#type1_"+scid).val($("#contact1_"+scid).val());

});

});

// function check_code(){
//     var code = $("#nic").val();
//     $.post("index.php/main/load_data/m_customer/check_code", {
//         code : code
//     }, function(res){
//         if(res == 1){
//             if(confirm("This NIC ("+code+") already added. \n\n Do you need edit it?")){
//                 alert("edit");
//                 //set_edit(code);
//             }else{
//                 $("#code").val('');
//             }
//         }
//     }, "text");
// }

 function load_data_reason(scid){
      $.post("index.php/main/load_data/utility/f1_selection_list", {
          data_tbl:"r_return_reason",
          field:"code",
          field2:"description",
          preview2:"Event Name",
          add_query: " AND type='6'",
          search : $("#pop_search4").val() 
      }, 
      function(r){ 
          $("#sr4").html(r);
          settings_reasonf1(scid);            
      }, "text");
  }

   function settings_reasonf1(scid){
      $("#item_list .cl").click(function(){     
          $("#type_"+scid).val($(this).children().eq(1).html());
          $("#tcode_"+scid).val($(this).children().eq(0).html());
          $("#pop_close4").click();                
      })    
  }

function load_data9(){
        $.post("index.php/main/load_data/utility/f1_selection_list", {
            data_tbl:"r_cus_category",
            field:"code",
            field2:"description",
            preview1:"Category ID",
            preview2:"Category Name",
            search : $("#pop_search2").val() 
        }, function(r){
            $("#sr2").html("");
            $("#sr2").html(r);
            settings9();            
        }, "text");
    }

function settings9(){
    $("#item_list .cl").click(function(){        
        $("#category_id").val($(this).children().eq(0).html());
        $("#category").val($(this).children().eq(1).html());   
        $("#blocker").css("display","none");
        $("#pop_close2").click();                
    })    
}


function load_data10(){
        $.post("index.php/main/load_data/utility/f1_selection_list", {
            data_tbl:"r_town",
            field:"code",
            field2:"description",
            preview1:"Town ID",
            preview2:"Town Name",
            search : $("#pop_search11").val() 
        }, function(r){
          
            $("#sr11").html(r);
            settings10();            
        }, "text");
    }

function settings10(){
    $("#item_list .cl").click(function(){        
        $("#town_id").val($(this).children().eq(0).html());
        $("#town_name").val($(this).children().eq(1).html());   
        $("#blocker").css("display","none");
        $("#pop_close11").click();                
    })    
}

function load_data11(){
        $.post("index.php/main/load_data/utility/f1_selection_list", {
            data_tbl:"r_area",
            field:"code",
            field2:"description",
            preview1:"Area ID",
            preview2:"Area Name",
            search : $("#pop_search12").val() 
        }, function(r){
          
            $("#sr12").html(r);
            settings11();            
        }, "text");
    }

function settings11(){
    $("#item_list .cl").click(function(){        
        $("#area_id").val($(this).children().eq(0).html());
        $("#area_name").val($(this).children().eq(1).html());   
        $("#blocker").css("display","none");
        $("#pop_close12").click();                
    })    
}

function load_data12(){
        $.post("index.php/main/load_data/utility/f1_selection_list", {
            data_tbl:"r_root",
            field:"code",
            field2:"description",
            preview1:"Root ID",
            preview2:"Root Name",
            search : $("#pop_search13").val() 
        }, function(r){
          
            $("#sr13").html(r);
            settings12();            
        }, "text");
    }

function settings12(){
    $("#item_list .cl").click(function(){        
        $("#root_id").val($(this).children().eq(0).html());
        $("#root_name").val($(this).children().eq(1).html());   
        $("#blocker").css("display","none");
        $("#pop_close13").click();                
    })    
}

function load_data13(){
        $.post("index.php/main/load_data/utility/f1_selection_list", {
            data_tbl:"r_nationality",
            field:"code",
            field2:"description",
            preview1:"ID No.",
            preview2:"Name",
            search : $("#pop_search14").val() 
        }, function(r){
          
            $("#sr14").html(r);
            settings13();            
        }, "text");
    }

function settings13(){
    $("#item_list .cl").click(function(){        
        $("#nationality_id").val($(this).children().eq(0).html());
        $("#nationality").val($(this).children().eq(1).html());   
        $("#blocker").css("display","none");
        $("#pop_close14").click();                
    })    
}

function check_ID(){

	var ccode=$("#code").val();
	var code_num=ccode.substring(0,9);

	if($("#cus_type").val()=="1")
	{
		if(ccode.length=="10")
		{
			if(ccode.substring(9)=="v" || ccode.substring(9)=="V")
			{
				
				var intRegex = /^\d+$/;
				var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
				var str = code_num;
				if(intRegex.test(str) || floatRegex.test(str))
				{

				}  
  				else
				{
					set_msg('Please enter valid NIC','error');
					return false;
				}
			}
			else
			{
				set_msg("Please enter valid NIC","error");
			}
		}
		else
		{
			set_msg("Please enter valid NIC","error");
		}
	}
}

function check_level(){
    var l1 = parseFloat($("#l1").val());
    var l2 = parseFloat($("#l2").val());
    var l3 = parseFloat($("#l3").val());
    
    if(! isNaN(l2) && !isNaN(l3) && !isNaN(l3)){
        if((l1 < l2) && (l2 < l3)){
            return true;
        }else{
            set_msg("Please use credit limits correctly");
            return false;
        }
    }else{
        set_msg("Please use credit limits correctly");
        return false;
    }
}


function save(){
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
            }else if(pid == 3){
                set_msg("No permission to edit data.");
            }else{
                set_msg("Error : \n"+pid);
            }
            
      }
    });
}


function get_data_table(){
    $.post("index.php/main/load_data/m_customer/get_data_table", {
        
    }, function(r){
        $("#grid_body").html(r);
    }, "text");
}



function check_code(){
    var code = $("#nic").val();
    $.post("index.php/main/load_data/m_customer/check_code", {
        code : code
    }, function(res){
        if(res.data!= 2){
            if(confirm("This NIC ("+code+") already added. \n\n Do you need edit it?")){
                if(res.edit==1){
                    set_edit(res.data[0].code);
                }else{
                    set_edit(res.data[0].code);
                    $("#btnSave").attr("disabled",true);
                    $("#nic").attr("readonly","readonly");
                }
            }else{
                $("#nic").val('');
            }
        }
    }, "json");
}


/*function check_type(nic){
    $.post("index.php/main/load_data/m_customer/check_nic", {
        code : nic
    }, function(res){
        if(res!= 2){
           check_type(res[0].nic);                
        }
    }, "json");

}
*/


function validate(){

	var ccode=$("#nic").val();
	var code_num=ccode.substring(0,9);

    if($("#nic").val() == "" || $("#nic").val() == $("#nic").attr("title") )
    {
        set_msg("Please enter code","error");
        return false;
    }
    else if($("#name").val() == "" || $("#name").val() == $("#name").attr("title"))
    {
        set_msg("Please enter name","error");
        return false;
    }
    else if($("#cus_type").val()=="0")
    {	
		set_msg("please select customer type","error");
		return false;
	}
    else if($("#cus_category").val()==0)
    {
        set_msg("please select category","error");
        return false;
    }
    else if($("#town").val()==0)
    {
        set_msg("please select town","error");
        return false;
    }
    else if($("#area").val()==0)
    {
        set_msg("please select area","error");
        return false;
    }
    else if($("#area").val()==0)
    {
        set_msg("please select area","error");
        return false;
    }
    else if(!$(".is_type").is(":checked"))
    {
        set_msg("please select customer or guarantor type");
        return false;
    }
    else if($("#root").val()==0)
    {
        set_msg("please select root","error");
        return false;
    }
    else if($("#nation").val()==0)
    {
        set_msg("please select nationality","error");
        return false;
    }else{
        return true;
    }
}

function validateNIC(){

if($("#cus_type").val()=="1"){

     var nic = $("#nic").val();
     var filter = /^[0-9]{9}[v|V|x|X]$/;

     return filter.test(nic);
     if(filter.test(nic)){
      return true;
     }
     else{
      return false;
     }
 }
  else{
      return true;
     }
} 
    
function set_delete(code){
   if(confirm("Are you sure delete "+code+"?")){
        loding();
        $.post("index.php/main/delete/m_customer", {
            code : code
        }, function(res){
            if(res == 1){
                loding();
                delete_msg();
            }else{
                set_msg(res);
            }
            
        }, "text");
    }
}



    
function set_edit(code){
     loding();
    $("#code_find").attr({value:code,Title:code});
    $.post("index.php/main/get_data/m_customer", {
        code : code
    }, function(res){

        $("#cus_code").val(res.data.code_id);
        $("#code_").val(res.data.code_id);
        $("#code").val(res.data.code_id);
        $("#nic").val(res.data.nic);
        $("#name").val(res.data.name);
        $("#company_name").val(res.data.company_name);
        $("#address1").val(res.data.address1);
       
        $("#address2").val(res.data.address2);
        $("#address3").val(res.data.address3);
        $("#email").val(res.data.email);
        $("#doj").val(res.data.doj);
        $("#dob").val(res.data.dob);
        $("#credit_limit").val(res.data.credit_limit);
        $("#credit_period").val(res.data.credit_period);
        $("#is_tax").val(res.data.is_tax);
        $("#tax_id").val(res.data.tax_reg_no);
        $("#inactive").val(res.data.inactive);
/*        $("#tp").val(res.data.tp);
        $("#mobile_number").val(res.data.mobile);*/
        $("#bl").val(res.data.bl);
        $("#bl_reason").val(res.data.bl_reason);
        $("#bl_officer").val(res.data.bl_officer);
        $("#bl_date").val(res.data.bl_date);
        $("#occupation").val(res.data.occupation);
        $("#salary").val(res.data.salary);
        $("#cus_type").val(res.data.type);

        $("#cont_name").val(res.data.cn_name);
        $("#cont_address").val(res.data.cn_address);
        $("#cont_tel").val(res.data.cn_tel);
        $("#cont_email").val(res.data.cn_email);
        $("#po_code").val(res.data.postal_code);
        
         $("#category_id").val(res.data.category);
         $("#category").val(res.data.category_name);

         $("#town_id").val(res.data.town);
         $("#town_name").val(res.data.town_name);

         $("#area_id").val(res.data.area);
         $("#area_name").val(res.data.area_name);

         $("#root_id").val(res.data.root);
         $("#root_name").val(res.data.root_name);

         $("#nationality_id").val(res.data.nationality);
         $("#nationality").val(res.data.nationality_name);

        $("#name_title").val(res.data.title);
        $("#sur_name").val(res.data.surname);
        $("#other_name").val(res.data.other_name);
        $("#initials").val(res.data.initials);
        $("#f_name").val(res.data.full_name);

        if(res.data.customer_status == "" || res.data.customer_status == null){
            $("#hid_cus_status").css("background-color","");
            $("#cus_status").val("");
            $("#hid_cus_status").val("");
            $("#hid_cus_status").addClass("hid_value");
            $("#hid_cus_status").removeClass("input_txt");

        }else{
            $("#cus_status").val(res.data.customer_status);
            $("#hid_cus_status").val(res.data.cus_status);
            $("#hid_cus_status").removeClass("hid_value");
            $("#hid_cus_status").addClass("input_txt");
            $("#hid_cus_status").css("background-color",res.data.color);
        }
          
        if(res.data.is_tax==1){
            $("#is_tax").attr("checked", "checked");
            }else{
            $("#is_tax").removeAttr("checked");  
        }
        if(res.data.inactive==1){
            $("#inactive").attr("checked", "checked");
            }else{
            $("#inactive").removeAttr("checked");
        }
        if(res.data.is_customer==1){
            $("#is_customer").attr("checked", "checked");
            }else{
            $("#is_customer").removeAttr("checked");
        }
        if(res.data.is_guarantor==1){
            $("#is_guarantor").attr("checked", "checked");
            }else{
            $("#is_guarantor").removeAttr("checked");
        }
        if(res.data.bl==1){
            $("#bl").attr("checked", "checked");
            }else{
            $("#bl").removeAttr("checked");
        }

        for(var i=0; i<10; i++){
            $("#type1_"+i).val("");
            $("#contact1_"+i).val("");
            $("#des_"+i).val("");
            $("#tp_"+i).val("");
        }
       for(var i=0; i<res.b.length; i++){
            $("#contact1_"+i).val(res.b[i].type);
            $("#type1_"+i).val(res.b[i].type);
            $("#des_"+i).val(res.b[i].description);
            $("#tp_"+i).val(res.b[i].tp);
        }
            
        for(var i=0; i<res.c.length; i++){
            $("#type_"+i).val(res.c[i].description);
            $("#tcode_"+i).val(res.c[i].type);
            $("#ddate_"+i).val(res.c[i].ddate);
            $("#comment_"+i).val(res.c[i].comments);
        }

        $("#balance").val(res.acc);


        loding(); 
        input_active();
        is_edit=1;
  
    }, "json");
}


function genarate_name(){
    var p_name = $("#name_title").val()+' '+$("#sur_name").val()+' '+$("#initials").val();
    $("#name").val(p_name);

}


function cus_status(){
    $.post("index.php/main/load_data/utility/f1_selection_list",{
            data_tbl:"m_customer_status",
            field:"code",
            field2:"description",
            hid_field:"color",
            search:$("#pop_search4").val()
    },function(r){
        $("#sr4").html(r);
        cus_status_settings();
    },"text");
}

function cus_status_settings(){
    $("#item_list .cl").click(function(){
        $("#cus_status").val($(this).children().eq(0).html());
        $("#hid_cus_status").val($(this).children().eq(1).html());
        $("#hid_color").val($(this).children().find('input').val());
        $("#hid_cus_status").removeClass("hid_value");
        $("#hid_cus_status").addClass("input_txt");
        $("#hid_cus_status").css("background-color",$(this).children().find('input').val());
        $("#blocker").css("display","none");
        $("#pop_close4").click();
    });
}
