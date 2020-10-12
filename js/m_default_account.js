$(document).ready(function(){
    $("#code").blur(function(){
        check_code();
    });
    
    $("#srchee").keyup(function(){  
    $.post("index.php/main/load_data/utility/get_data_table", {
                    code:$("#srchee").val(),
                    tbl:"m_default_account",
                    tbl_fied_names:"Code-Acc Code-Description",
                    fied_names:"code-acc_code-description",
                    col4:"Y"

                 }, function(r){
            $("#grid_body").html(r);
        }, "text");
    });

           
    $("#search_all").click(function(){
         if($("#search_all").is(':checked')){
            $.post("index.php/main/load_data/utility/get_data_table", {
                    code:$("#srchee").val(),
                    tbl:"m_default_account",
                    tbl_fied_names:"Code-Acc Code-Description",
                    fied_names:"code-acc_code-description",
                    col4:"Y",
                    search_all:"Y"
                    }, function(r){
                $("#grid_body").html(r);
                }, "text");
        }else{
            $("#srchee").keyup();
        }
      
    });


    $("#grid").tableScroll({
        height:355, 
        width:590
    });

    $("#acc_code").autocomplete('index.php/main/load_data/m_account/auto_com', {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatItems,
        formatResult: formatItemsResult
    });


    $("#acc_code").keydown(function(e){
        if(e.keyCode == 13){
            set_cus_values($(this));
        }
    });

    $("#acc_code").blur(function(){
        set_cus_values($(this));
    });

    $("#acc_code").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search4").val($("#acc_code").val());
            load_accf1();
            $("#serch_pop4").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search4').focus()", 100);
        }
        $("#pop_search4").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                 load_accf1();
            }
        }); 
        if(e.keyCode == 46){
            $("#acc_code").val("");
            $("#acc_code2").val("");
        }
    });
});

function load_accf1(){
      $.post("index.php/main/load_data/utility/f1_selection_list", {
          data_tbl:"m_account",
          field:"code",
          field2:"description",
          preview2:"Account Name",
          search : $("#pop_search4").val() 
      }, 
      function(r){
          $("#sr4").html(r);
          settings_accf1();            
      }, "text");
  }

  function settings_accf1(){
      $("#item_list .cl").click(
        function(){        
          $("#acc_code").val($(this).children().eq(0).html());
          $("#acc_code2").val($(this).children().eq(0).html());
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

function get_data_table(){
    $.post("index.php/main/load_data/m_default_account/get_data_table", {
        
        }, function(r){
            $("#grid_body").html(r);
        }, "text");
}

function check_code(){
    loding();
    var code = $("#code").val();
    $.post("index.php/main/load_data/m_default_account/check_code", {
        code : code
    }, function(res){
        if(res == 1){
            if(confirm("This code ("+code+") already added. \n\n Do you need edit it?")){
                set_edit(code);
            }else{
                $("#code").val('');
                $("#code").attr("readonly",false);
                input_reset();
            }
        }
        loding();
    }, "text");
}

function validate(){
    if($("#code").val() == $("#code").attr('title') || $("#code").val() == ""){
        set_msg("Please enter code.");
        $("#code").focus();
        return false;
    }else if($("#acc_code").val() === $("#acc_code").attr('title') || $("#acc_code").val() == ""){
        set_msg("Please enter account code.");
        $("#acc_code").focus();
        return false;
    }else if($("#des").val() === $("#des").attr('title') || $("#des").val() == ""){
        set_msg("Please enter description");
        return false;
    }else{
        return true;
    }
}
    
function set_delete(code){

    if(confirm("Are you sure delete "+code+"?")){
        loding();
        $.post("index.php/main/delete/m_default_account", {
            code : code
        }, function(res){
            if(res == 1){
                loding();
                delete_msg()
            }else{
                set_msg(res);
            }
            
        }, "text");
    }
}

function set_edit(code){
    loding();
    $.post("index.php/main/get_data/m_default_account", {
        code : code
    }, function(res){
        $("#code_").val(res.code);
        $("#code").val(res.code);
        $("#code").attr("readonly", true);
        $("#des").val(res.description);
        $("#acc_code").val(res.acc_code);
        $("#acc_code2").val(res.acc_code);
       
        loding(); 
        input_active();
    }, "json");
}

function set_cus_values(f){
    var v = f.val();
    v = v.split("|");
    if(v.length == 2){
        f.val(v[1]);
        $("#acc_code2").val(v[0]);
              
    }
}

function formatItems(row){
    return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
}

function formatItemsResult(row){
    return row[0]+"|"+row[1];
}