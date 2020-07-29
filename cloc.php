<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>DogeMMO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="format-detection" content="telephone=no" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="MobileOptimized" content="176" />
    <meta name="HandheldFriendly" content="True" />
    <meta name="robots" content="noindex, nofollow" />
    <link rel="shortcut icon" href="/img/favicon.ico?1" type="image/x-icon">
    <link href="/css/roboto.css" rel="stylesheet" type="text/css">
    <link href="/css/telegram.css" rel="stylesheet" type="text/css">
    <link href="/css/bootstrap.min.css?3" rel="stylesheet">
    <link href="/css/bootstrap-extra.css?2" rel="stylesheet">
    <link href="/css/comments.css?27" rel="stylesheet">
    <style></style>
  </head>
  <body class="emoji_image" style="display:none">
<section align="center" class="bc-main">
    <div class="bc-main-content">
    <img src="/img/dogecoin.svg" width="102" height="102">
    <h1>Count Lines Of Code</h1>
    <pre style="text-align: left;max-width: 540	px"><?php echo str_replace(
        "\n",
        "<br />",
        shell_exec('cloc ./')
    ); ?></pre>
  </div>
</section>
    <script src="/js/jquery.min.js"></script>
    <script src="/js/jquery-ui.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
  </body>
</html>
