<?php if($this->user_permissions->is_view('find_item_current_stock')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<link href="<?=base_url()?>css/t_forms.css" type="text/css" rel="stylesheet">
<script type='text/javascript' src='<?=base_url()?>js/find_item_current_stock.js'></script>
<h2>Find Items Current Stock</h2>
<div class="dframe" id="mframe" style="width:1100px;">

 
            <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/m_item_rol" >
            <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size: 12px;">
                      
                        <tr>
                            <td>Cluster</td>
                            <td style="width:450px;"><input type="text" id="txt_cluster" name="txt_cluster" class="input_txt" title='<?=$cluster_code?>' />
                            <input type="text" class="hid_value" id="hid_cluster" name="hid_cluster" style="width: 250px;" title='<?=$cluster_name?>' />
                         
                            </td>
                            <td style="width:230px;"> Price From <input class="input_txt g_input_amo pp" type="text" id="from_price" name ="from_price" style="text-aign:right;"></td> <td style="width:175px;"> To <input type="text" class="input_txt g_input_amo pp" id="to_price" name ="to_price"></td>
                            <td> <input type="button" id="btnLoad_data" name="btnLoad_data" title="Load Details" />  <input type="button" id="btnClear" name="btnClear" title="Clear">
                        </tr>

                        <tr>
                            <td>Branch</td>
                            <td><input type="text" id="txt_branch" name="txt_branch" class="input_txt" title='<?=$branch_code?>' /> 
                            <input type="text" class="hid_value" id="hid_branch" name="hid_branch" style="width: 250px;" title='<?=$branch_name?>'/>
                          </td>
                          <td><input type="radio" id="cost" name="find" title="1" value="1"> Cost </td>
                          <td style="padding-left:15px; padding-top:5px;"> </td>
                        </tr>

                        <tr>
                            <td>Store</td>
                            <td><input type="text" id="txt_store" name="txt_store" class="input_txt" title='<?=$store_code?>'>
                            <input type="text" class="hid_value" id="hid_store" name="hid_store" style="width: 250px;" title='<?=$store_name?>'>
                           </td>
                           <td> <input type="radio" id="min_cost" name="find" title="1" value="1"> Min  </td>
                        </tr>
                        <tr>
                            <td> </td>
                            <td> </td>
                            <td>  <input type="radio" id="max_cost" name="find" title="1" value="1" checked="checked"> Max  </td>
                        </tr>
                      

                        <tr>
                            <td style="padding-bottom:10px;">Search</td>
                            <td style="padding-bottom:10px;"><input type="text" id="txt_search" name="txt_search" style="width:400px;" class="input_txt"></td>
                            <td> <input type="checkbox" id="fine" name="fine"/> Find All item</td>                            
                        </tr>
                    </table>

            
            <table style="width:100%;" cellpadding="0" id="grid">
                <thead>
                            <tr>
                                <th width="250" class="tb_head_th" style="width: 200px;">Code</th>
                                <th width="400" class="tb_head_th" style="width: 450px">Description</th>
                                <th width="250" class="tb_head_th" style="width: 150px;">Model</th>
                                <th width="175" class="tb_head_th" style="width: 75px;">Batch</th>
                                <th width="250" class="tb_head_th" style="width: 125px;">Purchase Price</th>
                                <th width="250" class="tb_head_th" style="width: 125px;">Min Price</th>
                                <th width="250" class="tb_head_th" style="width: 125px;">Max Price</th>
                                <th width="200" class="tb_head_th" style="width: 100px;">Current Qty</th>
                                <th width="250" class="tb_head_th" style="width: 125px;">Total</th>
                            </tr>
                </thead>
                
                <tbody id="item_ld">
                            <?php
                                
                                for($x=0; $x<50; $x++){
                                    echo "<tr class='cl' style='cursor:pointer;'>";
                                        echo "<td>
                                        <input type='hidden' name='h_".$x."' id='h_".$x."' title='0' />
                                        <input type='text' readonly='readonly' class='g_input_txt fo' id='0_".$x."' name='0_".$x."' style='border:dotted 1px #ccc;background-color: #f9f9ec; cursor:pointer;'/></td>";
                                        echo "<td ><input type='text'  class='g_input_txt' style='border:dotted 1px #ccc;background:transparent;width:100%;background-color: #f9f9ec; cursor:pointer;' id='n_".$x."' name='n_".$x."' readonly='readonly'/></td>";
                                        echo "<td> <input type='text' class='g_input_txt' id='2_".$x."' readonly='readonly' name='2_".$x."'  style='border:dotted 1px #ccc;background:transparent;background-color: #f9f9ec; cursor:pointer;'/></td>";
                                        echo "<td> <input type='text' class='g_input_num' id='3_".$x."' readonly='readonly' name='3_".$x."'  style='border:dotted 1px #ccc;background:transparent;background-color: #f9f9ec; cursor:pointer;'/></td>";
                                        echo "<td> <input type='text' class='g_input_num' id='4_".$x."' readonly='readonly' name='4_".$x."'  style='border:dotted 1px #ccc;background:transparent;background-color: #f9f9ec; cursor:pointer;'/></td>";
                                        echo "<td style='background-color: #f9f9ec;'> <input type='text' style='background:transparent;border:dotted 1px #ccc; cursor:pointer;' class='g_input_num' id='5_".$x."' name='4_".$x."' readonly='readonly' /></td>";
                                        echo "<td style='background-color: #f9f9ec;'> <input type='text' style='background:transparent;border:dotted 1px #ccc; cursor:pointer;' class='g_input_num' id='6_".$x."' name='6_".$x."' readonly='readonly' /></td>";
                                        echo "<td style='background-color: #f9f9ec;'> <input type='text' style='background:transparent;border:dotted 1px #ccc; cursor:pointer;' class='g_input_num' id='7_".$x."' name='7_".$x."'readonly='readonly' /></td>";
                                        echo "<td> <input type='text' style='background:transparent;border:dotted 1px #ccc; background-color: #f9f9ec; cursor:pointer;' class='g_input_amo' id='8_".$x."' name='8_".$x."' readonly='readonly'/></td>";

                                       
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