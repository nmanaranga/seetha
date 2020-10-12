$(document).ready(function(){
    $("#btnLogin").click(function(){
        clickLoad()
    });
});


function clickLoad(){
    if(validate()){
        $("#f").show();
        var a=$("#txtUserName").val(),b=$("#txtPassword").val();
        $.post("index.php/main/login/",{
            userName:a,
            userPassword:b
        },function(a){
            a==null||a==""?message("Internet Problem. No data Recived"):a==1?$("#f").delay(1E3).fadeOut(500,function(){
                $("#a").html("<span class='label label-success'>Loading......</span>").fadeIn(1E3).delay(1E3,function(){
                    window.location="";
                })
            }):a==2?message("Wrong company information"):message("Wrong login information");
        }, "text");
    }
}

function validate(){
    return $("#txtUserName").val()==""||$("#txtPassword").val()==""?($("#a").html("<span class='label label-warning'> Please fill all the fields</span>").fadeIn(20).delay(2E3).fadeOut(1E3),!1):!0
}

