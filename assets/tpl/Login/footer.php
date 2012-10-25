</div></div>
<?php if (isset($logout) && $logout === TRUE) : ?>
    <!-- These iframes won't actually load because of X-Frame-Options, but Google will still log the user out... -->
    <iframe src="https://mail.google.com/a/studentrnd.org/?logout&amp;hl=en&amp;hlor" style="width:1px;height:1px;visibility:hidden"></iframe>
    <iframe src="https://www.google.com/a/cpanel/logout?continue=https://www.google.com/a/cpanel/studentrnd.org" style="width:1px;height:1px;visibility:hidden"></iframe>
<?php endif; ?>
<script data-main="<?=ASSETS_URI?>/js/studentrnd/login" src="<?=ASSETS_URI?>/js/require.js"></script>
</body>
</html>
