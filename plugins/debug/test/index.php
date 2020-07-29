<section>
    <div>
    <img src="<?= WEB ?>/img/planning.svg" width="256" height="256">
    <h1>List of all tests</h1>
    <p><?php
    foreach (glob(getcwd()."/plugins/debug/test/*") as $test) {
        $name = str_replace(getcwd()."/plugins/debug/test/","",$test);
        if ($name === 'index.php') continue;
        ?>
        <a href="<?= WEB; ?>/game.php?action=debug/test/<?= $name ?>"><?= $name ?></a>
        <?php
    }
    ?></p>
    <a href="<?= WEB ?>/game.php?action=debug">Go Back</a>
  </div>
</section>
