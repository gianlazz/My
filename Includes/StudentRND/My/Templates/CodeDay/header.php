<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:og="http://ogp.me/ns#"
      xmlns:fb="https://www.facebook.com/2008/fbml">
<head>
    <?php
        $pages = array(
                            '/' => array('name' => 'Home', 'uri' => '/'),
                            '/schedule.html' => array('name' => 'Schedule', 'uri' => '/schedule.html'),
                            '/register.html' => array('name' => 'Register', 'uri' => '/register.html'),
                            '/faq.html' => array('name' => 'FAQ', 'uri' => '/faq.html'),
                            '/past.html' => array('name' => 'Past Events', 'uri' => 'http://codeday.org/pastevents.php', 'display' => false),
                        );

        if (isset($this)) {
            $current = explode('/', $this->request->uri);
            $current = '/' . $current[count($current) - 1];
        } else {
            $pages[] = array('name' => 'Error', 'uri' => '/error');
            $current = '/error.html';
        }
    ?>
    <title>StudentRND CodeDay <?=$this->current_codeday->name?> - <?=$pages[$current]['name']?></title>
    <link rel="stylesheet" href="<?=ASSETS_URI?>/css/bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="<?=ASSETS_URI?>/css/codeday.css"/>
    <link rel="image_src" href="<?=ASSETS_URI?>/img/biggestcodeday.jpg" />

    <meta property="og:title" content="StudentRND CodeDay"/>
    <meta property="og:type" content="activity"/>
    <meta property="og:image" content="<?=ASSETS_URI?>/img/biggestcodeday.jpg"/>
    <meta property="og:description" content="Pitch ideas, form teams, and build something amazing in 24 hours."/>
    <meta property="fb:app_id" content="254735791261112"/>
    <meta property="fb:admins" content="529109178"/>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="span5">
                <ul class="nav nav-pills">
                    <?php foreach($pages as $page) : ?>
                        <li <?php if ($current == $page['uri']) echo 'class="active"'?>>
                            <a href="<?php if (substr($page['uri'], 0, 7) !== 'http://') echo '.'?><?=$page['uri']?>"><?=$page['name']?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="hero-unit" style="
            background-image:url('<?=$this->current_codeday->hero_background_url?>');
            background-position:0 -120px;
            color: <?=$this->current_codeday->hero_foreground_color?>;
        ">
            <h1 style="color: <?=$this->current_codeday->hero_foreground_color?>;">CodeDay <?=$this->current_codeday->name?></small></h1>
          <p><?=$this->current_codeday->tagline?></p><br/><br/>
            <?php if ($this->current_codeday->is_ended) : ?>
                <span class="btn btn-inverse btn-large" style="text-decoration:none">Event Ended</span>
            <?php elseif ($this->current_codeday->is_sold_out) : ?>
                <span class="btn btn-inverse btn-large" style="text-decoration:none">Sold Out!</span>
            <?php elseif($this->current_codeday->is_now) : ?>
                <span class="btn btn-info btn-large" style="text-decoration:none">Tickets available at the door</span>
            <?php elseif ($this->current_codeday->is_presale) : ?>
                <a href=".<?=\CuteControllers\Router::get_link('/register.html');?>" class="btn btn-primary btn-large" style="text-decoration:none">Register Today!</a>
            <?php endif; ?>
            <?=date('l F j', $this->current_codeday->start_date)?><?php if (date('Y') !== date('Y', $this->current_codeday->start_date) ||
                    date('Y', $this->current_codeday->start_date) !== date('Y', $this->current_codeday->end_date)) : ?>, <?=date('Y', $this->current_codeday->start_date)?><?php endif; ?>,

            <?php if (date('ga', $this->current_codeday->start_date) == '12pm' && date('ga', $this->current_codeday->end_date) == '12pm'): ?>
                Noon - Noon
            <?php else : ?>
                <?=date('ga', $this->current_codeday->start_date)?> to
                <?php if ($this->current_codeday->end_date - $this->current_codeday->start_date > (60*60*24*2)) : ?>
                    <?=date('l F j', $this->current_codeday->end_date)?>
                    <?php if (date('Y') !== date('Y', $this->current_codeday->end_date) ||
                            date('Y', $this->current_codeday->start_date) !== date('Y', $this->current_codeday->end_date)) : ?>, <?=date('Y', $this->current_codeday->end_date)?><?php endif; ?>, <?=date('ga', $this->current_codeday->end_date)?>
                <?php else : ?>
                    <?=date('ga', $this->current_codeday->end_date)?>
                <?php endif; ?>
            <?php endif; ?>
            at <a href="http://maps.google.com/?q=<?=urlencode($this->current_codeday->location_address)?>"><?=$this->current_codeday->location_name?></a>.
        </div>
