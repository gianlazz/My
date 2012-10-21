<?php include_once('header.php'); ?>
    <div class="row">
        <?php if (\StudentRND\My\Models\User::current()->is_admin && $this->user->username !== \StudentRND\My\Models\User::current()->username) : ?>
            <div class="span12 well">
                <h1>Send Password Reset Email</h1>
                <p>Clicking the button below will do the following:
                    <ol>
                        <li>Automatically generate and set a new password for the user</li>
                        <li>Set the "password change required on next login" flag on the user</li>
                        <li>Send the user an email with the new password</li>
                    </ol></p>
                <p>The new password will be generated automatically from the system dictionary. You won't be told the new password if you use this
                    method.</p>
                <form method="post" action="/user/send_password_reset_email?username=<?=$this->user->username?>">
                    <input type="submit" class="btn btn-warning" value="Reset and Email" />
                </form>
                <p>(The new password will be sent to: <strong><tt><?=$this->user->email?></tt></strong>)</p>
            </div>
        <?php endif; ?>
        <div class="span12 box">
            <h1>Change Password</h1>
            <form method="post">
                <?php if(\StudentRND\My\Models\User::current()->is_admin) : ?>
                    <input type="password" name="current" placeholder="Admin Password" /> * Your password, not the user's!<br />
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
