<style type="text/css">
#mframe {
    -moz-box-shadow : 0px 0px 8px #AAA;
    -webkit-box-shadow : 0px 0px 8px #AAA;
    box-shadow : 0px 0px 8px #AAA;
	background:#EDEDED;
    width: 500px;
    padding: 7px;
    margin: auto;
	margin-top:10px;
    z-index: 1;
}
</style>
<script type="text/javascript" src="<?=base_url()?>js/s_company.js"></script>
<h2 style="text-align: center;">Company</h2>
<div class="dframe" id="mframe">
    <form method="post" action="<?=base_url()?>index.php/main/save/s_company" id="form_">
        <table style="width: 100%" border="0">
            <tr>
                <td>Company Name</td>
                <td><input type="text" class="input_active" title='<?=$name;?>' id="name" name="name"  maxlength="255"/></td>
            </tr><tr>
                <td>Address 01</td>
                <td><input type="text" class="input_active" id="address_no" title="<?=$address01;?>" name="address01" style="width: 200px;"  maxlength="255"/></td>                       
            </tr><tr>
                <td>Address 02</td>
                <td><input type="text" class="input_active" id="address_street" name="address02" title="<?=$address02;?>" style="width: 200px;"  maxlength="255"/></td>                          
            </tr><tr>
                <td>Address 03</td>
                <td><input type="text" class="input_active" id="address_city" name="address03" title="<?=$address03;?>" style="width: 200px;"  maxlength="255"/></td>                      
            </tr><tr>
                <td>Phone Numbers</td>
                <td>
                    <input type="text" class="input_active" id="p_mobile" title="<?=$phone01;?>" style="width: 85px;" maxlength="10" name="phone01" />
                    <input type="text" class="input_active" id="p_office" title="<?=$phone02;?>" style="width: 85px;" maxlength="10" name="phone02" />
                    <input type="text" class="input_active" id="p_fax" title="<?=$phone03;?>" style="width: 85px;" maxlength="10" name="phone03" />
                </td>                        
            </tr><tr>
                <td>Email Address</td>
                <td><input type="text" class="input_active" title='<?=$email;?>' id="email" name="email" /></td>
            </tr>
        </table>
        <div style="text-align: right; padding-top: 20px; padding-right: 5px;">
            <input type="button" id="btnExit" title="Exit" />
            <input type="button" id="btnSave" title='Save' />
            <input type="button" id="btnReset" title='Reset'>
        </div>
    </form>
</div>