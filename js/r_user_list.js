$(document).ready(function(){
	var OrAction=$("#print_pdf").attr("action");
	$("#btnPrint").click(function(){     
		if($("input[type='radio']:checked").length == 0){
			alert("Please select report");
			return false;
		}else{
	    $("#print_pdf").attr("action",OrAction+"/reports/generate");//1020-106
	    $("#print_pdf").submit();//1020-106
	    $("#print_pdf").attr("action",OrAction);//1020-106 
	}
});

	$("#printExcel").click(function(){//1020-106
		$("#print_pdf").attr("action",OrAction+"/excelReports/generate");
		$(".printExcel").submit();
		$("#print_pdf").attr("action",OrAction);        
	}); 

	$('input:radio').click(function () {
		if ($(this).attr('excel')) {
			$('#printExcel').removeAttr("disabled")
		} else{
			$('#printExcel').attr("disabled","disabled")
		};
	});

	$("#cluster").change(function(){

		var path;

		if($("#cluster").val()!=0)
		{
			path="index.php/main/load_data/utility/get_branch_name2";
		}
		else
		{
			path="index.php/main/load_data/utility/get_branch_name3";
		}


		$.post(path,{
			cl:$(this).val(),
		},function(res){
			$("#branch").html(res);
		},'text');  

	});

	
});