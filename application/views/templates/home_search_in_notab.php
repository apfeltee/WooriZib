<link rel="stylesheet" href="/assets/plugin/multiselect/css/bootstrap-multiselect.css" type="text/css">
<script type="text/javascript" src="/assets/plugin/multiselect/js/bootstrap-multiselect.js"></script>

<form action="/search/set_search/main" id="search_form" method="post">
	<input type="hidden" id="search_type" name="search_type">
	<input type="hidden" id="search_value" name="search_value">
	<input type="hidden" id="type" name="type" class="type">
	<input type="hidden" id="lat" name="lat">
	<input type="hidden" id="lng" name="lng">
	<input type="hidden" id="sido_val" name="sido_val" value="<?php echo $config->INIT_SIDO;?>">
	<input type="hidden" id="gugun_val" name="gugun_val" value="<?php if($config->INIT_SIDO) echo $config->INIT_GUGUN;?>">
	<input type="hidden" id="dong_val" name="dong_val">
	<input type="hidden" id="subway_local_val" name="subway_local_val">
	<input type="hidden" id="hosun_val" name="hosun_val">
	<input type="hidden" id="station_val" name="station_val">
	<input type="hidden" id="theme" name="theme[]">
	<input type="hidden" id="keyword_front" name="keyword_front">
	<input type="hidden" id="region" name="region">
	<?php echo $this->load->view("templates/top_search_main");?>
</form>