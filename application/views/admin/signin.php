

<form action="<?php echo site_url('admin/login/verify_admin_user'); ?>" method="post" accept-charset="utf-8">
 <fieldset>
    <legend>Administrator Login...</legend>
    <label>type in your email</label>
    <input type="text" placeholder="Email Address">
    <span class="help-block">Example block-level help text here.</span>
    <label>type in your password</label>
    <input type="password" placeholder="Password">
    <span class="help-block">The password you log in with</span>
    <button type="submit" class="btn">Submit</button>
  </fieldset>	
</form>