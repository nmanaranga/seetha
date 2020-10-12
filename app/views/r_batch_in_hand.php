
<h2 style="text-align: center;">Stock In Hand Report </h2>
<link rel="stylesheet" href="<?=base_url()?>css/report.css" />
<script type="text/javascript" src="<?=base_url()?>js/r_batch_in_hand.js"></script>

<div class="dframe" id="r_view2" style="width: 1100px;">
    <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
    <h3 style="font-family:Arial;color:#000;">Branch - <?php echo $branch; ?></h3>
    <fieldset>
            Select Stores - <?php echo $store_list; ?>

     </fieldset>    

    <fieldset>
        <legend>Report</legend>
        <div id="report_view" style="height: 400px; overflow: auto;"></div>
        <div style="text-align: right; padding-top: 7px;">
                      <button id="btnExit">Exit</button><button id="print">Print</button></div>
    </fieldset>
         <input type="hidden" name='by' value='r_stock_in_hand' title="r_stock_in_hand" class="report">
         <input type="hidden" name='page' value='A4' title="A4" >
         <input type="hidden" name='orientation' value='P' title="P" >
         <input type="hidden" name='type' value='r_stock_in_hand' title="r_stock_in_hand" >
         <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
</form>
</div>

