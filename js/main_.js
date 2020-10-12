var ove = true; var d = false; var to; var fcid, scid; var banks;
$(document).ready(function(){
    $(".menuitem").mouseover(function(){
        set_menu($(this).attr("id"));
    });
    
    $(".submenu").mouseleave(function(e){
	unset_menu();
    });
    
    set_t_body();
    
    $("#customer_search").center();
    
    $("#cus_search").keyup(function(){
	load_customer_tb($(this).val());
    });
    
    $("#pop_close").click(function(){
        $("#serch_pop").css("display", "none");
        $("#blocker").css("display", "none");
    });
    
    $("#btnSave").click(function(){
        if(validate()){
            save();
        }
    });
    
    $("#btnReset").click(function(){
        reset();
    });
    
    setup_payment_form();
    //setup_payment_form2();
    
    $("#btnClosePay").click(function(){
	$("#payment_methods").css("display", "none");
	$("#blocker").css("display", "none");
    });
    
    $("#btnPayments").click(function(){
	$("#payment_methods").center();
	$("#blocker").css("display", "block");
    });
    

    $(".bank").autocomplete('index.php/main/load_data/m_banks/auto_com', {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatItem,
        formatResult: formatResult
    });
    
    $(".branch").autocomplete('index.php/main/load_data/m_bank_branch/auto_com', {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatItem,
        formatResult: formatResult
    });
    
    $(".branch").blur(function(){
	set_values($(this));
    });
    
    $(".branch").keydown(function(e){
	
	if(e.keyCode == 13){
	    //alert('ghfgh');
	    set_values($(this));
	}
    });
    
    $("#btnExit").click(function(){
	window.open('index.php', '__self');
    });
    
    get_bank();
    
});



//function get_pay_total(){
//    var t = ttt = 0;
//    $(".tf").each(function(){
//	t = parseFloat($(this).val());
//	if(! isNaN(t)){
//	    ttt += t;
//	}
//    });
//    
//    return ttt;
//}

function get_bank(){
    $.post('index.php/main/load_data/m_banks/auto_com', {
	}, function(r){
	banks = r;
    }, "json");
}


function set_bank(id, v){
     
    $("#qbh_"+id).val(v);
    $("#q0_"+id).val(banks[v][1]);
}

function sale_analyze(){
    window.open('index.php/plot/', '_blank');
}

function set_values(f){
    //alert(f);
    var v = f.val();
    v = v.split("~");
    if(v.length == 3){
	f.val(v[1]);
	var v2 = f.attr("id");
	v2 = v2.split("_");
	
	$("#qbbh_"+v2[1]).val(v[0]);
	set_bank(v2[1], v[2]);
	
	$("#q1_"+v2[1]).focus();
    }
}

function formatItem(row){
    return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong> | <strong> " +row[2] + "</strong>";
}

function formatResult(row){
    return row[0]+"~"+row[1]+"~"+row[2];
}

function setup_payment_form(){
    var form = $("#pay_form").html();
    $("#payment_methods").html(form);
    $("#pay_form").html("");
}


$(document).keydown(function(e){
    //alert(e.keyCode);
    if(e.keyCode == 27){
        $("#pop_close").click();
    }else if(e.keyCode == 113){
        $("#btnSave").click();
    }
});

function reset(){
    location.reload();
}

function load_customer_tb(v){
    var root = $("#root option:selected").val();
    $.post("index.php/content/customer_table", {
        cus_search : v,
	search : false,
	root : root
    }, function(res){
        $("#cus_grid_body").html(res["grid_body"]);
        $("#grid").tableScroll({height:350});
	
	$("#cus_grid_body tr").click(function(){
	    $(this).find("td").each(function(i){
		if(i==0){
		    $("#customer").val($(this).html());
		    $("#customer").attr("class", "input_txt_f");
		}else if(i==2){
		    $("#customer_des").val($(this).html());
		    $("#customer_des").attr("class", "input_txt_f");
		}
	    });
	    close_cus_search();
	});
    }, "json");
}

function cus_search(){
    var root = $("#root option:selected").val();
    if(root){
	if(root == 0){
	    alert("Please select root");
	}else{
	    $("#customer_search").fadeIn(1000);
	    $("#cus_search").focus();
	}
    }else{
	$("#customer_search").fadeIn(1000);
	$("#cus_search").focus();
    }
}

function close_cus_search(){
    $("#customer_search").fadeOut(1000);
}

function error_maz(){
    if(d == true){
	alert("Operation fail please contact admin.");
	loding();
	d=false;
    }
}

function set_t_body(){
    var x = $(window).width()-50;
    $("#t_body").css("width", x);
    $("#t_body").css("top", 130);
    $("#t_body").css("left", 25);
}

$(document).keydown(function(e){
    if(e.keyCode == 27){
        unset_menu();
    }
});

function set_menu(id){
    unset_menu();
    var pos = $("#"+id).offset();
    $("#"+id).css("background-color", "#FFF");
    $("#"+id+"_m").css("top", pos.top+15);
    $("#"+id+"_m").css("left", pos.left);
    $("#"+id+"_m").fadeIn(500);
}

function unset_menu(){
    $(".submenu").fadeOut(500);
    $(".menuitem").css("background-color", "#CCC");
}

function set_short(field){
    var temp;
    if(field==1) temp = "code"; else temp = "description";
    $.post("index.php/save/set_short_table", {
        field : temp
    }, function(res){
        if(res == "" || res == null){
            loard_grid(true);
        }
    }, "text");
}

function m_round(val){
    return Math.round(val*100)/100;
}

function cleanArray(actual){
    var newArray = new Array();
    for(var i = 0; i<actual.length; i++){
	if (actual[i]){
	    newArray.push(actual[i]);
	}
    }
    return newArray;
}

function set_select_dro_down(code, field){
    var list = $('#'+field+' option').map(function(i,n) {
        return $(n).attr('id');
    }).get();
    
    for(var i=0; i<list.length; i++){
        var temp_text = $("#"+field+" [id='"+list[i]+"']").text();
        if(temp_text == code){
            $("#"+field+" [id='"+list[i]+"']").attr("selected","selected");
        }
    }
}

function loding(){
    if($("#blanket").css("display")=="none"){
	to = setTimeout("error_maz()", 10000);
	d = true;
	$("#blanket").css("display", "block");
    }else{
	clearTimeout(to);
	d = false;
	$("#blanket").css("display", "none");
    }
}

function set_select(select_id, input_id){
    if($("#"+select_id+" option:selected").val() != '0'){
        $("#"+input_id).val($("#"+select_id+" option:selected").attr("title"));
        $("#"+input_id).attr("class", "input_txt_f");
    }else{
        $("#"+input_id).val($("#"+input_id).attr("title"));
        $("#"+input_id).attr("class", "input_txt");
    }
}

$.fn.center = function(){
    var h = this.height();
    var w = this.width();
    
    h = (h - $(window).height())/2;
    w = (w - $(window).width())/2;
    
    if(h<0){ h = h*-1; }
    if(w<0){ w = w*-1; }
    
    this.css("top", h);
    this.css("left", w);
    
    this.css("display", "block");
}


function set_cid(id){
    id = id.split('_');
    
    fcid = id[0];
    scid = id[1];
}

$.fn.gselect = function(){
    var index = -1; 
    this.keydown(function(e){
        if(e.keyCode == 38){
            $("#item_list tr").eq(index).css("background-color", "transparent");
            index--;
            $("#item_list tr").eq(index).css("background-color", "#e0e492");
        }else if(e.keyCode == 40){
            $("#item_list tr").eq(index).css("background-color", "transparent");
            index++;
            $("#item_list tr").eq(index).css("background-color", "#e0e492");
        }else if(e.keyCode == 13){
            $("#item_list tr").eq(index).click();
        }else{
            index = -1;
        }
    });
}