var is_edit=0;
$(document).ready(function(){
    $("#code").blur(function(){
        check_code();
    });

    $("#srchee").keyup(function(){  
    $.post("index.php/main/load_data/utility/get_data_table", {
                    code:$("#srchee").val(),
                    tbl:"m_bank_branch",
                    tbl_fied_names:"Bank-Code-Description",
                    fied_names:"bank-code-description",
                    col4:"Y"
                 }, function(r){
            $("#grid_body").html(r);
        }, "text");
    });

    $("#code").keyup(function(){
        if($("#sbank").val()!=""){
            if($("#code_").val()=="0"){
                 var code ="";
                 code = $("#b_branch_code").val()+"-"+$(this).val();
                 $("#branch_code").val(code);
            }           
        }else{
            set_msg("Please select bank first");
        }
    });

    $("#grid").tableScroll({height:355});

    $("#sbank").change(function(){
        set_select('bank','bank')
    });

    $("#sbank").keydown(function(e){
    if(e.keyCode == 112){
        $("#pop_search4").val($("#sbank").val());
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
        $("#sbank").val("");
        $("#bank_des").val("");
    }
  });


    $("#sbank").autocomplete('index.php/main/load_data/m_bank_branch/auto_com_bank', {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatbtype,
        formatResult: formatbtypeResult
    });
    
    $("#sbank").blur(function(){
        set_Bank_values($(this));
    });
    
    $("#sbank").keydown(function(e){
        if(e.keyCode == 13){
            set_Bank_values($(this));
        }
    });

});

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
          $("#sbank").val($(this).children().eq(0).html());
          $("#bank_des").val($(this).children().eq(1).html());
          $("#b_branch_code").val($(this).children().eq(0).html());
          $("#branch_code").val($(this).children().eq(0).html());
          $("#pop_close4").click();                
      })    
  }

function set_Bank_values(f){
    var v = f.val();
    v = v.split("~");
    if(v.length == 2){
        f.val(v[0]);
        $("#bank").val(v[0]);
        $("#bank_des").val(v[1]);
        $("#sbank").attr("class", "input_txt_f");

        $("#b_branch_code").val(v[0]);
        $("#branch_code").val(v[0]);
    }
}

function formatbtype(row){
    return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
}

function formatbtypeResult(row){
    return row[0]+"~"+row[1];
}

function check_delete_permission(code){

        if(confirm("Are you sure delete "+code+"?")){
        loding();
        $.post("index.php/main/delete/m_bank_branch", {
            code : code
        }, function(res){
            if(res == 1){
                loding();
                delete_msg();
            }else{
                set_msg("Item deleting fail.");
            }
            
        }, "text");
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
    $.post("index.php/main/load_data/m_bank_branch/get_data_table", {
        
    }, function(r){
        $("#grid_body").html(r);
    }, "text");
}

function check_code(){
    var code = $("#branch_code").val();
    $.post("index.php/main/load_data/m_bank_branch/check_code", {
        code : code
    }, function(res){
        if(res == 1){
            if(confirm("This code ("+code+") already added. \n\n Do you need edit it?")){
                set_edit(code);
            }else{
                $("#code").val('');
            }
        }
    }, "text");
}

function validate(){
        if($("#sbank").val() == "0"){
            set_msg("Please select bank");
            $("#bank").focus();
         }else if($("#code").val() === $("#code").attr('title') || $("#code").val() == ""){
            set_msg("Please enter code.");
            $("#code").focus();
            return false;
        }else if($("#description").val() === $("#description").attr('title') || $("#description").val() == ""){
            set_msg("Please enter description.");
            $("#des").focus();
            return false;
        }else{
            return true;
        }
}
    
function set_delete(code){
 check_delete_permission(code);
}
    
function set_edit(code){
    loding();
    $("#code").attr("readonly","readonly");
    $("#sbank").attr("readonly","readonly");
    
    $.post("index.php/main/get_data/m_bank_branch", {
        code : code
    }, function(res){

        $("#code").val(res.branch_code);
        $("#sbank").val(res.bank);
        $("#bank").val(res.bank);
        $("#bank_des").val(res.bank_des);
        $("#description").val(res.description);
        $("#code_").val(res.code);
        $("#branch_code").val(res.code);
         
        //set_select('bank','bank');
        loding(); 
        input_active();
        is_edit=1;
    }, "json");
}