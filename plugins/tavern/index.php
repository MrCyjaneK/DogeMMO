<section>
    <div>
    <img class="center" src="<?= WEB ?>/img/dogecoin.svg" width="256" height="256">
    <h1><?php echo gs("Tavern") ?></h1>
    <p align="left"><?php
    $info =
        gs("You walk inside tavern, loud and overcrowded as usual. Next to the bar you see some soldiers bragging about the recent news from the battle lines. In the back of the tavern some farmers are playing dice.

You can buy a pint of ale and sit down next to the soldiers: take a rest, listen to some gossips. If you are lucky, you might hear something interesting.") .
        gs("Price of one pint: ") .
        n(1) .
        " " .
        $config->currency .
        "💰

".gs("Or you can sit next to the gamblers and try your luck in dice.") .
gs("Entry fee: ") .
        n(5) .
        " " .
        $config->currency .
        "💰";
    echo str_replace("\n", "<br />", htmlspecialchars(print_r($info, 1)));
    ?></p>
    <a style="width:49.5%" href="<?= WEB ?>/game.php?action=tavern/pint" class="bc-bot-open-btn"><?= gs("Have a pint") ?></a>
    <a style="width:49.5%" href="<?= WEB ?>/game.php?action=tavern/dice" class="bc-bot-open-btn"><?= gs("Gamble some money") ?></a><br />
    <a style="width:100%" href="<?= WEB ?>/game.php?action=castle" class="bc-bot-open-btn"><?= gs("Go Back") ?></a>
  </div>
</section>
