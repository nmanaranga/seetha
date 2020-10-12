<script type='text/javascript' src='<?=base_url()?>js/m_seettu_item_category.js'></script>
<h2>Settu - Item Category</h2>
<table width="100%">
    <tr>
        <td valign="top" class="content" style="width:450px;">
            <div class="form" id="form" style="width:450px;">
               <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/r_groups" >
                <table>
                    <tr>
                        <td>Code</td>
                        <td>
                        <input type="text" class="" id="code" name="code" maxlength="10" style="width:100px;"  readonly ="readonly" >
                        
                        </td>
                    </tr>

                    <tr>
                       <td>Name</td>
                        <td colspan="2"><input type="text" class="input_txt" title='' id="name" name="name"  style="width:354px;" maxlength="255"/></td>
                    </tr>
                        <td>Value</td>
                        <td colspan="2"><input type="text" class="input_txt" title='' id="name" name="name"  style="width:154px;" maxlength="255"/></td>
                   

                     <tr>
                         <td>No Of Installment</td>
                        <td colspan="2"><input type="text" class="input_txt" title='' id="name" name="name"  style="width:154px;" maxlength="255"/></td>   
                    </tr>

                  
                     <tr>

                          <td>Installment Amount</td>
                        <td colspan="2"><input type="text" class="input_txt" title='' id="name" name="name"  style="width:154px;" maxlength="255"/></td>   
                      
                    </tr>
                    <tr>
                    <td colspan="2" style="text-align: right;">
                            <input type="button" id="btnExit" title="Exit" />
                            <input type="hidden" id="code_" name="code_" />
                            <input type="button" id="btnSave" title='Save <F8>' />
                            <input type="button" id="btnReset" title='Reset'>
                        </td>

                    </tr>
                   
                </table>
                </form>
            </div>
        </td>
        
    </tr>
</table>