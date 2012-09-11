<?php include_once('header.php'); ?>
    <div class="row">
        <div class="span12 box">
            <h1>Change Google Apps Password</h1>
            <p><strong>Stop!</strong> You can access your Google Apps account from My.StudentRND at any time without ever completing this step!
                This is only necessary if you want to access your email from a POP or IMAP client, or connect with an IM client.</p>
            <p>You can change this password as often as you would like. Changing this password <strong>will not</strong> change your
                My.StudentRND password, however you must first confirm your session using your My.StudentRND password.</p>
            <form method="post">
                <input type="password" name="confirm" placeholder="Current My.StudentRND Password" /><br />
                <input type="password" name="password" placeholder="New Google Apps Password" /><br />
                <input type="submit" class="btn btn-success" value="Change it!" />
            </form>
        </div>
    </div>
<?php include_once('footer.php'); ?>
