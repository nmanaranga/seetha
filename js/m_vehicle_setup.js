$(document).ready(function () {
    $("#btnReset").click(function(){
        location.href="";
    });


    $("#type_search").keyup(function () {
        get_data_table();
    });

    $("#grid").tableScroll({height: 355});

    $("#code").blur(function(){
        check_code();
    });

    //cluster key press event - - - - - - - - - - - -  - - -  - - - 
     $("#cl").keydown(function(e){
        if(e.keyCode==112){
           load_data_cluster();
            $("#pop_search").select();       
            $("#serch_pop").center();
            $("#blocker").css("display", "block");
        }

        $("#pop_search").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40  && e.keyCode != 112) { 
               load_data_cluster();
            }
        });

        if(e.keyCode == 46){
            $("#cl").val("");
            $("#cluster_des").val("");
        }
    });
//- - - - - - - -  - - -  - - -  - - - -  - - -  - - -  - - - -  - - - 

 //branch key press event - - - - - - - - - - - -  - - -  - - - 
     $("#bc").keydown(function(e){
        if(e.keyCode==112){
           load_data_branch();
            $("#pop_search6").select();       
            $("#serch_pop6").center();
            $("#blocker").css("display", "block");
        }

        $("#pop_search6").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40  && e.keyCode != 112) { 
               load_data_branch();
            }
        });

        if(e.keyCode == 46){
            $("#bc").val("");
            $("#branch_name").val("");
        }
    });
//- - - - - - - -  - - -  - - -  - - - -  - - -  - - -  - - - -  - - - 

//driver key press event - - - - - - - - - - - -  - - -  - - - 
     $("#driver").keydown(function(e){
        if(e.keyCode==112){
           load_data_driver();
            $("#pop_search10").select();       
            $("#serch_pop10").center();
            $("#blocker").css("display", "block");
        }

        $("#pop_search10").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40  && e.keyCode != 112) { 
               load_data_driver();
            }
        });

        if(e.keyCode == 46){
            $("#driver").val("");
            $("#driver_name").val("");
        }
    });
//- - - - - - - -  - - -  - - -  - - - -  - - -  - - -  - - - -  - - - 

//driver key press event - - - - - - - - - - - -  - - -  - - - 
     $("#stores").keydown(function(e){
        if(e.keyCode==112){
           load_data_stores();
            $("#pop_search11").select();       
            $("#serch_pop11").center();
            $("#blocker").css("display", "block");
        }

        $("#pop_search11").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40  && e.keyCode != 112) { 
               load_data_stores();
            }
        });

        if(e.keyCode == 46){
            $("#stores").val("");
            $("#stores_name").val("");
        }
    });
//- - - - - - - -  - - -  - - -  - - - -  - - -  - - -  - - - -  - - - 

    
    $("#pop_search").select();

});


function load_data_cluster(){
    $.post("index.php/main/load_data/utility/f1_selection_list", {
        data_tbl:"m_cluster",
        field:"code",
        field2:"description",
        preview2:"Cluster Name",
        search : $("#pop_search").val() 
    }, function(r){
        $("#sr").html(r);
        settings8();            
    }, "text");
}

function settings8(){
    $("#item_list .cl").click(function(){        
        $("#cl").val($(this).children().eq(0).html());
        $("#cluster_name").val($(this).children().eq(1).html());
        $("#pop_close").click();                
    })    
}

function load_data_branch(){
    if($("#cl").val()==""){
        var q="";
    }else{
        var q='AND cl="'+$("#cl").val()+'"';
    }
    $.post("index.php/main/load_data/utility/f1_selection_list", {
        data_tbl:"m_branch",
        field:"bc",
        field2:"name",
        preview2:"Cluster Name",
        search : $("#pop_search6").val(),
        add_query:q  
    }, function(r){
        $("#sr6").html(r);
        settings9();            
    }, "text");
}

function settings9(){
    $("#item_list .cl").click(function(){        
        $("#bc").val($(this).children().eq(0).html());
        $("#branch_name").val($(this).children().eq(1).html());
        $("#pop_close6").click();                
    })    
}

function load_data_driver(){
    $.post("index.php/main/load_data/m_vehicle_setup/driver_category", {
        
        search : $("#pop_search10").val(),
        //add_query:"category='DRV0001'"
    }, function(r){
        $("#sr10").html(r);
        settings10();            
    }, "text");
}

function settings10(){
    $("#item_list .cl").click(function(){        
        $("#driver").val($(this).children().eq(0).html());
        $("#driver_name").val($(this).children().eq(1).html());
        $("#pop_close10").click();                
    })    
}

//load_stores 
function load_data_stores(){
    if($("#cl").val()=="" && $("#bc").val()==""){
        var q="";
    }else{
        var q='AND cl="'+$("#cl").val()+'" AND bc="'+$("#bc").val()+'"' ;
    }
    $.post("index.php/main/load_data/utility/f1_selection_list", {
        data_tbl:"m_stores",
        field:"code",
        field2:"description",
        preview2:"Stores Name",
        search : $("#pop_search11").val(),
        add_query:q  
    }, function(r){
        $("#sr11").html(r);
        settings11();            
    }, "text");
}

function settings11(){
    $("#item_list .cl").click(function(){        
        $("#stores").val($(this).children().eq(0).html());
        $("#stores_name").val($(this).children().eq(1).html());
        $("#pop_close11").click();                
    })    
}
//- - --  - - - - --------------------------------------------------

function save() {
    var frm = $('#form_');
    loding();
    $.ajax({
        type: frm.attr('method'),
        url: frm.attr('action'),
        data: frm.serialize(),
        success: function (pid) {
            if (pid == 1) {
               loding();
               sucess_msg();
            } else if (pid == 2) {
                set_msg("No permission to add data.");
            } else if (pid == 3) {
                set_msg("No permission to edit data.");
            } else {
                set_msg(pid);
            }
            
        }
    });
}


function get_data_table() {
    $.post("index.php/main/load_data/m_vehicle_setup/get_data_table", {
           
    }, function(r){
        $("#grid_body").html(r);
    }, "text");
}


function check_code() {
    loding();
    var code = $("#code").val();
    $.post("index.php/main/load_data/m_vehicle_setup/check_code", {
        code: code
    }, function (res) {
        if (res == 1) {
            if (confirm("This code (" + code + ") already added. \n\n Do you need edit it?")) {
                set_edit(code);
            } else {
                $("#code").val('');
                $("#code").attr("readonly", false);
            }
        }
        loding();
    }, "text");
}

function validate() {
    if ($("#code").val() == "") {
        set_msg("Please enter code.");
        $("#code").focus();
        return false;
    } else if ($("#description").val() === $("#description").attr('title') || $("#description").val() == "") {
        set_msg("Please enter description.");
        $("#description").focus();
        return false;
    }else{
        return true;
    }  

   
}

function set_delete(code) {

    if (confirm("Are you sure delete " + code + "?")) {
        loding();
        $.post("index.php/main/delete/m_vehicle_setup", {
            code: code
        }, function (res) {
            if (res == 1) {
                loding();
                delete_msg();
            } else {
                set_msg("Record deleting fail.");
            }
        }, "text");
    }

}

function set_edit(code){
    loding();
    $.post("index.php/main/get_data/m_vehicle_setup", {
        code : code
    }, function(res){
        $("#cl").val(res[0].cl);
        $("#cluster_name").val(res[0].cl_name);
        $("#bc").val(res[0].bc);
        $("#branch_name").val(res[0].bc_name);
        $("#description").val(res[0].description);
        $("#driver").val(res[0].driver);
        $("#driver_name").val(res[0].driver_name);
        $("#stores").val(res[0].stores);
        $("#stores_name").val(res[0].stores_name);
        $("#code_").val(res[0].code);
        $("#code").val(res[0].code);        
        if(res.inactive==1){
            $("#inactive").attr("checked", "checked");
            }else{
            $("#inactive").removeAttr("checked");
        }

        loding(); input_active();
    }, "json");
}