<a href="<?=\CuteControllers\Router::link('/user?username=' . $user->username)?>" class="tiny-profile">
    <img src="<?=$user->avatar_url?>" />
    <span><?=$user->name?></span>
</a>
