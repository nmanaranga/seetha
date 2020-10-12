<?php if($this->user_permissions->is_view('039')){ ?>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>css/t_forms.css" />
<style type="text/css">
    .posting_div{
        background-color: #FFF;
        border: 1px dotted #CCC;
        padding: 7px;
        padding-buttom: 0px;
    }
    
    .heading {
        background-color: #aee8c8;
        margin: 5px;
        border: 2px solid #FFF;
        box-shadow: 0px 0px 5px #AAA;
        text-align: left;
        padding: 7px;
    }
</style>
<script type="text/javascript" src="<?=base_url()?>js/t_cheque_book.js"></script>
<h2 style="text-align: center;">Cheque Book</h2>
<div class="dframe" id="mframe" style="text-align: center; width: 650px;">
    <form action="index.php/main/save/t_cheque_book" method="post" id="_form">
        <table style="width: 100%" border="0">
            <tr>
                <td>Bank A/C</td>
                <td>
                    <?=$accounts;?>
                    <input type="text" class="input_txt" id="sto_des" title="Bank Account" style="width: 202px;" readonly="readonly" />
                </td>
                <td style="width: 50px;">No</td>
                <td>
                    <input type="text" class="input_active_num" name="id" id="id" title="<?=$max_no?>" />
                    <input type="hidden" id="hid" name="hid" title="0" />
                </td>
            </tr><tr>
                <td>No of Pages</td>
                <td>
                    <input type="text" class="input_number" name="pages" id="pages" title="No of Pages" maxlength="255" />
                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;
                    Start No : <input type="text" class="input_number" name="start_no" id="start_no" title="Start No" maxlength="255" />                   
                </td>
                <td style="width: 100px;">Date</td>
                <td style="width: 100px;"><input type="text" class="input_date_down_future" readonly="readonly" name="date" id="date" title="<?=date('Y-m-d')?>" /></td>
            </tr>
        </table>
        <div style="text-align: right; padding: 7px;">
            <input type="button" id="btnExit" title="Exit" />
            <input type="button" id="btnReset" title="Reset" />
            <input type="button" id="btnDelete" title="Delete" />
            <?php if($this->user_permissions->is_view('039')){ ?>
            <input type="button" id="btnSave" title='Save <F8>' />
           <?php } ?>
        </div>
    </form>
</div>3
<?php } ?>