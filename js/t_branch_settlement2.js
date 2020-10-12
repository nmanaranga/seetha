var a=0;//this is for save function.......
var b=0;
var c=0;

$(document).ready(function(){

    $("#btnPrint").click(function(){
      if($("#hid").val()=="0"){
        set_msg("Please load data before print");
        return false;
    }
    else
    {
        $("#print_pdf").submit();
    }
});

    $("#id").keypress(function(e){
        if(e.keyCode==13){
            load_data();
        }
    });

    $("#load").click(function() {
     load_item_cr($("#acc_code").val(),$("#no_1").val(),$("#no_2").val(),$("#t_type").val());
 });
    $(".tdr").blur(function(){
        total_dr();
    });

    $(".tcr").blur(function(){
        total_cr();
    }); 

    $(".tcr").click(function(){
        set_cid($(this).attr("id")); 
    });

    $("#btnDelete").click(function(){
        set_delete(); 
    });

    $("#acc_code").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search4").val($("#acc_code").val());
            load_data_supf1();
            $("#serch_pop4").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search4').focus()", 100);
        }

        $("#pop_search4").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
             load_data_supf1();

         }
     }); 

        if(e.keyCode == 46){
            $("#acc_code").val("");
            $("#acc_des").val("");
        }
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
                    set_msg("Item "+$("#0_"+scid).val()+" is already added.");
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
    
    
    $("#pop_search").gselect();    



});


function load_data_supf1(){
    $.post("index.php/main/load_data/t_branch_settlement/f1_selection_current_account", {
        search : $("#pop_search4").val() 
    }, 
    function(r){
      $("#sr4").html(r);
      settings_accounts();            
  }, "text");
}

function settings_accounts(){
  $("#item_list .cl").click(function(){        
      $("#acc_code").val($(this).children().eq(0).html());
      $("#acc_des").val($(this).children().eq(1).html());
      $("#pop_close4").click();  
  })    
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
    $.post("index.php/main/load_data/t_branch_settlement/item_list_all", {
        search : $("#pop_search").val(),
        acc_code:$("#acc_code").val(),
        stores : false,
        t_type:$("#t_type").val()       
    }, function(r){
        $("#sr").html(r);
        settings();
        
    }, "text");

}


function settings(){
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
    // if($("#code").val() === $("#code").attr('title') || $("#code").val() == ""){
    //     set_msg("Please enter code.");
    //     $("#code").focus();
    //     return false;
    // }else if($("#description").val() === $("#description").attr('title') || $("#description").val() == ""){
    //     set_msg("Please enter description.");
    //     $("#description").focus();
    //     return false;
    // }else if($("#description").val() === $("#code").val()){
    //     set_msg("Please enter deferent values for description & code.");
    //     $("#des").focus();
    //     return false;
    // }else{
    //     return true;
    // }
    if( $("#ttl_dr").val() != $("#ttl_cr").val() ){
        set_msg("Please check the CR Amount with DR Amount");
        return false;
    }
    else{
      return true;  
  }

}

function set_delete(){
    var id = $("#hid").val();
    if(id != 0){
        if(confirm("Are you sure to delete this settlement ["+$("#id").val()+"]? ")){
          $.post("index.php/main/delete/t_branch_settlement", {
            trans_no:id,
        },function(r){
          if(r != 1){
            set_msg(r);
        }else{
           delete_msg();
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
    for(var i=0; i<100; i++){
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
    for(var i=0; i<100; i++){
        $("#00_"+i).val('');
        $("#nn_"+i).val('');
        $("#sn_"+i).val('');
        $("#11_"+i).val('');
        $("#22_"+i).val('');
        $("#33_"+i).val('');  
        $("#44_"+i).val('');
        $("#55_"+i).val('');
    }
}


function load_item_cr(cus,no_1,no_2,ttype){
  empty_grid_cr();
  $.post("index.php/main/load_data/t_branch_settlement/load_item_cr",
  {
      cus:cus,
      no_1:no_1,
      no_2:no_2,
      t_type:ttype
  },
  function(r){
    if(r.det!=2){
        for (var i = 0; i < r.det.length; i++) {
            $("#00_"+i).val(r.det[i].description);
            $("#hh_"+i).val(r.det[i].TYPE);
            $("#nn_"+i).val(r.det[i].trans_no);
            $("#sn_"+i).val(r.det[i].sub_no);
            $("#11_"+i).val(r.det[i].ddate);
            $("#22_"+i).val(r.det[i].amount);
            $("#33_"+i).val(r.det[i].balance);
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
                set_msg("Check balance and settle");
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
                set_msg("Check balance and settle");
                x=0;
            }
            
            total_cr+=x;    
        }

    });
    $("#ttl_cr").val(m_round(total_cr));
}


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

    $.post("index.php/main/load_data/t_sup_settlement/save_data",
    {
        cr:cr,
        type:type,
        sub_type:sub_type,
        sub_no:sub_no,
        no:no
    },
    function(r){
        if(r==1){
            set_msg("added successfully..");
        }else{


        }
    },
    "json");

}


function load_data(){

    empty_grid_dr();
    empty_grid_cr();
    loding();
    $.post("index.php/main/load_data/t_branch_settlement/load_data",
    {
        id:$("#id").val()
    },
    function(r){

        $("#acc_code").val( r.sup_det[0].acc_code );
        $("#acc_des").val( r.sup_det[0].name );
        $("#ttl_dr").val( r.sum[0].amount );
        $("#ttl_cr").val( r.sum[0].amount );
        $("#date").val( r.sum[0].ddate );


        $("#0_0").val( r.dr_det[0].des );
        $("#h_0").val( r.dr_det[0].TYPE);
        $("#hid").val($("#id").val());
        $("#qno").val($("#id").val());
        $("#n_0").val( r.dr_det[0].trans_no );
        $("#1_0").val( r.dr_det[0].date );
        $("#2_0").val( r.dr_det[0].amount );
        $("#3_0").val( r.dr_det[0].balance );
        $("#4_0").val( r.dr_det[0].settle );
        $("#5_0").val( r.dr_det[0].description );

        if(r.sum[0].is_cancel==1){
            $("#btnDelete").attr("disabled", "disabled");
            $("#btnSave").attr("disabled", "disabled");
            $("#mframe").css("background-image", "url('img/cancel.png')");
        }

        for(var i=0; i< r.cr_det.length; i++){            
            $("#00_"+i).val( r.cr_det[i].des );
            $("#hh_"+i).val( r.cr_det[i].TYPE );
            $("#nn_"+i).val( r.cr_det[i].trans_no );
            $("#sn_"+i).val( r.cr_det[i].sub_no );
            $("#11_"+i).val( r.cr_det[i].date );
            $("#22_"+i).val( r.cr_det[i].amount );
            $("#33_"+i).val( r.cr_det[i].balance );
            $("#44_"+i).val( r.cr_det[i].settle );
            $("#55_"+i).val( r.cr_det[i].description );
        }

        setTimeout(function(){
            $("#total_amount").val(m_round(get_column_total('.tcr','#22_')));
            $("#total_balance").val(m_round(get_column_total('.tcr','#33_')));
        }, 200);

        loding();
    }
    ,"json");
}


