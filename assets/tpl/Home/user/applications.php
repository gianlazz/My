<?php include_once(TEMPLATE_DIR . '/Home/header.php'); ?>
    <div class="row">
        <div class="span12 box">
            <h1>Application Tokens</h1>
            <table class="table">
                <thead>
                    <tr>
                        <td>Name</td>
                        <td>Application ID</td>
                        <td>Private Key</td>
                    </tr>
                </thead>
                <?php foreach ($this->user->applications as $app) : ?>
                    <tr>
                        <td><?=$app->name?></td>
                        <td><?=$app->applicationID?></td>
                        <td><?=$app->key?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <?php if (\StudentRND\My\Models\User::current()->is_admin) : ?>
            <div class="span12 well">
                <form method="post" action="<?=\CuteControllers\Router::link('/user/applications/create?username=' . $this->user->username)?>">
                    <input type="text" placeholder="Name" name="name" /><br />
                    <input type="submit" value="Issue Application Key" class="btn btn-primary" />
                </form>
            </div>
        <?php endif; ?>
    </div>
<?php include_once(TEMPLATE_DIR . '/Home/footer.php'); ?>
