<html>
    <head>
        <title><?=$title;?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <link rel="stylesheet" href="<?=base_url()?>css/report.css" />
        <script type="text/javascript" src="<?=base_url(); ?>js/jquery.js"></script>
        
        <?php 
        if($cancel > 0){ ?>
            <script type="text/javascript">
                $(document).ready(function(){
                    //$("#greport").children().css("background-image", "url('../../../../img/cancel.png')");
                });
            </script>
        <?php } ?>
        
        <script type="text/javascript">
            $(document).keyup(function(e){
                if(e.keyCode == 80){
                    window.print();
                    window.close();
                }
            });
        </script>
    </head>
    <body>
        <?=$view;?>
    </body>
</html>