<!DOCTYPE html>
<html lang="en">
<head>
    <title>My.StudentRND</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author" content="StudentRND" />
    <meta name="description" content="The StudentRND user portal" />
    <link rel="shortcut icon" type="image/x-icon" href="/assets/img/favicon.ico" />
    <?php
        $arr = array(
                     'x9nzr.jpg',
                     'cdBAw.jpg'
                     );
        $bg = $arr[array_rand($arr)];
    ?>
    <style type="text/css">
        body {
            background-image: url(<?=ASSETS_URI?>/img/login/backgrounds/<?=$bg?>);
        }
    </style>
    <link rel="stylesheet" href="<?=ASSETS_URI?>/css/login.css" />
</head>
<body>
    <div id="wrap"><div id="inner">
        <h1>My.StudentRND</h1>
        <?php if (!isset($error)) : ?>
            <p>Welcome to the new ice cream social.</p>
        <?php else : ?>
            <p class="error"><?=$error;?></p>
        <?php endif; ?>
