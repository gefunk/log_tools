
<div id="tabs-wrapper">
  <div class="row-fluid">
		<a class="span2 tab <?php echo ($user_link == "all") ? 'active' : ''; ?>" href="<?php echo site_url(); ?>/users"> Users </a>
		<a class="span2 tab <?php echo ($user_link == "new") ? 'active' : ''; ?>" href="<?php echo site_url(); ?>/users/add"> New user </a>
		<a class="span2 tab <?php echo ($user_link == "profile") ? 'active' : ''; ?>" href="<?php echo site_url(); ?>/users/profile"> Profile </a>
	</div>
</div>