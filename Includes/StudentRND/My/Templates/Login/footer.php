</div></div>
<?php if (isset($logout) && $logout === TRUE) : ?>
    <iframe src="https://mail.google.com/a/studentrnd.org/?logout" style="width:1px;height:1px;visibility:hidden"></iframe>
<?php endif; ?>
<script data-main="<?=ASSETS_URI?>/js/studentrnd/login" src="<?=ASSETS_URI?>/js/require.js"></script>
</body>
</html>
