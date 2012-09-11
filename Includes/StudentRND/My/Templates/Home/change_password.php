<?php include_once('header.php'); ?>
    <div class="row">
        <div class="span12 box">
            <h1>Change Password</h1>
            <form method="post">
                <?php if(\StudentRND\My\Models\User::current()->is_admin) : ?>
                    <input type="password" name="current" placeholder="Admin Password" /><br />
                <?php else : ?>
                    <input type="password" name="current" placeholder="Current Password" /><br />
                <?php endif; ?>
                <input type="password" name="password" placeholder="New Password" /><br />
                <input type="password" name="password2" placeholder="New Password (confirm)" /><br />
                <input type="submit" class="btn btn-success" value="Change it!" />
            </form>
        </div>
    </div>
<?php include_once('footer.php'); ?>
