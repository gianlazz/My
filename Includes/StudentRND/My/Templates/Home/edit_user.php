<?php include_once('header.php'); ?>
    <div class="row">
        <div class="span12 box">
            <form method="post">
                <?php $user = $this->user; ?>
                <?php include_once('widgets/user_edit.php'); ?>
                <input type="submit" class="btn btn-success" value="Update" />
            </form>
        </div>
    </div>
<?php include_once('footer.php'); ?>
