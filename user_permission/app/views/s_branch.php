<script type='text/javascript' src='<?=base_url()?>js/s_branch.js'></script>
<h2>Branches</h2>
<table width="100%">
    <tr>
        <td valign="top" class="content" style="width: 400px;">
            <div class="form" id="form">
                <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/s_branch" >
                <table>
                    <tr>
                        <td>Branch Code</td>
                        <td><input type="text" class="input_txt" title='Code' id="code" name="code" maxlength="10"></td>
                    </tr><tr>
                        <td>Branch Name</td>
                        <td><input type="text" class="input_txt" title='Branch Name' id="name" name="name"  maxlength="255"/></td>
                    </tr><tr>
                        <td>Address 01</td>
                        <td><input type="text" class="input_txt" id="address_no" title="No" name="address01" style="width: 200px;"  maxlength="255"/></td>                       
                    </tr><tr>
                        <td>Address 02</td>
                        <td><input type="text" class="input_txt" id="address_street" name="address02" title="Street" style="width: 200px;"  maxlength="255"/></td>                          
                    </tr><tr>
                        <td>Address 03</td>
                        <td><input type="text" class="input_txt" id="address_city" name="address03" title="City" style="width: 200px;"  maxlength="255"/></td>                      
                    </tr><tr>
                        <td>Phone Numbers</td>
                        <td>
                            <input type="text" class="input_txt" id="p_mobile" title="Mobile" style="width: 85px;" maxlength="10" name="phone01" />
                            <input type="text" class="input_txt" id="p_office" title="Office" style="width: 85px;" maxlength="10" name="phone02" />
                            <input type="text" class="input_txt" id="p_fax" title="Fax" style="width: 85px;" maxlength="10" name="phone03" />
                        </td>                        
                    </tr><tr>
                        <td>Email</td>
                        <td><input type="text" class="input_txt" title='Email' id="email" name="email" /></td>
                    </tr><tr>
                        <td colspan="2" style="text-align: right;">
                            <input type="button" id="btnExit" title="Exit" />
                            <input type="hidden" id="code_" name="code_" />
                            <input type="button" id="btnSave" title='Save' />
                            <input type="button" id="btnReset" title='Reset'>
                        </td>
                    </tr>
                </table>
                </form>
            </div>
        </td>
        <td class="content" valign="top">
            <div class="form">
                <div id="grid_body"><?=$table_data;?></div>
            </div>
        </td>
    </tr>
</table>