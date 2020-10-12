$(document).ready(function(){
    

    $("#left,#right").hide().fadeIn(5E3);
    
    $("#f, #a").hide();
    
    $("#btnLogin").click(function(){
        clickLoad()
    });
    
    $("#txtPassword, #log_date").keypress(function(a){
        a.keyCode==13&&clickLoad()
    });
    
    $("#txtUserName").keypress(function(e){
        if(e.keyCode==13) { $("#txtPassword").focus(); }
    });
    
    $("#txtUserName").focus();

    $("#txtUserName").blur(function(){
        var clust=$("#cluster").val();
        $.post("index.php/main/load_data/login/select_cluster",{
            username:$("#txtUserName").val()

        },function(res){
           $("#cluster").html(res.result);
        },"json"

        );     
    });

    $(document).on('change','#cl', function(){    
        var clust=$("#cl").val();
        $.post("index.php/main/select/login",{
            cluster:clust,
            username:$("#txtUserName").val()

        },function(res){
           $("#branch").html(res.result);
        },"json"

        );     
    });
});

function clickLoad(){
    if(validate()){
        $("#f").show();
        var a=$("#txtUserName").val(),b=$("#txtPassword").val();
        $.post("index.php/main/login/",{
            userName:a,
            userPassword:b,
            company:$("#company").val(),
            cluster:$("#cl").val(),
            date   :$("#txtDate").val()
        },function(a){
            a==null||a==""?message("Internet Problem. No data Recived"):a==1?$("#f").delay(1E3).fadeOut(500,function(){
                $("#a").html("loading......").fadeIn(1E3).delay(1E3,function(){
                    window.location="";
                })
            }):a==2?message("Wrong company information"):message("Wrong login information");
        }, "text");
    }
}

function validate(){
    return $("#txtUserName").val()==""||$("#txtPassword").val()==""?($("#a").html("please fill all the fields").fadeIn(20).delay(2E3).fadeOut(1E3),!1):!0
}

function message(a){
    $("#txtUserName,#txtPassword").removeAttr("readonly");
    $("#f").delay(1E3).fadeOut(500,function(){
        $("#a").html(a).fadeIn(1E3).delay(2E3).fadeOut(1E3);
        $("#txtPassword").val("");
        $("#txtUserName").select();
    });
}