<?php
$wpsaf = json_decode(get_settings('wpsaf_options'));
?>
<html>

<head>
    <title>Landing..</title>
    <meta name="referrer" content="no-referrer">

    <META NAME="robots" CONTENT="noindex,nofollow">
</head>

<body>
    <form id="landing" method="POST" action="<?php echo get_bloginfo('url'); ?>/">
        <input type="hidden" name="humanverification" value="1">
        <input type="hidden" name="newwpsafelink" value="<?php echo $_GET['code'] ?>">
    </form>
    <script>
        window.onload = function() {
            document.getElementById('landing').submit();
        }
    </script>
</body>

</html>
