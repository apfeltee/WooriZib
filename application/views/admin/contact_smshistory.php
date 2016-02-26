
<div class="row">
    <div class="col-lg-12">
        <h3 class="page-title">sms 전송 <small>내역</small></h3>
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="/adminhome/index">홈</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="#">sms 전송 내역</a>
                </li>
            </ul>
        </div>
    </div>
</div><!-- /.row -->

<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="help-block">* 총 <?php echo $total;?>건의 로그가 검색되었습니다.</div>
        <table class="table table-bordered table-striped table-condensed flip-content">
            <thead>
            <tr>
                <th class="text-center" style="width:100px;">IDX</th>
                <th class="text-center">보낸 번호</th>
                <th class="text-center">연락처</th>
                <th class="text-center" style="width:500px;">메세지</th>
                <th class="text-center" >종류</th>
                <th class="text-center" style="width:40px;">횟수</th>
                <th class="text-center" style="width:150px;">로그일자</th>

            </tr>
            </thead>
            <tbody>
            <?php if(!$query){?>
                <tr>
                    <td class="text-center" colspan="6">로그 내역이 없습니다.</td>
                </tr>
            <?php }?>
            <?php foreach($query as $value){?>
                <tr>
                    <td class="text-center"><?php echo $value->id;?></td>
                    <td style="word-break:break-all;"><?php echo $value->source;?></td>
                    <td style="word-break:break-all;"><?php echo $value->destination;?></td>
                    <td class="text-center" style="word-break:break-all;"><?php echo $value->msg;?></td>
                    <td class="text-center"><?php echo $value->kind;?></td>
                    <td class="text-center"><?php echo $value->cnt;?></td>
                    <td class="text-center"><?php echo $value->date;?></td>
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