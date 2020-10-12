<?php 
	session_start();
?>
<html>
    <head>
        <style type="text/css">

            body{
                margin: 0px;
                overflow: hidden;
            }

            #dis {
                position: absolute;
                width : 100%;
                text-align : left;
                font-size : 14px;
                font-family: Times;
                font-weight: bold;
                color : #000;
                padding : 30px;
                border:1px solid #ccc;
                background-color: #f9f9f9;
                z-index: 10;
                
                -moz-box-shadow: 0px 0px 15px #AAA;
                -webkit-box-shadow: 0px 0px 15px #AAA;
            }
        </style>
        <script type="text/javascript" src="jquery.js"></script>
        <?php
            echo "<script type='text/javascript'> var link = '".$_GET['f'].".sql'</script>";
        ?>
        <script type="text/javascript" >
        var client_tb = new Array(); var pos = 0; var start = false;
        var lim = 0; var P = 0;
        var num_of_tables = 0;
            $(document).ready(function(){
               // setDiv(); 
                c_start();
            });
            
            function c_start(){
                $.post("client.php", {
                    action : 'get_tables'
                }, function(res){
                    client_tb = res;
                    num_of_tables = client_tb.length;    


//                    $("#divProgOuter").css("width",( num_of_tables  * 10) + "px")


                    check_tables();
                }, "json");
            }
            
            function check_tables(){

                var q = {};
                
                location.href.split('?')[1].split('&').forEach(function(i){
                    q[i.split('=')[0]]=i.split('=')[1];
                });                

                $("#dis").html("Backup Table : "+client_tb[pos]);
                $.post("client.php", {
                    action : 'get_query',
                    tb : client_tb[pos],
                    limit : lim,
                    start : start,
                    f:q.f
                }, function(res){
                    if(res["change"] == true){
                        if(res["finish"] == true){
                            pos++;
                            if(client_tb[pos] != undefined){
                                setTimeout("check_tables()", 500);
                                lim = 0;
                            }else{
                                $("#dis").html("Backup Complete");
                                $("#divProgInner").html("");
                                setTimeout("download()", 3000);
                            }
                        }else{
                            if(res["error"] == true){
                                alert("There is some error");
                            }else{
                                var cur_tb = client_tb[pos];
                                client_tb.splice (0,1);
                                client_tb.push(cur_tb);
                                
                                setTimeout("check_tables()", 500);
                            }
                        }
                    }else{
                        lim += 5000;
                        $("#dis").html("Backup Table : "+client_tb[pos]+" ("+lim+")");
                        setTimeout("check_tables()", 500);
                    }
                    
                    start = res.start;
                    P = parseInt ( ( (pos + 1) / num_of_tables) * 100) - 1;
                    
                    $("#presentageText").html((P) + "%");
                    //$("#divProgInner").css("width",(pos * 10) + "px" );
                    $("#divProgInner").html("<img src='loading.gif' >");

                }, "json");
            }
            
            function download(){
                window.open("download.php?f="+link);
            }
            
            function setDiv(){
                var h_d = $("#dis").height();
                var h_w = $(window).height();
                
                
                var w_d = $("#dis").width();
                var w_w = $(window).width();
                
                $("#dis").css("top", ((h_w-h_d)/2)-100);
                $("#dis").css("left", ((w_w-w_d)/2));
            }
        </script>
        <title>Backup MySQL</title>
    </head>
    <body>
    
    
    
    
        <div id="divProgOuter" style='position:absolute;border:0px solid red;width:470px;40px;height:50px;left:50%;margin:auto; top:50%; margin-left:-235px;margin-top:-20px;'>
            <div id="divProgInner" style="border:0px solid blue; width:470px;height:40px;"></div>            
        </div>        


        <div style='padding:10px;'>Database backup for <?php echo $_SERVER['HTTP_HOST']; ?></div>
        <div id="presentageText" style='z-index:110;font-family:Trebuchet MS;padding-top:45px;padding-right:20px;font-size:55px;text-align:right;width:200px; height:100px;right:0px; top:0px; position:absolute'></div>
        <div style="font-family:Trebuchet MS;" id="dis">Loding...</div>

    </body>
</html>