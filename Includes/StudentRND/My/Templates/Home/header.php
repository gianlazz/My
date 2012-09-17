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
</head>
<body>
<div id="main">
        <div id="index" class="container">
            <div class="row" style="margin:10px 0px"></div>
            <a href="http://studentrnd.org/"><img src="<?=ASSETS_URI?>/img/home/srndlogotransparent.png"/></a>
            <div id="whitewrap">
                <div class="row nav-bar">
                    <div class="span8">
                        <ul class="nav nav-pills">
                            <?php
                                $pages = array(
                                    array('name' => 'Home', 'uri' => '/home'),
                                    array('name' => 'Profile', 'uri' => '/user'),
                                    array('name' => 'Membership', 'uri' => '/subscriptions'),
                                    //array('name' => 'Groups', 'uri' => '/groups')
                                );

                                if (\StudentRND\My\Models\User::current()->has_group(new \StudentRND\My\Models\Group(8))) {
                                    $pages[] = array('name' => 'CodeDay', 'uri' => '/codeday-admin');
                                }

                                if (\StudentRND\My\Models\User::current()->is_admin) {
                                    $pages[] = array('name' => 'Admin', 'uri' => '/admin');
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
                                    <a href="<?php echo \CuteControllers\Router::get_link($page['uri']); ?>"><?=$page['name'];?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="span4" id="logout">Ahoy, <strong><?=\StudentRND\My\Models\User::current()->name?></strong>! (<a href="<?=APP_URI?>/login/bye">Not you?</a>)</div>
                </div>

                <?php if (isset($error)) : ?>
                    <div class="alert alert-error">
                        <?=$error?>
                    </div>
                <?php endif; ?>
