<?php if($this->user_permissions->is_view('r_groups')){ ?>
<script type='text/javascript' src='<?=base_url()?>js/r_groups.js'></script>
<h2>Group Sales</h2>
<table width="100%">
    <tr>
        <td valign="top" class="content" style="width:500px;">
            <div class="form" id="form" style="width:500px;">
               <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/r_groups" >
                <table>
                    <tr>
                        <td>Code</td>
                        <td>
                        <input type="text" class="" id="code" name="code" maxlength="10" style="width:100px;"  readonly ="readonly" title="<?=$max_no?>">
                        <input type="checkbox" name="inactive" id="inactive" />Inactive
                        </td>
                    </tr>

                    <tr>
                       <td>Name</td>
                        <td colspan="2"><input type="text" class="input_txt" title='' id="name" name="name"  style="width:354px;" maxlength="255"/></td>
                    </tr>

                    <tr>
                        <td>From Date</td>
                        <td colspan="2">
                        <input type="text" id="fdate" name="fdate" class="input_date_down_future" title="<?php  echo date('Y-m-d')?>"/>
                      

                        To Date
                        <input type="text" id="tdate" name="tdate" class="input_date_down_future" title="<?php echo date('Y-m-d')?>"/></td>
                  
                    </tr>

                    <tr>
                        <td>Category</td>
                        <td colspan="2">
                            <?php echo $sales_category;?>
                            <input type="hidden" id="sales_category1" name="sales_category1" title="0" />
                        </td>
                    </tr>

                     <tr>
                        <td>Officer</td>
                        <td colspan="2"><input type="text" class="input_active" id="officer" name="officer"  title="" style="width:100px;" maxlength=""/>
                        <input type="text" class="hid_value" readonly="readonly" title='' id="officer2"   style="width:250px;" maxlength="255"/>
                        <input type="button" id='emp_s' title="...">
                        </td>
                   
                    </tr>

                  
                        <tr>
                        <td colspan="2" style="text-align: right;">
                            <input type="button" id="btnExit" title="Exit" />
                            <input type="hidden" id="code_" name="code_" />
                            <?php if($this->user_permissions->is_add('r_groups')){ ?><input type="button" id="btnSave" title='Save <F8>' /><?php } ?>
                            <input type="button" id="btnReset" title='Reset'>
                        </td>
                    </tr>
                   
                </table>
                </form>
            </div>
        </td>
        <td valign="top" class="content" style="width:600px;">
            <div class="form" style="width:600px;">
            <table>
                <tr>
                    <td style="padding-right:64px;"><label>Search</label></td>
                    <td>
                        <input type="text" class="input_txt" title='' id="srchee" name="srch" style="width:230px; marging-left:20px;">
                    </td>
                </tr>
            </table>
                <div id="grid_body"><?=$table_data;?></div>
            </div>
        </td>
    </tr>
</table>
<?php } ?>