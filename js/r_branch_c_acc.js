$(document).ready(function(){

	$("#btnReset").click(function(){
		location.href="index.php?action=r_branch_c_acc";
	});

  $("#btnPrint").click(function(){
    $("#print_pdf").submit();
  });

  load_grid();
  $(document).on('keydown','.fo',function(e){
        set_cid($(this).attr("id"));
        if(e.keyCode==112){
            $("#pop_search11").val($("#acc").val());
            load_data9();
            $("#serch_pop11").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search11').focus()", 100);
        }
        $("#pop_search11").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                 load_data9();
            }
        }); 
        if(e.keyCode==46){
          $("#acc_"+scid).val("");
          $("#accName_"+scid).val("");
        }
    });
   
});

function save(){
  loding();
  var frm = $('#form_');
  $.ajax({
  type: frm.attr('method'),
  url: frm.attr('action'),
  data: frm.serialize(),
	success: function (pid){
            if(pid == 1){
              if(confirm("Save Completed, Do You Want A print?")){
                if($("#is_prnt").val()==1){
                    $("#print_pdf").submit();
                    //load_data();
                    reload_form();
                }
                reload_form();
              }else{
                location.href="";
              }
            }else{
                set_msg(pid);
            }
            loding();
        }
    });
}

function reload_form(){
  setTimeout(function(){
    location.href= '';
    l
  },50); 
}



function validate(){
  var y=false;
  for(var f=0; f<$("#hid_count").val(); f++){
    if($("#acc_"+f).val()==""){
      y=true;
    }
  }

  if(y==true){
    set_msg("Please Enter Account","error");
    return false;
  }else{
    return true;
  }

 
}
    
// function set_delete(code){
//     if(confirm("Are you sure delete no "+code+"?")){
//         loding();
//         $.post("index.php/main/delete/t_bankrec", {
//             code : code
//         }, function(res){
//             if(res == 1){
//                 delete_msg();
//             }else{
//                 set_msg(res);
//             }
//             loding();
//         }, "text");
//     }
// }

  


function load_grid(){
  loding();
   $.post("index.php/main/load_data/r_branch_c_acc/load_grid", {
      }, function(r){
        var tabl=""
        var z=1;
        y=r.det.length;
        for(var x=0; x< r.det.length; x++){
           tabl+="<tr>";
           tabl+="<td ><input type='text' name='no_"+x+"' id='no_"+x+"' value="+z+" title='"+z+"' style='width:100%;' /></td>";
           tabl+="<td ><input type='text' name='cl_"+x+"' id='cl_"+x+"' value='"+r.det[x].cl+"' style='width:100%;' /></td>";
           tabl+="<td ><input type='text'  class='g_input_txt g_col_fixed'  id='description_"+x+"' value='"+r.det[x].description+"' readonly='readonly' name='description_"+x+"' style='width:100%;'/></td>";
           tabl+= "<td ><input type='text' class='g_input_txt g_col_fixed'  id='bc_"+x+"' name='bc_"+x+"' value='"+r.det[x].bc+"' readonly='readonly' style='width:100%;'/></td>";
           tabl+= "<td ><input type='text' class='g_input_txt g_col_fixed'  id='bcName_"+x+"' name='bcName_"+x+"' value='"+r.det[x].name+"' readonly='readonly' style='width:100%;'/></td>";       
           tabl+= "<td ><input type='text' class='fo'  id='acc_"+x+"' name='acc_"+x+"'  style='text-align:left;' value=''style='width:100%;'/></td>";
           tabl+= "<td><input class='g_input_txt g_col_fixed' type='text' id='accName_"+x+"' name='accName_"+x+"' value='' readonly='readonly' style='text-align:left; width:100%;'/></td>";
           tabl+= "</tr>";
          z++;
        }
        $("#hid_count").val(y)
        $("#load_g").html(tabl);
        $("#tgrid").tableScroll({height:300,width:980});
        chk_is_saved();
        loding();
      }, "json");
    } 

function chk_is_saved(){
   $.post("index.php/main/load_data/r_branch_c_acc/is_saved", {
    }, function(r){
        if(r==1){
          load_saved_data();
        }    
    }, "text");
}

function load_saved_data(){
  $.post("index.php/main/load_data/r_branch_c_acc/load_saved_data", {
    }, function(r){
      if(r==2){
        set_msg("No records.")
      }else{
          $(".fo").each(function(e){
            for(var x=0; x<r.det.length; x++){
              //alert($("#bc_"+e).val());
            //alert(r.det[x].ref_bc +"=="+ $("#bc_"+e).val());
            if($("#bc_"+e).val() == r.det[x].ref_bc){
              $("#acc_"+e).val(r.det[x].acc_code);
              $("#accName_"+e).val(r.det[x].description);
            }
          }
        });
      }
    }, "json");
}


function load_data9(){
    $.post("index.php/main/load_data/utility/f1_selection_list", {
        data_tbl:"m_account",
        field:"code",
        field2:"description",
        preview2:"Account Name",
        search : $("#pop_search11").val() 
    }, function(r){
        $("#sr11").html(r);
        settings9();            
    }, "text");
}

function settings9(){
    $("#item_list .cl").click(function(){   
        $("#acc_"+scid).val($(this).children().eq(0).html());
        $("#accName_"+scid).val($(this).children().eq(1).html());
        $("#pop_close11").click();                
    })    
}

