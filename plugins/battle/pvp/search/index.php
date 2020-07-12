<?php
$itemtypes = [
    /* 0 */ "Sword",
    /* 1 */ "Helmet",
    /* 2 */ "Breastplate",
    /* 3 */ "Leg",
    /* 4 */ "Boots",
    /* 5 */ "Throwing weapon", // Bow, Slingshot
    /* 6 */ "Cartridge", // Arrow, Rock
    /* 7 */ "Potions",
    /* 8 */ "Magical Powers"
];
$taglist = [
    /* 0 */ "Common",
    /* 1 */ "Normal",
    /* 2 */ "Uncommon",
    /* 3 */ "Rare",
    /* 4 */ "Very Rare",
    /* 5 */ "Legendary",
    /* 6 */ "Undefinable",
    /* 7 */ "It must be cheated",
    /* 8 */ "As rare as men who understand women"
];
?>
<section>
    <div>
    <img class="center" src="<?= WEB; ?>/img/dogecoin.svg" width="256" height="256">
    <h1><?php echo getString("PvP",$user->langcode); ?></h1>
    <?php
    $randlevel = $user->lvl + rand(-1, 2);
    $pvp_users = $db->prepare(
        "SELECT * FROM `users` WHERE lvl=:lvl AND canfight=1 AND ID<>:id AND hp=100 ORDER BY RAND() LIMIT ".rand(1,3)
    );
    $pvp_users->bindParam(":lvl", $randlevel);
    $pvp_users->bindParam(":id", $user->ID);
    $pvp_users->execute();
    $pvp_users = $pvp_users->fetchAll();
    if ($pvp_users == []) {
        $string =
            "<p>".getString("I'm sorry, there's nobody you can fight with.",$user->langcode)."</p>";
    }
    if (!isset($string)) {
        foreach ($pvp_users as $pvp_user) {
            //                    Nice
            echo '<a style="width:69%" href="'.WEB.'/game.php?action=profile&id='.$pvp_user['ID'].'" class="bc-bot-open-btn">'.$pvp_user['username']."</a>";
            echo '<a style="width:30%" href="'.WEB.'/game.php?action=battle/pvp/fight&id=' .
                $pvp_user['ID'] .
                '" class="bc-bot-open-btn">'.getString("Fight",$user->langcode) .
                '</a><br />';
        }
    } else {
        echo str_replace("\n", "<br />", print_r($string, 1));
    }
    ?>
    <a style="width:100%" href="<?= WEB; ?>/game.php?action=battle/pvp/search" class="bc-bot-open-btn"><?php echo getString("Refresh list",$user->langcode); ?></a><br />
    <a style="width:100%" href="<?= WEB; ?>/game.php?action=battle/pvp/" class="bc-bot-open-btn"><?php echo getString("Go Back",$user->langcode); ?></a>
  </div>
</section>
