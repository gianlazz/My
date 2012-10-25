<?php include_once('header.php'); ?>
<form id="user-cp" action="<?=APP_URI?>/login/change_password" method="post">
    <input type="text" disabled="true" value="<?php echo \StudentRND\My\Models\User::current()->username ?>" />
    <input type="password" name="password" placeholder="New Password" />
    <input type="password" name="password2" placeholder="New Password (Confirm)" />
    <input type="submit" value="Change my password! &raquo;" />
    <hr class="clearfix" />
</form>
<?php include_once('footer.php'); ?>
