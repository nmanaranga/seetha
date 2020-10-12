<?php if($this->user_permissions->is_view('m_item_free')){ ?>
<script type='text/javascript' src='<?=base_url()?>js/m_item_free.js'></script>
<h2>Item Free Issue</h2>

    <table width="100%" border="0">
        <tr>
            <td valign="top"  class="content"  style="width: 600px;">
             <div class="form" id="form">
                <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/m_item_free" >
					
					
						<table style="width:500px" cellpadding="0" border="0">
							<tr>
								<td style="width:100px;"></td>
								<td style="width:100px;"></td>
								
								<td style="width:200px;text-align:right;" colspan="2"><span style="margin-right:12px;">No&nbsp;&nbsp;</span>
								<input type="text" class="input_active" title=<?php echo $max_no; ?>  id="nno" name="nno" style="width:130px;"></td>
							</tr>
							
							<tr>
								<td style="width:100px;"></td>
								<td style="width:100px;"></td>
								
								<td style="width:200px;text-align:right;" colspan="2"><span style="margin-right:12px;">Date</span>
								<input type="text" class="input_date_down_future" readonly="readonly" id="date" name="ddate" style="width:130px;" title="<?php echo date('Y-m-d')?>"></td>
							</tr>
						
							<tr>
								<td style="width:100px;">Item</td>
								<td style="width:100px;"><input type="text" title="" class="input_txt" id="item" name="code"/></td>
								<td style="width:300px;" colspan="2"><input type="text" class="input_txt" title='' readonly="readonly" id="item_id" name="test"  style="width:350px;"></td>
							</tr>
							
								<tr>
								<td style="width:100px;">Description</td>	
								<td style="width:400px;" colspan="3"><input type="text" class="input_txt" title='' id="description" name="description" maxlength="100" style="width:350px;"></td>
							</tr>
							
							<tr>
								<td style="width:100px;">Quantity</td>
								<td style="width:400px;" colspan="3"><input type="text" class="input_txt" title=''  id="qty" name="qty" style="width:150x;">
								<input type="checkbox" id="inactive" name="inactive" title="1"/><span style="margin-right:10px;">In active</span>	
								</td>
							</tr>
							
							<tr>
								<td style="width:400px;" colspan="4">&nbsp;</td>
							</tr>
							<tr>
								<td style="width:400px;" colspan="4"><hr class="hline"/></td>
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
                                <th width="200" class="tb_head_th" style="width: 80px;">Free Item</th>
                                <th width="465" class="tb_head_th">Description</th>
                            </tr>
                </thead>
				<tbody>
					
							<?php
                               
                                for($x=0; $x<12; $x++){
                                    echo "<tr>";
                                        echo "<td style='background-color: #f9f9ec;' width='90'><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                                        <input type='text' class='g_input_txt fo' id='0_".$x."' name='0_".$x."' style='background:transparent;border:1px dotted #ccc;'  /></td>";
                                        echo "<td >
                                        <input type='text' class='g_input_txt'  id='n_".$x."' name='n_".$x."'  style='background:transparent;border:1px dotted #ccc;' readonly='readonly' maxlength='150'/></td>";
                                      
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
                     <?php if($this->user_permissions->is_add('m_item_free')){ ?><input name="button2" type="button" id="btnSave" title='Save <F8>' /><?php } ?>
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
