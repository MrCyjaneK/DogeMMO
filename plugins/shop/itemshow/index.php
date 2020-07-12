<?php
include "./plugins/shop/note.php"; ?>

<section class="bc-main">
    <div class="bc-main-content">
    <img class="center" src="<?= WEB ?>/img/dogecoin.svg" width="256" height="256">
    <h1><?php echo getString("🏪AliÐoge",$user->langcode); ?></h1>

<?php
$item = $db->prepare(
    "SELECT * FROM `shop_items` WHERE ID=:id AND inshop=:inshop"
);
$item->bindParam(":id", $_GET['id']);
$item->bindParam(":inshop", $_GET['inshop']);
$item->execute();
$item = $item->fetchAll();
if ($item == []) {
    //$client->answerCallbackQuery($callback_id,"We don't have this item in our ".$strings->bank->name,true);
    echo str_replace(
        "\n",
        "<br />",
        getString("Item was not found in",$user->langcode)." " . gs("🏪AliÐoge")
    );
} else {
    $item = $item[0];
    $price = n($item['price']);
    if ($price == 0) {
        $price = getString("Free!",$user->langcode);;
    }
    $itemstring =
        "" .
        $item["emoji"] .
        " " .
        getString($item['name']) .
        " [" .
        getString($taglist[$item["tag"]],$user->langcode) .
        "]\n" .
        "⚔" .
        round($item["attack"], 2) .
        " 🛡️" .
        round($item["defense"], 2) .
        " 🏋‍♂" .
        round($item["weight"], 2) .
        "kg 🏃‍♂" .
        round($item["speed"], 2) .
        "\n" .
        "" .
        $item["emoji"] .
        " " .
        getString($itemtypes[$item["type"]],$user->langcode) .
        "\n" .
        "💰 " .
        $price .
        " " .
        $config->currency .
        "\n" .
        "❓" .
        getString($item["about"]) .
        "";
    echo str_replace("\n", "<br />", $itemstring);
    echo '<a style="width:100%" href="'.WEB.'/game.php?action=shop/buy&id=' .
        $_GET['id'] .
        '&inshop=' .
        $_GET['inshop'] .
        '" class="bc-bot-open-btn">' .
        getString("Buy",$user->langcode).
        '' .
        '</a>';
}?>
    <a style="width:100%" onclick="window.history.back()" class="bc-bot-open-btn"><?php echo getString("Go Back",$user->langcode);?></a>
  </div>
</section>
