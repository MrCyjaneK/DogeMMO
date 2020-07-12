<?php
/*

!!!!!!!!!!!!!
    - If you are here, something is wrong in `item_cat_info` in MySQL, not here
!!!!!!!!!!!!!

  Footnote:
    [ID] => 1
        - Auto Increment ID in MySQL
    [name] => Wooden Sword
        - Item name
    [type] => 0
        - Type of item:/
$itemtypes = [
    / 0 / "Sword",
    / 1 / "Helmet",
    / 2 / "Breastplate",
    / 3 / "Leg",
    / 4 / "Boots",
    / 5 / "Throwing weapon", // Bow, Slingshot
    / 6 / "Cartridge", // Arrow, Rock
    / 7 / "Potions",
    / 8 / "Magical Powers",
    / 9 / "Found"
];
/*  [attack] => 0.10
        - Attack ower
    [defense] => 0.00
        - Defense
    [speed] => -0.50
        - Does it affect speed of moving?
    [weight] => 0.50
        - Weight
    [inshop] => castle
        - Where it can be found? ('castle' is default, others will be easter egg)
    [minlvl] => 0
        - What's the minimum level
    [emoji] => :sword:
        - emoji
    [price] => 0.000000000
        - Price
    [tag] => 0
        - Tag of this item
$taglist = [
    / 0 / "Common",
    / 1 / "Normal",
    / 2 / "Uncommon",
    / 3 / "Rare",
    / 4 / "Very Rare",
    / 5 / "Legendary",
    / 6 / "Undefinable",
    / 7 / "It must be cheated",
    / 8 / "As rare as men who understand womens"
];
    [own_limit] => 1
        - How much can one person own?
    [capacity] => 500000
        - TODO: How much items like this can be in game
                It should be done in cron.php
    [token] => 690195901
        - In which bot it should be used?
*/





$it = $db->prepare("SELECT * FROM `item_cat_info` WHERE 1");
$it->execute();
$it = $it->fetchAll();
$itemtypes = [];
$taglist = [];
foreach ($it as $item) {
    $itemtypes[$item['ID']] = $item['type'];
    $taglist[$item['ID']] = $item['tag'];
}
