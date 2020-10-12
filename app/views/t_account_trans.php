



<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<script type="text/javascript" src="<?=base_url()?>js/t_account_trans.js"></script>

<h2 style="text-align: center;">Opening Balance</h2>
<div class="dframe" id="mframe" style="width:980px;" >
    <form method="post" action="<?=base_url()?>index.php/main/save/t_account_trans" id="form_">
        <table style="width: 100%" border="0">
            <tr>
                <td style="width: 100px;">Note</td>
                <td> 
                  <input type="text" name="note1" id="note1" class="input_txt" style="width:100px;"/><input type="text" class="hid_value" name="note" id="note" title="" style="width: 360px;" maxlength="255" /></td>
                </td>
                <td ></td>
                <td></td>

            </tr>
            <tr>
                <td>Journal Type</td>
                <td><input type="text" name="jt1" id="jt1" class="input_txt" style="width:100px;"/><input type="text" class="hid_value" name="jt" id="jt" title="" style="width: 360px;" maxlength="255" /></td>
                <td ></td>
                <td ></td>
            </tr>

            <tr>
                <td>Date</td>
                <td><input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" />
                    <input type="button" title="Find" style="margin-left:260px;"/>
                </td>
                <td ></td>
                <td ></td>
           
            </tr>

          <tr>
                <td colspan="4" style="text-align: center;">
                	<div style="height:300;overflow:scroll">
                    <table style="width: 100%;" id="tgrid">
                        <thead>
                            <tr>
                                <th class="tb_head_th" style="width: 80px;">Code</th>
                                <th class="tb_head_th">Description</th>
                                <th class="tb_head_th" style="width: 80px;">Dr Amount</th>
                                <th class="tb_head_th" style="width: 80px;">Cr Amount</th>
                                
                            </tr>
                        </thead><tbody>
                            <?php
                                //if will change this counter value of 25. then have to change edit model save function.
                                for($x=0; $x<25; $x++){
                                    echo "<tr>";
                                        echo "<td><input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                                                <input type='text' class='g_input_txt fo' id='0_".$x."' name='0_".$x."'  /></td>";
                                        echo "<td><input type='text' class='g_input_txt'  id='n_".$x."' name='n_".$x."' maxlength='150'/></td>";
                                        echo "<td><input type='text' class='g_input_num2 qun' id='1_".$x."' name='1_".$x."' /></td>";
                                        echo "<td><input type='text' class='g_input_amo amo' id='2_".$x."' name='2_".$x."' /></td>";
                                        
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                                               
                    </table>
                </div>


                    
                
                    
                </td>
            </tr>
            <tr>
            	<td colspan="4">

            			
                        <input type="button" id="btnExit" title="Exit" />
                        <input type="button" id="btnReset" title="Reset" />
        
                       
                        <input type="button"  id="btnSave1" title='Save <F8>' />

                       
                        
	                  

	                    
            	
            	
            		
					
                    <span style="margin-left:300px;">    
					Total
            		<input type="text" class="input_txt" name="dr_amount" id="dr_amount" style="width:120px;"/>
            		<input type="text" class="input_txt" name="cr_amount" id="cr_amount" style="width:120px;"/>
                </span>
            		<br/>
            		

            		
            	</td>

            </tr>
            <tr>
                <td colspan="4">
                   <span style="margin-left:582px;"> Balance &nbsp;<input type="text" class="input_txt" name="balance" id="balance" style="width:120px;"/>
                    
                   </span>
                </td>
            </tr>

        </table>
    </form>
</div>
