<h2 style="text-align: center;">Age Analyze Report</h2>
<link rel="stylesheet" href="<?=base_url()?>css/report.css" />
<script type="text/javascript" src="<?=base_url()?>js/r_age_analyze_supplier.js"></script>
<div class="dframe" id="r_view2">
    <fieldset>
        <legend>Controls</legend>
        Paper Size :
        <select id="paper" style="">
            <option value="l">Letter</option>
            <option value="a4">A4 Paper</option>
        </select>
        &nbsp; &nbsp; &nbsp; &nbsp;
        Type :
        <select id="type" style="">
            <option value="sum">Summery</option>
            <option value="det">Detail</option>
        </select>
    </fieldset>
    <fieldset>
        <legend>Report</legend>
        <div id="report_view" style="height: 400px; overflow: auto;"><?//=$report?></div>
        <div style="text-align: right; padding-top: 7px;">
                        <input type="button" id="btnExit" title="Exit" /><button id="print">Print</button></div>
    </fieldset>
</div>