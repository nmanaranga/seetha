<?php if($this->user_permissions->is_view('m_item_promotions')){ ?>
<script type='text/javascript' src='<?=base_url()?>js/m_item_promotions.js'></script>
<h2>Item Promotion Setup</h2>

<table width="100%" border="0">
	<tr>
		<td valign="top"  class="content"  style="width: 800px;">
			<div class="form" id="form">
				<form id="form_" method="post" action="<?=base_url()?>index.php/main/save/m_item_promotions" >
					
					
					<table style="width:600px" cellpadding="0" border="0">
						<tr>
							<td style="width:100px;"></td>
							<td style="width:100px;"></td>
							<td style="width:200px;text-align:right;" colspan="2"><span style="margin-right:12px;">No&nbsp;&nbsp;</span>
								<input type="text" class="input_active" title=<?php echo $max_no; ?>  id="nno" name="nno" style="width:130px;text-align: right;"></td>
							</tr>
							
							<tr>
								<td style="width:100px;"></td>
								<td style="width:100px;"></td>
								<td style="width:200px;text-align:right;" colspan="2"><span style="margin-right:12px;">Date</span>
									<input type="text" class="input_date_down_future" readonly="readonly" id="date" name="ddate" style="width:130px;text-align: right;" title="<?php echo date('Y-m-d')?>"></td>
								</tr>
								
							</table>
							<fieldset style="margin-bottom:15px;margin-top:10px;">
								<legend>Date Range</legend>
								<table style="width:500px;">
									<tr>
										<td>From</td>
										<td><input type="text" class="input_date_down_future"  readonly="readonly" id="date_from"  name="date_from" style="width: 150px;"></td>
										<td>&nbsp;</td>
										<td>To</td>
										<td><input type="text" class="input_date_down_future"  readonly="readonly" id="date_to"  name="date_to" style="width: 150px;"></td>
									</tr>
								</table>
							</fieldset>
							<table  id="tgrid" cellpadding='0'>
								<thead>
									<tr>
										<th width="150" class="tb_head_th">Item</th>
										<th width="350" class="tb_head_th">Description</th>
										<th width="100" class="tb_head_th">Promotion Type</th>
										<th width="300" class="tb_head_th">Note</th>
									</tr>
								</thead>
								<tbody>
									<?php

									for($x=0; $x<12; $x++){
										echo "<tr>";
										echo "<td style='width='90'><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
										<input type='text' class='g_input_txt fo' id='0_".$x."' name='0_".$x."' style='background:transparent;border:1px dotted #ccc;'  /></td>";
										echo "<td style='background-color: #f9f9ec;' '><input type='text' class='g_input_txt'  id='n_".$x."' name='n_".$x."'  style='background:transparent;border:1px dotted #ccc;' readonly='readonly' maxlength='100'/></td>";
										echo "<td>
										<select id='p_".$x."' name='p_".$x."'>
										<option value='0'>---</option>
										<option value='1'>FOC</option>
										<option value='2'>Back to Back</option>
										<option value='3'>Interest Free</option>
										<option value='4'>Credit Card</option>
										</select>
										</td>";
										echo "<td><input type='text' class='g_input_txt'  id='1_".$x."' name='1_".$x."'  style='background:transparent;border:1px dotted #ccc;'maxlength='150'/></td>";
										echo "</tr>";
									}
									?>

								</tbody>
								<tfoot>
									<tr style="background-color: transparent;">
										<td style="text-align: right; font-weight: bold; font-size: 12px;"></td>
										<td>&nbsp;</td>
									</tr>
								</tfoot>
							</table>
							<div style="height20px;"></div>
							<table style="width:100%">
								<tr>
									<td colspan="2" style="height:20px;"><hr class="hline"/></td>
								</tr>
								<tr>
									<td colspan="2" style="width: 40%" align="right">                            
										<input type="button" id="btnExit" title="Exit" />
										<?php if($this->user_permissions->is_add('m_item_promotions')){ ?><input name="button2" type="button" id="btnSave" title='Save <F8>' /><?php } ?>
											<input type="hidden" id="code_" name="code_" />   
											<input name="button" type="button" id="btnReset" title='Reset' /></td>
										</tr>

									</table>
								</form>
							</div>
						</td>
						<td id="items_table" valign="top"  class="content">
							<div class="form" id="form">
								<table>
									<tr>
										<td style="padding-right:64px;"><label>Search</label></td>
										<td><input type="text" class="input_txt" title='' id="srchee" name="srch" style="width:230px; marging-left:20px;"></td>
									</tr>
								</table> 

								<!--<table><tr><td><input type="checkbox" name="item"/></td><td><span>Show Selected Item Only</span></td></tr></table>-->
								<div id="grid_body"><?=$table_data;?></div>
							</div>
						</td>
					</tr>
				</table>
			</div>
			<?php } ?>
