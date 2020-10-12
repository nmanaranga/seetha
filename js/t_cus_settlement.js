var a=0;//this is for save function.......
var b=0;
var c=0;

$(document).ready(function(){


 $(".tcr").keyup(function(){
    total_cr();
    var B = $("#3_0").val();
    var T = $("#ttl_cr").val();

    $("#ttl_cr_nt_bal").val( B-T );

}); 

 $("#customer").keydown(function(e){ 
    if(e.keyCode==112){
      $("#pop_search2").val($("#customer").val());
      load_customer();
      $("#serch_pop2").center();
      $("#blocker2").css("display", "block");
      setTimeout("$('#pop_search2').focus()", 100);   
  }
  $("#pop_search2").keyup(function(e){
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
        load_customer();
    }
});
  if(e.keyCode==46){
     $("#customer").val("");
     $("#customer_id").val("");
 }  
});


 $("#auto_fill").click(function(){
   if($("#auto_fill").is(':checked')){

    var actual_amount=parseFloat($("#4_0").val());
    if(!isNaN($("#net").val())){
      amount=parseFloat($("#4_0").val());
  }else{
      amount=0;
  }
  var amount=parseFloat($("#4_0").val());
  var settlement=0;
  $(".foo").each(function(e){
      $("#44_"+e).attr("readonly",true);
      var balance=parseFloat($("#33_"+e).val());
      if(!isNaN(balance)){
       if(amount>balance){
        amount=amount-balance;
        $("#44_"+e).val(m_round(balance));
        settlement=settlement+parseFloat($("#44_"+e).val());
        $("#ttl_cr").val(m_round(settlement)); 
        $("#ttl_cr_nt_bal").val(m_round(actual_amount-settlement));  
    }else{

        $("#44_"+e).val(m_round(amount));
        settlement=settlement+parseFloat($("#44_"+e).val());
        $("#ttl_cr").val(m_round(settlement)); 
        $("#ttl_cr_nt_bal").val(m_round(actual_amount-settlement));  

        return false;
    }
}
});    


}else{

    $(".foo").each(function(e){
      $("#44_"+e).removeAttr("readonly");
      $("#44_"+e).val("");
      $("#ttl_cr").val("");
      $("#ttl_cr_nt_bal").val("");                     
  });
}              
});


 $("#code").blur(function(){
    check_code();
});



 $("#id").keydown(function(e){
    if(e.keyCode==13){
        load_data();
    }
});


 $("#btnDelete").click(function(e){
    set_delete();
});


 $(".tdr").blur(function(){

    total_dr();
});

 $(".tcr").blur(function(){

        // total_cr();
    }); 

 $(".tcr").click(function(){
    set_cid($(this).attr("id")); 
        //save_data(scid);

    }); 

 $("#tgrid").tableScroll({height:200});
 $("#tgrid2").tableScroll({height:200});


 $(".fo").keydown(function(e){
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
                set_msg("Item "+$("#0_"+scid).val()+" is already added.");
            }

        }
    }, "json");

}


});

       // load_items();
       $("#pop_search").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { load_items();
        }
    });     


       $("#pop_search").gselect();    




       $("#customer").autocomplete('index.php/main/load_data/m_customer/auto_com', {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatItems,
        formatResult: formatItemsResult
    });

       $("#customer").keydown(function(e){
        if(e.keyCode == 13){
            set_cus_values($(this));
        }
    });

       $("#customer").blur(function(){
        set_cus_values($(this));
        setTimeout(function(){
            $("#total_amount").val(m_round(get_column_total('.tcr','#22_')));
            $("#total_balance").val(m_round(get_column_total('.tcr','#33_')));
        }, 200);
    });


   });

/*function load_customer(){
  $.post("index.php/main/load_data/utility/f1_selection_list", {
      data_tbl:"m_customer",
      field:"code",
      field2:"name",
      preview2:"Customer Name",
      search : $("#pop_search2").val() 
  }, function(r){
      $("#sr2").html(r);
      settings_cus();      
 }, "text");
}*/

function load_customer(){
    $.post("index.php/main/load_data/utility/f1_selection_list_customer", {
        data_tbl:"m_customer",
        field:"code",
        field2:"name",
        field3:"nic",
        preview1:"Customer ID",
        preview2:"Customer Name",
        preview3:"Customer NIC",
        search : $("#pop_search2").val() 
    }, function(r){
        $("#sr2").html("");
        $("#sr2").html(r);
        settings_cus();            
    }, "text");
}



function settings_cus(){
    $("#item_list .cl").click(function(){        
        $("#customer").val($(this).children().eq(0).html());
        $("#customer_id").val($(this).children().eq(1).html());
        $("#customer").blur();
        $("#pop_close2").click();                
    })    
}



function formatItems(row){
    return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
}

function formatItemsResult(row){
    return row[0]+"-"+row[1];
}

function set_cus_values(f){
    var v = f.val();
    v = v.split("-");
    load_item_cr(v[0]);

    if(v.length == 2){

        f.val(v[0]);
        $("#customer_id").val(v[1]);

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
            $("#btnSave").attr("disabled",true);
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


function select_search(){
    $("#pop_search").focus();
}


function load_items(){

    $.post("index.php/main/load_data/t_cus_settlement/item_list_all", {
        search : $("#pop_search").val(),
        cus:$("#customer").val(),
        stores : false
    }, function(r){
        $("#sr").html(r);
        settings();
        
    }, "text");

}


function settings(){


   //empty_grid_dr();
   $("#item_list .cl").click(function(){
    if($(this).children().eq(0).html() != "&nbsp;"){
        $("#0_"+scid).val($(this).children().eq(5).html());
        $("#h_"+scid).val($(this).children().eq(0).html());
        $("#n_"+scid).val($(this).children().eq(1).html()); 
        $("#1_"+scid).val($(this).children().eq(2).html());                 
        $("#2_"+scid).val($(this).children().eq(3).html()); 
        $("#3_"+scid).val($(this).children().eq(4).html());  
        $("#5_"+scid).val($(this).children().eq(5).html()); 


        $("#pop_close").click();
    }
});
}
function get_data_table(){
    $.post("/index.php/main/load_data/t_settlemet/get_data_table", {

    }, function(r){
        $("#grid_body").html(r);
    }, "text");
}


function check_code(){
    loding();
    var code = $("#code").val();
    $.post("index.php/main/load_data/t_settlemet/check_code", {
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

    if( $("#ttl_dr").val() != $("#ttl_cr").val() ){
        set_msg("Please check the CR Amount with DR Amount");
        return false;
    }
    else{
      return true;  
  }

}

function set_delete(code){
    var id = $("#hid").val();
    if(id != 0){
        if(confirm("Are you sure to delete this settlement ["+$("#id").val()+"]? ")){
          $.post("index.php/main/delete/t_cus_settlement", {
            trans_no:id,
        },function(r){
          if(r != 1){
            set_msg(r);
        }else{
            delete_msg()
        }
    }, "text");
      }
  }else{
    set_msg("Please load record");
}
}

function is_edit($mod)
{
    $.post("index.php/main/is_edit/user_permissions/is_edit", {
        module : $mod
        
    }, function(r){
     if(r==1)
     {
       $("#btnSave").removeAttr("disabled", "disabled");
   }
   else{
       $("#btnSave").attr("disabled", "disabled");
   }

}, "json");

}

function set_edit(code){
    loding();
    $.post("index.php/main/get_data/t_settlemet", {
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

function empty_grid_dr(){
    for(var i=0; i<300; i++){
        $("#0_"+i).val('');
        $("#n_"+i).val('');
        $("#1_"+i).val('');
        $("#2_"+i).val('');
        $("#3_"+i).val('');
        $("#4_"+i).val('');
        $("#5_"+i).val('');
    }
}

function empty_grid_cr(){
    for(var i=0; i<300; i++){
        $("#00_"+i).val('');
        $("#nn_"+i).val('');
        $("#11_"+i).val('');
        $("#22_"+i).val('');
        $("#33_"+i).val('');  
        $("#44_"+i).val('');
        $("#55_"+i).val('');
    }
}

// function load_item_dr(cus){
//     empty_grid_dr();
//     $.post("index.php/main/load_data/t_cus_settlement/load_item_dr",
//         {
//           cus:cus  
//         },
//         function(r){
//             c=r.det.length;//this is for save function.......
//             for (var i = 0; i < r.det.length; i++) {
//                 $("#0_"+i).val(r.det[i].type);
//                 $("#n_"+i).val(r.det[i].trans_no);
//                 $("#1_"+i).val(r.det[i].ddate);
//                 $("#2_"+i).val(r.det[i].amount);
//                 $("#3_"+i).val(r.det[i].balance);
//                 $("#5_"+i).val(r.det[i].description);
//             }

//         },
//         "json");

// }

function load_item_cr(cus){
  empty_grid_cr();
  $.post("index.php/main/load_data/t_cus_settlement/load_item_cr",
  {
      cus:cus  
  },
  function(r){

    if(r.det!=2){
        for (var i = 0; i < r.det.length; i++) {
            $("#00_"+i).val(r.det[i].description);

            $("#nn_"+i).val(r.det[i].trans_no);
            $("#11_"+i).val(r.det[i].ddate);
            $("#22_"+i).val(r.det[i].amount);
            $("#33_"+i).val(r.det[i].balance);
            $("#hh_"+i).val(r.det[i].TYPE);
            $("#55_"+i).val(r.det[i].description);
        }    
    }
},
"json");
}

function total_dr(){
    var x=0; var total_dr=0;
    $(".tdr").each(function(){
        set_cid($(this).attr("id"));       
        if($(this).val()==""){
            x=0;
        }
        else{
            x=parseFloat($(this).val());

            if(x > parseFloat( $("#3_"+scid).val() ) || $("#3_"+scid).val() == ""){
                set_msg("check balance and settle");
                x=0;
            }
            
            total_dr+=x;    
        }

    });
    $("#ttl_dr").val(m_round(total_dr));
}

function total_cr(){
    var x=0; var total_cr=0;
    $(".tcr").each(function(){
        set_cid($(this).attr("id"));       
        if($(this).val()==""){
            x=0;
        }
        else{
            x=parseFloat($(this).val());

            if(x > parseFloat( $("#33_"+scid).val()  ) || $("#33_"+scid).val() == ""){
                set_msg("check balance and settle");
                x=0;
            }
            
            total_cr+=x;    
        }

    });
    $("#ttl_cr").val(m_round(total_cr));
}


// function  save_data_m_m(){
//     var j=0;var q=0;
//     var cr=0,type='',sub_type='',no=0;
//     var balance=0;
//     //set_msg("in save_data ");
//     for(var i=0;i<c;i++){
//         for(j;  j < a ;j++){
//             q=parseFloat( $("#44_"+j).val() )+balance;
//             if( q > parseFloat( $("#4_"+i).val() ) ){

//                 cr=parseFloat( $("#4_"+i).val() );
//                 type=$("#0_"+i).val();
//                 sub_type=$("#00_"+j).val();
//                 sub_no=$("#nn_"+j).val();
//                 no=$("#n_"+i).val();
//                 balance= q - $("#4_"+i).val();
//                 i++;
//                 if(!balance){
//                     break;
//                 }

//             }
//             else{
//                 cr=parseFloat( $("#44_"+j).val() )+balance; 
//                 type=$("#0_"+i).val();
//                 sub_type=$("#00_"+j).val( );
//                 sub_no=$("#nn_"+j).val( );
//                 no=$("#n_"+i).val();
//                 balance=parseFloat($("#4_"+j).val()) - parseFloat($("#44_"+i).val());
//                 //i++;
//                 if(!balance){
//                     break;
//                 }

//             } 


// //set_msg();
//             $.post("index.php/main/load_data/t_cus_settlement/save_data",
//                 {
//                     cr:cr,
//                     type:type,
//                     sub_type:sub_type,
//                     sub_no:sub_no,
//                     no:no
//                 },
//                 function(r){
//                     if(r==2){
//                         set_msg("Error"+j);
//                     }else{
//                         set_msg("added successfully..");
//                     }
//                 },
//                 "json");

//         }
//         //if(a < c &&  )

//     }


// }

function save_data_1_m(){
    var cr=[];
    var type=[];
    var sub_type=[];
    var sub_no=[];
    var no=[];
    for(var i=0; i < a ; i++){
        cr[i]=$("#44_"+i).val();
        type[i]=$("#0_0").val();
        sub_type[i]=$("#00_"+i).val();
        sub_no[i]=$("#nn_"+i).val();
        no[i]=$("#n_0").val();      

    }

    $.post("index.php/main/load_data/t_cus_settlement/save_data",
    {
        cr:cr,
        type:type,
        sub_type:sub_type,
        sub_no:sub_no,
        no:no
    },
    function(r){
        if(r==2){
            set_msg("Error");
        }else{
            set_msg("added successfully..");
        }
    },
    "json");

}


function load_data(){

    empty_grid_dr();
    empty_grid_cr();
    loding();
    $.post("index.php/main/get_data/t_cus_settlement/",
    {
        id:$("#id").val()
    },
    function(r){
        $("#hid").val($("#id").val());
        $("#customer").val(r.sum[0].customer );
        $("#customer_id").val(r.cus_det[0].name );
        $("#date").val(r.sum[0].sum_date);
        
        $("#ttl_cr").val(r.sum[0].amount);
        $("#ttl_dr").val(r.sum[0].amount);

        if(r.sum[0].is_cancel==1){
            $("#btnDelete").attr("disabled", "disabled");
            $("#btnSave").attr("disabled", "disabled");
            $("#mframe").css("background-image", "url('img/cancel.png')");
        }
        

        $("#0_0").val( r.dr[0].description );
        $("#h_0").val( r.dr[0].type );
        $("#n_0").val( r.dr[0].trans_no );
        $("#1_0").val( r.dr[0].date );
        $("#2_0").val( r.dr[0].amount );
        $("#3_0").val( r.dr[0].balance );
        $("#4_0").val( r.dr[0].settle );
        $("#5_0").val( r.dr[0].description_dr );




        for(var i=0; i< r.cr.length; i++){            
            $("#00_"+i).val( r.cr[i].description );
            $("#hh_"+i).val( r.cr[i].type_cr );
            $("#nn_"+i).val( r.cr[i].trans_no_cr);
            $("#11_"+i).val( r.cr[i].date_cr);
            $("#22_"+i).val( r.cr[i].amount_cr);
            $("#33_"+i).val( r.cr[i].balance_cr);
            $("#44_"+i).val( r.cr[i].settle_cr);
            $("#55_"+i).val( r.cr[i].description_cr);

        }

        setTimeout(function(){
            $("#total_amount").val(m_round(get_column_total('.tcr','#22_')));
            $("#total_balance").val(m_round(get_column_total('.tcr','#33_')));
        }, 200);

        var B = $("#3_0").val();
        var T = $("#ttl_cr").val();

        $("#ttl_cr_nt_bal").val( B-T );
        loding();
    }
    ,"json");
}


