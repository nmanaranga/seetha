<?php if($this->user_permissions->is_view('t_bankrec')){ ?>
<script type='text/javascript' src='<?=base_url()?>js/t_bankrec.js'></script>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<h2>Bank Reconcilation</h2>
<div class="dframe" id="mframe" style="margin-top:10px; width:984px; padding-right:25px;">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_bankrec" id="form_">
        <table style="width: 100%" border="0">
            <tr>
                <td style="width: 80px;">Bank</td>
                <td><input type="text" class="input_txt" id="bank_id" name="bank_id" title="" style="width:100px;" />
                    <input type="text" id="bank" name="bank" class="hid_value" style="width:300px;" maxlength="255" readonly="readonly"/>
                </td>
                <td style="width: 50px;">No</td>
                <td>
                    <input type="text" class="input_active_num" id="id" name="id" title="<?php echo $id?>" style="width:150px;" />
                    <input type="hidden" id="hid" name="hid" title="0" />
                </td>
            </tr><tr>
                <td>Date From </td>
                <td><input type="text" class="input_date_down_future" style="text-align:right;" readonly="readonly" name="date_from" id="date_from" style="width:100px;" title="" />
                	<span style="margin-left:20px;">Date To</span>
                	<input type="text" class="input_date_down_future" style="text-align:right;" readonly="readonly" name="date_to" id="date_to" style="width:100px;" title="" />
                	<span style="margin-left:30px;"><input type="button" title="Load Details" id="load" /></span>
                </td>
                <td style="width: 50px;">Date</td>
                <td>
                    <?php if($this->user_permissions->is_back_date('t_credit_card_reconcil')){ ?>
                    <input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" style="width:100%; text-align:right;"/>
                    <?php } else { ?>
                    <input type="text" class="input_txt" readonly="readonly" name="date" id="date" style="width:100%; text-align:right;" title="<?=date('Y-m-d')?>" />
                    <?php } ?> 
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td style="width: 100px;">Opening Balance</td>
                <td style="width: 100px;"><input type="text" class="input_txt" readonly="readonly" name="op_balance" id="op_balance" title="" style="text-align:right;"/></td>
            </tr>

            <tr>
            	<td colspan="4">
                   <table style="width:100%" id="tgrid" border="0">
                       <thead>
                        <tr>
                         <th class="tb_head_th" style="width:100px">Date</th>
                         <th class="tb_head_th" style="width:400px">Description</th>
                         <th class="tb_head_th" style="width:60px">Trans Code</th>
                         <th class="tb_head_th" style="width:200px">Trans Code</th>
                         <th class="tb_head_th" style="width:70px">NO</th>
                         <th class="tb_head_th" style="width:70px">Dr</th>
                         <th class="tb_head_th" style="width:70px">Cr</th>
                         <th class="tb_head_th" style="width:70px">Reconcile</th>
                     </tr>
                 </thead>
                 <tbody>
                    <?php

                    for($x=0; $x<200; $x++){
                      echo "<tr>";
                      echo "<td ><input type='hidden' name='auto_".$x."' id='auto_".$x."' title='0'style='width:100%;' />
                      <input type='hidden' name='code_".$x."' id='code_".$x."' title='0'style='width:100%;' />
                      <input  type='text' class='g_input_txt' id='date_".$x."' name='date_".$x."'style='width:100%;' /></td>";
                      echo "<td ><input type='text' class='g_input_txt'  id='description_".$x."' readonly='readonly' name='description_".$x."' style='width:100%;'/></td>";
                      echo "<td ><input type='text' class='g_input_txt'  id='tc_".$x."' name='tc_".$x."' readonly='readonly' style='width:100%;'/></td>";
                      echo "<td ><input type='text' class='g_input_txt'  id='tcd_".$x."' name='tcd_".$x."' readonly='readonly' style='width:100%;'/></td>";				
                      echo "<td ><input type='text' class='g_input_txt'  id='no_".$x."' name='no_".$x."' readonly='readonly' style='text-align:right;' style='width:100%;'/></td>";
                      echo "<td ><input type='text' class='g_input_txt bl'  id='dr_".$x."' name='dr_".$x."' readonly='readonly' style='text-align:right;' style='width:100%;'/></td>";
                      echo "<td ><input type='text' class='g_input_txt'  id='cr_".$x."' name='cr_".$x."'readonly='readonly' style='text-align:right;' style='width:100%;'/></td>";
                      echo "<td align='center' ><input type='checkbox' class='chk'  id='reconz_".$x."' name='reconz_".$x."'readonly='readonly'style='width:100%;'/></td>";
                      echo "</tr>";
                  }

                  ?>											
              </tbody>
          </table>
      </td>
  </tr>
  <tr>
    <td style="width:180px;">
        Undifined Depost &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type='button' id='un_deposit' title="..." />
    </td>
    <td colspan="4">
        <span style="margin-left:328px;"><b>Total</b></span>
        <input type="text" class="hid_value g_input_amounts" name="total_no" id="total_no" style="width:100px;border: 1px solid #003399;margin-left:50px;"/>
        <input type="text" class="hid_value g_input_amounts" name="total_dr" id="total_dr" style="width:100px;border: 1px solid #003399;margin-left:5px;"/>
        <input type="text" class="hid_value g_input_amounts" name="total_cr" id="total_cr" style="width:100px;border: 1px solid #003399;margin-left:5px;"/>         
    </td>
</tr>
<tr>
    <td style="width:180px;">
        Errors Made By The Bank
        <input type='button' id='er_bank' title="..." />
    </td>
    <td colspan="4">
        <span style="margin-left:329;"><input type="checkbox"  checked="true" disabled="true"></span>
        <input type="text" class="hid_value g_input_amounts" name="chk_no" id="chk_no" style="width:100px;border: 1px solid #003399;margin-left:56px;"/>
        <input type="text" class="hid_value g_input_amounts" name="chk_dr" id="chk_dr" style="width:100px;border: 1px solid #003399;margin-left:5px;"/>
        <input type="text" class="hid_value g_input_amounts" name="chk_cr" id="chk_cr" style="width:100px;border: 1px solid #003399;margin-left:5px;"/>         
    </td>
</tr>
<tr>
    <td style="width:180px;">
        Bank Chargers&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type='button' id='bank_chg' title="..." />
    </td>
    <td colspan="4">
        <span style="margin-left:330px;"><input type="checkbox"  disabled="true"></span>
        <input type="text" class="hid_value g_input_amounts" name="unchk_no" id="unchk_no" style="width:100px;border: 1px solid #003399;margin-left:56px;"/>
        <input type="text" class="hid_value g_input_amounts" name="unchk_dr" id="unchk_dr" style="width:100px;border: 1px solid #003399;margin-left:5px;"/>
        <input type="text" class="hid_value g_input_amounts" name="unchk_cr" id="unchk_cr" style="width:100px;border: 1px solid #003399;margin-left:5px;"/>         
    </td>
</tr>
<tr>
    <td colspan="2" style="text-align: center;">
        <div style="text-align:left; padding-top: 7px;">
         <input type="button" id="btnExit" title="Exit" />
         <input type="button" id="btnReset" title="Reset" />
         <input type="hidden" name='temp_save' id='temp_save' value="0" title="0">
         <?php if($this->user_permissions->is_delete('t_bankrec')){ ?><input type="button" id="btnDelete" title="Delete" /><?php } ?>
         <?php if($this->user_permissions->is_re_print('t_bankrec')){ ?><input type="button" id="btnPrint" title="Print" /><?php } ?> 
         <?php if($this->user_permissions->is_add('t_bankrec')){ ?><input type="button" id="btnSave" title="Save" /><?php } ?>
         <?php if($this->user_permissions->is_add('t_bankrec')){ ?><input type="button" id="btnSaveTemp" title="Tempory Save" /><?php } ?>
     </div>
 </td>
 <td>Closing Balance</td>
 <td style="width:150px;">
     <input type="text" class="input_txt_f" id="cl_balance" name="cl_balance" title="" readonly="readonly"/>
 </td>
</tr>
</table>
<?php 
if($this->user_permissions->is_print('t_bankrec')){ ?>
<input type="hidden" name='is_prnt' id='is_prnt' value="1" title="1">
<?php } ?> 
</form>
</form>
<form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">      
    <input type="hidden" name='by' value='t_bankrec' title="t_bankrec" class="report">
    <input type="hidden" name='page' value='A4' title="A4" >
    <input type="hidden" name='orientation' value='P' title="P" >                 
    <input type="hidden" name='pdf_id' value='' title="" id="pdf_id" >
    <input type="hidden" name='qno' value='' title="" id="qno" >
    <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
    <input type="hidden" name='dt' value='' title="" id="dt" > 
</form>
</div>
<?php } ?>