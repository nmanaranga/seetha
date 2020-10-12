$(document).ready(function(){
    $(".g_input_txt").autocomplete('index.php/main/load_data/m_defult_account/auto_com', {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatCustomer,
        formatResult: formatCustomerResult
    });
    
    $(".g_input_txt").blur(function(){
	set_cus_values($(this));
    });
    
    $(".g_input_txt").keydown(function(e){
	if(e.keyCode == 13){
	    set_cus_values($(this));
	}
    });
});

function set_cus_values(f){
    var v = f.val();
    v = v.split("~");
    if(v.length == 2){
	f.val(v[0]);
        $("#h_"+f.attr("id")).val(v[0]);
        f.parent().parent().children().eq(2).html(v[1]);
    }
}

function formatCustomer(row){
    return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
}

function formatCustomerResult(row){
    return row[0]+"~"+row[1];
}