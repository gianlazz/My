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
            <div class="row nav-bar">
                <div class="span8">
                    <ul class="nav nav-pills">
                        <?php
                            $pages = array(
                                array('name' => 'Home', 'uri' => '/home'),
                                array('name' => 'Me', 'uri' => '/user?username=' . \StudentRND\My\Models\User::current()->username),
                                array('name' => 'Facebook Group', 'uri' => 'https://www.facebook.com/groups/studentrnd')
                            );

                            if (\StudentRND\My\Models\User::current()->is_admin) {
                                $pages[] = array('name' => 'Admin', 'uri' => '/admin');
                            }

                            $current = explode('/', $this->request->uri);
                            $current = '/' . $current[1];
                        ?>
                        <?php foreach ($pages as $page) : ?>
                            <li<?php if($current == $page['uri']) echo ' class="active"'; ?>>
                                <a href="<?php echo \CuteControllers\Router::get_link($page['uri']); ?>"><?=$page['name'];?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="span4" id="logout"><a href="<?=APP_URI?>/login/bye">Logout</a></div>
            </div>

            <?php if (isset($error)) : ?>
                <div class="alert alert-error">
                    <?=$error?>
                </div>
            <?php endif; ?>
