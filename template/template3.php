<?php
$wpsaf = json_decode(get_option('wpsaf_options'));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php the_title() ?></title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta name="robots" content="noindex,nofollow">
    <style>
        body {
            font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
            font-size: 14px;
            line-height: 1.42857143;
            color: #333;
            background-color: #fff;
        }

        .adb {
            display: none;
            position: fixed;
            width: 100%;
            height: 100%;
            left: 0;
            top: 0;
            bottom: 0;
            background: rgba(51, 51, 51, 0.9);
            z-index: 10000;
            text-align: center;
            color: #111;
        }

        .adbs {
            margin: 0 auto;
            width: auto;
            min-width: 400px;
            position: fixed;
            z-index: 99999;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            padding: 20px 30px 30px;
            background: rgba(255, 255, 255, 0.9);
            -webkit-border-radius: 12px;
            -moz-border-radius: 12px;
            border-radius: 12px;
        }

        #content-wrapper {
            text-align: center;
            border: 1px solid #ddd;
            padding: 20px;
            max-width: 1000px;
            margin: 0 auto;
        }

        #content-wrapper .btn-primary {
            display: inline-block;
            padding: 6px 12px;
            margin-bottom: 0;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.42857143;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            -ms-touch-action: manipulation;
            touch-action: manipulation;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            background-image: none;
            border: 1px solid #2e6da4;
            border-radius: 4px;
            color: #fff;
            background-color: #337ab7;
        }

        <?php if ($wpsaf->skipverification == 1): ?>
            #content-wrapper { display: none; }
        <?php endif; ?>
    </style>
</head>

<body>
<section id="content-wrapper" style='margin-top:80px'>
    <div class='container'>
        <div class='row'>
            <div class='col-md-12'>
                <div class='panel panel-default'>
                    <div class='panel-body'>
                        <div class="wpsafe-top text-center">
                            <h3 style="color:red;">Verifing Your Link.. Please wait..</h3>
                            <p>Please click the button below to proceed to the destination page.</p>
                            <form name="dsb" action="<?php the_permalink() ?>" method="post">
                                <input type="hidden" name="newwpsafelink" value="<?php echo $_GET['code'] ?>">
                                <button class="btn btn-primary" type="submit" value="Submit">Im a Human</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if ($_GET['adb'] == '1') : ?>
    <div class="adb" id="adb">
        <div class="adbs">
            <h3><?php echo $_GET['adb1']; ?></h3>
            <p><?php echo $_GET['adb2']; ?></p>
        </div>
    </div>
<?php endif; ?>

<?php if ($wpsaf->skipverification == 1): ?>
    <script type="text/javascript">
        document.dsb.submit();
    </script>
<?php endif; ?>

<script type="text/javascript">
    var count = <?php echo $_GET['delay']; ?>;

    async function detectAdBlock() {
        let adBlockEnabled = false
        const googleAdUrl = 'https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js'
        try {
            await fetch(new Request(googleAdUrl)).catch(_ => adBlockEnabled = true)
        } catch (e) {
            adBlockEnabled = true
        } finally {
            if (adBlockEnabled) adBlockDetected();
        }
    }

    detectAdBlock()

    function adBlockDetected() {
        document.getElementById("adb").setAttribute("style", "display:block");
        count = 10000;
    }
</script>
</body>

</html>