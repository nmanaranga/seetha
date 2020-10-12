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
<?php if($this->user_permissions->is_add('017')){ ?>
<script type="text/javascript" src="<?=base_url()?>js/m_defult_account.js"></script>
<h2 style="text-align: center;">Defult Account</h2>
<div class="dframe" id="mframe" style="text-align: center; width: 600px;">
    <form action="index.php/main/save/m_defult_account" method="post">
    <table style="width: 100%;" id="tgrid">
        <tr>
            <th class="tb_head_th" >Account Type</th>
            <th class="tb_head_th" >Account No</th>
            <th class="tb_head_th" >Account Description</th>
        </tr>
        <tr>
            <td>Cash In Hand</td>
            <td>
                <input type="text" class='g_input_txt' id="cash_in_hand" style="width: 100%" title="<?=$table_data->cash_in_hand;?>" />
                <input type="hidden" name="cash_in_hand" id="h_cash_in_hand" title="<?=$table_data->cash_in_hand;?>"  />            </td>
            <td><?=$table_data->cash_in_hand_des;?></td>
        </tr>
        <tr>
            <td>Cheque In Hand</td>
            <td>
                <input type="text" class='g_input_txt' id="cheque_in_hand" style="width: 100%"  title="<?=$table_data->cheque_in_hand;?>" />
                <input type="hidden" name="cheque_in_hand" id="h_cheque_in_hand"   title="<?=$table_data->cheque_in_hand;?>"/>            </td>
            <td><?=$table_data->cheque_in_hand_des;?></td>
        </tr>
        <tr>
            <td>Issued Cheque</td>
            <td>
                <input type="text" class='g_input_txt' id="issued_cheque" style="width: 100%"  title="<?=$table_data->issued_cheque;?>" />
                <input type="hidden" name="issued_cheque" id="h_issued_cheque"  title="<?=$table_data->issued_cheque;?>"/>            </td>
            <td><?=$table_data->issued_cheque_des;?></td>
        </tr>
        <tr>
            <td>Debtor Control A/C</td>
            <td>
                <input type="text" class='g_input_txt' id="debtor_control" style="width: 100%"  title="<?=$table_data->debtor_control;?>"/>
                <input type="hidden" name="debtor_control" id="h_debtor_control" title="<?=$table_data->debtor_control;?>"/>            </td>
            <td><?=$table_data->debtor_control_des;?></td>
        </tr>
        <tr>
            <td>Credit For Control A/C</td>
            <td>
                <input type="text" class='g_input_txt' id="creditor_control" style="width: 100%" title="<?=$table_data->creditor_control;?>"/>
                <input type="hidden" name="creditor_control" id="h_creditor_control" title="<?=$table_data->creditor_control;?>"/>            </td>
            <td><?=$table_data->creditor_control_des;?></td>
        </tr>
        <tr>
            <td>Sales</td>
            <td>
                <input type="text" class='g_input_txt' id="sales" style="width: 100%" title="<?=$table_data->sales;?>" />
                <input type="hidden" name="sales" id="h_sales" title="<?=$table_data->sales;?>"/>            </td>
            <td><?=$table_data->sales_des;?></td>
        </tr><tr>
            <td>Sales Return</td>
            <td>
                <input type="text" class='g_input_txt' id="sales_return" style="width: 100%" title="<?=$table_data->sales_return;?>" />
                <input type="hidden" name="sales_return" id="h_sales_return" title="<?=$table_data->sales_return;?>"/>            </td>
            <td><?=$table_data->sales_return_des;?></td>
        </tr>
        <tr>
            <td>Purchase</td>
            <td>
                <input type="text" class='g_input_txt' id="purchase" style="width: 100%" title="<?=$table_data->purchase;?>"/>
                <input type="hidden" name="purchase" id="h_purchase" title="<?=$table_data->purchase;?>"/>            </td>
            <td><?=$table_data->purchase_des;?></td>
        </tr><tr>
            <td>Purchase Return</td>
            <td>
                <input type="text" class='g_input_txt' id="purchase_return" style="width: 100%" title="<?=$table_data->purchase_return;?>"/>
                <input type="hidden" name="purchase_return" id="h_purchase_return" title="<?=$table_data->purchase_return;?>"/>            </td>
            <td><?=$table_data->purchase_return_des;?></td>
        </tr><tr>
            <td>Discount Given</td>
            <td>
                <input type="text" class='g_input_txt' id="discount" style="width: 100%" title="<?=$table_data->discount;?>"/>
                <input type="hidden" name="discount" id="h_discount" title="<?=$table_data->discount;?>"/>            </td>
            <td><?=$table_data->discount_des;?></td>
        </tr>
        <tr>
          <td>Stock</td>
          <td><input type="text" class='g_input_txt' id="stock" style="width: 100%" title="<?=$table_data->stock;?>"/>
          <input type="hidden" name="stock" id="h_stock" title="<?=$table_data->stock;?>"/></td>
          <td><?=$table_data->stock_des;?></td>
        </tr>
        <tr>
          <td>Good In Transist</td>
          <td><input type="text" class='g_input_txt' id="good_in_transist" style="width: 100%" title="<?=$table_data->good_in_transist;?>"/>
          <input type="hidden" name="good_in_transist" id="h_good_in_transist" title="<?=$table_data->good_in_transist;?>"/></td>
          <td><?=$table_data->good_in_transist_des;?></td>
        </tr>
        <tr>
          <td>Cost of Sales</td>
          <td><input type="text" class='g_input_txt' id="cost_of_sales" style="width: 100%" title="<?=$table_data->cost_of_sales;?>"/>
          <input type="hidden" name="cost_of_sales" id="h_cost_of_sales" title="<?=$table_data->cost_of_sales;?>"/></td>
          <td><?=$table_data->cost_of_sales_des;?></td>
        </tr>
           <tr>
             <td>VAT</td>
             <td><input  type="text" class='g_input_txt' id="vat" style="width: 100%" title="<?=$table_data->vat;?>"/>
               <input type="hidden" name="vat" id="h_vat" title="<?=$table_data->vat;?>"/></td>
             <td><?=$table_data->vat_des;?></td>
           </tr>
           <tr>
         <td>Customer Discount </td>
             <td><input  type="text" class='g_input_txt' id="customer_discount" style="width: 100%" title="<?=$table_data->customer_discount;?>"/>
               <input type="hidden" name="customer_discount" id="h_customer_discount" title="<?=$table_data->customer_discount;?>"/></td>
             <td><?=$table_data->customer_discount_des;?></td>
        </tr>

           <tr>
             <td>Suspend</td>
             <td><input type="text" class='g_input_txt' id="suspend" style="width: 100%" title="<?=$table_data->suspend;?>"/>
               <input type="hidden" name="suspend" id="h_suspend" title="<?=$table_data->suspend;?>"/></td>
             <td><?=$table_data->suspend_des;?></td>
           </tr>
             <td>Advance</td>
             <td><input   type="text" class='g_input_txt' id="advance" style="width: 100%" title="<?=$table_data->advance;?>"/>
               <input type="hidden" name="advance" id="h_advance" title="<?=$table_data->advance;?>"/></td>
             <td><?=$table_data->advance_des;?></td>
        </tr>		
    </table>
    <div style="text-align: right; padding: 7px;">
        <input type="button" id="btnExit" title='Exit' />
        <input type="button" id="btnReset" title="Reset" />
        <?php if($this->user_permissions->is_add('017')){ ?>
        <input type="submit" id="btnSave1" title='Save <F8>' />
        <?php } ?>
    </div>
    </form>
</div>
<?php } ?>