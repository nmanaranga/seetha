<html>
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link type="image/png" href="img/ico.png" rel="icon">
        <style type="text/css">
            h1 {
                color: red;
                text-align: center;
            }
            
            #code {
                font-size: 24px;
                text-align: center;
                padding: 25px;
            }
        </style>
        
        <script type="text/javascript" src="<?=base_url(); ?>js/jquery.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                setTimeout('reload()', 5000);
            });
            
            function reload(){
                $.post('/index.php/main/get_data/reg', {
                    
                }, function(r){
                    if(r == 1){
                        location.reload();
                    }else{
                        setTimeout('reload()', 5000);
                    }
                }, 'json');
            }
        </script>
    </head>
    <body>
        <h1>Access is forbidden. <br />Please contact admin & make permission</h1>
        <div id="code">Computer ID : <?php echo substr(get_cookie('softmast_curr_id'), 2, 5); ?></div>
    </body>
</html>