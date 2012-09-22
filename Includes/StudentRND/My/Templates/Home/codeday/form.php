<?php require(TEMPLATE_DIR . '/Home/header.php'); ?>

<?php if ($this->request->request('eventID')) : ?>
    <a href="<?=\CuteControllers\Router::get_link('/codeday/manage/index?eventID=' . $this->request->request('eventID'))?>">
        &laquo; Back to Event
    </a>
<?php endif; ?>

<div class="row">
    <div class="span12 box">
        <?php include(TEMPLATE_DIR . '/Home/widgets/form.php'); ?>
    </div>
</div>

<?php require(TEMPLATE_DIR . '/Home/footer.php'); ?>
