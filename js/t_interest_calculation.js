var x=0; var emp_count = 0; var emp; var com;
$(document).ready(function(){
    $("#view").center();
    $("#view").fadeIn();
    $("#blocker_ins").fadeIn();
    
    
    $("#btnCancel").click(function(){
            $("#blocker_ins").fadeOut();
            $("#view").fadeOut();
    });
    
    $("#btnProcess").click(function(){
            check_process();
            check_previous_process();     
    });
    
});

function check_process(){
    $("#blocker_ins").fadeOut();
    $("#view").fadeOut();
}
  
function load_customer(){
    $.post("index.php/main/load_data/t_interest_calculation/load_customer", {
        date : $("#date").val()
    }, function(r){
        if(r.emp == false){

            $("#progres").progressbar({ value: x, max : r.count });
            emp_count = parseInt(r.count);
            emp = r.det; com = r.com;
            pro();
        }else{
            alert("No any emplyee found");
        }
    }, "json");
}

    function check_previous_process()
    {
        $.post("index.php/main/load_data/t_interest_calculation/check_previous_process",{
           date : $("#date").val() 
        },function(r)    
        {    
            if(r.r>0)
            {
               alert("Please do interest calculation process for date of "+r.prev);
               $("#view").center();
               $("#view").fadeIn();
               $("#blocker_ins").fadeIn();
               $("#date").val(r.prev);
            }    
            else
            {
                check_is_process();
            }    
            
        },"json");
    }
    
    function check_is_process()
    {
        $.post("index.php/main/load_data/t_interest_calculation/check_is_process",{
           date : $("#date").val() 
        },function(r)    
        {    
            if(r>0)
            {
                alert("Data already process for date of "+$("#date").val());
            }    
            else
            {
                  load_customer();       
            }    
            
        },"text");
        
        
    }
    
    function pro()
    {
        if(x < emp_count) {
        var img = "<img src='' alt='"+emp[x]['code']+'~'+emp[x]['name']+"' />";

        $("#img_tag").html(img);
        $.post("index.php/main/load_data/t_interest_calculation/interest_calculation", {
            date : $("#date").val(),
            customer : emp[x]['code']
        }, function(r){
            if(r == 1){
                setTimeout("pro()", 1000);
            }else{
                alert("Data not Recived.Pleace check the values");
            }
        }, "text");
        }else{
            $("#img_tag").html("<h1>Interest Calculation Process Success.</h1>");
        }
        $("#progres").progressbar({ value: x++});
   
    }