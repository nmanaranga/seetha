<h2 style="text-align: center;">Sales Report</h2>
<link rel="stylesheet" href="<?=base_url()?>css/report.css" />
<script type="text/javascript" src="<?=base_url()?>js/r_sales.js"></script>
<div class="dframe" id="r_view2">
    <fieldset>
        <legend>Controls</legend>
        
        Paper Size :
        <select id="paper">
            <option value="l">Letter</option>
            <option value="a4">A4 Paper</option>
        </select>
        &nbsp; 
        Type :
        <select id="type">
            <option value="sum">Summary</option>
            <option value="det">Detail</option>
        </select>
        Category :
        <select id="category">
            <option value="cash">Cash</option>
            <option value="credit">Credit</option>
        </select>
        <br><br>
        Date : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="text" class="input_date_down_future" id="from" title="<?=date('Y-m-d')?>" />
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        To :&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="text" class="input_date_down_future" id="to" title="<?=date('Y-m-d')?>" />
    </fieldset>
    <fieldset>
        <legend>Report</legend>
        <div id="report_view" style="height: 400px; overflow: auto;"><?//=$report?></div>
        <div style="text-align: right; padding-top: 7px;">
                        <input type="button" id="btnExit" title="Exit" /><button id="print">Print</button></div>
    </fieldset>
</div>