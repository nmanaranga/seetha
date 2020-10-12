$(document).ready(function(){

	$("#btnReset").click(function(){
		location.href="index.php?action=s_role";
	});

 $("#tgrid").tableScroll({height:280});
$("#all_view").change(function(){
  
  var c = $(this).attr("checked");
        $("#tgrid").find("tr").each(function(){
            $(this).find(":checkbox").each(function(i){
                if(i == 0){
                    if(c){
                        $(this).attr("checked", "checked");
                    }else{
                        $(this).removeAttr("checked");
                    }
                }
            });
        });
 });


$("#btnReset").click(function()
{
    
   $(".ob_a").removeAttr("checked");
   $(".ob_b").removeAttr("checked");
   $(".ob_c").removeAttr("checked");
   $(".ob_d").removeAttr("checked");
   $(".ob_e").removeAttr("checked");
   $(".ob_f").removeAttr("checked");
   $(".ob_g").removeAttr("checked");
   $(".ob_h").removeAttr("checked");
    
    
    
});


 $("#all_add").change(function(){
    var c = $(this).attr("checked");
    $("#tgrid").find("tr").each(function(){
        $(this).find(":checkbox").each(function(i){
            if(i == 1){
                if(c){
                    $(this).attr("checked", "checked");
                }else{
                    $(this).removeAttr("checked");
                }
            }
        });
    });
});

$("#all_edit").change(function(){
    var c = $(this).attr("checked");
    $("#tgrid").find("tr").each(function(){
        $(this).find(":checkbox").each(function(i){
            if(i == 2){
                if(c){
                    $(this).attr("checked", "checked");
                }else{
                    $(this).removeAttr("checked");
                }
            }
        });
    });
});

$("#all_delete").change(function(){
    var c = $(this).attr("checked");
    $("#tgrid").find("tr").each(function(){
        $(this).find(":checkbox").each(function(i){
            if(i == 3){
                if(c){
                    $(this).attr("checked", "checked");
                }else{
                    $(this).removeAttr("checked");
                }
            }
        });
    });
});

$("#all_print").change(function(){
    var c = $(this).attr("checked");
    $("#tgrid").find("tr").each(function(){
        $(this).find(":checkbox").each(function(i){
            if(i == 4){
                if(c){
                    $(this).attr("checked", "checked");
                }else{
                    $(this).removeAttr("checked");
                }
            }
        });
    });
});

$("#all_r_print").change(function(){
    var c = $(this).attr("checked");
    $("#tgrid").find("tr").each(function(){
        $(this).find(":checkbox").each(function(i){
            if(i == 5){
                if(c){
                    $(this).attr("checked", "checked");
                }else{
                    $(this).removeAttr("checked");
                }
            }
        });
    });
});

$("#all_back_date").change(function(){
    var c = $(this).attr("checked");
    $("#tgrid").find("tr").each(function(){
        $(this).find(":checkbox").each(function(i){
            if(i == 6){
                if(c){
                    $(this).attr("checked", "checked");
                }else{
                    $(this).removeAttr("checked");
                }
            }
        });
    });
});

    $("#code").autocomplete('index.php/main/load_data/s_role/auto_com', {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatItems,
        formatResult: formatItemsResult
    });

    $("#code").blur(function(){
	set_cus_values($(this));
    });
    
    $("#code").keydown(function(e){
	if(e.keyCode == 13){
	    set_cus_values($(this));
	}
    });

});//end of document .ready

function set_cus_values(f){
    set_cid(f.attr("id"));
    var v = f.val();
    v = v.split("~");
    
   // alert(v.length);
    
    
 if(v.length == 4){
	$("#code_").val(v[0]);
	f.val(v[1]);
	$("#des").val(v[2]);
	$("#bc").val(v[3]);
        load_data(v[0]);
        
 }
//    }else{
  
//		$.post("index.php/main/load_data/s_role/check_role", {
//			code : f.val()
//		}, function(r){
//			if(r > 0){
//				alert("Code alredy added.");
//				f.val('');
//				f.focus();
//			}
//		}, "json");
//		$("#code_").val(0);
//	}
    input_active(v[0]);
}

function formatItems(row){
    return "<strong> " +row[1] + "</strong> | <strong> " +row[2] + "</strong>";
}

function formatItemsResult(row){
    return row[0]+"~"+row[1]+"~"+row[2]+"~"+row[3];
}

function load_data(id){
    empty_grid();
    $.post("index.php/main/get_data/s_role/", {
        id : id
    }, function(r){
        $("#tgrid tr").each(function(){
            for(var i=0; i<r.length; i++){
                if($(this).children().eq(0).children().val() == r[i].module_id){
                    if(r[i].is_view == 1){ $(this).children().eq(2).children().attr("checked", "checked"); }
                    if(r[i].is_add == 1){ $(this).children().eq(3).children().attr("checked", "checked"); }
                    if(r[i].is_edit == 1){ $(this).children().eq(4).children().attr("checked", "checked"); }
                    if(r[i].is_delete == 1){ $(this).children().eq(5).children().attr("checked", "checked"); }
                    if(r[i].is_print == 1){ $(this).children().eq(6).children().attr("checked", "checked"); }
                    if(r[i].is_re_print == 1){ $(this).children().eq(7).children().attr("checked", "checked"); }
                    if(r[i].is_back_date == 1){ $(this).children().eq(8).children().attr("checked", "checked"); }
                }
            }
        });
    
        $("#code_").val(id);
    
    }, "json");
}

function empty_grid(){
    $(":checkbox").removeAttr('checked');
}

function validate(){
    if($("#code").val() == $("#code").attr("title")){
        alert("Please enter code");
        $("#code").focus();
        return false;
    }else if($("#des").val() == $("#des").attr("title")){
         alert("Please enter description");
        $("#des").focus();
        return false;
    }else if($("#bc").val() == "0"){
         alert("Please select branch");
        return false;
    }else{
        return true;
    }
}

function save(){
    $("#_form").submit();
}