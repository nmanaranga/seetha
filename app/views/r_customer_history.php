<h2 style="text-align: center;">Customer History</h2>
<link rel="stylesheet" href="<?=base_url()?>css/report.css" />
<script type="text/javascript" src="<?=base_url()?>js/r_customer_history.js"></script>
<div class="dframe" id="r_view2">
    <fieldset>
        <legend>Controls</legend>
        From : <input type="text" class="input_date_down_future" id="from" title="<?=date('Y-m-d')?>" style="" />
        To :<input type="text" class="input_date_down_future" id="to" title="<?=date('Y-m-d')?>" style=""  />
        &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
        Paper Size :
        <select id="paper" style="">
            <option value="l">Letter</option>
            <option value="a4">A4 Paper</option>
        </select>
        <!--&nbsp; &nbsp;-->
        &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;
        Customer Code : <input type="text" class="input_txt" id="customer_code" title="Customer Code" style="width: 150px;" />
        <input type="hidden" id="customer" title="0" />
    </fieldset>
    <fieldset>
        <legend>Report</legend>
        <div id="report_view" style="height: 400px; overflow: auto;"><?//=$report?></div>
        <div style="text-align: right; padding-top: 7px;">
                        <input type="button" id="btnExit" title="Exit" /><button id="print">Print</button></div>
    </fieldset>
</div>