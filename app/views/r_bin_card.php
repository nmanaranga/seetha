
<h2 style="text-align: center;">Bin Card</h2>
<link rel="stylesheet" href="<?=base_url()?>css/report.css" />
<script type="text/javascript" src="<?=base_url()?>js/r_bin_card.js"></script>
<div class="dframe" id="r_view2">
    <fieldset>
        <legend>Controls</legend>
	<form action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
        Date : <input type="text" class="input_date_down_future" id="from" name="from_date" title="<?=date('Y-m-d')?>" style="width: 80px;" />
        To <input type="text" class="input_date_down_future" id="to" name="to_date" title="<?=date('Y-m-d')?>" style="width: 80px;"  />
        &nbsp; &nbsp;
        <!-- Paper Size :  //$rep;
        <!--<select id="paper" style="width: 100px;">
            <option value="l">Letter</option>
            <option value="a4">A4 Paper</option>
        </select>-->
        &nbsp; &nbsp;
        Stores : <?php //$stores; ?>
        &nbsp; &nbsp;
        Item Code : <input type="text" class="input_txt" id="item_code" name="item_code" title="Item Code" style="width: 130px;" />
        <input type="hidden" id="item_id" name="item_id" title="0" />
		Order
		     <select name="select" id="sort_by">
               <option value="date">---</option>
               <option value="date">Date</option>
               <option value="in_quantity">Qty In</option>
               <option value="trance_type">Trans Code</option>
               <option value="trance_id">Trans No</option>
             </select> 
		
	    <input type="hidden" name="by" id="by" value="r_bin_card">
            <input type="hidden" name="format" id="format" value="2">
            <input type="hidden" name="page" id="page" value="A4">
            <input type="hidden" name="headr" id="headr" value="true">
            <input type="hidden" name="orientation" id="orientation" value="P">
    </fieldset>
    <fieldset>
        <legend>Report</legend>
        <div id="report_view" style="height: 400px; overflow: auto;"><?php //$report;?></div>
        <div style="text-align: right; padding-top: 7px;"><button id="btnExit">Exit</button><button id="print">Print</button></div>
    </fieldset>
    </form>
</div>
