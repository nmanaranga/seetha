// var req_id = 0;

// function load_permission_request(v){
//     if(req_id == 0){
//         $("#permission_conform").center();
//         //$("#blocker").css("display", "block");
//         $("#rid").html(v.id);
//         $("#customer_n").html(v.name);
//         $("#rb").html(v.discription);
//         $("#ra").html(v.request_balance);
//         $("#rt").html(v.action_date);
//         $("#cl1").html(v.l1);
//         $("#cl2").html(v.l2);
//         $("#cl3").html(v.l3);
//         $("#s").html('<embed src="sound/ring.mp3" autostart="true" loop="true"></embed>');
//         req_id = v.id;
        
//         check_request_status();
//     }
// }

// function check_request_status(){
//     if(req_id != 0){
//         $.post("index.php/main/load_data/loder/cheque_permission", {
//             request_id : req_id
//         }, function(r){
//             if(r.conform == 0){
//                 setTimeout("check_request_status()", 2000);
//             }else{
//                 req_id = 0;
//                 $("#permission_conform").css("display", "none");
//                 $("#blocker").css("display", "none");
//             }
//         }, "json");
//     }
// }

// function respons_for_request(v){
//     $.post("index.php/main/load_data/loder/respons_for_request",{
//         action : v,
//         request_id : req_id
//     }, function(r){
//         if(r == 1){
//             req_id = 0;
//             $("#permission_conform").css("display", "none");
//             $("#blocker").css("display", "none");
//         }else{
//             alert("Error");
//         }
//     }, "text");
// }