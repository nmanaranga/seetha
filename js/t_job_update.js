$(document).ready(function(){
     
$("#grid").tableScroll({height:450});

$("#btnReset").click(function(){
    for(var i=0;i<25;i++){
    $("#sel_"+i).removeAttr("checked");
    $("#gur_"+i).removeAttr("checked");
}
});

$("#btnPrint").click(function(){
    if($("#qno").val()==""){
        set_msg("Please Load a Record");
    }else{
        $("#print_pdf").submit();
    }
});

    $("#sup_id").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search12").val($("#sup_id").val());
            load_sup();
            $("#serch_pop12").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search12').focus()", 100);
        }
       $("#pop_search12").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                 load_sup();
            }
        }); 
        if(e.keyCode == 46){
            $("#sup_id").val("");
            $("#supplier").val("");
            
        }
    });

$("#id").keydown(function(e){
            if(e.keyCode == 13){
                $(this).blur();
                $("#hid").val($(this).val());
                load_data($(this).val());
            }
    });


$("#btnDelete").click(function(){
        set_delete();
    });

});

function load_sup(){
        $.post("index.php/main/load_data/utility/f1_selection_list", {
            data_tbl:"m_supplier",
            field:"code",
            field2:"name",
            search : $("#pop_search12").val() 
        }, function(r){
            $("#sr12").html("");
            $("#sr12").html(r);
            settings_sup();            
        }, "text");
    }

function settings_sup(){
    $("#item_list .cl").click(function(){        
        $("#sup_id").val($(this).children().eq(0).html());
        $("#supplier").val($(this).children().eq(1).html());
        load_jobs($(this).children().eq(0).html());
        $("#pop_close12").click();               
    })    
}
function load_jobs(sup_code){
    $.post("index.php/main/load_data/t_job_update/load_data", {
        sup_code : sup_code
    }, function(r){

    for(var i=0; i<r.a.length;i++){
        $("#0_"+i).val(r.a[i].nno);
        $("#n_"+i).val(r.a[i].ddate);
        $("#1_"+i).val(r.a[i].cus_id+"-"+r.a[i].name);
        if(r.a[i].item_code!=""){
            $("#2_"+i).val(r.a[i].item_code+"-"+r.a[i].itm);
        }else{
            $("#2_"+i).val(r.a[i].item_name);
        }
        
        $("#3_"+i).val(r.a[i].fault);
        $("#4_"+i).val(r.a[i].serial_no);
        if(r.a[i].is_guarantee=="1"){
            
            $("#gur_"+i).attr("checked","checked");
        }
        $("#5_"+i).val(r.a[i].guarantee_cardno);
      }

    }, "json");
}

function sup_id(sup_code){
    $.post("index.php/main/load_data/t_job_update/load_data", {
        sup_code : sup_code
    }, function(r){

    for(var i=0; i<r.a.length;i++){
        $("#0_"+i).val(r.a[i].nno);
        $("#n_"+i).val(r.a[i].ddate);
        $("#1_"+i).val(r.a[i].cus_id+"-"+r.a[i].name);
        if(r.a[i].item_code!=""){
            $("#2_"+i).val(r.a[i].item_code+"-"+r.a[i].itm);
        }else{
            $("#2_"+i).val(r.a[i].item_name);
        }
        
        $("#3_"+i).val(r.a[i].fault);
        $("#4_"+i).val(r.a[i].serial_no);
        if(r.a[i].is_guarantee=="1"){
            
            $("#gur_"+i).attr("checked","checked");
        }
        $("#5_"+i).val(r.a[i].guarantee_cardno);
      }

    }, "json");
}
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
    success: function (pid){
            if(pid == 1){
                loding();
                sucess_msg();
            }else if(pid == 2){
                set_msg("No permission to add data.");
            }else if(pid == 3){
                set_msg("No permission to edit data.");
            }else{
                set_msg(pid);
            }
        }
    });
}

function load_data(id){
    loding();
    $.post("index.php/main/get_data/t_job_update", {
        id : id
    }, function(res){
        empty_grid();
        $("#ref_no").val(res.a[0].ref_no);
        $("#date").val(res.a[0].ddate);
        $("#sup_id").val(res.a[0].supplier);
        $("#supplier").val(res.a[0].name);
        $("#comment").val(res.a[0].memo);
        $("#qno").val($("#id").val());
        if(res.a[0].is_cancel==1){
            $("#btnDelete").attr("disabled", "disabled");
            $("#btnSave").attr("disabled", "disabled");
            $("#mframe").css("background-image", "url('img/cancel.png')");
        }

        for(var i=0; i<res.b.length;i++){            
            $("#sel_"+i).attr("checked","checked");
            $("#0_"+i).val(res.b[i].job_no);
            $("#n_"+i).val(res.b[i].ddate);
            $("#1_"+i).val(res.b[i].cus_id+"-"+res.b[i].name);
            if(res.b[i].item_code!=""){
                $("#2_"+i).val(res.b[i].item_code+"-"+res.b[i].description);
            }else{
                $("#2_"+i).val(res.b[i].Item_name);
            }   
            $("#3_"+i).val(res.b[i].fault);
            $("#4_"+i).val(res.b[i].serial_no);
            $("#5_"+i).val(res.b[i].guarantee_cardno);
            if(res.b[i].is_guarantee=="1"){
            
            $("#gur_"+i).attr("checked","checked");
        }
            $("#6_"+i).val(res.b[i].name);

        } 
                
        loding(); 
    }, "json");
}

function set_delete(){
    var id = $("#hid").val();
    if(id != 0){
        if(confirm("Are you sure ? ")){
            $.post("index.php/main/delete/t_job_update", {
                id : id
            }, function(r){
                if(r != 1){
                    alert("Record cancel fail.");
                }else{
                    location.href="";
                }
            }, "text");
        }
    }else{
        alert("Please load record");
    }
}
function empty_grid(){

    for(var i =0;i<25;i++){
        $("#0_"+i).val("");
        $("#n_"+i).val("");
        $("#1_"+i).val("");
        $("#2_"+i).val("");
        $("#3_"+i).val("");
        $("#4_"+i).val("");
        $("#5_"+i).val("");

    }
}