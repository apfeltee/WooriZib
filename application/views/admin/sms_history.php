<div class="row">
    <div class="col-lg-12">
        <h3 class="page-title">문자 전송 <small>내역</small></h3>
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="/adminhome/index"><?php echo lang("menu.home");?></a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="#">문자 전송 내역</a>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="help-block">* 총 <?php echo $total;?>건의 전송 내역이 검색되었습니다.</div>
        <table class="table table-bordered table-striped table-condensed flip-content">
            <thead>
            <tr>
                <th class="text-center">IDX</th>
                <th class="text-center">발신자</th>
                <th class="text-center">수신자</th>
                <th class="text-center" width="500">메세지</th>
                <th class="text-center">전송타입</th>
                <th class="text-center">차감횟수</th>
                <th class="text-center" width="200">결과값</th>
				<th class="text-center">전송페이지</th>
				<th class="text-center">발송인</th>
                <th class="text-center">발송시간</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!$query){?>
                <tr>
                    <td class="text-center" colspan="10"><?php echo lang("msg.nodata");?></td>
                </tr>
            <?php }?>
            <?php foreach($query as $val){?>
                <tr>
                    <td class="text-center"><?php echo $val->id;?></td>
                    <td class="text-center"><?php echo $val->sms_from;?></td>
                    <td class="text-center"><a href="/adminsms/history_view/<?php echo $val->id;?>">수신자확인</a></td>
                    <td style="word-break:break-all;"><?php echo cut($val->msg,200);?></td>
                    <td class="text-center">
					<?php 
						if($val->type=="A")	echo "단문(SMS)";
						if($val->type=="C")	echo "장문(LMS)";
						if($val->type=="D")	echo "포토(MMS)";
					?>
                    </td>
					<td class="text-center"><?php echo $val->minus_count;?></td>
					<?php if($val->result=="발신성공"){?>
                    <td><strong><?php echo $val->result?></strong></td>
					<?php } else {?>
					<td style="color:red;"><?php echo $val->result?></td>
					<?php }?>
					<td class="text-center">
					<?php 
						if($val->page=="member")	echo "회원관리";
						if($val->page=="contact")	echo "고객관리";
						if($val->page=="enquire")	echo "의뢰하기";
						if($val->page=="signup")	echo "회원가입";
						if($val->page=="concern")	echo "실시간연락받기";
						if($val->page=="user_enquire")	echo "의뢰접수";
						if($val->page=="confirm")	echo "SMS인증";
					?>
					</td>
                    <td class="text-center">
					<?php 
					if($val->member_id){
						echo $val->member_name."(".$val->member_email.")";							
					}else{
						echo "사이트발송";
					}?>
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