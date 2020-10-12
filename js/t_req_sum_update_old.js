var global_item = []; //namal
var global_t = [];
var k=0; var xz=0;
global_item[0]="no_item";
global_t[0]=0;
$(document).ready(function(){
    $("#code").blur(function(){
        check_code();
    });
  // num_of_branches();
 $("#load_item_request").click(function(){       
       load_data();
    });


    $("#supplier_id").autocomplete('index.php/main/load_data/m_supplier/auto_com', {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatItems,
        formatResult: formatItemsResult
        });
   

        $("#supplier_id").keydown(function(e){
            if(e.keyCode == 13){
                set_cus_values($(this));
            }
        });
    
        $("#supplier_id").blur(function(){
            set_cus_values($(this));
        });        

});



function set_cus_values(f){
            var v = f.val();
            v = v.split("|");
             if(v.length == 2){
                f.val(v[0]);
                $("#supplier").val(v[1]);
               // $("#supplier").attr("class", "input_txt_f");

        }
    }


function formatItems(row){
            return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
        }

function formatItemsResult(row){
            return row[0]+"|"+row[1];
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
                input_reset();
                get_data_table();
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

function get_data_table(){
    $.post("/index.php/main/load_data/t_req_sum_update/get_data_table", {
        
    }, function(r){
        $("#grid_body").html(r);
    }, "text");
}


function check_code(){
        loding();
    var code = $("#code").val();
    $.post("index.php/main/load_data/t_req_sum_update/check_code", {
        code : code
    }, function(res){
        if(res == 1){
            if(confirm("This code ("+code+") already added. \n\n Do you need edit it?")){
                set_edit(code);
            }else{
                $("#code").val('');
		$("#code").attr("readonly", false);
            }
        }
        loding();
    }, "text");
}

function validate(){
    if($("#code").val() === $("#code").attr('title') || $("#code").val() == ""){
        alert("Please enter code.");
        $("#code").focus();
        return false;
    }else if($("#description").val() === $("#description").attr('title') || $("#description").val() == ""){
        alert("Please enter description.");
        $("#description").focus();
        return false;
    }else if($("#description").val() === $("#code").val()){
        alert("Please enter deferent values for description & code.");
        $("#des").focus();
        return false;
    }else{
        return true;
    }
}
    
function set_delete(code){
    if(confirm("Are you sure delete "+code+"?")){
        loding();
        $.post("index.php/main/delete/t_req_sum_update", {
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
    
function set_edit(code){
    loding();
    $.post("index.php/main/get_data/t_req_sum_update", {
        code : code
    }, function(res){
        $("#code_").val(res.code);
        $("#code").val(res.code);
	$("#code").attr("readonly", true);
        $("#description").val(res.description);
        
           if(res.is_vehical == 1){
            $("#is_vehical").attr("checked", "checked");
        }else{
            $("#is_vehical").removeAttr("checked");
        }
        
        
        
       // is_edit('010');
        loding(); input_active();
    }, "json");
}

function empty_grid(){
    for(var i=0; i<25; i++){
        $("#h_"+i).val(0);
        $("#0_"+i).val("");
        $("#n_"+i).val("");
        $("#t_"+i).html("&nbsp;");
        $("#1_"+i).val("");
        $("#2_"+i).val("");
        $("#3_"+i).val("");
        $("#4_"+i).val("");
        $("#5_"+i).val("");
        
    }
}

function load_data(){
    var total=0;var z=0;var i=0;
    empty_grid(); 
    $.post("index.php/main/load_data/t_req_sum_update/load_data", {
        
    }, function(r){
        var x=0;
            if(r=="1"){
               alert("No records");
            }else{ 

            
            //for(var i=0; i<r.det.length;i++){
                
                if(z==0){
                $("#0_"+z).val(r.det[i].Item);
                $("#n_"+z).val(r.det[i].description);
                $("#1_"+z).val(r.det[i].model);
                $("#3_0"+z).val(r.det[i].rol);
                $("#4_0"+z).val(r.det[i].roq);
               
                $("#5_0"+z).val(r.det[i].cur_qty); z++;i++;
                }   
                    for(var x=1; x<r.det.length;x++){
                    
                        if(r.det[x-1].Item==r.det[x].Item){
                         
                            if(r.det[x-1].BC !=r.det[x].BC){ //alert(z); 
                              set_different_branches_values(r.det[x-1].Item ,z);
                            
                               // //alert("#3_"+""+(x-1)+""+i);
                               // for(var a=0;;a++){
                               //  $("#3_"+""+(x)+""+z).val(r.det[i].rol);
                               //  $("#4_"+""+(x)+""+z).val(r.det[i].roq);
                               //  $("#5_"+""+(x)+""+z).val(r.det[i].cur_qty); i++;
                               //  }  
                               i++;
                            }else{
                             i++;                       
                            }
                            
                        }
                        else{
                            $("#0_"+z).val(r.det[i].Item);
                            $("#n_"+z).val(r.det[i].description);
                            $("#1_"+z).val(r.det[i].model);
                            
                            $("#3_0"+z).val(r.det[i].rol);
                            $("#4_0"+z).val(r.det[i].roq);
                            $("#5_0"+z).val(r.det[i].cur_qty);
                            z++;i++;
                        }
                        
                    }
                   get_item_total();
                
           // }
            input_active();
    }

}, "json");
}

function num_of_branches(item){
   
    $.post("index.php/main/load_data/t_req_sum_update/num_of_branches",
        {
         item:item
        },
        function(r){
        xz=r;
        }
        );
    return xz;
}

function  get_num_of_branches(r){
    return r;
    //alert("item "+item +"-"+r)
} 


function set_different_branches_values(item,i){     
     var q=0;
     global_t[k]=i-1;
     global_item[k]=item;

     if(global_item[k-1]==item){
        q=global_t[k-1];
     }
     else{
        q=global_t[k];
     }

  var length= num_of_branches(item);
   
  
  alert("Item - "+ item);
     $.post("index.php/main/load_data/t_req_sum_update/set_different_branches_values",
        {
            item:item
        }
        ,function(r){
        //alert(length);
            for(var x=0;x<length;x++){

                $("#3_"+""+(x+1)+""+q).val(r.det[x].rol);
                $("#4_"+""+(x+1)+""+q).val(r.det[x].roq);
                $("#5_"+""+(x+1)+""+q).val(r.det[x].cur_qty);
            }
     },"json");
     
     k++;
}


   //SELECT GROUP_CONCAT(bc,"_") FROM `t_req_det`  

function get_item_total(){
   
    $.post("index.php/main/load_data/t_req_sum_update/get_item_total",
        {
            
        }
        ,function(r){
            for(var i=0;i<r.tt.length;i++){
                $("#2_"+i).val(parseFloat(r.tt[i].total));
                //alert();
            }
     },"json");
   
}

function  namal_test(){
    var my_branch=[];
    $.post("index.php/main/load_data/t_req_sum_update/namal_test",{}
        ,function(r){


            for(var i=0;i<r.det1.length;i++){
                my_branch[i]=r.det1[i].b_count;
                for(var x=0;x < my_branch[i]; x++){
                    $("#3_"+""+x+""+i).val(r.det[x].rol);
                    $("#4_"+""+x+""+i).val(r.det[x].roq);
                    $("#5_"+""+x+""+i).val(r.det[x].cur_qty); 
               }
               
            }


     },"json");

} 











// SELECT COUNT(DISTINCT(`t_req_det`.`BC`))
//               AS `b_count`,SUM(`t_req_det`.`total`)
//               AS `total` ,`t_req_det`.`Item` ,GROUP_CONCAT(DISTINCT(`t_req_det`.`rol`) ORDER BY `t_req_det`.`BC` ,`t_req_det`.`item`  ,'') AS 'c'
//               FROM `t_req_det`    
//               WHERE  `t_req_det`.`CL`='c1'         
//               GROUP BY `t_req_det`.`Item`
//               ORDER BY bc,`t_req_det`.`Item`












 












































 // $("#0_"+i).val(r.det[i].item);
                // $("#n_"+i).val(r.det[i].description);
                // $("#1_"+i).val(r.det[i].model);
                // $("#2_"+i).val(r.det[i].item);
                // $("#3_"+i).val(r.det[i].rol);
                // $("#4_"+i).val(r.det[i].week1);
                // $("#5_"+i).val(r.det[i].week2);
                // $("#6_"+i).val(r.det[i].week3);
                // $("#7_"+i).val(r.det[i].week4);
                // $("#9_"+i).val(r.det[i].Supplier);
                // $("#c_"+i).val(r.det[i].cmnt);
                





//                 function load_data(){
//     var total=0;var z=0;var i=0;
//     empty_grid(); 
//     $.post("index.php/main/load_data/t_req_sum_update/load_data", {
        
//     }, function(r){
//         var x=0;
//             if(r=="1"){
//                alert("No records");
//             }else{ 

            
//             //for(var i=0; i<r.det.length;i++){
                
//                 if(z==0){
//                 $("#0_"+z).val(r.det[i].Item);
//                 $("#n_"+z).val(r.det[i].description);
//                 $("#1_"+z).val(r.det[i].model);
//                 $("#3_0"+z).val(r.det[i].rol);
//                 $("#4_0"+z).val(r.det[i].roq);
//                 $("#5_0"+z).val(r.det[i].cur_qty); z++;i++
//                 }   
//                     for(var x=1; x<r.det.length;x++){
                    
//                         if(r.det[x-1].Item==r.det[x].Item){
                         
//                             if(r.det[x-1].BC !=r.det[x].BC){  
//                                // //alert("#3_"+""+(x-1)+""+i);
//                                for(){
//                                 $("#3_"+""+(x)+""+z).val(r.det[i].rol);
//                                 $("#4_"+""+(x)+""+z).val(r.det[i].roq);
//                                 $("#5_"+""+(x)+""+z).val(r.det[i].cur_qty); i++;
//                                 }  
//                             }else{
//                              i++;                       
//                             }
                            
//                         }
//                         else{
//                             $("#0_"+z).val(r.det[i].Item);
//                             $("#n_"+z).val(r.det[i].description);
//                             $("#1_"+z).val(r.det[i].model);
//                             $("#3_0"+z).val(r.det[i].rol);
//                             $("#4_0"+z).val(r.det[i].roq);
//                             $("#5_0"+z).val(r.det[i].cur_qty);
//                             z++;i++;
//                         }
                        
//                     }
                
//            // }
//             input_active();
//     }

// }, "json");
// }
