<script type='text/javascript' src='<?=base_url()?>js/r_groups.js'></script>
<h2>Group Sales</h2>
<table width="100%" border="1">
    <tr>
        <td valign="top" class="content" style="width: 450x;">
            <div class="form" id="form" style="width: 450px;">
                <form id="form_" method="post" action="<?=base_url()?>index.php/main/save/r_groups" >
                <table  style="width: 415px;" border="1">
                    

                    <tr>
                        <td>Code</td>
                        <td><input type="text" class="input_txt" title='Code' id="code" name="code" maxlength="10" style="width:100px;">
                        <input type="checkbox" name="inactive" id="inactive" />Inactive
                        </td>
                        
                    </tr>



                    <tr>
                        <td>Name</td>
                        <td colspan="2"><input type="text" class="input_txt" title='Name' id="name" name="name"  style="width:354px;" maxlength="255"/></td>
                    </tr>

                    <tr>
                        <td>Date</td>
                        <td colspan="2"><input type="text" id="ddate" name="ddate"class="input_date_down_future" title="<?php echo date('Y-m-d')?>"/></td>
                    </tr>

                    <tr>
                        <td>Officer</td>
                        <td colspan="2"><input type="text" class="input_active" id="officer" name="officer"  title="" style="width:100px;" maxlength=""/>
                        <input type="text" class="input_txt_f" readonly="readonly" title='Officer' id="officer2"   style="width:250px;" maxlength="255"/></td>
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
        <td class="content" valign="top" style="width:600px;">
            <div class="form" style="width:600px;">
                <div id="grid_body"><?=$table_data;?></div>
            </div>
        </td>
    </tr>
</table>