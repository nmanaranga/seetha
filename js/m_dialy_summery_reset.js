$(document).ready(function(){


    $("#btnPrint").click(function(){
        $("#print_pdf").submit();
    });

    $("#pdate").change(function(){
        $("#pd").val($("#pdate").val());
    });


});

function validate(){
    return true;
}

function save(){
    var frm = $('#form_');
    loding();
    $.ajax({
       type: frm.attr('method'),
       url: frm.attr('action'),
       data: frm.serialize(),
       success: function (pid) {
         if(pid==1){
            $("#btnSave").attr("disabled",true);
            location.href="";
        }else{
            set_msg(pid);
        }     
    }
});       
}
