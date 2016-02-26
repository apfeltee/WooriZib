<div class="row">
    <div class="col-lg-12">
        <h3 class="page-title">건축물자가진단 의뢰<small>관리</small></h3>
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="/adminhome/index"><?php echo lang("menu.home");?></a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="#">건축물자가진단 의뢰</a>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="help-block">* 총 <?php echo $total;?>건</div>
        <table class="table table-bordered table-striped table-condensed flip-content">
            <thead>
            <tr>
                <th class="text-center">지번주소</th>
                <th class="text-center">의뢰인</th>
				<th class="text-center">견적서</th>
                <th class="text-center">의뢰일자</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!$query){?>
                <tr>
                    <td class="text-center" colspan="4"><?php echo lang("msg.nodata");?></td>
                </tr>
            <?php }?>
            <?php foreach($query as $val){?>
                <tr>
                    <td class="text-center"><a href="/adminbuilding/building_enquire_view/<?php echo $val->id;?>"><?php echo $val->address;?></a></td>
                    <td class="text-center"><?php echo $val->member_name;?>(<?php echo $val->member_email;?>, <?php echo $val->member_phone;?>)</td>
					<td class="text-center">
						<?php foreach($val->estimate as $file){?>
							<i class="glyphicon glyphicon-list-alt"></i>
						<?php }?>
					</td>
                    <td class="text-center"><?php echo $val->date;?></td>
                </tr>
            <?php }?>
            </tbody>
        </table>

        <div class="row text-center">
            <div class="col-sm-12">
                <ul class="pagination" style="float:none;">
                    <?php echo $pagination;?>
                </ul>
            </div>
        </div>
    </div>
</div>