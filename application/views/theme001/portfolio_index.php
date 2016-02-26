<?php 
	$page_category = ($page_category) ? $page_category : 0;
?>
<link rel="stylesheet" type="text/css" href="/assets/plugin/megafolio/css/settings.css" media="screen" /> 
<script type="text/javascript" src="/assets/plugin/megafolio/js/jquery.themepunch.tools.min.js"></script> 
<script type="text/javascript" src="/assets/plugin/megafolio/js/jquery.themepunch.megafoliopro.js"></script>
<script>
var total;
var api;
var category = '<?php echo $page_category;?>';
jQuery(document).ready(function($) {
	get_list(category,0);
	
	jQuery(".fancybox").fancybox();
	
	jQuery('.addmore').click(function() {
		get_list(0, $("#next_page").val());
	})
});

function init_mega(){
	api=jQuery('.megafolio-container').megafoliopro({
		filterChangeAnimation:"scale", 
		filterChangeSpeed:400,         
		filterChangeRotate:99,         
		filterChangeScale:0.4,         
		delay:10,
		paddingHorizontal:10,
		paddingVertical:10,
		layoutarray:[17]   
	});   
}

function get_list(category, page){
	$.getJSON("/portfolio/get_json/"+category+"/"+page+"/"+Math.round(new Date().getTime()),function(data){
		var option = "";
		var str = "";
		var next_page = "";
		$.each(data, function(key, val) {
			if(key=="paging"){
				next_page = val;
				$("#next_page").val(val);
			}
			if(key=="result"){
				str = val;
			}
		});
		if(next_page<13){
			$("#megafolio-container").html(str); 
			init_mega();
		} else {
			api.megaappendentry(str);
		}
		if(next_page) $("#pagination_more").show();
	});  
}
</script>
<div class="main">
  <div class="_container">
    <ul class="breadcrumb">
      <li><a href="/"><?php echo lang("menu.home");?></a></li>
      <li class="active">
		<?php foreach($mainmenu as $val){
			if($val->type=="gallery") echo $val->title;
		}?>
	  </li>
    </ul>
    <!-- BEGIN SIDEBAR & CONTENT -->
    <div class="row margin-bottom-40">
      <!-- BEGIN CONTENT -->
      <div class="col-lg-12">
        <h1>
		<?php foreach($mainmenu as $val){
			if($val->type=="gallery") echo $val->title;
		}?>
		</h1>
        <div class="content-page">
          <div class="filter_padder">
            <div class="filter_wrapper" style="max-width:650px;">
				<a href="<?php echo (!$page_category) ? "#" : "/portfolio/index";?>">
				  <div class="filter <?php echo (!$page_category) ? "selected" : "";?>" data-category=""><?php echo lang("site.all");?></div>
				</a>
                <?php
			    foreach($portfolio_category as $key=>$val){
				  $category_selected = ($val->id==$page_category) ? "selected" : "";
				  $category_last = (end($portfolio_category)->id==$page_category) ? "last-child" : "";				 
				  $category_link = ($val->id==$page_category) ? "#" : "/portfolio/index/".$val->id;
			    ?>
				<a href="<?php echo $category_link;?>">
				  <div class="filter <?php echo $category_selected;?>" data-category="<?php echo $val->id;?>"><?php echo $val->name;?></div>
				</a>
			    <?php }?>
              <div class="clear"></div>
            </div>
          </div>
          <div class="clear"></div>
          <div id="megafolio-container" class="megafolio-container"></div>
          <div style="margin:auto;text-align:center;">
              <input type="hidden" id="next_page"/>
              <!-- THE ADD MORE BUTTON -->
              <button id="pagination_more" type="button" class="addmore btn btn-default" style="width:30%;display:none;"><i class="fa fa-chevron-circle-down"></i> <?php echo lang("site.more");?></button>
          </div>
          <div class="divide90"></div>
        </div>
        <!-- END CONTENT -->
      </div>
      <!-- END SIDEBAR & CONTENT -->
  </div>
</div>