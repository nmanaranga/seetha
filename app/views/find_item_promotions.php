<?php if($this->user_permissions->is_view('find_item_promotions')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<link href="<?=base_url()?>css/t_forms.css" type="text/css" rel="stylesheet">
<script type='text/javascript' src='<?=base_url()?>js/find_item_promotions.js'></script>
<h2>Find Items Current Stock</h2>
<div class="dframe" id="mframe" style="width:1100px;">
    <form id="print_pdf" action="<?php echo site_url(); ?>/reports/generate" method="post" target="_blank">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size: 12px;">
            <tr>
                <td style="width:120px;">Cluster</td>
                <td style="width:450px;"><input type="text" id="txt_cluster" name="txt_cluster" class="input_txt" title='<?=$cluster_code?>' />
                    <input type="text" class="hid_value" id="hid_cluster" name="hid_cluster" style="width: 250px;" title='<?=$cluster_name?>' />
                    &nbsp;&nbsp;<b>Date</b>
                </td>
                <td style="width:120px;"><input type="text" class="input_date_down_future" readonly="readonly" style="width:150px; text-align:right;" name="fdate" id="fdate" title="<?=date('Y-m-d')?>" /></td> 
                <td>&nbsp;&nbsp;<input type="button" id="btnLoad_data" title="Load"></td>
            </tr>
            <tr>
                <td>Branch</td>
                <td><input type="text" id="txt_branch" name="txt_branch" class="input_txt" title='<?=$branch_code?>' /> 
                    <input type="text" class="hid_value" id="hid_branch" name="hid_branch" style="width: 250px;" title='<?=$branch_name?>'/>
                </td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

            <tr>
                <td>Search</td>
                <td><input type="text" id="txt_search" name="txt_search" style="width:400px;" class="input_txt"></td>
                <td><select name='add_search' id='add_search'>
                    <option value="0">-- Searching Options --</option>
                    <option value="1">Code</option>
                    <option value="2">Description</option>
                    <option value="3">Model</option>
                </select></td>
                <td></td>
                <td>&nbsp;<select name='promo_type' id='promo_type'>
                    <option value="0">-- Promotion Type --</option>
                    <option value='1'>FOC</option>
                    <option value='2'>Back to Back</option>
                    <option value='3'>Interest Free</option>
                    <option value='4'>Credit Card</option>
                </select></td>
            </tr>

        </table>
        <table style="width:100%;" cellpadding="0" id="grid">
            <thead>
                <tr>
                    <th width="200" class="tb_head_th" style="width: 200px;">Code</th>
                    <th width="400" class="tb_head_th" style="width: 450px">Description</th>
                    <th width="150" class="tb_head_th" style="width: 150px;">Model</th>
                    <th width="150" class="tb_head_th" style="width: 150px;">Date From</th>
                    <th width="150" class="tb_head_th" style="width: 150px;">Date To</th>
                    <th width="175" class="tb_head_th" style="width: 200px;">Promotion Type</th>
                    <th width="400" class="tb_head_th" style="width: 400px;">Note</th>
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
                    echo "<td> <input type='text' class='g_input_txt' id='dt_".$x."' readonly='readonly' name='dt_".$x."'  style='border:dotted 1px #ccc;background:transparent;background-color: #f9f9ec; cursor:pointer;'/></td>";
                    echo "<td> <input type='text' class='g_input_txt' id='df_".$x."' readonly='readonly' name='df_".$x."'  style='border:dotted 1px #ccc;background:transparent;background-color: #f9f9ec; cursor:pointer;'/></td>";
                    
                    echo "<td> <input type='text' class='g_input_txt' id='3_".$x."' readonly='readonly' name='3_".$x."'  style='border:dotted 1px #ccc;background:transparent;background-color: #f9f9ec; cursor:pointer;'/></td>";
                    echo "<td> <input type='text' class='g_input_txt' id='4_".$x."' readonly='readonly' name='4_".$x."'  style='border:dotted 1px #ccc;background:transparent;background-color: #f9f9ec; cursor:pointer;'/></td>";
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
                        <input name="button" type="button" id="btnReset" title='Reset' />
                        <!-- <input name="button" type="button" id="btnPrint" title='Print' /> --></td>
                    </tr>

                </table>
                <input type="hidden" name='ptypes' id='ptypes'>
                <input type="hidden" name='by' value='find_item_promotions' title="find_item_promotions" class="report">
                <input type="hidden" name='page' value='A4' title="A4" >
                <input type="hidden" name='orientation' value='P' title="P" >

            </form>


        </div>          
        <?php } ?>