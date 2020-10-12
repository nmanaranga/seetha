<?php if($this->user_permissions->is_view('t_serial_adjustment_sum')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/t_serial_adjustment_sum.js"></script>

<div id="fade" class="black_overlay"></div>


<h2 style="text-align: center;">Serial Number Adjustment</h2>
<div class="dframe" id="mframe" style="padding-right:5px;">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_serial_adjustment_sum" id="form_">
        <table style="width: 100%" border="0">
            <tr>
                
                <td style="width: 80px;">Store</td>
                <td>
                   <input type="text" class="input_txt" name="store" id="store"  style="width:150px;"/>
                   <input type="text" class="hid_value"  id="store_des" name="store_des"  title="" style="width: 251px;" />
                </td>
                
                <td style="width: 50px;">No</td>
                <td>
                    <input type="text" class="input_active_num" name="id" style="width:100%" id="id" title="<?=$id?>" />
                    <input type="hidden" id="hid" name="hid" title="0" />
                </td>
            </tr>

            <tr>
                <td style="width: 80px;">Item</td>
                <td>
                   <input type="text" class="input_txt" name="item" id="item" style="width:150px;"/>
                   <input type="text" class="hid_value"  id="item_des" name="item_des"  title="" style="width: 251px;" />
                </td>

                <td style="width: 100px;">Date</td>
                <td style="width: 100px;">
                    <?php if($this->user_permissions->is_back_date('t_serial_adjustment_sum')){ ?>
                        <input type="text" class="input_date_down_future" readonly="readonly" name="ddate" id="ddate" title="<?=date('Y-m-d')?>" style="text-align:right;" />
                    <?php } else { ?>
                        <input type="text" class="input_txt" readonly="readonly" name="ddate" id="ddate" title="<?=date('Y-m-d')?>"  style="text-align:right;" />
                    <?php } ?>    
                </td>
            </tr>

            <tr>
                <td>Batch</td>
                <td>
                    <select id="batch" name="batch">
                        <option value='1' class='selctbtch'>1 </option>
                    </select>
                   
                   <input type="button" id="load_serail" title="Load Serial Numbers" style="margin-left:95px; width:150px;">
                </td>
                <td>Adjustment No</td>
                <td style="display:none;"><input type="button" id="ad_load"/></td>
                <td><input type="text" class="input_active_num" name="adj_no" style="width:100%" id="adj_no"/></td>
            </tr>

            <tr>
                <td>Current Stock</td>
                <td>
                   <input type="text" class="hid_value" readonly="readonly" name="c_stock" id="c_stock" style="width:150px;"/>
                   <input type="hidden" class="input_active_num" name="c_stock2" id="c_stock2" style="width:150px;"/>
                   Stock Value 
                   <input type="text" class="hid_value" readonly="readonly"  id="stock_val" name="stock_val"  title="" style="width: 150px; margin-left:28px;" />
                </td>
            </tr>
                        
            <tr>
                <td colspan="4" style="text-align: center;">
                   <table style="width: 100%;" id="tgrid" >
                        <thead>
                            <tr>
                                <th class="tb_head_th" >Serial Number</th>
                                <th class="tb_head_th" >Other Number 1</th> 
                                <th class="tb_head_th" >Other Number 1</th>                                                            
                            </tr>
                            <input type='hidden' id='transtype' title='SERIAL ADJUSTMENT' value='SERIAL ADJUSTMENT' name='transtype' />
                        </thead>
                        </table>
                        <table>
                        <tbody>
                        <div id='tgrid1' style='height:185px; overflow-y:scroll; display:none;'>

                        </div>
                        
                        </tbody>
                        </table>
                        <div>
                        <table>
                            <tr>
                                <td>
                                    <input type="button" name="get_all" id="get_all" title="Gett All" style="margin-left:790px; width:100px;"/>
                                </td>
                            </tr>
                        </table>
                        </div> 

                        <div id="table_body2">
                        <hr>
                        <table>
                            <tr>
                                <td>
                                    New Stock 
                                    <input type="text" class="input_txt" name="n_stock_hid" id="n_stock_hid" style="width:150px; margin-left:17px;"/>
                                    Current Stock    
                                    <input type="text" class="hid_value" readonly="readonly" name="n_stock" id="n_stock" style="width:150px; margin-left:17px;"/>
                                    Stock Value 
                                    <input type="text" class="hid_value"  readonly="readonly" id="stock_val2" name="stock_val2"  title="" style="width: 150px; margin-left:28px;" />
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Serial No    
                                    <input type="text" class="input_active_num" name="add_serial" id="add_serial" style="width:150px; margin-left:24px; text-transform: uppercase;"/>
                                    Other No 01
                                    <input type="text" class="input_active_num"  id="add_other1" name="add_other1"  title="" style="width: 150px; margin-left:24px;" />
                                    Other No 02
                                    <input type="text" class="input_active_num"  id="add_other2" name="add_other2"  title="" style="width: 150px; margin-left:28px;" />
                                    <input type="button" name="add" id="add" title="Add" style="margin-left:63px; width:100px;"/>
                                </td>
                            </tr>   
                        </table>

                        <table id="tgrid3" style="width:100%">
                            <tr>
                                <td>
                                    <tr>
                                        <th class="tb_head_th" >Serial Number</th>
                                        <th class="tb_head_th" >Other Number 1</th> 
                                        <th class="tb_head_th" >Other Number 1</th>                                                            
                                        
                                     </tr> 
                                </td>
                            </tr>
                        </table>
                        <table>
                        <tbody>

                                    <div id='tgrid2' style='height:185px; overflow-y:scroll; display:none;'>
                                    <input type="hidden" class="cl2"/>
                                    </div>             
                                    
                             </tbody>
                        </table>
                        </div>  
                            
                        </tbody>
                    </table>
					
					<input type="hidden" title="0" name="no_row" id="no_row">
				<fieldset>
					<table>
						<legend>Account</legend>
							<tr>	
								<td>
                                    Credit Acc
                                </td>
								<td></td>
								<td>
                                    <input type="text" class="input_txt" title=''  id="cr_acc" name="cr_acc" style="width: 150px; margin-left:4px;">
                                    <input type="text" class="hid_value" title=''  id="cr_acc_des" name="cr_acc_ders" style="width: 250px;background-color:#f9f9ec !important">

                                </td>
							   
                            </tr>
							
							<tr>	
								<td>
                                    Debit Acc
                                </td>
								<td></td>
								<td>
                                    <input type="text" class="input_txt" title=''  id="dr_acc" name="dr_acc" style="width: 150px; margin-left:4px;">
                                    <input type="text" class="hid_value" title=''  id="dr_acc_des" name="dr_acc_ders" style="width: 250px;background-color:#f9f9ec !important">
                                </td>
							</tr>
                        </table>
					</fieldset>	

                <input type="hidden" name="srls" id="srls"/>
                <input type='hidden' id='transCode' value='14' title='14'/>
                <div style="text-align: right; padding-top: 7px; margin-right:2px;">
                    <input type="button" id="" title="Exit" />
                    <input type="button" id="btnReset" title="Reset" />
                    <!-- <input type="button" id="btnDelete" title="Cancel" /> -->
                    <?php if($this->user_permissions->is_re_print('t_serial_adjustment_sum')){ ?><input type="button" id="btnPrint" title="Print" /><?php } ?>
			        <?php if($this->user_permissions->is_add('t_serial_adjustment_sum')){ ?><input type="button" title='Save <F8>' value="Save" id="btnSave" /><?php } ?>
                    <input type="hidden" name='save_chk' value="0" title="0" id="save_chk" > 
                   
                      
                    <input type="hidden" id="po" name="po" title="0" />
            		<input type="hidden" id="response" name="response" title="0" />
            		<input type="hidden" id="reject" name="reject" title="0" />
                </div>
                
                </td>
            </tr>
        </table>
        <?php 
        if($this->user_permissions->is_print('t_serial_adjustment_sum')){ ?>
            <input type="hidden" name='is_prnt' id='is_prnt' value="1" title="1">
        <?php } ?> 
    </form>

    <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
                  
        <input type="hidden" name='by' value='t_damage_sum' title="t_damage_sum" class="report">
        <input type="hidden" name='page' value='A4' title="A4" >
        <input type="hidden" name='orientation' value='P' title="P" >
                 
        <input type="hidden" name='pdf_id' value='' title="" id="pdf_id" >
        <input type="hidden" name='qno' value='' title="" id="qno" >
        <input type="hidden" name='dd' title="<?=date('Y-m-d')?>" id="dd" >
        <input type="hidden" name='dt' value='' title="" id="dt" > 
     
    </form>

</div>
<?php } ?>