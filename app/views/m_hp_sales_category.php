
<script type='text/javascript' src='<?=base_url()?>js/m_hp_sales_category.js'></script>
<h2>Hire Purchase Sales Category</h2>
<table width="100%">
<tr>
    <td valign="top" class="content" style="width:460px;" >
        <div class="form" id="form" style="width:450px;" >
            <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/m_hp_sales_category" >
            <table>

              
                <tr>
                    <td>Code</td>
                    <td>
                        <input type="text" class="input_txt" title='' name="code" id="code" maxlength="4" style="width:150px; text-transform: uppercase;">
                        <!-- <input type="text" class="input_txt" title='' name="code_gen" id="code_gen" maxlength="2" style="width:50px; text-transform: uppercase;"> -->
                    </td>
                </tr>

                <tr>
                    <td>Description</td>
                    <td><input type="text" class="input_txt" title='' name="description" id="description"  style="width:350px;" maxlength="50"/></td>
                </tr>
                <tr>
                    <td>Agriment No Start With</td>
                    <td>
                        <input type="text" class="input_txt" title='' name="agriment_no_start_with" id="agriment_no_start_with"style="width:150px;">
                        
                    </td>
                </tr>
                <tr>
                    <td>Start Serial No</td>
                    <td>
                        <input type="text" class="input_txt" title='' name="start_serial_no" id="start_serial_no" maxlength="4" style="width:150px;">
                       
                    </td>
                </tr>
                <tr>
                    <td>Note</td>
                    <td>
                        <textarea class="input_txt" title='' name="note" id="note"></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: right;">
                        <input type="hidden" id="code_" name="code_" />
                        <input type="button" id="btnExit" title="Exit" />
                        <input type="button" id="btnSave1" title='Save <F8>' />
                        <input type="button" id="btnReset" title='Reset'>
                    </td>
                </tr>
            </table>
            </form>
        </div>
    </td>
    <td class="content" valign="top" style="width:600px;">
        <div class="form" id="form" style="width:600px;">

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
