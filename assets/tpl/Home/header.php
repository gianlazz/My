<?php

    $allgroups = new \TinyDb\Collection('\StudentRND\My\Models\Group', \TinyDb\Sql::create()
                                                      ->select('*')
                                                      ->from(\StudentRND\My\Models\Group::$table_name));

    $allplans = new \TinyDb\Collection('\StudentRND\My\Models\Plan', \TinyDb\Sql::create()
                                                      ->select('*')
                                                      ->from(\StudentRND\My\Models\Plan::$table_name));

    $allusers = new \TinyDb\Collection('\StudentRND\My\Models\User', \TinyDb\Sql::create()
                                                       ->select('*')
                                                       ->from(\StudentRND\My\Models\User::$table_name)
                                                      ->order_by('userID DESC'));
    $allplans = new \TinyDb\Collection('\StudentRND\My\Models\Plan', \TinyDb\Sql::create()
                                                       ->select('*')
                                                       ->from(\StudentRND\My\Models\Plan::$table_name)
                                                      ->order_by('planID DESC'));
?>
<!DOCTYPE html>
<html>
<head>
    <title>My.StudentRND</title>
    <link rel="stylesheet" href="<?=ASSETS_URI?>/css/bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="<?=ASSETS_URI?>/css/home.css"/>
    <link rel="shortcut icon" type="image/x-icon" href="/assets/img/favicon.ico" />
</head>
<body>
<div id="main">
        <div id="index" class="container">
            <div class="row" style="margin:10px 0px"></div>
            <a href="<?=\CuteControllers\Router::link('/')?>"><img src="<?=ASSETS_URI?>/img/home/srndlogotransparent.png"/></a>
            <div id="whitewrap">
                <div class="row nav-bar">
                    <div class="span8">
                        <?php if (\StudentRND\My\Models\User::is_logged_in()) : ?>
                            <ul class="nav nav-pills">
                                <?php
                                    $pages = array(
                                        array('name' => 'Home', 'uri' => '/home'),
                                        array('name' => 'Profile', 'uri' => '/user'),
                                        array('name' => 'Membership', 'uri' => '/subscriptions'),
                                        array('name' => 'Directory', 'uri' => '/groups'),
                                        //array('name' => 'Groups', 'uri' => '/groups')
                                    );

                                    if (\StudentRND\My\Models\User::current()->has_group(new \StudentRND\My\Models\Group(8))) {
                                        $pages[] = array('name' => 'CodeDay', 'uri' => '/codeday');
                                    }

                                    if (\StudentRND\My\Models\User::current()->is_admin) {
                                        $pages[] = array('name' => 'Admin', 'uri' => '/admin');
                                    }

                                    if (\StudentRND\My\Models\User::current()->has_group(new \StudentRND\My\Models\Group(11))) {
                                        // Beta features go here!
                                    }

                                    if (isset($this)) {
                                        $current = explode('/', $this->request->uri);
                                        $current = '/' . $current[1];
                                    } else {
                                        $pages[] = array('name' => 'Error', 'uri' => '/error');
                                        $current = '/error';
                                    }
                                ?>
                                <?php foreach ($pages as $page) : ?>
                                    <?php
                                        list($pre) = explode('?', $page['uri'], 2);
                                        $pre = explode('/', $pre);
                                        $page_base = '/' . $pre[1];
                                    ?>
                                    <li<?php if($current == $page_base) echo ' class="active"'; ?>>
                                        <a href="<?php echo \CuteControllers\Router::link($page['uri']); ?>"><?=$page['name'];?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                    <?php if (\StudentRND\My\Models\User::is_impersonating()) : ?>
                        <div class="span4" id="logout" style="background-color:#D02614;color:white;position:relative;top:-4px;padding:4px;">
                            Impersonating <strong><?=\StudentRND\My\Models\User::current()->name?></strong> -
                            <a style="color:white;border-bottom:1px solid white;" href="<?=APP_URI?>/user/impersonate/bye">Switch back!</a>
                        </div>
                    <?php elseif (\StudentRND\My\Models\User::is_logged_in()) : ?>
                        <div class="span4" id="logout">Ahoy, <strong><?=\StudentRND\My\Models\User::current()->name?></strong>! (<a href="<?=\CuteControllers\Router::link('/login/bye')?>">Not you?</a>)</div>
                    <?php endif; ?>
                </div>

                <?php if (isset($error)) : ?>
                    <div class="alert alert-error">
                        <?=$error?>
                    </div>
                <?php endif; ?>


                <?php if (isset($info)) : ?>
                    <div class="alert alert-info">
                        <?=$info?>
                    </div>
                <?php endif; ?>
