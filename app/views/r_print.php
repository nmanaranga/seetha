<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?=$title?></title>
        <link rel="stylesheet" href="<?=base_url()?>css/report.css" />
        <script type="text/javascript" src="<?=base_url()?>js/jquery.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                window.print();
                //window.close();
            });
        </script>
    </head>
    <body>
        <?=$report?>
    </body>
</html>