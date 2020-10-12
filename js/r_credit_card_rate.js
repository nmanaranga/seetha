    $(document).ready(function(){
    
    $("#bank_id").autocomplete('index.php/main/load_data/m_bank/auto_com2', {
    width: 350,
    multiple: false,
    matchContains: true,
    formatItem: formatItems,
    formatResult: formatItemsResult
    });


    $("#bank_id").keydown(function(e){
        if(e.keyCode == 13){
            set_cus_values($(this));
        }

        if(e.keyCode == 46){
            $(this).val("");
            $("#bank").val("");
        }


    });

    $("#bank_id").keydown(function(e){
        if(e.keyCode == 112){

       $("#pop_search4").val($("#bank_id").val());
            load_bankf1();
            $("#serch_pop4").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search4').focus()", 100);
        }

       $("#pop_search4").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                 load_bankf1();
            }
        }); 
        if(e.keyCode == 46){
            $("#bank_id").val("");
            $("#bank").val("");
        }
    });


    $(".g_input_amo").keydown(function(e){
        set_cid($(this).attr("id"));
        if(e.keyCode == 112){
            load_acccodef1();
            $("#serch_pop4").center();
             $("#blocker").css("display", "block");
            setTimeout("$('#pop_search4').focus()", 100);
        }

       $("#pop_search4").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                 load_acccodef1();
            }
        }); 
        if(e.keyCode == 46){
            $("#acc_"+scid).val("");
            $("#acc_name_"+scid).val("");
        }
    });

    $("#bank_id").blur(function(){
        set_cus_values($(this));
    });

    $("#srchee").keyup(function(){  
    $.post("index.php/main/load_data/r_credit_card_rate/data_table", {
                    code:$("#srchee").val(),
                    is_src:"1",
                 }, function(r){
            $("#grid_body").html(r);
        }, "text");
    });



    function set_cus_values(f){
            var v = f.val();
            v = v.split("|");
             if(v.length == 2){
                f.val(v[0]);
                $("#bank").val(v[1]);

                load_data(v[0]);
        }
    }
    function formatItems(row){
        return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
    }

    function formatItemsResult(row){
        return row[0]+"|"+row[1];
    }

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
    
});

function load_acccodef1(){
      $.post("index.php/main/load_data/utility/f1_selection_acc_code", {
          data_tbl:"m_account",
          field:"code",
          field2:"description",
          preview2:"Acc Name",
          search : $("#pop_search4").val() 
      }, 
      function(r){
          $("#sr4").html(r);
              settings_accf1();        
      }, "text");
  }

function load_bankf1(){
      $.post("index.php/main/load_data/utility/f1_selection_list", {
          data_tbl:"m_bank",
          field:"code",
          field2:"description",
          preview2:"Bank Name",
          search : $("#pop_search4").val() 
      }, 
      function(r){
          $("#sr4").html(r);
          settings_bankf1();            
      }, "text");
  }

  function settings_bankf1(){
      $("#item_list .cl").click(
        function(){        
          $("#bank_id").val($(this).children().eq(0).html());
          $("#bank").val($(this).children().eq(1).html());
          $("#pop_close4").click();                
      })    
  }

  function settings_accf1(){
      $("#item_list .cl").click(
        function(){        
          $("#acc_"+scid).val($(this).children().eq(0).html());
          $("#acc_name_"+scid).val($(this).children().eq(1).html());
          $("#pop_close4").click();                
      })    
  }

function validate(){
    save();
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
                alert("No permission to add data.");
            }else if(pid == 3){
                alert("No permission to edit data.");
            }else{
                alert(pid);
            }
    
        }
    });
}

function load_data(id){
    empty_grid();
    $.post("index.php/main/get_data/r_credit_card_rate/", {
        id: id
        }, function(r){

        if(r.card_rate=="2"){
           set_msg("No records","error");
        }else{
             
                for(var i=0; i<r.card_rate.length;i++){           
                  $("#rate_"+i).val(r.card_rate[i].rate);
                  $("#month_"+i).val(r.card_rate[i].month);

                }
            
        }
    },"json");    
}

function empty_grid(){
    for(x=0;x<12;x++){
        $("#rate_"+x).val("");
        $("#month_"+x).val("");
    }
}

function set_delete(code){
     check_delete_permission(code); 
}
    

function empty_grid(){

  $("#terminal_id").val("");
  $("#bank_id").val("");
  $("#bank").val("");
  $("#date").val("");
  
  for(var i=0; i<12; i++){
    $("#month_"+i).val("");
    $("#rate_"+i).val("");
    $("#merchant_id_"+i).val("");
    $("#acc_"+i).val("");
    $("#acc_name_"+i).val("");
  }
  

} 


function set_edit(code){

    empty_grid();
    loding();
    $.post("index.php/main/get_data/r_credit_card_rate", {
        code : code
    }, function(res){

        
           $("#hid").val(res.card_rate[0].nno);
          
        for(var i=0;i<res.card_rate.length;i++){

            $("#month_"+i).val(res.card_rate[i].month);
            $("#rate_"+i).val(res.card_rate[i].rate);
            $("#merchant_id_"+i).val(res.card_rate[i].merchant_id);
            $("#acc_"+i).val(res.card_rate[i].acc_no);
            $("#acc_name_"+i).val(res.card_rate[i].acc_des);
            if(res.card_rate[i].is_inactive==1){
              $("#active_"+i).attr("checked",true);
            }else{
              $("#active_"+i).attr("checked",false);  
            }  

        }
       

        $("#code_").val(res.card_rate[0].terminal_id);
        $("#terminal_id").val(res.card_rate[0].terminal_id);
        $("#terminal_id_h").val(res.card_rate[0].terminal_id);
        $("#id").val(res.card_rate[0].nno);
        $("#id").attr("readonly", true);
        $("#bank_id").val(res.card_rate[0].bank_id);
        $("#bank").val(res.card_rate[0].b_des);
        $("#date").val(res.card_rate[0].ddate);
        
        
        loding(); 
        input_active();
        is_edit=1;
    }, "json");
}

function check_delete_permission(code)
{
   
    if(confirm("Are you sure delete "+code+"?")){
        loding();
        $.post("index.php/main/delete/r_credit_card_rate", {
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