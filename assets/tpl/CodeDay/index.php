<?php include('header.php'); ?>
<div class="row" id="index">
    <div class="span6" style="margin-left:0">
        <?php $i = 0; foreach ($this->current_codeday->blocks as $block) : $i++; if ($i != 2 && $i % 2 == 0) continue; ?>
            <div class="span6 box">
                <?php if ($block->image) : ?>
                    <img src="<?=$block->image?>" /><br/>
                <?php endif; ?>
                <h1><?=$block->title?></h1>
                <?=$block->html?>
            </div>
        <?php endforeach; ?>
        <div class="span6 box" style="text-align:center">
            <h1>Sponsors:</h1>
            <?php include('sponsors.php') ?>
            <?php foreach ($this->current_codeday->sponsors as $sponsor) : ?>
                <br /><br />
                <?php if ($sponsor->link) : ?>
                    <a href="<?=$sponsor->link?>"><img src="<?=$sponsor->logo?>" alt="<?=$sponsor->name?>" /></a>
                <?php else : ?>
                    <img src="<?=$sponsor->logo?>" alt="<?=$sponsor->name?>" />
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="span6">
        <div class="span6 box">
            <img src="https://www.filepicker.io/api/file/rMfeBYVwSjyz_62kb4iW/convert?fit=crop&amp;h=279&amp;w=418"><br>
            <h1>What is CodeDay?</h1>
            <p>CodeDay is an amazing 24-hour event where students who love technology hang out and code! </p>
            <p>It will be fun for both beginners and advanced programmers!</p><br>
            <a href="https://twitter.com/share" class="twitter-share-button" data-text="Register for CodeDay <?=$this->current_codeday->name?> on <?=date('M j', $this->current_codeday->start_date)?> -- " data-via="StudentRND">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
        </div>
        <?php $i = 0; foreach ($this->current_codeday->blocks as $block) : $i++; if ($i == 2 || $i % 2 == 1) continue; ?>
            <div class="span6 box">
                <?php if ($block->image) : ?>
                    <img src="<?=$block->image?>" /><br/>
                <?php endif; ?>
                <h1><?=$block->title?></h1>
                <?=$block->html?>
            </div>
        <?php endforeach; ?>
        <div class="span6 box">
            <img src="https://www.filepicker.io/api/file/cAvqKporSjqIIlhJmTBj/convert?fit=crop&amp;h=279&amp;w=418"><br>
            <h1>Thank The Sponsors!</h1>
            <p>At CodeDay, we'll have:</p><ul><li>Free Food!</li><li>Awesome Swag!</li><li>Cool Prizes &amp; Giveaways!</li><li>An Amazing Atmosphere. </li></ul><p></p>
            <p>Thank the sponsors for making this an amazing event! </p>
        </div>
        <div class="span6 box">
            <h1>Presented By:</h1>
            <a href="http://studentrnd.org/"><img src="http://studentrnd.org/images/srndlogotransparent.png"/></a>
            <br/><h2>Follow us on <a href="http://facebook.com/studentrnd">Facebook</a> and <a href="http://twitter.com/studentrnd">Twitter</a>!</h2>
        </div>
    </div>
</div>
<?php include('footer.php'); ?>
