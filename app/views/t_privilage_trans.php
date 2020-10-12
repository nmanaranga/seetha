<?php if($this->user_permissions->is_view('t_privilage_trans')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type='text/javascript' src='<?=base_url()?>js/t_privilege_card.js'></script>
<h2>Privilage Card Dash Board</h2>
<div class="dframe" id="mframe" style="padding-right:25px;">
	
    <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/t_privilege_card" >
	<table style="width:100%;" id="tbl1" border="0">
    <tr>
        <td>Card No</td>
		<td colspan="3"><input type="text" id="card_no" name="card_no" title=" " style="width:150px;" class="input_txt"/></td>
    </tr>

    <tr>
    	<td>ID</td>
    	<td width="100"><input type="text" class="input_txt" id="customer_id" name="customer_id" title="" style="width: 150px;" /></td>
    	<td colspan="2">
		<input type="text" id="id" name="id" title="" style="width:350px;" class="hid_value"/>
	</td>
    </tr>

    <tr>
    	<td>Address</td>
    	<td colspan="3"><input type="text" id="address" name="address" title="" style="width:505px;" class="input_txt"/>
    </tr>

     <tr>
    	<td>TP</td>
    	<td><input type="text" id="tp" name="tp" title="" style="width:150px;" class="input_txt"/></td>
    	<td align="right">Email</td>
    	<td align="left"><input type="text" id="email" name="email" title="" style="width:284px;" class="input_txt"/></td>
    </tr>
	</table>

	<table style="width:100%;"  border="0">
		<tr>
			<td>

				<fieldset style="width:250px;">
					<legend>Points</legend>
						<table>
							<tr>
								<td>Earned</td>
								<td><input type="text" id="earned" name="earned" class="input_txt tb_row_tdr" /></td>
							</tr>
							<tr>
								<td>Used</td>
								<td><input type="text" id="used" name="used" class="input_txt tb_row_tdr" /></td>
							</tr>
							<tr>
								<td>Balance</td>
								<td><input type="text" id="balance" name="balance" class="input_txt tb_row_tdr" /></td>
							</tr>
						</table>
				</fieldset>

			</td>
			<td rowspan="2">
				<fieldset style="margin-top:110px;z-index:9;background:transparent;">
					<legend>Invoice History</legend>
					<table style="width:200px;" valign="top" id="tgrid">
					<thead>
						<tr>
							<th class="tb_head_th" >Inv No</th>
							<th class="tb_head_th" >Date</th>
							<th class="tb_head_th" >Amount</th>

						</tr>
					</thead>
					<tbody>
						<?php
													   
							for($x=0; $x<15; $x++){
								echo "<tr>";
								echo "<td><input type='hidden' name='code_".$x."' id='code_".$x."' title='0' />
															<input style='background-color: #f9f9ec;' type='text' class='g_input_txt tb_row_tdr txt_align' id='invno_".$x."' name='invno_".$x."' /></td>";
															echo "<td style='background-color: #f9f9ec;' ><input type='text' class='g_input_txt tb_row_tdr txt_align'  id='date".$x."' name='date_".$x."'/></td>";				
															echo "<td style='background-color: #f9f9ec;' ><input type='text' class='g_input_txt tb_row_tdr txt_align'  id='amount_".$x."' name='amount".$x."'/></td>";				
								echo "</tr>";
							}
												
						?>											
					</tbody>
				</table>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td>
				<fieldset>
					<legend>Points History</legend>
					
						<table style="width:100%" id="tgrid2">
					<thead>
						<tr>
							<th class="tb_head_th">TR Code</th>
							<th class="tb_head_th">TR No</th>
							<th class="tb_head_th">Date</th>
							<th class="tb_head_th">+Points</th>
							<th class="tb_head_th">-Points</th>
						</tr>
					</thead>
					<tbody>
						<?php
													   
							for($x=0; $x<10; $x++){
								echo "<tr>";
								echo "<td><input type='hidden' name='code_".$x."' id='code_".$x."' title='0' />
								<input style='background-color: #f9f9ec;' type='text' class='g_input_txt tb_row_tdr txt_align' id='trcode_".$x."' name='trcode_".$x."' /></td>";
								echo "<td style='background-color: #f9f9ec;' ><input type='text' class='g_input_txt tb_row_tdr txt_align'  id='trno_".$x."' name='trno_".$x."'/></td>";
								echo "<td style='background-color: #f9f9ec;' ><input type='text' class='g_input_txt tb_row_tdr txt_align'  id='ddate".$x."' name='ddate_".$x."'/></td>";				
								echo "<td style='background-color: #f9f9ec;' ><input type='text' class='g_input_txt tb_row_tdr txt_align'  id='point1".$x."' name='point1".$x."'/></td>";				
															echo "<td style='background-color: #f9f9ec;' ><input type='text' class='g_input_txt tb_row_tdr txt_align'  id='point2_".$x."' name='point2_".$x."'/></td>";	
								
								echo "</tr>";
							}
												
						?>											
					</tbody>
					<tfoot>
						<tr>
							<td colspan="5">
								<input type="hidden" id="code_" name="code_" title="0" />
								<input type="button" id="btnExit" title='Exit' />
								<?php if($this->user_permissions->is_add('t_privilage_trans')){ ?><input type="button" id="btnSave1" title='Save <F8>' /><?php } ?>
								<input type="button" id="btnReset" title='Reset'></td>
						</tr>
					</tfoot>
						</table>
					
				</fieldset>
			</td>
		</tr>



	</table>


</table><!--tbl1-->
</form><!--form_-->

</div>
<?php } ?>