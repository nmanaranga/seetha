$(document).ready(function(){
    var arrayOfData = new Array(
        [10.3,'Jan','#f3f3f3'],
        [15.2,'Feb','#f4f4f4'],
        [13.1,'Mar','#cccccc'],
        [16.3,'Apr','#333333'],
        [14.5,'May','#666666']
    );
    
    $('#divForGraph').jqBarGraph({ data: arrayOfData, legend : true, barSpace : 7, width : 1000});
});