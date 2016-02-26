<HTML>
	<HEAD>
		<meta http-equiv="refresh" content="10">
	</HEAD>
	<script type="text/javascript">
		<?php 
		if($this->session->userdata("timeout")){
			if($this->session->userdata("timeout") + $config->AUTO_LOGOUT * 60 < time()){?>
			parent.location.href="/member/logout";
			<?php 
			} 
		}
		?>
	</script>
	<BODY>
		auto logout page
	</BODY>
</HTML>