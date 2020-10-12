
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />

<script type="text/javascript" src="<?=base_url()?>js/t_price_change.js"></script>

<h2 style="text-align: center;">Price Change - Bulk</h2>
<div class="dframe" id="mframe" style="text-align: center; width: 470px; padding: 15px;">
    <form id="form_" action="index.php/main/save/t_price_change" method="post" >
        <table>
            <tr>
                <td>No</td>
                <td><input type="text" class="input_active_num" name="id" id="id" title="<?=$max_no?>" />
                    <input type="hidden" id="hid" name="hid" title="0" /></td>
            </tr><tr>
                <td>Main Category</td>
                <td><?=$main_cat;?>&nbsp; <input type="text" class="input_txt" title='Main Category Description' readonly="readonly" id="m_cat_des" style="width: 150px;"></td>
            </tr><tr>
                <td>Sub Category</td>
                <td><?=$sub_cat;?>&nbsp; <input type="text" class="input_txt" title='Sub Category Description' readonly="readonly" id="s_cat_des"  style="width: 150px;"></td>
            </tr>
            <tr>
                <td>Type</td>
                <td><select id="type" name="type">
                    <option value="1">Decrease</option>
                    <option value="2">Increase</option>
                </select>
            </tr><tr>
                <td>Value</td>
                <td><input type="text" title="Valaue" id="value" class="input_amount" name="value" /> %</td>
            </tr><tr>
                <td colspan="2" style="text-align: right;">
                    <input type="button" id="btnExit" title="Exit" />
                    <input type="button" title="Reset" id="btnReset" />
                    <input type="button" title='Save <F8>' id="btnSave" />
                </td>
            </tr>
            
        </table>
    </form>
</div>
