var ove = true; var d = false; var to; var fcid, scid; var banks; var tab_id = 0;
var Y = false;
// var message="";

// function clickIE() {if (document.all) {(message);return false;}}
// function clickNS(e) {if 
// (document.layers||(document.getElementById&&!document.all)) {
// if (e.which==2||e.which==3) {(message);return false;}}}
// if (document.layers) 
// {document.captureEvents(Event.MOUSEDOWN);document.onmousedown=clickNS;}
// else{document.onmouseup=clickNS;document.oncontextmenu=clickIE;}

// document.oncontextmenu=new Function("return false");



$(document).ready(function(){
    // $(document).bind("contextmenu",function(e) {
    //  e.preventDefault();
    // });
    
    blink(900000, 1000);

    $("#slide_arrow").click(function(){
        $("#det_box").slideToggle(); 
    }); 

    $(".fo").attr("readonly",true);

    $("#st_rprt").click(function(){
        window.open("?action=find_item_current_stock");
    }); 

    $("#itmPromo").click(function(){
        window.open("?action=find_item_promotions");
    }); 


    /*$(".input_txt").keyup(function(){
        $(this).val(toPascalCase($(this).val()));
    });*/

    $(".input_txts").keyup(function(){
        var str = $(this).val();
        $(this).val(str.toUpperCase()); 
    });

    $("#pending_chq").click(function(){
        $("#det_box2").slideToggle(); 
    }); 

    $("#holdBill").click(function(){
        $("#det_box3").slideToggle(); 
    }); 

    $("#open_det_box").click(function(){
        loding();
        $.post("index.php/main/load_data/utility/pending_list", {
        },function(r){
            loding();
            $("#det_box").html(r);
        }, "text");
    });

    $.post("index.php/main/load_data/utility/post_dated_chq_list", {
    },function(r){
     $("#det_box2").html(r);
 }, "text");

    $.post("index.php/main/load_data/utility/hold_bill_list", {
    },function(r){
     $("#det_box3").html(r);
 }, "text");

    $(document).on('click', '.pd_chq_list .cll', function(){
        var cus = $(this).children().eq(4).html();
        var cname = $(this).children().eq(5).html();
        var amount = $(this).children().eq(3).html();
        
        var bank = $(this).children().eq(6).html();
        var branch = $(this).children().eq(7).html();
        var realize_date = $(this).children().eq(8).html();
        var account = $(this).children().eq(1).html();
        var chq_no = $(this).children().eq(2).html();
        var curr_acc = $(this).children().eq(9).html();
        if(curr_acc==1){
            window.open("?action=t_internal_trans_receipt&xxx="+cus+"="+cname+"="+amount+"="+bank+"="+branch+"="+account+"="+chq_no+"="+realize_date,"_blank");   
        }else{
            window.open("?action=t_receipt&xxx="+cus+"="+cname+"="+amount+"="+bank+"="+branch+"="+account+"="+chq_no+"="+realize_date,"_blank");   
        }        
    });

    $(document).on('click', '.hd_bill_list .cll', function(){
        var nno = $(this).children().eq(0).html();
        
        window.open("?action=t_cash_sales_sum&xxx="+'4'+"="+nno,"_blank");   

    });

    $(document).keydown(function(e){

        /*if(e.keyCode ==119){
            if(!$("#btnSave,#btnSave1,#btnSave2").is(':disabled')){
                $("#btnSave,#btnSave1,#btnSave2").click();
            }
        }*/
        if(e.keyCode ==123){
            $("#btnReset,#btnResett").click();
        }
        if(e.keyCode ==113){
            $("#print_pdff").submit();
        }
        if(e.keyCode ==27){
            $("#pop_close").click();
            $("#pop_close6").click();
            $("#pop_close2").click();
            $("#pop_close3").click();
            $("#pop_close4").click();
            $("#pop_close7").click();
            $("#pop_close10").click();
            $("#pop_close11").click();
            $("#pop_close12").click();
            $("#pop_close13").click();
            $("#pop_close14").click();
            $("#pop_close15").click();
            $("#pop_close_find").click();
        }
    });
    
    var index =1;
    $(document).keydown(function(e){          

             //alert(e.keyCode);
             if(e.keyCode == 38){
                $("#item_list tr, #po_list tr").eq(index).css("background-color", "transparent");
                index--;
                $("#item_list tr, #po_list tr").eq(index).css("background-color", "#e0e492");
            }else if(e.keyCode == 40){
                $("#item_list tr, #po_list tr").eq(index).css("background-color", "transparent");
                index++;
                $("#item_list tr, #po_list tr").eq(index).css("background-color", "#e0e492");
            }else if(e.keyCode == 13){
               // $("#item_list tr, #po_list tr").eq(index).click();
           }else{
            index = 1;
        }  
    });


    $("#approve4").css('display','none');
    $("#light").css("display","none");
    $("#fade").css("display","none");
    
    $(document).on('click', '#hed1', function(){
        $("#sub1").slideToggle();
    });

    $(document).on('click', '#hed2', function(){
        $("#sub2").slideToggle();
    });
    
    $(".menuitem").mouseover(function(){
        set_menu($(this).attr("id"));
    });
    
    $(".submenu").mouseleave(function(e){
       unset_menu();
   });

    $("#close_msg_box").click(function(){
        $("#msg_box").slideUp();
    });

    $("#close_det_box").click(function(){
        $("#det_box").slideUp();
    });
    

    $("#pop_close").click(function(){
        $("#serch_pop").css("display", "none");
        $("#blocker").css("display", "none");
    });

    $("#pop_close10").click(function(){
        $("#serch_pop10").css("display", "none");
        $("#blocker").css("display", "none");
        $("#blocker4").css("display", "none");
    });

    $("#pop_close11").click(function(){
        $("#serch_pop11").css("display", "none");
        $("#blocker").css("display", "none");
    });

    $("#pop_close12").click(function(){
        $("#serch_pop12").css("display", "none");
        $("#blocker").css("display", "none");
    });

    $("#pop_close13").click(function(){
        $("#serch_pop13").css("display", "none");
        $("#blocker").css("display", "none");
    });

    $("#pop_close14").click(function(){
        $("#serch_pop14").css("display", "none");
        $("#blocker").css("display", "none");
    });

    $("#pop_close15").click(function(){
        $("#serch_pop15").css("display", "none");
        $("#blocker").css("display", "none");
    });

    $("#pop_close_find").click(function(){
        $("#serch_pop_find").css("display", "none");
        $("#blocker").css("display", "none");
    });

    $("#pop_close6").click(function(){
        $("#serch_pop6").css("display", "none");
        $("#blocker").css("display", "none");
    });

    $("#pop_close7").click(function(){
        $("#serch_pop7").css("display", "none");
        $("#blocker2").css("display", "none");
    });

    $("#pop_close2").click(function(){
        $("#serch_pop2").css("display", "none");
        $("#blocker").css("display", "none");
        $("#blocker2").css("display", "none");
    });

    $("#pop_close3").click(function(){
        $("#serch_pop3").css("display", "none");
        $("#blocker3").css("display", "none");
    });
    
    $("#pop_close4").click(function(){
        $("#serch_pop4").css("display", "none");
        $("#blocker4").css("display", "none");
        $("#blocker").css("display", "none");
        $("#pop_search4").val("");
    });
    
    var running = false;
    var cc_count= 0;

    $("#btnSave").click(function(){
        if(validate()){
            if(running== false){
                save();
                cc_count++;
                running=true;
            }else if(running== true && parseFloat(cc_count)>0){
                if(confirm("Are You Sure To Save More Than One Times?")){
                    save();
                    cc_count++;
                    running=true;
                }
            }
        }
    });
    
    
    $("#btnReset").click(function(){
        reset();
    });
    
    setup_payment_form();
    
    $("#btnClosePay").click(function(){
       $("#payment_methods").css("display", "none");
       $("#blocker").css("display", "none");
   });
    
    $("#btnPayments").click(function(){
       $("#payment_methods").center();
       $("#blocker").css("display", "block");
   });
    
    
    $("#btnExit").click(function(){
       window.open('index.php', '_self');
   });
    
    $("#btnGenerate").click(function(){
       make_ser();
   });
    
    $("#btnClear").click(function(){
       clear_ser();
   });

    
    $("#btnAdd").click(function(){
       $("#select_all_ser").removeAttr("checked");
       add_serials();
   });
    
    $("#select_all_gen").change(function(){
       if($(this).attr("checked") == "checked"){
           $(".ser").attr("checked", "checked");
       }else{
           $(".ser").removeAttr("checked");
       }
   });
    
    $("#select_all_ser").change(function(){
       set_select_ser($(this).attr("checked"));
   });
    
    $("#scustomers").keydown(function(e){
       if(e.keyCode == 13){
           set_cus_values($(this));
       }
   });
    
    $("#btnOK").click(function(){
    	if(parseInt($("#sel_qty").val(), 10) < parseInt($("#ser_quantity").val(), 10)){
            alert("Please select "+$("#ser_quantity").val()+" serial number(s).");
        }else{
            $("#serials").css("display", "none");
            $("#blocker").css("display", "none");
            $("#serch_pop").css("display", "none");
        }
    });
});



function setup_payment_form(){
    var form = $("#pay_form").html();
    $("#payment_methods").html(form);
    $("#pay_form").html("");
}

$(document).keydown(function(e){
    //alert(e.keyCode);
    if(e.keyCode == 27){
      $("#pop_close").click();
      $("#pop_close2").click();
      $("#pop_close3").click();
      $("#pop_close4").click();
  }else if(e.keyCode == 113){
       // $("#btnSave").click();
   }
});

function load_data_form_main(id){
    var code = id.split("-");
    var t_code = code[1];
    var nno = code[0];
    var typ = code[2];

    if(t_code==31){
        location.href="?action=t_req_sum&xxx="+nno+" ";
    }else if(t_code==32){
        location.href="?action=t_req_approve_sum&xxx="+nno+"";
    }else if(t_code==8){
        if(typ==2){
            location.href="?action=t_sales_return_sum_without_invoice&xxx="+nno+"";
        }else{
            location.href="?action=t_sales_return_sum&xxx="+nno+"";
        }
    }else if(t_code==4){
        location.href="?action=t_cash_sales_sum&xxx="+'0'+"="+nno+"";
    }else if(t_code==10){
        location.href="?action=t_pur_ret_sum&xxx="+nno+"";
    }else if(t_code==17){
        window.open("?action=t_credit_note&xxx="+nno+"");
    }else if(t_code==18){
        window.open("?action=t_debit_note&xxx="+nno+"");
    }else if(t_code==63){
        window.open("?action=g_t_sales_sum&xxx="+nno+"");
    }else if(t_code==42){
        window.open("?action=t_internal_transfer&xxx="+nno+'~'+typ+"");
    }

    
}


function reset(){
    location.href='';
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

function m_round(val){
    var number = Math.round(val * Math.pow(10, 2)) / Math.pow(10, 2);
    return number.toFixed(2);
}


function loding(){
    if($("#blanket").css("display")=="none"){
       to = setTimeout("error_maz()", 100000);
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
      //  $("#"+input_id).attr("class", "input_txt_f");
  }else{
    $("#"+input_id).val($("#"+input_id).attr("title"));
       // $("#"+input_id).attr("class", "input_txt");
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
    var index = 1; 
    this.keydown(function(e){
        if(e.keyCode == 38){
            $("#item_list tr, #po_list tr").eq(index).css("background-color", "transparent");
            index--;
            $("#item_list tr, #po_list tr").eq(index).css("background-color", "#e0e492");
        }else if(e.keyCode == 40){
            $("#item_list tr, #po_list tr").eq(index).css("background-color", "transparent");
            index++;
            $("#item_list tr, #po_list tr").eq(index).css("background-color", "#e0e492");
        }else if(e.keyCode == 13){
            $("#item_list tr, #po_list tr").eq(index).click();
        }else{
            index = 1;
        }
    });
}

function get_column_total(cn,id){
    var total=0;
    $(cn).each(function(e){    
        if($(id+e).val()!="")
        {
            total=total+parseFloat($(id+e).val());
        }
    });
    return total;
}


function sucess_msg(){
    alert("Save Completed");
    location.href="";
}

function delete_msg(){
    alert("Record Deleted Successfully");
    location.href="";
}

function set_msg(msg,type){
    if(type=='undefined'){type='error';}
    if(type=="error"){
        $("#display_msg").css("background-color","red")
    }else if(type=="notify"){
        $("#display_msg").css("background-color","green")
    }
    $("#display_inner_msg").html(msg.toUpperCase());
    $("#display_msg").fadeIn(500);
    setTimeout("close_display_msg()",1000);
}

function close_display_msg(){
    $("#display_msg").fadeOut(4000);
}

function blink(time, interval){
    var timer = window.setInterval(function(){
        $("#pending_chq").css("opacity", "0.1");
        window.setTimeout(function(){
            $("#pending_chq").css("opacity", "1");
        }, 200);
    }, interval);
    window.setTimeout(function(){clearInterval(timer);}, time);

     /*var timer = window.setInterval(function(){
        $("#pending_chq").fadeOut();
        window.setTimeout(function(){
            $("#pending_chq").fadeIn();
        }, 200);
    }, interval);
    window.setTimeout(function(){clearInterval(timer);}, time);*/
}

function toPascalCase(str) {
    var arr = str.split(/\s|_/);
    for(var i=0,l=arr.length; i<l; i++) {
        arr[i] = arr[i].substr(0,1).toUpperCase() + 
        (arr[i].length > 1 ? arr[i].substr(1).toLowerCase() : "");
    }
    return arr.join(" ");
}

function make_delay(func){
    clearTimeout(Y);
    Y = setTimeout(func,1000);
}