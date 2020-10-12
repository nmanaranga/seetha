$(document).ready(function(){

    $("#btnReset").click(function(){
       location.href="?action=t_job";
   });

    $("#")

    $("#customer_create").click(function(){
        window.open("?action=m_customer","_blank");
    });

    $("#btnPrint").click(function(){
        if($("#qno").val()==""){
            set_msg("Please Load a record");
        }else{
            $("#print_pdf").submit();
        }
    });

    $("#cus_id").keydown(function(e){

        if(e.keyCode == 112){

            $("#pop_search4").val();
            select_customer();
            $("#serch_pop4").center();
            $("#blocker").css("display","block");
            setTimeout("$('#pop_search4').focus()",100);

            $("#pop_search4").keyup(function(e){
             if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ){ 
                select_customer();
            }     
        });   
        }

        if(e.keyCode == 46){
            $("#cus_id").val("");
            $("#customer").val("");
            $("#address").val("");

        }

    });

    $("#btnDelete").click(function(){
        set_delete();
    });

    $("#ex").click(function(){
        $("#customer_create").removeAttr("disabled");
        $("#f_set1").attr("disabled","disabled");
        $("#inv_type").val("");
        $("#inv_no").val("");
        $("#inv_date").val("");
        $("#item_des").css("display","block");
        $("#brand_des").css("display","block");
        $("#item_id").css("display","none");
        $("#item").css("display","none");
        $("#brand_id").css("display","none");
        $("#brand").css("display","none");
        $("#model").removeAttr("readonly");
        $("#serial").removeAttr("readonly");  
        $("#cus_id").removeAttr("readonly");
    });


    $("#in").click(function(){
        $("#f_set1").removeAttr("disabled");
        $("#item_des").css("display","none");
        $("#brand_des").css("display","none");
        $("#item_id").css("display","inline");
        $("#item").css("display","inline");
        $("#brand_id").css("display","inline");
        $("#brand").css("display","inline");
        $("#customer_create").attr("disabled","disabled");
        $("#cus_id").attr("readonly","readonly");

    });
    $("#gur_crd").click(function(){
      if($("#gur_crd").attr("checked")=="checked"){
        $("#gur_no").removeAttr("readonly");
        $("#gur_no").removeClass("hid_value");
        $("#gur_no").addClass("input_txt");
        $("#start_date").removeClass("hid_value");
        $("#end_date").removeClass("hid_value");
        $("#start_date").addClass("input_date_down_future")
        $("#end_date").addClass("input_date_down_future");
        $("#start_date").css("pointer-events","auto");
        $("#end_date").css("pointer-events","auto");


    }else{
        $("#gur_no").attr("readonly","readonly");
        $("#gur_no").val("");
        $("#gur_no").removeClass("input_txt");
        $("#gur_no").addClass("hid_value");
        $("#start_date").addClass("hid_value");
        $("#end_date").addClass("hid_value");
        $("#start_date").val("");
        $("#end_date").val("");
        $("#start_date").removeClass("input_date_down_future");
        $("#end_date").removeClass("input_date_down_future");
        $("#start_date").removeClass("hasDatepicker");
        $("#end_date").removeClass("hasDatepicker");
        $("#start_date").css("pointer-events","none");
        $("#end_date").css("pointer-events","none");

    }
});

    $("#inv_type").change(function(){
        var inv_val=($("#inv_type").val());
        $("#inv_type_h").val(inv_val);
    });

    $("#inv_no").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search").val($("#customer").val());
            load_invno();
            $("#serch_pop").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search').focus()", 100);
        }
        $("#pop_search").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
               load_invno();
           }
       }); 
        if(e.keyCode == 46){
            $("#inv_no").val("");
            $("#inv_date").val("");
            $("#customer").val("");
            $("#cus_id").val("");
            $("#address").val("");
            $("#item_id").val("");
            $("#item").val("");
            $("#brand_id").val("");
            $("#brand").val("");
            $("#model").val("");
            $("#serial").val("");
            $("#sup_id").val("");
            $("#supplier").val("");
        }
    });

    $("#item_id").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search2").val($("#item_id").val());
            load_item();
            $("#serch_pop2").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search2').focus()", 100);
        }
        $("#pop_search2").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
               load_item();
           }
       }); 
        if(e.keyCode == 46){
            $("#item_id").val("");
            $("#item").val("");
            $("#brand_id").val("");
            $("#brand").val("");
            $("#model").val("");
            $("#serial").val("");
            $("#sup_id").val("");
            $("#supplier").val("");
            
        }
    });

    $("#sup_id").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search12").val($("#sup_id").val());
            load_sup();
            $("#serch_pop12").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search12').focus()", 100);
        }
        $("#pop_search12").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
               load_sup();
           }
       }); 
        if(e.keyCode == 46){
            $("#sup_id").val("");
            $("#supplier").val("");
            
        }
    });


    $("#id").keydown(function(e){
        if(e.keyCode == 13){
            $(this).blur();
            $("#hid").val($(this).val());
            load_data($(this).val());
        }
    });
//default_option();

});

/*function default_option(){
   
    $.post("index.php/main/load_data/utility/default_option", {
  }, function(r){
         
          
          $("#cus_id").val(r.def_cash_customer);
          $("#customer").val(r.customer);
 }, "json");
}*/
function load_invno(){
    $.post("index.php/main/load_data/t_job/f1_selection_inv_no", {
        inv_type:$("#inv_type_h").val(),
        search : $("#pop_search").val() 
    }, function(r){
        $("#sr").html("");
        $("#sr").html(r);
        settings_invno();            
    }, "text");
}

function settings_invno(){
    $("#item_list .cl").click(function(){        
        $("#inv_no").val($(this).children().eq(0).html());
        $("#inv_date").val($(this).children().eq(1).html());
        $("#customer").val($(this).children().eq(2).html());
        $("#cus_id").val($(this).children().eq(4).html());
        var address=$(this).children().eq(5).html()+','+$(this).children().eq(6).html()+','+$(this).children().eq(7).html();
        $("#address").val(address);
        $("#start_date").val($("#inv_date").val());
        $("#start_date").removeClass("hid_value");
        $("#start_date").addClass("input_txt");
        $("#pop_close").click();                
    })    
}


function load_item(){
    $.post("index.php/main/load_data/t_job/f1_selection_item", {
        inv_type:$("#inv_type_h").val(),
        inv_no:$("#inv_no").val(),
        cus:$("#cus_id").val(),
        search : $("#pop_search2").val() 
    }, function(r){
        $("#sr2").html("");
        $("#sr2").html(r);
        settings_item();            
    }, "text");
}

function settings_item(){
    $("#item_list .cl").click(function(){        
        $("#item_id").val($(this).children().eq(0).html());
        $("#item").val($(this).children().eq(1).html());
        $("#brand_id").val($(this).children().eq(4).html());
        $("#brand").val($(this).children().eq(6).html());
        $("#model").val($(this).children().eq(5).html());
        $("#serial").val($(this).children().eq(3).html());
        $("#sup_id").val($(this).children().eq(7).html());
        $("#supplier").val($(this).children().eq(8).html());
        $("#pop_close2").click();               
    })    
}

function load_sup(){
    $.post("index.php/main/load_data/utility/f1_selection_list", {
        data_tbl:"m_supplier",
        field:"code",
        field2:"name",
        search : $("#pop_search12").val() 
    }, function(r){
        $("#sr12").html("");
        $("#sr12").html(r);
        settings_sup();            
    }, "text");
}

function settings_sup(){
    $("#item_list .cl").click(function(){        
        $("#sup_id").val($(this).children().eq(0).html());
        $("#supplier").val($(this).children().eq(1).html());
        $("#pop_close12").click();               
    })    
}

function validate(){
    status=0;
    if($('#in').is(':checked')){
        var status="1";
    }
    if($('#ex').is(':checked')){
        var status="2";
    }
    if($("#inv_type_h").val()=="" && status != 2){
        set_msg("Please Select Invoice Type");
        return false;
    }if($("#inv_no").val()=="" && status != 2){
        set_msg("Please Select Invoice No");
        return false;
    }if($("#item_id").val()=="" && status=="1" ){
        set_msg("Please Select Item");
        return false;
    }if($("#item_des").val()=="" && status=="2"){
        set_msg("Please Enter Item");
        return false;
    }if($("#brand_des").val()=="" && status=="2"){
        set_msg("Please Enter Brand");
        return false;
    }if($("#model").val()=="" && status=="2"){
        set_msg("Please Enter Model");
        return false;
    }if($("#serial").val()=="" && status=="2"){
        set_msg("Please Enter Serial");
        return false;
    }if($("#gur_crd").attr("checked")=="checked"){

        if($("#gur_no").val()== ""){
            set_msg("Please Gurantee Card No");
            return false;   
        }
        if($("#start_date").val()==""){
            set_msg("Please Enter Warranty Start Date");
            return false;
        }
        if($("#end_date").val()==""){
            set_msg("Please Enter Warranty Expire Date");
            return false;
        }

    }if($("#fault").val()==""){ 
        set_msg("Please Enter Fault");
        return false;
    }if($("#advance").val()==""){
        set_msg("Please Enter Advance");
        return false;
    }if($("#sup_id").val()==""){
        set_msg("Please Select Suplier");
        return false;
    }else{
        return true;
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
                set_msg(pid);
            }
        }
    });
}


function load_data(id){
    loding();
    $.post("index.php/main/get_data/t_job", {
        id : id
    }, function(res){
        if(res.a==2){
            set_msg("No Records");
        }else{
            if(res.a.inv_type=="Cash Sales"){
                var inv_type="1"; 
            }if(res.a.inv_type=="Credit Sales"){
                var inv_type="2"; 
            }if(res.a.inv_type=="Hire Purchase"){
                var inv_type="3"; 
            }if(res.a.inv_type=="Cash and Card Sales"){
                var inv_type="4"; 
            }if(res.a.inv_type=="Sales Order Sales"){
                var inv_type="5"; 
            }
            $("#inv_type_h").val(inv_type);
            $("#ref_no").val(res.a.ref_no);
            $("#date").val(res.a.ddate);
            $("#inv_type").val(inv_type);
            $("#inv_no").val(res.a.inv_no);
            $("#cus_id").val(res.a.cus_id);
            $("#customer").val(res.a.cus);
            $("#address").val(res.a.address1+','+res.a.address2+','+res.a.address3);
            $("#model").val(res.a.model);
            $("#serial").val(res.a.serial_no);
            $("#gur_no").val(res.a.guarantee_cardno);
            $("#fault").val(res.a.fault);
            $("#parts").val(res.a.parts);
            $("#advance").val(res.a.advance_amount);
            $("#sup_id").val(res.a.supplier);
            $("#supplier").val(res.a.sup);
            if(res.a.w_start_date == "0000-00-00"){
             $("#start_date").val(""); 
         }else{
            $("#start_date").val(res.a.w_start_date);
        } 
        if(res.a.w_end_date == "0000-00-00"){
         $("#end_date").val(""); 
     }else{
        $("#end_date").val(res.a.w_end_date);
    } 
    
    $("#qno").val($("#id").val());

    if(res.a.type == 0){
        $("#in").attr("checked", "checked");
        $("#inv_date").val(res.a.inv_date);
        $("#item_des").css("display","none");
        $("#brand_des").css("display","none");
        $("#item_id").css("display","inline");
        $("#item").css("display","inline");
        $("#brand_id").css("display","inline");
        $("#brand").css("display","inline");
        $("#item_id").val(res.a.item_code);
        $("#item").val(res.a.item_n);
        $("#brand_id").val(res.a.brand);
        $("#brand").val(res.a.brand_n);
    }else{
        $("#ex").attr("checked", "checked");
        $("#f_set1").attr("disabled","disabled");
        $("#item_des").css("display","block");
        $("#brand_des").css("display","block");
        $("#item_id").css("display","none");
        $("#item").css("display","none");
        $("#brand_id").css("display","none");
        $("#brand").css("display","none");
        $("#item_des").val(res.a.Item_name);
        $("#brand_des").val(res.a.brand_name);

    }

    if(res.a.is_guarantee == 1){
        $("#gur_crd").attr("checked", "checked");
    }else{
        $("#gur_crd").removeAttr("checked");
    }
    if(res.a.is_cancel==1){
        $("#btnDelete").attr("disabled", "disabled");
        $("#btnSave").attr("disabled", "disabled");
        $("#mframe").css("background-image", "url('img/cancel.png')");
    }

    if(res.a.crn_no!=0){

        $("#crn_no").val(res.a.crn_no);
        $("#crn_no_hid").val(res.a.crn_no);
    }else{
       $("#crn_no").val(res.crn);
       $("#crn_no_hid").val(0);
   }

}
loding(); 
}, "json");
}

function set_delete(){
    var id = $("#hid").val();
    if(id != 0){
        if(confirm("Are you sure ? ")){
            $.post("index.php/main/delete/t_job", {
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

function select_customer(){

    $.post("index.php/main/load_data/utility/f1_selection_list_customer",{
        data_tbl:"m_customer",
        field:"code",
        field2:"name",
        field3:"nic",
        field4:"tp",
        field_address:"address1-address2-address3",
        search:$("#pop_search4").val()
    },function(r){
        $("#sr4").html(r);
        customer_settings();
    },"text");
}

function customer_settings(){

    $("#item_list .cl").click(function(){
        $("#cus_id").val($(this).children().eq(0).html());
        $("#customer").val($(this).children().eq(1).html());
        $("#address").val($(this).children().eq(3).html());
        //$("#sup_id").val($(this).children().eq(0).html());
        $("#pop_close4").click();
    });
}
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

