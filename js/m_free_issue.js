$(document).ready(function(){
    $("#tgrid").tableScroll({height:400, width:435});
    $("#tgrid2").tableScroll({height:400, width:435});
    $(".tablescroll_wrapper").css({width:458});

    $(document).on('keydown', ".supplier_id", function (e) {
        set_cid($(this).attr("id")); 
        if (e.keyCode == 112) {
            load_supplier();
            $("#serch_pop").center();
            $("#blocker").css("display", "block");
            setTimeout("select_search()", 100);
        }
    });

    $(document).on('keydown', ".fo", function (e) {        
        set_cid($(this).attr("id")); 
        if (e.keyCode == 112) {
           $("#pop_search").val("");
            load_items();
            $("#serch_pop").center();
            $("#blocker").css("display", "block");
            setTimeout("select_search()", 100);  
        }

        $("#pop_search").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                load_items();
            }
        });
    });


    $(document).on('keydown', ".foo", function (e) {        
        set_cid($(this).attr("id")); 
        if (e.keyCode == 112) {
            $("#pop_search").val("");
            load_items2();
            $("#serch_pop").center();
            $("#blocker").css("display", "block");
            setTimeout("select_search()", 100);  
        }

        $("#pop_search").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                load_items2();
            }
        });
    });

    $(".fo").keyup(function(e){
        if(e.keyCode==46){
            $("#h_"+scid).val("");
            $("#0_"+scid).val("");
            $("#1_"+scid).val("");
            $("#2_"+scid).val("");
            $("#3_"+scid).val("");
        }        
    });

    $(".foo").keyup(function(e){
        if(e.keyCode==46){
            $("#hh_"+scid).val("");
            $("#00_"+scid).val("");
            $("#11_"+scid).val("");
            $("#22_"+scid).val("");
            $("#33_"+scid).val("");
        }
    });

    $("#load_f").click(function(){
        $("#pop_search").val("");
        load_foc();
        $("#serch_pop").center();
        $("#blocker").css("display", "block");
        setTimeout("select_search()", 100);  
    });
    
    $("#btnDelete").click(function(){
        set_delete();
    });

    $("#btnSave").click(function(){
        validate();
    });

    $("#no").keydown(function(e){
        if (e.keyCode == 13){
            load_data();
        }
    });

    $("#btnReset").click(function(){
        $("#btnSave1").attr("disabled",false)
    });

});

function load_data(){
    empty_grid();
    $.post("index.php/main/get_data/m_free_issue/", {
        no: $("#no").val()
    }, function (r) {  
        if(r==2){
            set_msg("No Records ! ! !");

        }else{
            $("#no").attr("readonly","readonly");
            $("#hid").val(r.sum[0].nno);

            $("#code").val(r.sum[0].code);
            $("#c_name").val(r.sum[0].name);
            $("#date").val(r.sum[0].date);
            $("#date_from").val(r.sum[0].dfrom);
            $("#date_to").val(r.sum[0].dto);

            
            for (var i=0; i<r.det.length;i++){
                $("#h_"+i).val(r.det[i].po_item);
                $("#0_"+i).val(r.det[i].po_item);
                $("#1_"+i).val(r.det[i].i_des);
                $("#2_"+i).val(r.det[i].i_mod);
                $("#3_"+i).val(r.det[i].po_qty);

                $("#hh_"+i).val(r.det[i].foc_item);
                $("#00_"+i).val(r.det[i].foc_item);
                $("#11_"+i).val(r.det[i].f_des);
                $("#22_"+i).val(r.det[i].f_mod);;
                $("#33_"+i).val(r.det[i].foc_qty);
            } 
            if(r.sum[0].is_cancel==1){
                $("#btnDelete").attr("disabled", "disabled");
                $("#btnSave").attr("disabled", "disabled");
                $("#mframe").css("background-image", "url('img/cancel.png')");
                $("#load_f").attr("disabled", "disabled");
                
            }               
        }      
    }, "json");
}

function load_items() {

    $.post("index.php/main/load_data/m_free_issue/item_list_all", {
        search: $("#pop_search").val(),
        item: $("#0_"+scid).val()
    }, function (r) {
        $("#sr").html(r);
        settings(1);

    }, "text");
}

function load_items2() {

    $.post("index.php/main/load_data/m_free_issue/item_list_all", {
        search: $("#pop_search").val(),
        item: $("#0_"+scid).val()
    }, function (r) {
        $("#sr").html(r);
        settings(2);
    }, "text");
}

function load_foc() {

    $.post("index.php/main/load_data/m_free_issue/foc_sale_list", {
        search: $("#pop_search").val(),
    }, function (r) {
        $("#sr").html(r);
        settings1();
    }, "text");
}


function settings(cd) {
    $("#item_list .cl").click(function () {
        if(cd==1){
            if(check_item_exist($(this).children().eq(0).html())){
                $("#h_"+scid).val($(this).children().eq(0).html());
                $("#0_"+scid).val($(this).children().eq(0).html());
                $("#1_"+scid).val($(this).children().eq(1).html());
                $("#2_"+scid).val($(this).children().eq(1).html());
                $("#pop_close").click();
            }else{
                set_msg("Item "+$(this).children().eq(0).html()+" is already added.");
            }
        }else{
            if(check_item_exist2($(this).children().eq(0).html())){
                $("#hh_"+scid).val($(this).children().eq(0).html());
                $("#00_"+scid).val($(this).children().eq(0).html());
                $("#11_"+scid).val($(this).children().eq(1).html());
                $("#22_"+scid).val($(this).children().eq(1).html());
                $("#pop_close").click();
            }else{
                set_msg("Item "+$(this).children().eq(0).html()+" is already added.");
            }
        }

    });
}

function settings1() {
    $("#item_list .cl").click(function () {
        $("#no").val($(this).children().eq(0).html());
        load_data();
        $("#pop_close").click();

    });
}

function empty_grid() {
    for(var i=0; i<25; i++){
        $("#h_"+i).val("");
        $("#0_"+i).val("");
        $("#1_"+i).val("");
        $("#2_"+i).val("");
        $("#3_"+i).val("");
        $("#hh_"+i).val("");
        $("#00_"+i).val("");
        $("#11_"+i).val("");
        $("#22_"+i).val("");
        $("#33_"+i).val("");
    }
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
    $(".hidd").each(function(){
        if($(this).val() == id){
            v = false;
        }
    });    
    return v;
}

function set_delete(){
    var id = $("#hid").val();
    if(id != 0){
        if(confirm("Are you sure to delete this bulk FOC ["+$("#hid").val()+"]? ")){
            $.post("index.php/main/delete/m_free_issue", {
            id:id,
        },function(r){
          if(r != 1){
            set_msg(r);
          }else{
            delete_msg();
          }
        }, "text");
    }
  }else{
    set_msg("Please load record","error");
  }
}

function load_supplier(){
    $.post("index.php/main/load_data/utility/f1_selection_list", {
        search : $("#pop_search2").val(),
        field2 :'name',
        data_tbl:'m_supplier'
    }, function(r){
        $("#sr2").html(r);
        settings2();
    }, "text"); 
}

function settings2(){
    $("#item_list .cl").click(function(){
        $("#supp_"+scid).val($(this).children().eq(0).html());
        $("#supplier_"+scid).val($(this).children().eq(1).html());
        $("#supp_name").val($(this).children().eq(1).html());

        $("#pop_close2").click();
    });
}

function select_search() {
    $("#pop_search").focus();
}

function check_item_exist3(id){
    var v = true;
    $(".fo").each(function(){
        $(this).val() == id;
        v = false;
    })
    return v;
}

function validate(){

  if ($("#code").val() == "" || $("#c_name").val() == "" ){
        set_msg("Please enter code and code name");
        return false;
    }else if ($("#date_from").val() == "" && $("#date_to").val() == "" ){
        set_msg("Please select from date and to date");
        return false;
    }else{
        return true;
    }
}

function save(){
    var frm = $("#form_");
    $.ajax({
        type: frm.attr('method'),
        url: frm.attr('action'),
        data: frm.serialize(),
        dataType : 'json',
        success: function (pid) {
            if (pid == 1){  
                $("#btnSave").attr("disabled",true);            
                sucess_msg();
            }else{
                set_msg(pid);
            }
        }
    });
}