<?php if($this->user_permissions->is_view('m_stores')){ ?>
<script type='text/javascript' src='<?=base_url()?>js/m_stores.js'></script>
<h2>Stores</h2>
 <?php 
// echo $end_date=date('Y-m-d', strtotime("+ 2 months", strtotime('2014-11-28')));
// echo '<br/>';
// echo $end_date=date('Y-m-d',(strtotime('2014-11-28')) + (86400 * 5));
// echo '<br/>';
// echo floor(4.9);




    // // Turn on output buffering
    // ob_start();
    // //Get the ipconfig details using system commond
    // system('ipconfig /all');
    // // Capture the output into a variable
    // $mycom=ob_get_contents();
    // // Clean (erase) the output buffer
    // ob_clean();
    // $findme = "Physical";
    // //Search the "Physical" | Find the position of Physical text
    // $pmac = strpos($mycom, $findme);
    // // Get Physical Address
    // $mac=substr($mycom,($pmac+36),17);
    // //Display Mac Address
    // echo $mac; 

    // echo md5('FGR');

 //echo date("Y-m-d H:m:s");
// echo phpversion();

?>
<table width="100%"> 

    <tr>
        <td valign="top" class="content" style="width:450px;">
            <div class="form" id="form" style="width:450px;">
                <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/m_stores" >
                
                <table>
						<tr>
							<td>Code</td>
							<td>
                                <input type="text" readonly class="input_hid" title='<?php echo $store_code ?>' id="pre_code" name="pre_code" style="width:50px; text-transform: uppercase; border: 1px solid #003399;padding: 3px;font-weight: bold;font-size: 12px;font-family: Arial;" maxlength="4">
                                <input type="text" class="input_hid" readonly title='<?php echo $get_next_code; ?>' id="code" name="code" style="width:130px; text-transform: uppercase;border: 1px solid #003399;padding: 3px;font-weight: bold;font-size: 12px;font-family: Arial;" maxlength="6">
                            </td>
						</tr>
						
						<tr>
							<td>Description</td>
							<td><input type="text" class="input_txt" title='' id="description" name="description" style="width:350px;" maxlength="100"/></td>
						</tr>
							<tr>
								<td>&nbsp;</td>
								<td><input type="checkbox" name="purchase" id="purchase" title="1" />Purchase</td>
							</tr>
							
							<tr>
								<td>&nbsp;</td>

								<td><input type="checkbox" name="sales" id="sales" title="1" />Sales</td>
							</tr>
							<tr>
								<td>&nbsp;</td>

								<td><input type="checkbox" name="group_sale" id="group_sale" title="1" />Group Sale</td>
							</tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td><input type="checkbox" name="transfer_location" id="transfer_location" title="1" />Transfer Location</td>
                            </tr>
						<tr>
							
							<td style="text-align:right" colspan="2">
                                <input type="hidden" id="code_" name="code_" title="0" />
    							<input type="button" id="btnExit" title="Exit" />
    							<?php if($this->user_permissions->is_add('m_stores')){ ?><input type="button" id="btnSave" title='Save <F8>' /><?php } ?>
                                <input type="button" id="btnReset" title='Reset'>
							</td>
                    </tr>
                </table>
                </form>
            </div>
        </td><td class="content" valign="top" style="width:600px;">
            <div class="form" id="form" style="width:600px;" >
            <table>
            <tr>
            <td style="padding-right:64px;"><label>Search</label></td>
            <td><input type="text" class="input_txt" title='' id="srchee" name="srch" style="width:230px; marging-left:20px;"></td>
            </tr>
            </table>
                <div id="grid_body"><?=$table_data;?></div>
            </div>
        </td>
    </tr>
</table>
<?php } ?>
