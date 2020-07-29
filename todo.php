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
    <h1>TODO</h1>
    <?php $todo = [
        [
            'Name',
            'About',
            'What\'s not ready?',
            'Who\'s going to do this?',
            'Done'
        ],
        [
            'Game Core',
            'All backend needed features',
            'Nothing - Already finished :)',
            'Cyjan',
            100
        ],
        [
            'Battles',
            'Battles between castles, users and guilds.',
            'Some bugs are found from time to time, and guilds are not finished.',
            '',
            75
        ],
        [
            'User',
            'Everything user related',
            'One small change is needed, at each level users can chose which power to powerup (attack or defense). And one bigger change, user classes, like defender.',
            '',
            35
        ],
        [
            'Items',
            'Items to be added to shop, and craftable items.',
            'Some items are already in shop, but many more need to be added + no craft option at the moment is available',
            '',
            20
        ],
        [
            "Comment Code",
            "To speed up work in future",
            "Some of files are not commented at all",
            'tingledev1',
            15
        ],
        [
            'Enchanting',
            "Powerup items.",
            "Everything, almost, I have place for it in  DB",
            '',
            5
        ],
        ['User Markets', "Allow users to trade", "Everything", '', 0],
        [
            'Make user an object',
            "Instead of using function $user = user(TELEGRAM_ID), we should use $user = New User(ID_IN_DATABASE), as you may see this will require us to change way we communicate with database, and partialy it will help us to be telegram independant, which is also one of milestones",
            "Everything",
            '',
            0
        ],
        [
            'Documentation',
            "Add comments, docs and stuff to code",
            "Everything",
            'Cyjan',
            0
        ],
        [
            'Graphics',
            'Graphics for every item in game and for each quest',
            'Everything. We don\'t have graphics',
            '',
            0
        ],
        [
            'Name',
            'Create better name for this game',
            'DogeMMO...',
            'tingledev1',
            0
        ],[
            '#bugs',
            'Help in executing bugs',
            'Any upcomcing bug',
            'tingledev1',
            1
        ]
    ]; ?>
	<table border="1" style="width:100%">
	  <?php foreach ($todo as $t) { ?>
	  <tr>
	    <?php foreach ($t as $i) { ?>
	    	<td><?php if (is_numeric($i)) {
          echo "<img src='http://progress.mrcyjanek.net/?progress=$i'>";
      } else {
          echo "$i";
      } ?></td>
	    <?php } ?>
	  </tr>
	  <?php } ?>
	</table>

  </div>
</section>
    <script src="/js/jquery.min.js"></script>
    <script src="/js/jquery-ui.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
  </body>
</html>
