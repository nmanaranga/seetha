<?php //if($this->user_permissions->is_view('r_account_report')){ ?>
<h2 style="text-align: center;">Bank Entry List Report </h2>
<link rel="stylesheet" href="<?=base_url()?>css/report.css" />
<script type="text/javascript" src="<?=base_url()?>js/r_bank_entry.js"></script>

<div class="dframe" id="r_view2" style="width: 1000px;">
    <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
   
    <fieldset>
        <legend>Date</legend>
        <table>
            <tr>
                <td><font size="2">From</font></td>
                <td><input type="text" class="input_date_down_future" id="from" name="from" title="<?=date('Y-m-d')?>" style="width: 80px;" /></td>
                <td style="padding-left:40px;"><font size="2">To</font></td>
                <td><input type="text" class="input_date_down_future" id="to" name="to"title="<?=date('Y-m-d')?>" style="width: 80px;"  /></td>
            </tr>
        </table>
    </fieldset>    




    <fieldset >
        <legend >Category</legend>
        <div id="report_view" style="overflow: auto;">



        <table border="0" cellpadding="0" cellspacing="0" id="cl_bc" style="font-size: 12px;">        
             <tr>
                <td style="width:83px;">Cluster</td>
                <td><?php echo $cluster; ?></td>
            </tr>

            <tr>
                <td>Branch</td>
                <td>
                    <select name='branch' id='branch' >
                        <option value='0'>---</option>
                    </select>
                    <!-- <?php echo $branch; ?> -->
                </td>
             </tr>
                <tr>
                    <td>Status</td>
                    <td>
                        <select name='status' id='status' >
                            <option value='0'>---</option>
                            <option value='P'>Pending</option>
                            <option value='D'>Deposit</option>
                            <option value='R'>Return</option>
                        </select>
                        <!-- <?php echo $branch; ?> -->
                    </td>
                </tr>
        </table>



        </div>
       
        
        <table border="0" cellpadding="0" cellspacing="0" style="font-size: 12px;">

            <tr>
                <td><input type="radio" id="bank_entry_list" name="acc"/>Bank Entry List</td>
            </tr>
            <tr>
                <?php if($this->user_permissions->is_view('r_cheque_list_')){ ?>
                <td><input type="radio" id="r_cheque_list"  name="acc"/>Cheque List</td>
                <?php }?>
            </tr>

        </table>

        <div style="text-align: right; margin-top:10px; padding-top: 7px;">
        <button id="btnReset">Reset</button>    
        <button id="btnExit">Exit</button>
        <button id="print">Print</button></div>
    </fieldset>
         <input type="hidden" id='by' name='by'  class="report">
         <input type="hidden" name='page' value='A4' title="A4" >
         <input type="hidden" name='orientation' value='P' title="P" >
         <input type="hidden" id='type' name='type' value='' title="" >
         <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
         <input type="hidden" name='row_count' title="row_count" id="row_count">
         <input type="hidden" name='clusters' title="" id="clusters" >
         <input type="hidden" name='branchs' title="" id="branchs">
</form>
</div>

<?php //} ?>