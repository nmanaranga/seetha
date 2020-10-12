$(document).ready(function(){
    input_reset();
    
    $(".g_col_fixed").attr("readonly","readonly");
    $(".hid_value").attr("readonly","readonly");

    $(".g_input_num").autoNumeric({mDec:0});
    $(".g_input_amo").autoNumeric({mDec:2});

    $("#code").keyup(function(){
        this.value = this.value.replace(/[^0-9\.-_a-z,',A-Z]/g,'');
    });


    
    $(".input_pass").focus(function(){
        if($(this).val() === $(this).attr("title")){
            $(this).val("");
            $(this).attr("class", "input_pass_f");
            document.getElementById($(this).attr("id")).type = "password";
        }
        
        $(this).blur(function(){
            if($(this).val() == ""){
                $(this).val($(this).attr("title"));
                $(this).attr("class", "input_pass");
                document.getElementById($(this).attr("id")).type = "text";
            }
        });
    });
    
    
    $(".input_amount").focus(function(){
        if($(this).val() == $(this).attr("title")){
            $(this).val("");
            $(this).attr("class", "input_amount_f");
        }
        $(".input_amount_f").autoNumeric({mDec:2});
        $(".input_amount_f").blur(function(){
            if($(this).val() == ""){
                $(this).val($(this).attr("title"));
                $(this).attr("class", "input_amount");
            }
        });
    });
    
    $(".input_number").focus(function(){
        if($(this).val() == $(this).attr("title")){
            $(this).val("");
            $(this).attr("class", "input_number_f");
        }
        $(".input_number_f").autoNumeric({mDec:0});
        $(".input_number_f").blur(function(){
            if($(this).val() == ""){
                $(this).val($(this).attr("title"));
                $(this).attr("class", "input_number");
            }
        });
    });
    
    
    $(".input_date_up_old").datepicker({
        showButtonPanel: false,
        changeMonth: true,
        changeYear: true,
        yearRange:'1940:-10',
        dateFormat: 'yy-mm-dd',
        beforeShow: function (input, inst) {
            var offset = $(input).offset();
            var height = $(input).height();
            window.setTimeout(function () {
                inst.dpDiv.css({ top: (offset.top - inst.dpDiv.height() - 4) + 'px', left: offset.left + 'px' })
            }, 1);
        }
    });
    
    $(".input_date_down_old").datepicker({
        showButtonPanel: false,
        changeMonth: true,
        changeYear: true,
        yearRange:'1940:-10',
        dateFormat: 'yy-mm-dd',
        beforeShow: function (input, inst) {
            var offset = $(input).offset();
            var height = $(input).height();
            window.setTimeout(function () {
                inst.dpDiv.css({ top: (offset.top + height + 4) + 'px', left: offset.left + 'px' })
            }, 1);
        }        
    });
    
    $(".input_date_up_future").datepicker({
        showButtonPanel: false,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        beforeShow: function (input, inst) {
            var offset = $(input).offset();
            var height = $(input).height();
            window.setTimeout(function () {
                inst.dpDiv.css({ top: (offset.top - inst.dpDiv.height() - 4) + 'px', left: offset.left + 'px' })
            }, 1);
        }        
    });
    
    
    $(".input_date_down_future").datepicker({
        showButtonPanel: false,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        beforeShow: function (input, inst) {
            var offset = $(input).offset();
            var height = $(input).height();
            window.setTimeout(function () {
                inst.dpDiv.css({ top: (offset.top + height + 4) + 'px', left: offset.left + 'px' })
            }, 1);
        }        
    });
    
    //$('.input_time').timepicker({
    //    //ampm: true
    //});


    
});



function enter_setup(){
    $('input[type=text]').keydown(function(e){
        //get the next index of text input element
        var next_idx = $('input[type=text]').index(this) + 1;
        
        //get number of text input element in a html document
        var tot_idx = $('body').find('input[type=text]').length;
        
        //enter button in ASCII code
        if(e.keyCode == 13){
            if(tot_idx == next_idx){
                //go to the first text element if focused in the last text input element
                $('#btnSave').focus();
            }else{
                //go to the next text input element
                $('input[type=text]:eq(' + next_idx + ')').focus();
            }
        }
    });
}

function enter_setup_trance(f){
    if(f == undefined){ f = "tgrid"; }
    $('#'+f+' input[type=text]').keydown(function(e){
        //get the next index of text input element
        var next_idx = $('#'+f+' input[type=text]').index(this) + 1;
        
        //get number of text input element in a html document
        var tot_idx = $('#'+f+'').find('input[type=text]').length;
        
        //enter button in ASCII code
        if(e.keyCode == 13){
            if(tot_idx == next_idx){
                //go to the first text element if focused in the last text input element
                $('#btnSave').focus();
            }else{
                //go to the next text input element
                $('#'+f+' input[type=text]:eq(' + next_idx + ')').focus();
            }
        }
    });
}

function input_reset(id){
    if(id == undefined){
       
        $("input").each(function(){
            $(this).val($(this).attr("title"));
            
            if($(this).attr("class") == "input_txt_f"){
                $(this).attr("class", "input_txt");
            }else if($(this).attr("class") == "input_pass_f"){
                $(this).attr("class", "input_pass");
            }
            
            $("textarea").val(""); $("select").val(0); 
            //$(":radio").removeAttr("checked");
        });
    }else{
        $("#"+id).val($("#"+id).attr("title"));
        
        var f, b;
        if($("#"+id).attr("class") == "input_txt"){
            f = "input_txt_f"; b = "input_txt";
        }else if($("#"+id).attr("class") == "input_number"){
            f = "input_number_f"; b = "input_number";
        }else if($("#"+id).attr("class") == "input_amount"){
            f = "input_amount_f"; b = "input_amount";
        }
        
        $("#"+id).focus(function(){
            if($(this).val() === $(this).attr("title")){
                $(this).val("");
                $(this).attr("class", f);
            }
            
            $(this).blur(function(){
                if($(this).val() == ""){
                    $(this).val($(this).attr("title"));
                    $(this).attr("class", b);
                }
            });
        });
    }
}

function input_active(){
    $("input").each(function(){        
        if($(this).attr("class") == "input_txt" && $(this).attr("title") != $(this).val()){
            $(this).attr("class", "input_txt_f");
        }else if($(this).attr("class") == "input_number" && $(this).attr("title") != $(this).val()){
            $(this).attr("class", "input_number_f");
        }else if($(this).attr("class") == "input_amount" && $(this).attr("title") != $(this).val()){
            $(this).attr("class", "input_amount_f");
        }
    });
}