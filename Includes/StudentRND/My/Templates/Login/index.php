<?php include_once('header.php'); ?>
<form id="user-login" action="<?=APP_URI?>/login" method="post">
    <input type="hidden" name="return" value="<?=$this->request->request('return');?>" />
    <input type="username" name="username" placeholder="User" />
    <input type="password" name="password" placeholder="Pass" />
    <?php
        $subarr = array(
                        "Let's do this",
                        "Engage!",
                        "Ready?",
                        );
        $subtxt = $subarr[array_rand($subarr)];
    ?>
    <input type="submit" value="<?=$subtxt;?> &raquo;" />
    <hr class="clearfix" />
</form>
<div id="rfid" style="display:none">
    <span class="or-block">
        <hr />
        <span>or</span>
        <hr />
        <hr />
    </span>
    <h2>Swipe your RFID token on the reader</h2>
    <form id="rfid-login" action="<?=APP_URI?>/login" method="post" style="display:none">
        <input type="text" name="rfid-token" id="rfid-token" />
    </form>
</div>
<?php include_once('footer.php'); ?>
