<?php if($this->user_permissions->is_view('m_employee_details')){ ?>
<script type='text/javascript' src='<?=base_url()?>js/m_employee_details.js'></script>
<h2>Employee Details</h2>
<table width="100%">
    <tr>
        <td valign="top" class="content" style="width: 600px;">
            <div class="form" id="form">
                <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/m_employee_details" >
                <table>
                     <tr>
                        <td>Category</td>
                        <td><?php echo $category; ?><input type="text" class="hid_value" title='' id="category_id" style="width:200px;"/></td>
                    </tr>
                    <tr>
                        <td>Code</td>
                        <td><input type="text" class="input_txt" title='' id="code" name="code" maxlength="10" style="text-transform:uppercase;"></td>
                        <td><input type="checkbox" name="inactive" id="inactive" title="1"/>Inactive</td>
                    </tr>   

                    <tr>

                        <td>Name</td>
                        <td colspan><input type="text" class="input_txt" title='' id="name" name="name"style="width:300px;" maxlength="100">                              
                        </td>

                    </tr>

                    <tr>
                        <td>Address 01</td>
                        <td><input type="text" class="input_txt" id="address1" title="" style="width: 150px;" name="address1"  maxlength="255"/></td>                       
                    </tr>
                        <tr>
                                <td>Address 02</td>
                                <td><input type="text" class="input_txt" id="address2" title="" style="width: 300px;"  name="address2" maxlength="255"/></td>                          
                        </tr>

                        <tr>
                                <td>Address 03</td>
                                <td><input type="text" class="input_txt" id="address3" title="" style="width: 300px;"  name="address3" maxlength="255"/></td>                      
                        </tr>

                        <tr>
                                <td>Phone Numbers</td>
                                <td>
                                    <input type="text" class="input_txt" id="tp1" title="" style="width: 95px;" maxlength="10" name="tp1" />
                                    <input type="text" class="input_txt" id="tp2" title="" style="width: 100px;" maxlength="10" name="tp2"  />
                                    <input type="text" class="input_txt" id="tp3" title="" style="width: 100px;" maxlength="10" name="tp3"  />
                                </td>                        
                        </tr>

                    <tr>
                        <td>DOJ</td>
                        <td><input type="text" class="input_date_down_future" title='' id="doj" name="doj"  style="width:150px;"/></td>
                    </tr>


                    <tr>
                        <td colspan="2" style="text-align: right; width:400px;">
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
        <td class="content" valign="top">
            <div class="form" id="form">
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
