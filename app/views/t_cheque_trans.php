<script type='text/javascript' src='<?=base_url()?>js/t_cheque_trans.js'></script>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<h2>Cheque Deposit</h2>
<div class="dframe" id="mframe" style=" margin-top:10px; width:984px;padding-right:25px;">
    <form method="post" action="<?=base_url()?>index.php/main/save/t_cheque_trans" id="form_">
        <table style="width: 100%" border="0">
            <tr>
                <td style="width: 80px;">Bank</td>
                <td><select></select>
                <input type="text" id="bank" name="bank" title="" class="hid_value" style="width:300px;" maxlength="255" readonly="readonly"/>
		    </td>
                <td style="width: 50px;">No</td>
                <td>
                    <input type="text" class="input_active_num" id="id" title="" style="width:150px;" />
                    <input type="hidden" id="no" name="no" title="0" />
                </td>
            </tr><tr>
                <td>Ref No </td>
                <td><input type="text" class="input_txt" name="ref_no" id="ref_no" title="Ref No" style="width: 100px;"/>
                	<span style="margin-left:45px;"><input type="button" title="Loading Pending Cheques" style="width:180px;"/></span>
                </td>
                <td style="width: 100px;">Date</td>
                <td style="width: 100px;"><input type="text" class="input_date_down_future" readonly="readonly" name="date" style="width:150px;" id="date" title="" /></td>
            </tr>

            <tr>
            	<td colspan="4">
            			<table style="width:100%" id="tgrid">
							<thead>
								<tr>
									<th class="tb_head_th" style="width:70px">Bank</th>
									<th class="tb_head_th" >Branch</th>
									<th class="tb_head_th" style="width:70px">Acc No</th>
									<th class="tb_head_th" style="width:70px">Cheque No</th>
									<th class="tb_head_th" style="width:70px">Amount</th>
									<th class="tb_head_th" style="width:70px">Realize Date</th>
									<th class="tb_head_th" style="width:70px">&nbsp;</th>
								</tr>
							</thead>
							<tbody>
								<?php
															   
									for($x=0; $x<25; $x++){
										echo "<tr>";
										echo "<td ><input type='hidden' name='code_".$x."' id='code_".$x."' title='0' />
										<input  type='text' class='g_input_txt' id='bank_".$x."' name='bank".$x."' /></td>";
										echo "<td ><input type='text' class='g_input_txt'  id='branch_".$x."' name='branch_".$x."'/></td>";
										echo "<td ><input type='text' class='g_input_txt'  id='accno_".$x."' name='accno_".$x."'/></td>";				
										echo "<td ><input type='text' class='g_input_txt'  id='cno_".$x."' name='cno_".$x."'/></td>";
										echo "<td ><input type='text' class='g_input_txt'  id='amount_".$x."' name='amount_".$x."'/></td>";
										echo "<td ><input type='text' class='g_input_txt'  id='rdate_".$x."' name='rdate_".$x."'/></td>";
										echo "<td align='center' ><input type='checkbox'  id='reconz_".$x."' name='reconz_".$x."'/></td>";
										echo "</tr>";
									}
														
								?>											
							</tbody>
						</table>

            	</td>
            </tr>
            	<tr>
            		<td colspan="4">
            				<div style="text-align:right; padding-top: 7px;">
            					<span >No Of Cheques</span>
            					<input type="text" id="nof_cheque" readonly="readonly" name="nof_cheque" title="" class="input_txt_f"/>
            					<span style="margin-left:25px;">Total Amount</span>
            					<input type="text" id="total" name="total"  readonly="readonly" title="" class="input_txt_f"/>
            				</div>
            		</td>
            	</tr>


                <tr>
                <td colspan="4" style="text-align: center;">
                    <div style="text-align:left; padding-top: 7px;">
                    	<input type="button" id="btnExit" title="Exit" />
                    	 <input type="button" id="btnReset" title="Reset" />
                    	
			    <input type="button" id="btnDelete" title="Delete" />
                       
                         <input type="button" id="btnPrint" title="Print" />
                      
			    <input type="button"  id="btnSave" title='Save <F8>' />
                        
			
                        
                        
                       
			
                        <?php //if($this->user_permissions->is_print('018')){ ?>
			<!-- <input type="button" id="btnLoadRo" title="Load RO" />-->
                        <?php //} ?>
                        <?php //if($this->user_permissions->is_print('018')){ ?>
                        <!--<input type="button" id="btnPrint" title="Print" />-->
                        <?php //} ?>
                    </div>
                </td>
                
            </tr>
        </table>
    </form>
</div>
