<?php
/*
  Potrzebny przypis:
    [ID] => 1
        - Auto Increment ID w MySQL
    [name] => Wooden Sword
        - Nazwa itemka
    [type] => 0
        - Typ itemków:*/
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
/*  [attack] => 0.10
        - Moc itka
    [defense] => 0.00
        - Przydatność w obronie
    [speed] => -0.50
        - Czy wplywa na predkosc przemieszczania sid
    [weight] => 0.50
        - Waga
    [inshop] => castle
        - W którym sklepie sie cos znajduje, dla ukrytych eventów.
    [minlvl] => 0
        - Od jakiego lvla można to nabyć?
    [emoji] => :sword:
        - Tfu
    [price] => 0.000000000
        - Cena
    [tag] => 0
        - Tag, rodzaj tej rzeczy*/
$taglist = [
    /* 0 */ "Common",
    /* 1 */ "Normal",
    /* 2 */ "Uncommon",
    /* 3 */ "Rare",
    /* 4 */ "Very Rare",
    /* 5 */ "Legendary",
    /* 6 */ "Undefinable",
    /* 7 */ "It must be cheated",
    /* 8 */ "As rare as men who understand womens"
];
/*  [own_limit] => 1
        - Ile na osobe mozna?
    [capacity] => 500000
        - Ile szt. zostanie wyprodukowanych.
    [token] => 690195901
        - w ktorym bocie mozna to uzyc.
*/
