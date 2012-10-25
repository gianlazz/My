<?php include_once(TEMPLATE_DIR . '/Home/header.php'); ?>
    <div class="row">
        <div class="span12 box">
            <?php if ($this->user->phone && \StudentRND\My\Models\User::current()->phone) : ?>
                <form method="post">
                    <h1>Call <?=$this->user->name?>?</h1>
                    <p>We'll automatically call you at <?=\StudentRND\My\Models\User::current()->phone?> and then connect you to
                        <?=$this->user->first_name?> at <?=$this->user->phone?>.</p>
                    <input type="submit" class="btn btn-success" value="Call Me" />
                </form>
            <?php elseif ($this->user->phone) : ?>
                <h1>Can't connect you</h1>
                <p>We can't connect you to <?=$this->user->first_name?> because you don't have a phone number configured in your profile.</p>
                <p>You can reach <?=$this->user->first_name?> at <?=$this->user->phone?></p>
            <?php else : ?>
                <h1>Can't connect you</h1>
                <p>We can't connect you because <?=$this->user->first_name?> doesn't have a phone number set.</p>
            <?php endif; ?>
        </div>
    </div>
<?php include_once(TEMPLATE_DIR . '/Home/footer.php'); ?>
