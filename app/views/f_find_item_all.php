<?php if($this->user_permissions->is_view('f_find_item_all')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<link href="<?=base_url()?>css/t_forms.css" type="text/css" rel="stylesheet">
<script type='text/javascript' src='<?=base_url()?>js/f_find_item_all.js'></script>
<h2>Find Item All</h2>
<div class="dframe" id="mframe">


            <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/m_item_rol" >
			<table style="width:100%; margin-top:15px;" cellpadding="0" border="0" >
				<tr>
					<?php foreach($branch as $row){ 
						$name= $row['name'];
						$bc=$row['bc'];
					} 
					?>
					<td style="width:100px;">Item</td>
					<td><input type="text" id="pop_searchs" name="search" class="" title="" style="width:350px;" title=""/>
					<!--<input type="text" class="hid_value" readonly="readonly" title="<?php echo $name;?>" style="width:350px;" title=""/>
					<input type="text" class="input_txt" id="bc_id" style="width:350px;"/>-->
						<input type="hidden" id="bc" name="bc" title="<?php echo $bc; ?>"/>
					</td>
					
				</tr>

				<tr>
					<td colspan="3" height="20"><hr class="hline"/></td>
				</tr>
			</table>
			
			
			<table style="width:100%;" cellpadding="0" id="grid">
				<thead>
                            <tr>
                                <th width="196" class="tb_head_th" style="width: 80px;">Code</th>
                                <th width="327" class="tb_head_th">Description</th>
								<th width="196" class="tb_head_th" style="width: 100px;">Model</th>
                                <th width="327" class="tb_head_th" style="width: 80px;">Min Price</th>
                                <th width="327" class="tb_head_th" style="width: 80px;">Max Price</th>
								<th width="196" class="tb_head_th" style="width: 80px;">QTY</th>
								<th width="196" class="tb_head_th" style="width: 80px;">Batch No</th>
                            </tr>
                </thead>
				
				<tbody id="item_ld">
							<?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<50; $x++){
                                    echo "<tr>";
                                        echo "<td>
                                        <input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                                        <input type='text' readonly='readonly' class='g_input_txt fo' id='0_".$x."' name='0_".$x."' style='border:dotted 1px #ccc;background-color: #f9f9ec;'/></td>";
                                        echo "<td ><input type='text'  class='g_input_txt' style='border:dotted 1px #ccc;background:transparent;width:100%;' id='n_".$x."' name='n_".$x."' readonly='readonly'/></td>";
										echo "<td> <input type='text' class='g_input_txt' id='2_".$x."' readonly='readonly' name='2_".$x."'  style='border:dotted 1px #ccc;background:transparent;'/></td>";
										echo "<td> <input type='text' class='g_input_txt' id='3_".$x."' readonly='readonly' name='3_".$x."'  style='border:dotted 1px #ccc;background:transparent;'/></td>";
										echo "<td style='background-color: #f9f9ec;'> <input type='text' style='background:transparent;border:dotted 1px #ccc;' class='g_input_num' id='4_".$x."' name='4_".$x."' /></td>";
										echo "<td style='background-color: #f9f9ec;'> <input type='text' style='background:transparent;border:dotted 1px #ccc;' class='g_input_num' id='5_".$x."' name='5_".$x."' /></td>";
                                       
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
		<tr><td height="20"><hr class="hline"/></td></tr>
		</table>


			<table style="width:80%;">
			<tr>
			<td >Item</td>
			<td id="itm"><input type='text' class='g_input_txt hid_value' id='itm2' readonly='readonly' name='itm'  style='border:dotted 1px #003399;background:transparent; width:150px;'/></td>
			
			<td>Min Price</td>
			<td id="mPrice"><input type='text' class='g_input_num hid_value' id='mPrice2' readonly='readonly' name='mPrice'  style='border:dotted 1px #003399;background:transparent; width:150px;'/></td>
			</tr>

			<tr>
			<td>Description</td>
			<td id="des"><input type='text' class='g_input_txt hid_value' id='des2' readonly='readonly' name='des'  style='border:dotted 1px #003399;background:transparent; width:300px;'/></td>
			
			<td>Max Price</td>
			<td id="mxPrice"><input type='text' class='g_input_num hid_value' id='mxPrice2' readonly='readonly' name='mxPrice'  style='border:dotted 1px #003399;background:transparent; width:150px;'/></td>
			</tr>

			<tr>
			<td>Batch</td>
			<td id="btch"><input type='text' class='g_input_txt hid_value' id='btch2' readonly='readonly' name='btch'  style='border:dotted 1px #003399;background:transparent; width:150px;'/></td>
			</tr>

			<tr>
			<td>Quantity</td>
			<td id="qnty"><input type='text' class='g_input_txt hid_value' id='qnty2' readonly='readonly' name='qnty'  style='border:dotted 1px #003399;background:transparent; width:150px;'/></td>
			</tr>

		</table>



		<table style="width:100%;">
			
				<tr>
					<td align="right"><input type="button" id="btnExit" title="Exit" />
					<!-- <input name="button2" type="button" id="btnSave" title='Save <F8>' /> -->
                      <input type="hidden" name="code_" id="code_"/>   
                     <input name="button" type="button" id="btnReset" title='Reset' /></td>
				</tr>
		
		</table>
			
			</form>
			
</div>			
<?php } ?>