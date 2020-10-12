<?php if($this->user_permissions->is_view('chq_pending_list')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<link href="<?=base_url()?>css/t_forms.css" type="text/css" rel="stylesheet">
<script type='text/javascript' src='<?=base_url()?>js/chq_pending_list.js'></script>
<h2>Pending Cheque Print</h2>
<div class="dframe" id="mframe" style='width:1160px;'>
<form id="form_" method="post" action="<?=base_url()?>index.php/main/save/chq_pending_list" >
	<table style="width:100%; margin-top:15px;" cellpadding="0" border="0" >
		<tr>
			
			<td style="width:100px;">From Date</td>
			<td>
				<input type="text" class="input_date_down_future " readonly="readonly" style="width:150px; align:right;" name="from_date" id="from_date" title="<?=date('Y-m-d')?>" />			
				<!-- <input type="hidden" id="bc" name="bc" title="<?php echo $bc; ?>"/>
				<input type="hidden" id="bc" name="bc" title="<?php echo $bc; ?>"/> -->
			</td>	
			<td style="width:100px;">To Date</td>
			<td>
				<input type="text" class="input_date_down_future " readonly="readonly" style="width:150px; align:right;" name="to_date" id="to_date" title="<?=date('Y-m-d')?>" />			
				<input type='button' name='load' id='load' title='Load Pending Vouchers'/>
			</td>
			
					
		</tr>
		<tr>
			<td colspan="5" height="20"><hr class="hline"/></td>
		</tr>
	</table>	
	
	<table style="width:1160px;" cellpadding="0" id="grid">
		<thead>
            <tr>
                <th width="196" class="tb_head_th" style="width: 40px;">Voc No</th>
                <th width="327" class="tb_head_th" style="width: 60px;">Date</th>
				<th width="196" class="tb_head_th" style="width: 70px;">Amount</th>
                <th width="327" class="tb_head_th" style="width: 60px;">Realize Date</th>
                <th width="327" class="tb_head_th" style="width: 70px;">Cheque No</th>
				<th width="196" class="tb_head_th" style="width: 80px;">Bank</th>
				<th width="196" class="tb_head_th" >Bank Name</th>
				<th width="196" class="tb_head_th" style="width: 100px;">Supplier ID</th>
				<th width="196" class="tb_head_th" style="width: 150px;">Supplier Name</th>
            </tr>
        </thead>				
		<tbody id="item_ld">
			<?php
                //if will change this counter value of 25. then have to change edit model save function.
                for($x=0; $x<25; $x++){
                    echo "<tr>";
                        echo "<td>
                        <input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                        <input type='text' readonly='readonly' class='g_input_txt fo' id='0_".$x."' name='0_".$x."' style='border:dotted 1px #ccc;background-color: #f9f9ec;'/></td>";
                        echo "<td> <input type='text'  class='g_input_txt' style='border:dotted 1px #ccc;background-color: #f9f9ec;width:100%;' id='n_".$x."' name='n_".$x."' readonly='readonly'/></td>";
						echo "<td> <input type='text' class='g_input_txt' id='2_".$x."' readonly='readonly' name='2_".$x."'  style='border:dotted 1px #ccc;background-color: #f9f9ec;'/></td>";
						echo "<td> <input type='text'  class='g_input_txt' style='border:dotted 1px #ccc;background-color: #f9f9ec;width:100%;' id='n_".$x."' name='n_".$x."' readonly='readonly'/></td>";
						echo "<td> <input type='text' class='g_input_txt' id='3_".$x."' readonly='readonly' name='3_".$x."'  style='border:dotted 1px #ccc;background-color: #f9f9ec;'/></td>";
						echo "<td> <input type='text' class='g_input_txt' id='4_".$x."' readonly='readonly' name='4_".$x."'  style='border:dotted 1px #ccc;background-color: #f9f9ec;'/></td>";
						echo "<td> <input type='text' class='g_input_txt' id='5_".$x."' readonly='readonly' name='5_".$x."'  style='border:dotted 1px #ccc;background-color: #f9f9ec;'/></td>";
						echo "<td> <input type='text' style='background-color: #f9f9ec;border:dotted 1px #ccc;' class='g_input_num' id='4_".$x."' name='4_".$x."' /></td>";
						echo "<td> <input type='text' style='background-color: #f9f9ec;border:dotted 1px #ccc;' class='g_input_num' id='5_".$x."' name='5_".$x."' /></td>";
                       
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


	<table style="width:100%;">
		<tr>
			<td height="20">
			<hr class="hline"/>
			</td>
		</tr>
	</table>

	<table style="width:100%;">		
		<tr>
			<td align="right"><input type="button" id="btnExit" title="Exit" />
		    <input type="hidden" name="code_" id="code_"/>   
            <input name="button" type="button" id="btnReset" title='Reset' /></td>
		</tr>	
	</table>
</form>			
</div>			
<?php } ?>