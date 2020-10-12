$(document).ready(function(){
 $("#div_supervisor").css("display","none");
    init("1");

    $("#btnClustr").click(function(){
        $("#div_cluster").css("display","block");
        $("#div_supervisor").css("display","none");
    });
    $("#btnSupervisor").click(function(){
       $("#div_cluster").css("display","none");
       $("#div_supervisor").css("display","block");
       
   });
// 
// 

});


function init(month){
  $.post("index.php/main/get_data/dashboard", {
    month:month,
}, function(D){
    $("#tblbodyx").html(D.tbl);
    $("#tblbodyy").html(D.emp_tbl);
    chartx(D.CL, D.tar,D.arch,D.month);
    charty(D.emp_det, D.emp_tar,D.emp_arch,D.month);


},"json");
}

function chartx(nameArray,targetArray,achvementArray,month){

   Highcharts.chart('containerx', {
    chart: {
        // backgroundColor: '#333',
        type: 'column'
    },
    title: {
        text: 'Cluster Wise Sales Target ('+month+')'
    },yAxis: {
        min: 0,
        title: {
            text: 'Amount (Rs.)'
        }
    },
    xAxis: {
        categories: nameArray,
        title: {
            text: 'Clusters'
        }
    },  tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
        '<td style="padding:0"><b>{point.y:.1f} Rs</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    credits: {
        enabled: false
    },
    series: [{
        name: 'Target',
        data: targetArray,
        color: {
            linearGradient: { x1: 0, x2: 0, y1: 0, y2: 1 },
            stops: [
            [0, '#21618c']
            ]
        }
    },  {
        name: 'Archivement',
        data: achvementArray,
        color: {
            linearGradient: { x1: 0, x2: 0, y1: 0, y2: 1 },
            stops: [
            [0, '#7fb3d5']
            ]
        }
    }]
});



}


function charty(nameArray,targetArray,achvementArray,month){

    
  Highcharts.chart('containery', {
    chart: {
        // backgroundColor: '#E9967A',
        type: 'column'
    },
    title: {
        text: 'Supervisor Wise Sales Target ('+month+')'
    },yAxis: {
        min: 0,
        title: {
            text: 'Amount (Rs.)'
        }
    },
    xAxis: {
        categories: nameArray,
        title: {
            text: 'Clusters'
        }
    },  tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
        '<td style="padding:0"><b>{point.y:.1f} Rs</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    credits: {
        enabled: false
    },
    series: [{
        name: 'Target',
        data: targetArray,
        color: {
            linearGradient: { x1: 0, x2: 0, y1: 0, y2: 1 },
            stops: [
            [0, '#7798BF']
            ]
        }
    },  {
        name: 'Archivement',
        data: achvementArray,
        color: {
            linearGradient: { x1: 0, x2: 0, y1: 0, y2: 1 },
            stops: [
            [0, '#90EE7E']
            ]
        }
    }]
});

}



      $(function() {
       $('.monthYearPicker').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'yy-mm'
      }).focus(function() {
        var thisCalendar = $(this);
        $('.ui-datepicker-calendar').detach();
        $('.ui-datepicker-close').click(function() {
         var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
         var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
         thisCalendar.datepicker('setDate', new Date(year, month, 1));

             init($("#txtMonth").val());
       });

      });
    });

