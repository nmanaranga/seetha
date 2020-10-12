<?php if($this->user_permissions->is_view('003')){ ?>
<script type='text/javascript' src='<?=base_url()?>js/m_sales_ref.js'></script>
<h2>Sales Ref</h2>
<table width="100%">
    <tr>
        <td valign="top" class="content">
            <div class="form" id="form">
                <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/m_sales_ref" >
                <table>
                    <tr>
                        <td>Person Code</td>
                        <td>
                            <input type="text" class="input_txt" id="code" name="code" title="Code" maxlength="10" />
                            <input type="hidden" id="code_" name="code_"title=""/>
                        </td>                        
                    </tr><tr>
                        <td>Person Name</td>
                        <td><input type="text" class="input_txt" id="name" name="name" title="Name" style="width: 200px;" /></td>                           
                    </tr><tr>
                        <td>Address 01</td>
                        <td><input type="text" class="input_txt" id="address_no" name="address01" title="No" style="width: 200px;" /></td>                       
                    </tr><tr>
                        <td>Address 02</td>
                        <td><input type="text" class="input_txt" id="address_street" name="address02" title="Street" style="width: 200px;" /></td>                          
                    </tr><tr>
                        <td>Address 03</td>
                        <td><input type="text" class="input_txt" id="address_city"  name="address03" title="City" style="width: 200px;" /></td>                      
                    </tr>
                    <tr>
                        <td>Phone Numbers</td>
                        <td>
                            <input type="text" class="input_txt" id="p_mobile" name="phone01" title="Mobile" style="width: 85px;" maxlength="10" />
                            <input type="text" class="input_txt" id="p_office" title="Office"  name="phone02" style="width: 85px;" maxlength="10" />
                            <input type="text" class="input_txt" id="p_fax" title="Fax"  name="phone03" style="width: 85px;" maxlength="10" />
                        </td>                        
                    </tr>
                    
                    <tr>
                        <td>Date Of Joined</td>
                        <td>
                        <input type="text" class="input_date_down_future" id="dateOfJoin" name='dateOfJoin' title="<?php echo date("Y-m-d"); ?>" id="sd" style="width: 100px;"/>
                        </td>                        
                    </tr>

                    <tr>
                        <td colspan="2" style="text-align:right;">
                        <input type="button" id="btnExit" title="Exit" />
                           <?php if($this->user_permissions->is_view('003')){ ?>
                            <input type="button" id="btnSave1" title='Save <F8>' />
                            <?php } ?>
                            <input type="button" id="btnReset" title='Reset'>
                        </td>        
                    </tr>
                </table>
                </form>
            </div>
        </td>
        <td valign="top" class="content">
            <div class="form" >
                <div id="grid_body">
                    <?=$table_data;?>
                </div>
            </div>
        </td>
    </tr>
</table>
<?php } ?>