<?php require(TEMPLATE_DIR . '/Home/header.php'); ?>
    <h1 style="text-align:center"><?=$this->group->name?></h1>
    <div class="row">
        <div class="span12">
            <div class="directory">
                <?php foreach ($this->group->users as $user) : ?>
                    <div class="entry">
                        <a href="<?=\CuteControllers\Router::link('/user/?username=' . $user->username)?>"><img src="<?=$user->avatar_url?>" /></a>
                        <div class="info">
                            <span class="first_name"><?=$user->first_name?></span>
                            <span class="last_name"><?=$user->last_name?></span>
                            <a href="mailto:<?=$user->email?>" class="email"><?=$user->email?></a>
                            <?php if ($user->phone) : ?>
                                <a href="<?=\CuteControllers\Router::link('/user/call?username=' . $user->username)?>" class="phone"><?=$user->phone?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <hr />
            </div>
        </div>
    </div>
<?php require(TEMPLATE_DIR . '/Home/footer.php'); ?>
