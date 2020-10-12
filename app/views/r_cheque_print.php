<?php if($this->user_permissions->is_view('r_cheque_print')){ ?>
<h2 style="text-align: center;">Cheques Report </h2>
<link rel="stylesheet" href="<?=base_url()?>css/report.css" />
<script type="text/javascript" src="<?=base_url()?>js/r_cheque_print.js"></script>

<div class="dframe" id="r_view2" style="width: 1100px;">
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

    <fieldset>
        <legend>Category</legend>
        <div id="report_view" style="overflow: auto;">

           <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size: 12px;">
                        
                        <tr>
                            <td>Cluster</td>
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

                       
                        <input type="hidden" id="d_cl" title='<?php echo $d_cl ?>' name="d_cl"/>
                        <input type="hidden" id="d_bc" title='<?php echo $d_bc ?>' name="d_bc"/>
                        <tr>
                            <td>Type</td>
                            <td>
                               <select name='cheque_list' id='cheque_list' >
                                <option value='0'>---</option>
                                <option value='1'>Pending Cheque List</option>
                                <option value='2'>Printed Cheque List</option>
                                </select>

                               <!-- <?php echo $branch; ?> -->
                            </td>

                        </tr>


                        <?php if($this->user_permissions->is_view('r_cheque_print')){ ?>
                    <tr>
                    <td colspan="3">
                      <input type='radio' name='by' value='r_cheque_print' title="r_cheque_print" id="r_cheque_print" class="report" checked="checked"/>Cheque Report
                    </td>
                    </tr>
                    <?php } ?>

                    
                    </table>
        </div>
        <div style="text-align: right; padding-top: 7px;">
        

        <button id="btnExit">Exit</button>
        <button id="print">Print</button></div>
    </fieldset>
         

        

         <input type="hidden" id='by' name='by' value='' title="" class="report">
         <input type="hidden" name='page' value='A4' title="A4" >
         <input type="hidden" name='orientation' value='P' title="P" >
         <input type="hidden" id='type' name='type' value='' title="" >
         <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >


</form>
</div>

<?php } ?>