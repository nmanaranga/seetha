<?php if($this->user_permissions->is_view('t_bankrec_bank_chg')){ ?>
<script type='text/javascript' src='<?=base_url()?>js/t_bankrec_bank_chg.js'></script>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<h2 id="form_name">Bank Chargers</h2>
<div class="dframe" id="mframe" style="margin-top:10px; width:984px; padding-right:25px;">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_bankrec_bank_chg" id="form_">
        <table style="width: 100%" border="0">
            <tr>
                <input type="hidden" id='rec_bank' name="rec_bank"/>
                <td style="width: 80px;"></td>
                <td></td>
                <td style="width: 50px;">No</td>
                <td>
                    <input type="text" class="input_active_num" id="id" name="id" title="" style="width:150px;" />
                    <input type="hidden" id="hid" name="hid" title="0" />
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="width: 50px;">Date</td>
                <td style="width: 50px;">
                    <?php if($this->user_permissions->is_back_date('t_bankrec_bank_chg')){ ?>
                    <input type="text" class="input_txt" readonly="readonly" name="date" id="date" title="" style="width:100%; text-align:right;"/>
                    <?php } else { ?>
                        <input type="text" class="input_txt" readonly="readonly" name="date" id="date" style="width:100%; text-align:right;" title="<?=date('Y-m-d')?>" />
                    <?php } ?> 
                </td>
                </tr>
            <tr>
            	<td colspan="4">
            			<table style="width:100%" id="tgrid" border="0">
							<thead>
								<tr>
                                    <th class="tb_head_th" style="width:12px">No</th>
									<th class="tb_head_th" style="width:100px">Date</th>
									<th class="tb_head_th" style="width:380px">Description</th>
									<th class="tb_head_th" style="width:200px">Remarks</th>
                                    <th class="tb_head_th" style="width:120px">Amount</th>
                                    <th class="tb_head_th" style="width:120px">Account</th>
                                    <th class="tb_head_th" style="width:190px">Acc Name</th>
								</tr>
							</thead>
							<tbody>
								<?php
                                    $y=1;
									for($x=0; $x<25; $x++){
										echo "<tr>";
										echo "<td ><input type='text' class='g_input_txt'  name='0_".$x."' id='0_".$x."' title='".$y."'style='width:100%;' />";
                                        echo "<td ><input type='text' class='g_input_txt input_date_down_future cl'  id='1_".$x."' readonly='readonly' name='1_".$x."' style='width:100%;'/></td>";
										echo "<td ><input type='text' class='g_input_txt des'  id='2_".$x."' name='2_".$x."' style='width:100%;'/></td>";
										echo "<td ><input type='text' class='g_input_txt'  id='3_".$x."' name='3_".$x."' style='width:100%;'/></td>";
                                        echo "<td ><input type='text' class='g_input_amo amo'  id='4_".$x."' name='4_".$x."' style='width:100%;'/></td>";	
										echo "<td ><input type='text' class='g_input_txt acc'  id='6_".$x."' name='6_".$x."' style='width:100%;'/></td>";     
                                        echo "<td ><input type='text' class='g_input_txt'  id='7_".$x."' name='7_".$x."' style='width:100%;'/></td>";
                                        echo "</tr>";
                                        $y++;
									}
														
								?>											
							</tbody>
						</table>
            	</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td style="text-align:right; width:100px; ">Net Amount</td>
                <td><input type="text" name='net' id ='net' class="hid_value g_input_amounts" style="font-size: 12px;font-weight: bold;width:130px;border: 1px solid #003399;"></td>
            </tr>
                
            <tr>
                <td colspan="2" style="text-align: center;">
                    <div style="text-align:left; padding-top: 7px;">
                    	<input type="button" id="btnExit" title="Exit" />
                    	<input type="button" id="btnReset" title="Reset" />
                        <?php if($this->user_permissions->is_re_print('t_bankrec_bank_chg')){ ?><input type="button" id="btnPrint" title="Print" /><?php } ?> 
                        <?php if($this->user_permissions->is_add('t_bankrec_bank_chg')){ ?><input type="button" id="btnSave" title="Save" /><?php } ?>
                    </div>
                </td>
            </tr>
        </table>
         <?php 
    if($this->user_permissions->is_print('t_bankrec_bank_chg')){ ?>
        <input type="hidden" name='is_prnt' id='is_prnt' value="1" title="1">
    <?php } ?> 
    </form>
    </form>
      <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">      
        <input type="hidden" name='by' value='t_bankrec_bank_chg' title="t_bankrec_bank_chg" class="report">
        <input type="hidden" name='page' value='A4' title="A4" >
        <input type="hidden" name='orientation' value='P' title="P" >                 
        <input type="hidden" name='pdf_id' value='' title="" id="pdf_id" >
        <input type="hidden" name='qno' value='' title="" id="qno" >
        <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
        <input type="hidden" name='dt' value='' title="" id="dt" > 
    </form>
</div>
<?php } ?>