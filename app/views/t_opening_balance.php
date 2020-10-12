<?php if($this->user_permissions->is_view('t_opening_balance')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/nprogress.css" />
<script type="text/javascript" src="<?=base_url()?>js/t_opening_balance.js"></script>
<h2>Opening Balance</h2>
<div class="dframe" id="mframe" style="width:980px;" >
    <form method="post" action="<?=base_url()?>index.php/main/save/t_opening_balance" id="form_">
        <table style="width: 100%" border="0">
         
                
              <td width="100" style="width: 100px;">&nbsp;</td>
              <td>&nbsp;</td>

              <td style="display:none;"></td>
              <td>No</td>
              <!-- <td><input type="button" name="a_id" id="a_id" title="Load Opening balance" style="width:150px;"/></td> -->
              <td width="144">  <input type="text" style='width: 150px;' class="input_active_num" name="id" id="id" title="<?=$max_no?>" />
            <input type="hidden" id="hid" name="hid" title="0" />            
            </tr>
            <tr>
                <td style="width: 100px;">&nbsp;</td>
                <td width="638">&nbsp;</td>
                <td width="51"><span style="width: 100px;">Date</span></td>
                <td>
                    <?php if($this->user_permissions->is_back_date('t_opening_balance')){ ?>
                        <input type="text" readonly="readonly" style='width: 150px; text-align:right;' name="date" id="date" title="<? echo $open_bal_date; ?>" />
                    <?php } else { ?>
                        <input type="text" class="input_txt" readonly="readonly" style='width: 150px; text-align:right;' name="date" id="date" title="<?=date('Y-m-d')?>" />
                    <?php } ?> 
                </td>    
            </tr>
			<tr>
                <td style="width: 100px;">Note</td>
                <td width="638">
                    <input type="text" class="input_txt" name="note" id="note" title="" style="width: 360px;" maxlength="50" />
                </td>
                <td width="51"><span style="width: 100px;">Ref no</span></td>
                <td><input type="text" class="input_txt" name="ref_no" id="ref_no" title="" style="width: 150px; text-align:right;" maxlength="10"/></td>
            </tr>
          <tr>
                <td colspan="5" style="text-align: center;">
                	<div style="height:310px;">
                    <table>
                        <tr>
                            <td style="width: 649px;">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td style="width: 80px;">Privious Total</td>
                            <td style="width: 100px;"><input type='text' id='p_dr' class="input_active g_input_amo" style="width:100%"></td>
                            <td style="width: 100px;"><input type='text' id='p_cr' class="input_active g_input_amo" style="width:100%"></td>
                        </tr>
                    </table>
                    <table style="width: 980px;" id="tgrid">
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width: 80px;">Code</th>
                                <th class="tb_head_th">Description</th>
                                <th class="tb_head_th" style="width: 80px;">Type</th>
                                <th class="tb_head_th" style="width: 80px;">Dr Amount</th>
                                <th class="tb_head_th" style="width: 80px;">Cr Amount</th>
                            </tr>
                        </thead><tbody>
                            <?php
                                for($x=0; $x<100; $x++){
                                    echo "<tr>";
                                        echo "<td><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                                                <input type='text' class='g_input_txt fo' id='0_".$x."' name='0_".$x."'  /></td>";
                                        echo "<td style='background-color: #f9f9ec;'><input type='text' class='g_input_txt'  id='n_".$x."' name='n_".$x."' maxlength='150' readonly='readonly' style='width:100%'/></td>";
                                        echo "<td style='background-color: #f9f9ec;'><input type='text' class='g_input_txt' readonly='readonly'  id='3_".$x."' name='3_".$x."' />
                                              <input type='hidden' class='g_input_txt' readonly='readonly'  id='4_".$x."' name='4_".$x."' /></td>";
                                        echo "<td><input type='text' class='g_input_amo dr' id='1_".$x."' name='1_".$x."' /></td>";
                                        echo "<td><input type='text' class='g_input_amo cr' id='2_".$x."' name='2_".$x."' /></td>";
                                        
                                        
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div> 
                <br>               </td>
            </tr>
            <tfoot>
            	<td colspan="5">
                    <input type="button" id="btnExit" title="Exit" />
                    <input type="button" id="btnReset" title="Reset" />
                    <?php if($this->user_permissions->is_delete('t_opening_balance')){ ?>
                    <!-- <input type="button" id="btnDelete1" title="Delete" /> -->
                    <?php } ?>
                    <?php if($this->user_permissions->is_re_print('t_opening_balance')){ ?>
                    <input type="button" id="btnPrint" title="Print" />
                    <?php } ?>
                    <?php if($this->user_permissions->is_add('t_opening_balance')){ ?>
                    <input type="button"  id="btnSave1" title='Save <F8>' />
                    <?php } ?>
                    <?php if($this->user_permissions->is_approve('t_opening_balance')){ ?>
                    <input type="button"  id="btnApprove" title='Approve' />
                    <?php } ?>
                    <span style=" font-weight: bold; margin-left:342px;">    
		                 Total
                		<input type="text" class="g_input_amo" name="tot_dr" id="tot_dr" title="0.00" style=" font-weight: bold; width:140px;text-align:right;"/>
                		<input type="text" class="g_input_amo" name="tot_cr" id="tot_cr" title="0.00" style="  font-weight: bold; width:111px;text-align:right;"/>
                    </span>
                    
                    <span style=" font-weight: bold; margin-left:674px;">
                        Balance
                        <input type="text" class="g_input_amo" title="0.00" name="balance" id="balance" style=" font-weight: bold; width:123px;text-align:right;"/>
        		    </span>   
                </td>
           </tfoot>
           
            <?php 
                if($this->user_permissions->is_print('t_opening_balance')){ ?>
                <input type="hidden" name='is_prnt' id='is_prnt' value="1" title="1">
            <?php } ?> 
        </table>
    </form>
</div>
<form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">

    <input type="hidden" name='by' value='t_opening_balance' title="t_opening_balance" class="report">
    <input type="hidden" name='page' value='A4' title="A4" >
    <input type="hidden" name='orientation' value='P' title="P" >
    <input type="hidden" name='type' value='' title="" >
    <input type="hidden" name='header' value='false' title="false" >
    <input type="hidden" name='qno' value='' title="" id="qno" >
    <input type="hidden" name='rep_sup' value='' title="" id="rep_sup" >
    <input type="hidden" name='rep_ship_bc' value='' title="" id="rep_ship_bc" >
    <input type="hidden" name='inv_date' value='' title="" id="inv_date" >
    <input type="hidden" name='inv_nop' value='' title="" id="inv_nop" >
    <input type="hidden" name='po_nop' value='' title="" id="po_nop" >
    <input type="hidden" name='po_dt' value='' title="" id="po_dt" >
    <input type="hidden" name='credit_prd' value='' title="" id="credit_prd" >
    <input type="hidden" name='rep_deliver_date' value='' title="" id="rep_deliver_date" >
    <input type="hidden" name='jtype' value='' title="" id="jtype" >
    <input type="hidden" name='jtype_desc' value='' title="" id="jtype_desc" >

</form>
<?php } ?>