<?php include_once('header.php'); ?>
    <?php
        $pictureusers = new \TinyDb\Collection('\StudentRND\My\Models\User', \TinyDb\Sql::create()
                                                                            ->select('*')
                                                                            ->from(\StudentRND\My\Models\User::$table_name)
                                                                            ->where('avatar_url IS NOT NULL')
                                                                            ->order_by('RAND()')
                                                                            ->limit(7 * 2));
    ?>
    <?php if ($this->user->has_group(new \StudentRND\My\Models\Group(10))) : ?>
        <div class="row">
            <div class="span12 alert alert-info">
                <p>You currently have 24/7 access to the workspace!</p>
                <p>Make sure you're aware of the <a href="http://wiki.studentrnd.org/Keyholders" target="_blank">keyholder rights and responsibilities</a>!</p>
            </div>
        </div>
    <?php elseif (count($this->user->access_grants) > 0) : ?>
        <div class="row">
            <div class="span12 alert alert-info">
                <p>You have access to the workspace during the following periods of time:</p>
                <ul>
                    <?php foreach ($this->user->access_grants as $grant) : ?>
                        <li>
                            <?php if (date('F j Y', $grant->start) == date('F j Y', $grant->end)) : ?>
                                <?=date('M. j', $grant->start)?> from <?=date('h:i A', $grant->start)?> to <?=date('h:i A', $grant->end)?>
                            <?php else : ?>
                                <?=date('M. j', $grant->start)?> at <?=date('h:i A', $grant->start)?> to
                                <?=date('M. j', $grant->end)?> at <?=date('h:i A', $grant->end)?>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <p>Make sure you're aware of the <a href="http://wiki.studentrnd.org/Keyholders" target="_blank">keyholder rights and responsibilities</a>!</p>
            </div>
        </div>
    <?php endif; ?>
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
                        <li><a href="<?=\CuteControllers\Router::get_link('/google_apps/login?to=mail')?>" target="_new">Mail</a></li>
                        <li><a href="<?=\CuteControllers\Router::get_link('/google_apps/login?to=calendar')?>" target="_new">Calendar</a></li>
                        <li><a href="<?=\CuteControllers\Router::get_link('/google_apps/login?to=drive')?>" target="_new">Drive</a></li>
                        <li class="divider"></li>
                        <li><a href="<?=\CuteControllers\Router::get_link('/google_apps/settings')?>" target="_new">Settings</a></li>
                        <?php if (\StudentRND\My\Models\User::current()->is_admin) : ?>
                            <li><a href="<?=\CuteControllers\Router::get_link('/google_apps/login?to=www')?>" target="_new">Admin</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <?php if (count($this->user->groups) > 0) : ?>
                <a class="btn btn-block btn-xlarge" href="<?=\CuteControllers\Router::get_link('/dreamspark/login')?>" target="_new">Dreamspark Premium</a>
            <?php endif; ?>
            <a class="btn btn-block btn-xlarge" href="<?=\CuteControllers\Router::get_link('http://wiki.studentrnd.org/')?>" target="_new">Wiki</a>
        </div>
    </div>
<?php include_once('footer.php'); ?>
