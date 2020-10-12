<h2 style="text-align: center;">Customer Outstand List</h2>
<link rel="stylesheet" href="<?=base_url()?>css/report.css" />
<script type="text/javascript" src="<?=base_url()?>js/r_customer_outstand.js"></script>
<div class="dframe" id="r_view2">
    <fieldset>
        <legend>Controls</legend>
        <!--Date : <input type="text" class="input_date_down_future" id="from" title="<?=date('Y-m-d')?>" />-->
        <!--To <input type="text" class="input_date_down_future" id="to" title="<?=date('Y-m-d')?>" />-->
        <!--&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;-->
        Paper Size :
        <select id="paper">
            <option value="l">Letter</option>
            <option value="a4">A4 Paper</option>
        </select>
        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
        Area : <?//=$area;?>
        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
        Route : <?//=$root;?>
        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
        Sales Ref : <?//=$ref;?>
        
        
        <!--<select id="type">-->
        <!--    <option value="det">Item List</option>-->
        <!--    <option value="sum">Price List</option>-->
        <!--</select>-->
    </fieldset>
    <fieldset>
        <legend>Report</legend>
        <div id="report_view" style="height: 400px; overflow: auto;"><?//=$report?></div>
        <div style="text-align: right; padding-top: 7px;">
                        <input type="button" id="btnExit" title="Exit" /><button id="print">Print</button></div>
    </fieldset>
</div>