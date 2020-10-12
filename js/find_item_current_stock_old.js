$(document).ready(function () {

    $("#grid").tableScroll({
        height: 355,
        width: 1080
    });  
    load_itemss();

    $("#fine").click(function(){
        if($("#fine").is(":checked")){
            item_all();
        }else{
            load_itemss();
        }
        
    });

    $("#btnLoad_data").click(function(){
        load_itemss();
    });

    $(".pp").keydown(function(e){
        if(e.keyCode==13){
            load_itemss();
        }
    });

    $("#txt_search").keyup(function(){
        if($("#fine").is(":checked")){
            item_all();
        }else{
          load_itemss();  
        }
        
    });
    
    
    $("#btnClear").click(function(){
        clear_ser();
    });

    $("#txt_cluster").keydown(function (e) {
        if (e.keyCode == 112) {
            $("#pop_search11").val();
            load_cluster();
            $("#serch_pop11").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search11').focus()", 100);
        }
        $("#pop_search11").keyup(function (e) {
            if (e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112) {
                load_cluster();
            }
        });
        if (e.keyCode == 46) {
            $("#txt_cluster").val("");
            $("#hid_cluster").val("");
            $("#txt_branch").val("");
            $("#hid_branch").val("");
            $("#txt_store").val("");
            $("#hid_store").val("");
        }
    });

    $("#txt_branch").keydown(function (e) {
        if (e.keyCode == 112) {
            $("#pop_search2").val();
            load_branch();
            $("#serch_pop2").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search2').focus()", 100);
        }
        $("#pop_search2").keyup(function (e) {
            if (e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112) {
                load_branch();
            }
        });
        if (e.keyCode == 46) {
            $("#txt_branch").val("");
            $("#hid_branch").val("");
            $("#txt_store").val("");
            $("#hid_store").val("");
        }
    });

    $("#txt_store").keydown(function (e) {
        if (e.keyCode == 112) {
            $("#pop_search4").val();
            load_store();
            $("#serch_pop4").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search4').focus()", 100);
        }
        $("#pop_search4").keyup(function (e) {
            if (e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112) {
                load_store();
            }
        });
        if (e.keyCode == 46) {
            $("#txt_store").val("");
            $("#hid_store").val("");
        }
    });


});

function load_cluster() {
    $.post("index.php/main/load_data/find_item_current_stock/get_cluster_name", {
        search: $("#pop_search11").val()
    }, function (r) {
        $("#sr11").html(r);
        cluster_settings();
    }, "text");
}

function cluster_settings() {
    $("#item_list .cl").click(function () {
        $("#txt_branch").val("");
        $("#hid_branch").val("");
        $("#txt_store").val("");
        $("#hid_store").val("");
        $("#txt_cluster").val($(this).children().eq(0).html());
        $("#hid_cluster").val($(this).children().eq(1).html());
        $("#pop_close11").click();
    })
}

function load_branch() {

    $.post("index.php/main/load_data/find_item_current_stock/get_branch_name", {
        cluster:$("#txt_cluster").val(),
        search: $("#pop_search2").val()
    }, function (r) {
        $("#sr2").html(r);
        branch_settings();
    }, "text");
}

function branch_settings() {
    $("#item_list .cl").click(function () {
        $("#txt_store").val("");
        $("#hid_store").val("");
        $("#txt_branch").val($(this).children().eq(0).html());
        $("#hid_branch").val($(this).children().eq(1).html());
        $("#pop_close2").click();
    })
}

function load_store() {
    $.post("index.php/main/load_data/find_item_current_stock/get_store", {
        bc:$("#txt_branch").val(),
        search: $("#pop_search4").val()
    }, function (r) {
        $("#sr4").html(r);
        store_settings();
    }, "text");
}

function store_settings() {
    $("#item_list .cl").click(function () {
        $("#txt_store").val($(this).children().eq(0).html());
        $("#hid_store").val($(this).children().eq(1).html());
        $("#pop_close4").click();
    })
}

function empty_grid(){
    for (var i = 0; i <= 50; i++) {
        $("#0_" + i).val("");
        $("#n_" + i).val("");
        $("#2_" + i).val("");
        $("#3_" + i).val("");
        $("#4_" + i).val("");
        $("#5_" + i).val("");
        $("#6_" + i).val("");
        $("#7_" + i).val("");
        $("#8_" + i).val("");
    }
}

function load_itemss() {
    if($("#cost").is(":checked")){
        var cost=1;
    }else{
        var cost=0;
    }
    if($("#min_cost").is(":checked")){
        var min=1;
    }else{
        var min=0;
    }
    if($("#max_cost").is(":checked")){
        var max=1;
    }else{
        var max=0;
    }
    $.post("index.php/main/load_data/find_item_current_stock/get_stock", {
        cl:$("#txt_cluster").val(),
        bc:$("#txt_branch").val(),
     store:$("#txt_store").val(),
     search:$("#txt_search").val(),
     from_price:$("#from_price").val(),
     to_price:$("#to_price").val(),
     cost:cost,
     min_cost:min,
     max_cost:max

    },function(r) {
        empty_grid();
        if(r.det == "2"){
            set_msg("No records");
        }else{            
            
            settings5();
            for (var i = 0; i < r.det.length; i++) {
                var tot = parseInt(r.det[i].qty) * parseFloat(r.det[i].b_cost);
                $("#0_" + i).val(r.det[i].item);
                $("#n_" + i).val(r.det[i].description);
                $("#2_" + i).val(r.det[i].model);
                $("#3_" + i).val(r.det[i].batch_no);
                $("#4_" + i).val(r.det[i].b_cost);
                $("#5_" + i).val(r.det[i].b_min);
                $("#6_" + i).val(r.det[i].b_max);
                $("#7_" + i).val(r.det[i].qty);
                $("#8_" + i).val(m_round(tot));

            }
        }
    }, "json");
}

function clear_ser(){
    $("#from_price").val("");
    $("#to_price").val("");
    $("#cost").removeAttr("checked");
    $("#min_cost").removeAttr("checked");
    $("#max_cost").removeAttr("checked");
}

function settings5() {
    $(document).on('hover', '#grid .cl', function (){
        $(".cl").children().find('input').css('background-color', '#f9f9ec');
        $(this).children().find('input').css('background-color', '#D9E6FF');
    });    
}


function item_all(){
    loding();
    $.post("index.php/main/load_data/find_item_current_stock/item_list",{
        search:$("#txt_search").val()

    },function(r){
       loding();
        empty_grid();
        for(i=0; i<r.data.length;i++){
         $("#0_" + i).val(r.data[i].code);
         $("#n_" + i).val(r.data[i].description);
         $("#2_" + i).val(r.data[i].model);
         $("#3_" + i).val("0");

         $("#4_" + i).val(r.data[i].purchase_price);
         $("#5_" + i).val(r.data[i].min_price);
         $("#6_" + i).val(r.data[i].max_price);
         $("#7_" + i).val("0");
         $("#8_" + i).val("0.00");
     }

 },"json");
}



