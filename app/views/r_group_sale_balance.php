<?php if($this->user_permissions->is_view('r_group_sale_balance')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type='text/javascript' src='<?=base_url()?>js/r_group_sale_balance.js'></script>
<h2>Group Sales Balance</h2>
<table width="96%" border="0" cellpadding="10" align="center" >
    <tr>
        <td valign="top" class="content" style="width:600px;">
        <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank"> 
            <fieldset>
                <legend>Date</legend>
                <table>
                    <tr>
                        <td><font size="2">From</font></td>
                        <td><input type="text" class="input_date_down_future" id="from" name="from" title="<?=date('Y-m-d')?>" style="width: 80px; text-align:right;" /></td>
                        <td style="padding-left:40px;"><font size="2">To</font></td>
                        <td><input type="text" class="input_date_down_future" id="to" name="to"title="<?=date('Y-m-d')?>" style="width: 80px; text-align:right;"  /></td>
                    </tr>
                </table>
            </fieldset>
                <fieldset>
                    <legend><b>Group Sales</b></legend>
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
                            <td>Code</td>
                            <td>
                                <input type="text" class="input_active" id="code" name="code"  title="" style="width:150px;" maxlength="" />
                                <input type="text" class="hid_value" readonly="readonly" title='' id="des"   style="width:250px;" maxlength="255" readonly='readonly'/>
                            </td>
                         </tr>
                    </table>
                 <input type="hidden" name='by' value='r_group_sale_balance' title="r_group_sale_balance" class="report">
                 <input type="hidden" name='page' value='A4' title="A4" >
                 <input type="hidden" name='orientation' value='P' title="P" >
            
                </form>
                   <div style="text-align: right; margin-top:10px; padding-top: 7px;">
                        <input type="button" value="Reset" title="Reset" id="btnReset"/>  
                        <input type="button" id="btnExit" title="Exit" />  
                        <input type="button" value="Print" title="Print" id="btnprint"/>
                  </div> 
            </fieldset> 
</fieldset>    
    </td>
</tr>
</table>
      
             

<?php } ?>