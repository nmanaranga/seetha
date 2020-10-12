<?php if($this->user_permissions->is_view('f_find_merchant')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<link href="<?=base_url()?>css/t_forms.css" type="text/css" rel="stylesheet">
<script type='text/javascript' src='<?=base_url()?>js/f_find_merchant.js'></script>
<h2>Find Credit Card Merchant</h2>
<div class="dframe" id="mframe">


            <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/m_item_rol" >
			<table style="width:100%; margin-top:15px;" cellpadding="0" border="0" >
				<tr>
					<?php foreach($branch as $row){ 
						$name= $row['name'];
						$bc=$row['bc'];
					} 
					?>
					<td style="width:100px;">Search</td>
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
                        <th width="196" class="tb_head_th" style="width: 80px;">Bank Code</th>
                        <th width="327" class="tb_head_th">Bank Name</th>
						<th width="196" class="tb_head_th" style="width: 120px;">Merchant ID</th>
                    </tr>
                </thead>
				
				<tbody id="item_ld">
							<?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<50; $x++){
                                    echo "<tr>";
                                        echo "<td><input type='text' readonly='readonly' class='g_input_txt fo' id='0_".$x."' name='0_".$x."' style='border:dotted 1px #ccc;background-color: #f9f9ec;'/></td>";
                                        echo "<td ><input type='text'  class='g_input_txt' style='border:dotted 1px #ccc;background:transparent;width:100%;' id='n_".$x."' name='n_".$x."' readonly='readonly'/></td>";
										echo "<td> <input type='text' class='g_input_txt' id='2_".$x."' readonly='readonly' name='2_".$x."'  style='border:dotted 1px #ccc;background:transparent;'/></td>";
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