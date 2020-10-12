<?php if($this->user_permissions->is_view('039')){ ?>

<?php if($is_process=='')
{
echo '<div id="msg"><h1>Please do interest calculation process for date of '.$prv_date.'.</h1></div>';
}
else {
echo  '<script type="text/javascript">$(".sf-menu").css("display","block");</script>';   
}

?>

<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/t_interest_calculation.js"></script>
<h2 style="text-align: center;">Interest Calculation Process</h2>
<div class="dframe" id="mframe" style="text-align: center; width: 1250px;">
<style type="text/css">

    .sf-menu{
        display: none;
    }
    
    #msg{
    text-align : center;
    color: red;
    font-size: 25px;   
    }
    
    #blocker_ins {
	background-color:#000;
	opacity: 0.50;
	position:fixed;
	z-index: 10001;
	top:0px;
	left:0px;
	width:100%;
	height:1000px;
	display : block;
	text-align : center;
    }
    
    #view {
        background-color: #FFF;
        position: absolute;
        width: 460px;
        z-index: 10002;
        display: none;
        border: 5px solid #CCC;
        padding: 25px;
    }
    
    #img_tag {
        text-align: center;
        padding: 25px;
    }
    
    #img_tag img {
        border: 5px solid #FFF;
        box-shadow:0px 0px 5px #000;
        -webkit-box-shadow:0px 0px 5px #000;
        -moz-box-shadow: 0px 0px 5px #000;
    }
</style>
<div id="blocker_ins"></div>
<div id="view">
    Date : <input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" />
    <button id="btnProcess">Process</button><button id="btnCancel">Cancel</button>
</div>
<div class="form" style="margin: 10px;">
    <div id="progres"></div>
    <div id="img_tag"></div>
</div>
</div>
<?php } ?>