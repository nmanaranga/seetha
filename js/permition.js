var alert_height = "0"; var s;
$(document).ready(function(){
    //s = $("#player").get(0);
    
   //load();
    
 //    $("#alert_close").click(function(){
	// $("#alert_dispaly").css("display", "none");
	// $("#blocker").css("display", "none");
 //    });

  //  $("#minzz").click(function(){
    //    $("#alert_dispaly").center();
	//$("#blocker").css("display", "block");

        
    //        $.post('index.php/main/load_data/loder/permission_request', {
        
    // }, function(r){
    //     var d = h =""; alert_height = a = 0;
     
    //      if(r.per.length != 0){
    //         $("#permission_request").css("display", "block");
    //         d += "<div class='maz'>"+r.per.length+" Permition request(s) | Cash Sales-Discount</div>";
    //         for(var i=0; i<r.per.length; i++){
  
    //             d += "<b><a href='?action=t_sales_conform&id="+r.per[i].id+"' style='color: #3104B4'>Invoice No: "+r.per[i].no+"| Operator : "+r.per[i].user+" | CUS. : "+ r.per[i].customer+"</a></b><br /><br />";
    
    //         alert_height += 38; a++;
    //         }
    //     }
        
       
    //     if(r.response.length != 0){
    //         $("#permission_request").css("display", "block");
    //         d += "<div class='maz'>"+r.response.length+"  Accepted response(s) | Cash Sales-Discount</div>";
    //         for(var i=0; i<r.response.length; i++){
    
    //            if(r.response[i].is_approve==1){
    //                         response='Accepted';
    //                     }
    //            if(r.response[i].is_reject==1){
    //                         response='Rejected';
    //                     }  

    //             d += "<b><a href='?action=t_sales&id="+r.response[i].id+"' style='color: #3104B4'>Date:"+r.response[i].date+"|Invoice No:"+r.response[i].no+"| Approved By : "+r.response[i].user+" | CUS. : "+ r.response[i].customer+"|response :"+response+"</a><br /><br />";      
                 
            
    //         alert_height += 38; a++;
    //         }
    //     }
        
    //             if(r.pero.length != 0){
    //         $("#permission_request").css("display", "block");
    //         d += "<div class='maz'>"+r.pero.length+" Permition request(s) | Credit Sales-Discount</div>";
    //         for(var i=0; i<r.pero.length; i++){

    //             d += "<b><a href='?action=t_sales_conform_cr&id="+r.pero[i].id+"' style='color: #3104B4'>Invoice No:"+r.pero[i].no+"| Operator : "+r.pero[i].user+" | CUS. : "+ r.pero[i].customer+"</a></b><br /><br />";
  
    //         alert_height += 38; a++;
    //         }
    //     }
  
        
    //     if(r.responseo.length != 0){
    //         $("#permission_request").css("display", "block");
    //         d += "<div class='maz'>"+r.responseo.length+"  Accepted response(s) | Credit Sales-Discount</div>";
    //         for(var i=0; i<r.responseo.length; i++){
    
    //            if(r.responseo[i].is_approve==1){
    //                         response='Accepted';
    //                     }
    //            if(r.responseo[i].is_reject==1){
    //                         response='Rejected';
    //                     }  

    //             d += "<b><a href='?action=t_sales_credit&id="+r.responseo[i].id+"' style='color: #3104B4'>Date:"+r.responseo[i].date+"|Invoice No:"+r.responseo[i].no+"| Approved by : "+r.responseo[i].user+" | CUS. : "+ r.responseo[i].customer+"|response :"+response+"</a><br /><br />";      
                 
            
    //         alert_height += 38; a++;
    //         }
    //     }
        
    //     if($("#alert_dispaly").css("display") == "block"){
    //         //s.play();
    //         $("#listt").html(d);
    //         $("#alertt").html(a+" alert(s).")
    //     }
        
    //     setTimeout('load()', 5000);
    // }, 'json'); 

 //   });
    
    // $("#minz").click(function(){
    //     if($("#list").css("height") == "0px"){
    //         $(this).attr("src", "img/up.png");
    //         $("#list").css("height", alert_height);
    //     }else{
    //         $(this).attr("src", "img/down.png");
    //         alert_height = $("#list").css("height");
    //         $("#list").css("height", 0);
    //     }
        
    // });
});

// function load(){
//     $.post('index.php/main/load_data/loder/permission_request', {
        
//     }, function(r){
//         var d = h =""; alert_height = a = 0;
//         if(r.per.length != 0){
//             $("#permission_request").css("display", "block");
//             d += "<div class='maz'>"+r.per.length+" Permition request(s) | Cash Sales-Discount</div>";
//             for(var i=0; i<r.per.length; i++){
  
//                 d += "<b><a href='?action=t_sales_conform&id="+r.per[i].id+"' style='color: #3104B4'>Invoice No:"+r.per[i].no+"| Operator : "+r.per[i].user+" | CUS. : "+ r.per[i].customer+"</a></b><br /><br />";
    
//             alert_height += 38; a++;
//             }
//         }
  
        
//         if(r.response.length != 0){
//             $("#permission_request").css("display", "block");
//             d += "<div class='maz'>"+r.response.length+"  Accepted response(s) | Cash Sales-Discount</div>";
//             for(var i=0; i<r.response.length; i++){
    
//                if(r.response[i].is_approve==1){
//                             response='Accepted';
//                         }
//                if(r.response[i].is_reject==1){
//                             response='Rejected';
//                         }  

//                 d += "<b><a href='?action=t_sales&id="+r.response[i].id+"' style='color: #3104B4'>Date:"+r.response[i].date+"|Invoice No:"+r.response[i].no+"| Approved by : "+r.response[i].user+" | CUS. : "+ r.response[i].customer+"|response :"+response+"</a><br /><br />";      
                 
            
//             alert_height += 38; a++;
//             }
//         }
        
//         if(r.pero.length != 0){
//             $("#permission_request").css("display", "block");
//             d += "<div class='maz'>"+r.pero.length+" Permition request(s) | Credit Sales-Discount</div>";
//             for(var i=0; i<r.pero.length; i++){

//                 d += "<b><a href='?action=t_sales_conform_cr&id="+r.pero[i].id+"' style='color: #3104B4'>Invoice No:"+r.pero[i].no+"| Operator : "+r.pero[i].user+" | CUS. : "+ r.pero[i].customer+"</a></b><br /><br />";
    
//             alert_height += 38; a++;
//             }
//         }
  
        
//         if(r.responseo.length != 0){
//             $("#permission_request").css("display", "block");
//             d += "<div class='maz'>"+r.responseo.length+"  Accepted response(s) | Credit Sales-Discount</div>";
//             for(var i=0; i<r.responseo.length; i++){
    
//                if(r.responseo[i].is_approve==1){
//                             response='Accepted';
//                         }
//                if(r.responseo[i].is_reject==1){
//                             response='Rejected';
//                         }  

//                 d += "<b><a href='?action=t_sales_credit&id="+r.responseo[i].id+"' style='color: #3104B4'>Date:"+r.responseo[i].date+"|Invoice No:"+r.responseo[i].no+"| Approved by : "+r.responseo[i].user+" | CUS. : "+ r.responseo[i].customer+"|response :"+response+"</a><br /><br />";      
                 
            
//             alert_height += 38; a++;
//             }
//         }
  
//         if($("#permission_request").css("display") == "block"){
//             //s.play();
//             $("#list").html(d);
//             $("#alert").html(a+" alert(s).")
//         }
        
//         setTimeout('load()', 5000);
//     }, 'json');
// }