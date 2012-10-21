<?php include_once('header.php'); ?>
    <div class="row">
        <div class="span6 box">
            <h1>Associated RFIDs</h1>
            <ul>
                <?php foreach ($this->user->rfids as $rfid) : ?>
                    <li>
                        <form method="post" action="<?=\CuteControllers\Router::get_link('/user/rfid_delete?username=' . $this->user->username)?>">
                            <?=$rfid?> (<?=str_pad(hexdec(substr($rfid, 4)), 10, "0", STR_PAD_LEFT)?>)
                            <input type="hidden" name="rfID" value="<?=$rfid?>" />
                            <input type="submit" value="delete" class="btn btn-danger" />
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="span5 well">
            <h1>Enroll new RFID</h1>
            <form method="post">
                <input type="text" placeholder="RFID Token ID" name="rfID_plaintext" />
                <input type="submit" value="add" class="btn btn-success" />
            </form>
            <div class="rfid-scan">
                <p>Or scan the RFID token on the reader.</p>
                <form method="post">
                    <input type="text" name="rfID" />
                </form>
            </div>
        </div>
    </div>
<?php include_once('footer.php'); ?>
