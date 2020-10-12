$(document).ready(function () {

    $("#grid").tableScroll({
        height: 355,
        width: 1080
    });  
    load_itemss();

    $("#btnPrint").click(function() {
        $("#print_pdf").submit();
    });


    $("#btnLoad_data").click(function(){
        load_itemss();
    }); 

    $("#promo_type").change(function(){
        load_itemss();
    });


    $("#txt_search").keyup(function(){
        load_itemss();
    });
    


    $("#txt_cluster").keypress(function (e) {
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

    $("#txt_branch").keypress(function (e) {
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

    

});

function load_cluster() {
    $.post("index.php/main/load_data/find_item_promotions/get_cluster_name", {
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

    $.post("index.php/main/load_data/find_item_promotions/get_branch_name", {
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


function empty_grid(){
    for (var i = 0; i <= 50; i++) {
        $("#0_" + i).val("");
        $("#n_" + i).val("");
        $("#2_" + i).val("");
        $("#3_" + i).val("");
        $("#4_" + i).val("");
        $("#dt_" + i).val("");
        $("#df_" + i).val("");
    }
}

function load_itemss() {

    $.post("index.php/main/load_data/find_item_promotions/get_stock", {
        cl:$("#txt_cluster").val(),
        bc:$("#txt_branch").val(),
        search:$("#txt_search").val(),
        add_search:$("#add_search").val(),
        fdate:$("#fdate").val(),
        promo_type:$("#promo_type").val(),
    },function(r) {
        empty_grid();
        if(r.det == "2"){
            set_msg("No records");
        }else{            

            settings5();
            for (var i = 0; i < r.det.length; i++) {
                var tot = parseInt(r.det[i].qty) * parseFloat(r.det[i].b_cost);
                $("#0_" + i).val(r.det[i].code);
                $("#n_" + i).val(r.det[i].description);
                $("#2_" + i).val(r.det[i].model);
                $("#dt_" + i).val(r.det[i].date_from);
                $("#df_" + i).val(r.det[i].date_to);

                if(r.det[i].promo_type=='1'){
                    $promo="FOC";
                }else if(r.det[i].promo_type=='2'){
                    $promo="Back to Back";
                }else if(r.det[i].promo_type=='3'){
                    $promo="Interest Free";
                }else if(r.det[i].promo_type=='4'){
                    $promo="Credit Card";
                }

                $("#3_" + i).val($promo);
                $("#4_" + i).val(r.det[i].note);


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
    $.post("index.php/main/load_data/find_item_promotions/item_list",{
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



