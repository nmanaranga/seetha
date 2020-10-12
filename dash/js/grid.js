(function($){
    
    $.fn.genTable = function(rows, cols, width){
        this.widths=[100]
        var id=this.attr("id");
        
        this.newt=function(rows, cols, id){
            this.gTrO	= "<tr>"+this.genarateTd(cols)+"</tr>";
            this.tHead	= "<table id='"+id+"' style='width : "+width+"px'><thead>"+this.gTrO+"</thead>"
            this.tBody  = "<tbody>"+this.genarateBody(rows,this.gTrO)+"<tbody>";
            
            this.parent().html(this.tHead+this.tBody+"</table>");
        }
        
        this.genarateBody=function(rows,gTrO){
            var body="";
            
            for(var i=0;i<rows;i++){
                body+=gTrO
            }
            return body;
        }
        
        this.genarateTd =function(cols){
            var row="";
            
            for(var i=0;i<cols;i++){
                row+="<td>&nbsp;</td>";
            }
            
            return row;
        }
        
        this.newt(rows, cols, id, width);
    }
    
    $.fn.grid = function(h){
        
        var id = this.attr("id");
        
        if(h != undefined){
            if(h.height == undefined){
                h.height = 250;
            }
            
            if(h.width == undefined){
                h.width = this.width();
            }
            
            if(h.prag != undefined){
                this.alignGrid(h.prag);
            }
        }else{
            h = new Object;
            h.height = 250;
            h.width = this.width();
        }
        
        var head = this.children("thead");
        
        var body = this.children("tbody");
        
        var foot = this.children("tfoot");
        
        body.each(function(){
            $(this).find("tr").each(function(i){
                $(this).find("td").each(function(j){
                    $(this).attr("id", id+"_"+i+"_"+j);
                    $(this).attr("class", id+"_"+j);
                    $(this).html("&nbsp;");
                });
            });
        });
        
        var g  = "<div style='background-color : #EEE; width : "+(h.width+17)+"px;' id='"+id+"'><div style='width : "+h.width+"px;'>";
            g += "<table id='"+id+"_head' class='tb_head' style='width : "+h.width+"px;' cellpadding='0' cellspacing='0'>"+head.html()+"</table>";
            g += "</div>";
            g += "<div style='width : "+(h.width+17)+"px;; height : "+h.height+"px;; overflow-y : scroll;'>";
            g += "<table id='"+id+"_body' class='tb_body' style='width : "+h.width+"px;' cellpadding='0' cellspacing='0'>"+body.html()+"</table>";
            g += "</div>";
            
            if(foot.html() != null){
                g += "<div style='width : "+h.width+"'>";
                g += "<table id='"+id+"_foot' class='tb_foot' style='width : "+h.width+"px;' cellpadding='0' cellspacing='0'>"+foot.html()+"</table>";
                g += "</div>";
            }
            
            g += "</div>";
        
        this.parent().html(g);
    }
    
    $.fn.alignGrid = function(a){
        var head = this.children("thead");
        
        var body = this.children("tbody");
        
        var foot = this.children("tfoot");
        
        head.each(function(){
            $(this).find("tr").each(function(i){
                $(this).find("td").each(function(j){
                    if(a[j] != undefined){
                        if(a[j]['align']){
                            $(this).css("text-align", a[j]['align']);
                        }
                        
                        if(a[j]['width']){
                            $(this).css("width", a[j]['width']);
                        }
                    }
                });
            });
        });
        
        body.each(function(){
            $(this).find("tr").each(function(i){
                $(this).find("td").each(function(j){
                    if(a[j] != undefined){
                        if(a[j]['align']){
                            $(this).css("text-align", a[j]['align']);
                        }
                        
                        if(a[j]['width']){
                            $(this).css("width", a[j]['width']);
                        }
                        
                        if(a[j]['edit']){
                            $(this).attr("contenteditable", a[j]['edit']);
                        }
                        
                        if(a[j]['total']){
                            if(a[j]['type']){
                                $(this).attr("value", a[j]['type']);
                            }else{
                                $(this).attr("value", 'amount');
                            }
                        }
                    }
                });
            });
        });
        
        foot.each(function(){
            $(this).find("tr").each(function(i){
                $(this).find("td").each(function(j){
                    if(a[j] != undefined){
                        if(a[j]['align']){
                            $(this).css("text-align", a[j]['align']);
                        }
                        
                        if(a[j]['width']){
                            $(this).css("width", a[j]['width']);
                        }
                    }
                });
            });
        });
    }
    
    $.fn.setGrid = function(s){
        this.each(function(){
            $(this).find("div").each(function(i){
                if(i == 1){
                    $(this).find("tr").each(function(j){
                        $(this).find("td").each(function(k){
                            if(s[j]!= undefined){
                                if(s[j][k] != undefined){ $(this).html(s[j][k]); }
                            }
                        });
                    });
                }
            });
        });
    }
    
    $.fn.setData = function(s){
        var len; var td_el; var total = new Array(); var val;
        var id = this.attr("id");
        this.each(function(){
            $(this).find("div").each(function(i){
                if(i == 1){
                    $(this).find("tr").each(function(j){
                        len = j; td_el = $(this);
                        $(this).find("td").each(function(k){
                            if(s[j]!= undefined){
                                if(s[j][k] != undefined){
                                    $(this).html(s[j][k]);
                                }else{
                                    $(this).html("&nbsp;");
                                    total[j] = 0;
                                }
                            }else{
                                $(this).html("&nbsp;");
                            }
                        });
                    });
                }
            });
        });
        
        if(len < s.length){
            var row = ""; 
            for(var i=0; i<s.length; i++){
                td_el.find("td").each(function(j){
                    $(this).html(s[i][j]);
                    $(this).attr("id", id+"_"+i+"_"+j);
                    val = $(this).attr("value");
                    if(val == "amount"){
                        if(total[j]){
                            total[j] += parseFloat(s[i][j]);
                        }else{
                            total[j] = parseFloat(s[i][j]);
                        }
                    }else if(val == "number"){
                        if(total[j]){
                            total[j] += parseInt(s[i][j]);
                        }else{
                            total[j] = parseInt(s[i][j]);
                        }
                    }else{
                        total[j] = 0;
                    }
                });
                row += "<tr>"+td_el.html()+"</tr>";
            }
            
            this.each(function(){
                $(this).find("div").each(function(i){
                    if(i == 1){
                        $(this).find("tbody").html(row);
                    }
                });
            });
            
        }
        
        this.each(function(){
            $(this).find("div").each(function(i){
                if(i == 2){
                    $(this).find("tr").each(function(j){
                        $(this).find("td").each(function(k){
                            if(total[k]){
                                $(this).html($.fn.num(total[k]));
                            }
                        });
                    });
                }
            });
        });
    }
    
    $.fn.amo = function (nStr) {
        nStr = $.fn.round(nStr);
        nStr += '';
        var x = nStr.split('.');
        var x1 = x[0];
        var x2 = x.length > 1 ? '.' + x[1] : '.00';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }
    
    $.fn.num = function (nStr) {
        nStr = $.fn.round(nStr);
        nStr += '';
        var x = nStr.split('.');
        var x1 = x[0];
        var x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }
    
    $.fn.round = function(x){
        return Math.round(x*100)/100;
    }

    $.fn.getData = function(d){
        var data1 = data2 = new Array(); var va = "";
        if(d != undefined){
            alert(d);
        }else{
            this.each(function(){
                $(this).find("div").each(function(i){
                    if(i == 1){
                        $(this).find("tr").each(function(j){
                            data1 = new Array();
                            $(this).find("td").each(function(k){
                                va = $(this).html(); va = va.replace("<br>", " ");
                                va = $.trim(va);
                                if(va != "&nbsp;") { data1[k] = va; }
                            });
                            if(data1.length > 0) { data2[j] = data1; }
                        });
                    }
                });
            });
        }
        
	return data2;
    }
    
    $.fn.emptyGrid = function(){
        this.each(function(){
            $(this).find("div").each(function(i){
                if(i >= 1){
                    $(this).find("td").each(function(k){
                        $(this).html("&nbsp;");
                    });
                }
            });
        });
    }
    
    $.fn.setTotal = function(l, c, a){
        var id = this.attr("id"); var total = t = tt =0; var val = "";
        if(c != undefined) { c = c.split(" "); }
        $("."+id+"_"+l).keyup(function(){
            total = 0; r = $(this).attr("id").split("_");
            $("#"+id).each(function(){
                $(this).find("div").each(function(i){
                    if(i == 1){
                        $(this).find("tr").each(function(j){
                            $(this).find("td").each(function(k){
                                if(k == l && $(this).html() != "&nbsp;"){
                                    t = parseFloat($(this).html());
                                    if(c != undefined){
                                        tt = parseFloat($("#"+id+"_"+r[1]+"_"+c[1]).html());
                                        if(isNaN(tt)){
                                            tt = 0;
                                        }
                                        switch(c[0]){
                                            case '>':
                                                if(t>tt){
                                                    
                                                }else{
                                                    t = 0;
                                                    $(this).html("");
                                                    if(a != undefined){
                                                        alert(a);
                                                    }
                                                }
                                                break;
                                            
                                            case '<':
                                                if(t<tt){
                                                    
                                                }else{
                                                    t = 0;
                                                    $(this).html("");
                                                    if(a != undefined){
                                                        alert(a);
                                                    }
                                                }
                                                break;
                                            
                                            case '<=':
                                                if(t<=tt){
                                                    
                                                }else{
                                                    t = 0;
                                                    $(this).html("");
                                                    if(a != undefined){
                                                        alert(a);
                                                    }
                                                }
                                                break;
                                            
                                            case '==':
                                                if(t==tt){
                                                    
                                                }else{
                                                    t = 0;
                                                    $(this).html("");
                                                    if(a != undefined){
                                                        alert(a);
                                                    }
                                                }
                                                break;
                                            
                                            case '>=':
                                                if(t>=tt){
                                                    
                                                }else{
                                                    t = 0;
                                                    $(this).html("");
                                                    if(a != undefined){
                                                        alert(a);
                                                    }
                                                }
                                                break;
                                            
                                        }
                                    }
                                    
                                    if(! isNaN(t)) { total += t; }
                                    val = $(this).attr("value");
                                }
                            });
                        });
                    }
                });
            });
            
            $("#"+id).each(function(){
                $(this).find("div").each(function(i){
                    if(i == 2){
                        $(this).find("tr").each(function(j){
                            $(this).find("td").each(function(k){
                                if(k == l){
                                    $(this).html($.fn.amo(total));
                                }
                            });
                        });
                    }
                });
            });
        });
    }
    
    $.fn.getTotal = function(l){
        var id = this.attr("id"); var total = 0; var val = 0;
        this.each(function(){
            $(this).find("div").each(function(i){
                if(i == 1){
                    $(this).find("tr").each(function(j){
                        $(this).find("td").each(function(k){
                            val = parseFloat($(this).html());
                            if(k == l && ! isNaN(val)){
                                total += val;
                            }
                        });
                    });
                }
            });
        });
        
        return total;
    }
})(jQuery);