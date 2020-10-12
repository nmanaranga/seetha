$(document).ready(function(){
   $("#pop_searchs").keyup(function(){
       load_items();
    });   
    $("#grid").tableScroll({height:200,width:880});

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
            $("#t_"+scid).html("&nbsp;"); 
        }
    });

        load_items();

        $("#pop_search").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { 
                load_items();
            }
        });
    
        $("#pop_search").gselect();


});


function set_cus_values(f,scid){
            var v = f.val();
            v = v.split("|");
             if(v.length == 5){
                f.val(v[0]);
                if(check_item_exist(f.val())){
                    $("#h_"+scid).val(v[0]);
                    $("#n_"+scid).val(v[1]);

                    $("#2_"+scid).val(v[2]);
                    $("#3_"+scid).val(v[3]);
                    $("#4_"+scid).val(v[4]);
                    $("#2_"+scid).focus();
            }else{
                 alert("Item "+f.val()+" is already added.");
            }
       }
    }

    function formatItems(row){
       return "<strong> " +row[0] + "</strong> | <strong> " +row[1];
    }

    function formatItemsResult(row){
       return row[0]+"|"+row[1]+"|"+row[2]+"|"+row[3]+"|"+row[4];
    }

    function select_search(){
        $("#pop_search").focus();
    }

    function load_items(){
        $.post("index.php/main/load_data/f_find_merchant/item_list_all", {
            search : $("#pop_searchs").val(),
            
        }, function(r){
            $("#item_ld").html(r);
            settings();
             settings5();
            
        }, "text");
    }

function load_itemss(){
    //empty_grid();
    //loding();
    $.post("index.php/main/load_data/f_find_merchant/item_list_alls", {
        search: $("#pop_searchs").val()
    }, function(r){

  if(r=="2"){
               alert("No records");

            }else{

 for(var i=0; i<r.det.length;i++)
 {
 	$("#0_"+i).val(r.det[i].code);
 	$("#n_"+i).val(r.det[i].description);
 	$("#2_"+i).val(r.det[i].model);
 	$("#3_"+i).val(r.det[i].min_price);
 	$("#4_"+i).val(r.det[i].max_price);
 }
}
// loding();
}, "json");
}


function settings(){
    $("#item_list .cl").click(function(){
        
        if($(this).children().eq(0).html() != "&nbsp;"){

            if(check_item_exist($(this).children().eq(0).html())){
                $("#h_"+scid).val($(this).children().eq(0).html());
                $("#0_"+scid).val($(this).children().eq(0).html());
                $("#n_"+scid).val($(this).children().eq(1).html());
                $("#2_"+scid).val($(this).children().eq(2).html());
                $("#3_"+scid).val($(this).children().eq(3).html());
                $("#4_"+scid).val($(this).children().eq(4).html());
                
                if($(this).children().eq(4).html() == 1){
                    $("#1_"+scid).autoNumeric({mDec:2});
                }else{
                    $("#1_"+scid).autoNumeric({mDec:2});
                }
                $("#1_"+scid).removeAttr("disabled"); 
                $("#2_"+scid).removeAttr("disabled");
                $("#3_"+scid).removeAttr("disabled");
                $("#1_"+scid).focus();$("#pop_close").click();
            }else{
                alert("Item "+$(this).children().eq(1).html()+" is already added.");
            }
        }else{
            $("#h_"+scid).val("");
            $("#0_"+scid).val("");
            $("#n_"+scid).val("");
            $("#1_"+scid).val(""); 
            $("#2_"+scid).val(""); 
            $("#3_"+scid).val(""); 
            $("#4_"+scid).val(""); 
            $("#t_"+scid).html("&nbsp;");
            $("#1_"+scid).attr("disabled", "disabled"); 
            $("#2_"+scid).attr("disabled", "disabled");
            $("#3_"+scid).attr("disabled", "disabled");
            $("#4_"+scid).attr("disabled", "disabled");
            
            $("#pop_close").click();
        }
    });
}


function settings5(){
    $("#grid .cl").click(function(){
        
        if($(this).children().eq(0).html() != "&nbsp;")
        {
            $("#itm2").val($(this).children().find('input').eq(0).val());
            $("#des2").val($(this).children().find('input').eq(1).val());
            $("#mPrice2").val($(this).children().find('input').eq(3).val());
            $("#mxPrice2").val($(this).children().find('input').eq(4).val());
            $("#btch2").val($(this).children().find('input').eq(6).val());
            $("#qnty2").val($(this).children().find('input').eq(5).val());
        }
        else
        {
            $("#itm2").val("");
            $("#des2").val("");
            $("#mPrice2").val("");
            $("#mxPrice2").val(""); 
            $("#btch2").val(""); 
            $("#qnty2").val("");            
            $("#pop_close").click();
        }
    });
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



function save(){
    var frm = $('#form_');
    loding();
    $.ajax({
	type: frm.attr('method'),
	url: frm.attr('action'),
	data: frm.serialize(),
	success: function (pid){
        
            if(pid == 1){
                location.reload();

            }else if(pid == 2){
                alert("No permission to add data.");
            }else if(pid == 3){
                alert("No permission to edit data.");
            }else{
                alert("Error : \n"+pid);
            }
            loding();
        }
    });
}

function validate(){
    var v = false;
    $("input[type='hidden']").each(function(){
        if($(this).val() != "" && $(this).val() != 0){
            v = true;
        }
    });
    
    if(v == false){
        alert("Please use minimum one item.");
    }else if($("#stores option:selected").val() == 0){
        alert("Please select stores");
        v = false;
    }
    
    return v;
}

    
function set_delete(code){
    if(confirm("Are you sure delete "+code+"?")){
        loding();
        $.post("index.php/main/delete/m_item_rol", {
            code : code
        }, function(res){
            if(res == 1){
                get_data_table();
            }else if(res == 2){
                alert("No permission to delete data.");
            }else{
                alert("Item deleting fail.");
            }
            loding();
        }, "text");
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
    
function set_edit(bc){
    //loding();
    $.post("index.php/main/get_data/m_item_rol", {
        bc : bc
    }, function(res){
        $("#code_").val(res.c[0].bc);
          for(var i=0; i<res.c.length; i++){
            $("#h_"+i).val(res.c[i].code);
            $("#n_"+i).val(res.c[i].description);
            $("#0_"+i).val(res.c[i].code);
            $("#2_"+i).val(res.c[i].model);
            $("#3_"+i).val(res.c[i].rol);
            $("#4_"+i).val(res.c[i].roq);

        }
    }, "json");
}

