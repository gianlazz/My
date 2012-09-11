<?php include_once('header.php'); ?>
    <?php
        $pictureusers = new \TinyDb\Collection('\StudentRND\My\Models\User', \TinyDb\Sql::create()
                                                                            ->select('*')
                                                                            ->from(\StudentRND\My\Models\User::$table_name)
                                                                            ->where('avatar_url IS NOT NULL')
                                                                            ->order_by('RAND()')
                                                                            ->limit(7 * 2));
    ?>
    <div class="row">
        <div class="pictures">
            <?php foreach ($pictureusers as $user) : ?>
                <img src="<?=$user->avatar_url?>" />
            <?php endforeach; ?>
            <hr />
        </div>
        <div class="pictures-hero">
            <h1>We are StudentRND</h1>
        </div>
    </div>
    <div class="row">
        <div class="span5 box">
            <h1>It's been a long time.</h1>
            <p><strong>Welcome to the new My.StudentRND.</strong> Aside from looking a lot nicer, this new system is more stable and has tons
                of new features!</p>
            <p>If you have any questions or feature suggestions, contact Tyler Menezes.</p>
        </div>
        <div class="span6 well">
            <h1>Resources</h1>
            <?php if (\StudentRND\My\Models\User::current()->studentrnd_email_enabled) : ?>
                <div class="btn-group">
                    <a class="btn dropdown-toggle btn-primary btn-block btn-xlarge" data-toggle="dropdown" href="#">
                    Google Apps
                    <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="<?=\CuteControllers\Router::get_link('/google_apps/login?to=mail')?>">Mail</a></li>
                        <li><a href="<?=\CuteControllers\Router::get_link('/google_apps/login?to=calendar')?>">Calendar</a></li>
                        <li><a href="<?=\CuteControllers\Router::get_link('/google_apps/login?to=drive')?>">Drive</a></li>
                        <li class="divider"></li>
                        <li><a href="<?=\CuteControllers\Router::get_link('/google_apps/settings')?>">Settings</a></li>
                        <?php if (\StudentRND\My\Models\User::current()->is_admin) : ?>
                            <li><a href="<?=\CuteControllers\Router::get_link('/google_apps/login?to=www')?>">Admin</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <?php if (count($this->user->groups) > 0) : ?>
                <a class="btn btn-block btn-xlarge" href="<?=\CuteControllers\Router::get_link('/dreamspark/login')?>">Dreamspark Premium</a>
            <?php endif; ?>
            <a class="btn btn-block btn-xlarge" href="<?=\CuteControllers\Router::get_link('http://wiki.studentrnd.org/')?>">Wiki</a>
        </div>
    </div>
<?php include_once('footer.php'); ?>
