<?php
// TODO: Make this code shorter, use loops instead of copy pasted code.
$it = $db->prepare("SELECT * FROM `item_cat_info` WHERE 1");
$it->execute();
$it = $it->fetchAll();
$itemtypes = [];
$taglist = [];
foreach ($it as $item) {
    $itemtypes[$item['ID']] = $item['type'];
    $taglist[$item['ID']] = $item['tag'];
}
?>
<section class="bc-main">
    <div class="bc-main-content">
    <img src="<?= WEB ?>/img/dogecoin.svg" width="102" height="102">
<?php
$id = $_GET['id'];
$enemy = $db->prepare(
    "SELECT * FROM `users` WHERE AND canfight=1 AND ID=:real_id AND ID<>:id AND hp=100"
);
$enemy->bindParam(":real_id", $id);
$enemy->bindParam(":id", $user->ID);
$enemy->execute();
$enemy = $enemy->fetchAll();
if ($enemy == []) {
    $e = getString("ERR_UNKNOWN_USER",$user->langcode);
    echo "<script>window.location.href='/inc/soft_error.php?e=" .
        urlencode($e) .
        "';</script>";
    die();
}
$enemy = new user($enemy[0]['ID'],2);
$allowed_levels = [$user->lvl - 1, $user->lvl, $user->lvl + 1, $user->lvl + 2];
if ($user->hp != 100) {
    ?>
    <script>
        window.location.href = WEB."/inc/soft_error.php?e=".urlencode(getString("Oups! It looks like you don't have enough hp.",$user->langcode));
    </script>
    <?php
    die();
}
if (in_array($enemy->lvl, $allowed_levels)) {
    /* Build profiles */
    /*
        $fighter[0] // $user
        $fighter[1] // $enemy
    */
    $enatc = 0;
    $usratc = 0;
    while ($user->hp > 0 && $enemy->hp > 0) {
        $totspeed =
            $user->speed * 1000 +
            $enemy->speed * 1000;


        if (rand(0, $totspeed) <= $enemy->speed * 1000) {
            // $enemy attack
            $hit =
                $enemy->attack * rand(0, 5) -
                $user->defense * rand(0, 5);
            if ($hit < 0.0001) {
                $hit = 0.0001;
            }
            $enatc++;
            $user->hp = $user->hp - $hit;
        } else {
            // $user attack
            $hit =
                $user->attack * rand(0, 5) -
                $enemy->defense * rand(0, 5);
            if ($hit < 0.0001) {
                $hit = 0.0001;
            }
            $usratc++;
            $enemy->hp = $enemy->hp - $hit;
        }
    }
    $reply = getString("Battle Results",$user->langcode)."\n";
    if ($user->hp < 0) {
        // $user lose
        $lose = n($enemy->balance * 0.1 * ($enemy->hp / 100));
        $won = n($lose * 1.1);
        $reply = $enemy->username . " " . gs("was stronger than") . $user->username . ' ' . gs("and have won").' '.n($lose).$config->currency;
        $user->balance = $user->balance-$lose;
        $enemy->balance = $enemy->balance+$won;
    } else {
        // $user win
        $lose = n($user->balance * 0.01 * ($user->hp / 100));
        $won = n($lose * 0.9);
        $reply = $user->username . " " . gs("was stronger than") . $enem->username . ' ' . gs("and have won").' '.n($won).$config->currency;
        $enemy->balance = $enemy->balance-$lose;
        $user->balance = $user->balance+$won;
    }
    //echo $user->hp;
    $enemy->pushNotification("You are under attack!",$reply);
    $user->save();
    $enemy->save();
    echo str_replace(
        "\n",
        "<br />",
        '<p algin="left">' .
        $reply .
        '</p>'
    );
} else {
    $e = getString("This battle was unfair!",$user->langcode);
    echo "<script>window.location.href='".WEB."/inc/soft_error.php?e=" .
        urlencode($e) .
        "';</script>";
    die();
}
?>
    <a style="width:100%" href="<?= WEB ?>/game.php?action=battle/pvp/search" class="bc-bot-open-btn"><?php echo getString("Search for enemies",$user->langcode); ?></a><br />
  </div>
</section>
