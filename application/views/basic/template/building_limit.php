<style>
#limit_table tr{
	height:25px;
}
</style>
<h4 style="margin-top:30px"><strong>건축가능용도</strong></h4>
<table class="border-table" id="limit_table">
	<tr>
		<th colspan="3">용도지역별건축제한</th>
		<th width="7%">관련법령에 의한 건축허용</th>
		<th width="7%">당해조례에 의한 건축허용</th>
		<th width="7%">관련법령에서 선택적 허용</th>
		<th width="7%">당해조례에서 선택적 허용</th>
		<th width="7%">건축불가</th>
	</tr>
	<?php
	foreach($building_limit as $val){
	
	?>
	<tr>
		<?php if($val->category_sub){?>
			<td class="text-center" style="background-color:#f4f4f4"><?php echo $val->category;?></td>
			<td class="text-center" style="background-color:#f4f4f4"><?php echo $val->category_sub;?></td>
		<?php } else {?>
			<td class="text-center" style="background-color:#f4f4f4" colspan="2"><?php echo $val->category;?></td>
		<?php }?>
		<td><?php echo $val->limit_title;?></td>
		<td class="text-center"><?php if($val->limit_num=="1"){?><i class="glyphicon glyphicon-ok" style="color:green"></i><?php }?></td>
		<td class="text-center"><?php if($val->limit_num=="2"){?><i class="glyphicon glyphicon-ok"></i><?php }?></td>
		<td class="text-center"><?php if($val->limit_num=="3"){?><i class="glyphicon glyphicon-ok"></i><?php }?></td>
		<td class="text-center"><?php if($val->limit_num=="4"){?><i class="glyphicon glyphicon-ok"></i><?php }?></td>
		<td class="text-center"><?php if($val->limit_num=="5"){?><i class="glyphicon glyphicon-ok" style="color:red"></i><?php }?></td>
	</tr>
	<?php }?>
</table>
<h4>
	<small class="text-danger"> * 상기 정보는 관련 공부 및 법령을 기반으로 한 개략적 내용이므로 정확한 내용은 반드시 허가관청 확인 요망</small>
</h4>
<script>
$.fn.rowspan = function(colIdx, isStats) {       
    return this.each(function(){      
        var that;     
        $('tr', this).each(function(row) {      
            $('td:eq('+colIdx+')', this).filter(':visible').each(function(col) {
                  
                if ($(this).html() == $(that).html()
                    && (!isStats 
                            || isStats && $(this).prev().html() == $(that).prev().html()
                            )
                    ) {
                    rowspan = $(that).attr("rowspan") || 1;
                    rowspan = Number(rowspan)+1;
  
                    $(that).attr("rowspan",rowspan);
                               
                    $(this).hide();
                      
                } else {
                    that = this;
                }
                that = (that == null) ? this : that;      
            });     
		});    
    });  
}; 
$.fn.colspan = function(rowIdx) {
    return this.each(function(){
          
        var that;
        $('tr', this).filter(":eq("+rowIdx+")").each(function(row) {
            $(this).find('th').filter(':visible').each(function(col) {
                if ($(this).html() == $(that).html()) {
                    colspan = $(that).attr("colSpan") || 1;
                    colspan = Number(colspan)+1;
                      
                    $(that).attr("colSpan",colspan);
                    $(this).hide();
                } else {
                    that = this;
                }
                that = (that == null) ? this : that;
                  
            });
        });
    });
}
$('#limit_table').rowspan(0);
$('#limit_table').rowspan(1);
</script>