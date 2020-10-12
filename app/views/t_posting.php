<?php if($this->user_permissions->is_view('035')){ ?>
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
<script type="text/javascript" src="<?=base_url()?>js/t_posting.js"></script>

<h2 style="text-align: center;">Posting</h2>
<div class="dframe" id="mframe" style="text-align: center;">
    <?=$table_data;?>
</div>
<?php } ?>