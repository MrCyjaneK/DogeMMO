
CREATE TABLE `achievements` (
  `ID` int(11) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `ICON` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO achievements VALUES
("0","Translator","Allows you to translate strings!","");



CREATE TABLE `active_quests` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `quest_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `timestarted` text NOT NULL,
  `isweb` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;




CREATE TABLE `auth_accounts` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `email` text NOT NULL,
  `linked_hash` text NOT NULL,
  `cookie` text DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;




CREATE TABLE `errors` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `error` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;




CREATE TABLE `guilds` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` text DEFAULT NULL,
  `level` int(11) NOT NULL DEFAULT 0,
  `xp` decimal(19,9) NOT NULL DEFAULT 0.000000000,
  `admin` int(11) NOT NULL DEFAULT 0,
  `moderator` int(11) NOT NULL DEFAULT 0,
  `balance` decimal(19,9) NOT NULL DEFAULT 0.000000000,
  `about` text DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;




CREATE TABLE `guilds_info` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `lvl_id` int(11) NOT NULL,
  `xp_need` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `max_members` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

INSERT INTO guilds_info VALUES
("1","0","0","0","2");



CREATE TABLE `item_cat_info` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `type` text NOT NULL,
  `tag` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

INSERT INTO item_cat_info VALUES
("0","Swords","CATEGORY->TAG->0"),
("1","Helemts","CATEGORY->TAG->1"),
("2","Breastplates","CATEGORY->TAG->2"),
("3","Legs","CATEGORY->TAG->3"),
("4","Boots","CATEGORY->TAG->4"),
("5","Throwing weapon","CATEGORY->TAG->5"),
("6","Catridge","CATEGORY->TAG->6"),
("7","Potions","CATEGORY->TAG->7"),
("8","Magical powers","CATEGORY->TAG->8"),
("9","Found","CATEGORY->TAG->9");



CREATE TABLE `levels` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `lvl_id` decimal(19,9) NOT NULL,
  `min_xp` decimal(19,9) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

INSERT INTO levels VALUES
("1","1.000000000","15.000000000"),
("2","2.000000000","125.000000000"),
("3","3.000000000","333.000000000"),
("4","4.000000000","999.000000000"),
("5","5.000000000","2500.000000000"),
("6","6.000000000","7777.000000000"),
("7","7.000000000","15000.000000000");



CREATE TABLE `quests` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `about` text NOT NULL,
  `minutes` int(11) NOT NULL,
  `rewards_items` text NOT NULL,
  `rewards_chance` text NOT NULL,
  `minlvl` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

INSERT INTO quests VALUES
("1","Forest","Relaxing, not dangerous activity","2","forest","50","0"),
("2","Cave","Dark and sad. Depression and rocks can be found in here","5","cave","33","4");



CREATE TABLE `quests_text` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `reward_from` text NOT NULL,
  `string` text NOT NULL,
  `author` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4;




CREATE TABLE `shop_items` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `type` int(11) NOT NULL COMMENT '/* 0 */ "Sword", 		/* 1 */ "Helmet", 		/* 2 */ "Breastplate", 		/* 3 */ "Leg", 		/* 4 */ "Boots", 		/* 5 */ "Throwing weapon", 		/* 6 */ "Cartridge", 		/* 7 */ "Potions", 		/* 8 */ "Magical Powers", 		/* 9 */ "Collectibles"',
  `attack` decimal(19,9) NOT NULL,
  `defense` decimal(19,9) NOT NULL,
  `speed` decimal(19,9) NOT NULL,
  `weight` decimal(19,9) NOT NULL,
  `magic` text NOT NULL,
  `can_be_bought` int(11) NOT NULL DEFAULT 1,
  `can_eq` int(11) NOT NULL,
  `about` text NOT NULL,
  `inshop` text NOT NULL,
  `minlvl` int(11) NOT NULL DEFAULT 0,
  `emoji` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(19,9) NOT NULL,
  `tag` int(11) NOT NULL COMMENT '/* 0 */ "Common", 		/* 1 */ "Normal", 		/* 2 */ "Uncommon", 		/* 3 */ "Rare", 		/* 4 */ "Very Rare", 		/* 5 */ "Legendary", 		/* 6 */ "Undefinable", 		/* 7 */ "It must be cheated", 		/* 8 */ "As rare as men who understand womens"',
  `own_limit` int(11) NOT NULL DEFAULT 1,
  `capacity` int(11) NOT NULL,
  `icon` text DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4;

INSERT INTO shop_items VALUES
("0","Leaf Boots","4","0.010000000","0.010000000","2.000000000","0.010000000","none","1","1","They are Mike\'s","castle","0","👞","0.000000000","0","1","500000",""),
("1","Wooden Sword","0","0.100000000","0.000000000","0.000000000","0.500000000","none","1","1","Wooden sword, it ain\'t strong, but it\'s free","castle","0","⚔","0.000000000","0","1","500000","/images/items/wooden_sword.png"),
("2","Leaf Helmet","1","0.000000000","0.100000000","0.000000000","0.050000000","none","1","1","Helmet, made from leafs. Our scientific research show that they are stronger than regular wooden or iron helmets, can\'t believe? Try it yourself, get iron and leaf helmet, drop it from plane and you will see which one is stronger.","castle","0","🎩","0.000000000","0","1","500000",""),
("3","Leaf Breastplate","2","0.000000000","0.500000000","0.000000000","1.500000000","none","1","1","Leaf is new iron.","castle","0","👕","0.000000000","0","1","500000",""),
("4","Leaf Leg","3","0.010000000","0.300000000","0.000000000","0.750000000","none","1","1","They are trendy.","castle","1","👖","0.000000000","0","1","500000",""),
("7","Stick","9","0.010000000","0.000000000","0.000000000","0.010000000","none","0","0","It\'s a stick. You can collect it and sell it later. Or just collect. Do whatever you want. It\'s just a damn stick.","forest","0","🌴","0.000000100","0","5000","100000000",""),
("8","Rock","9","0.010000000","0.000000000","0.000000000","0.010000000","none","0","0","Rock. That\'s it. It\'s just a rock.","cave","0","⛰️","0.000001000","0","5000","100000000",""),
("9","Big Rock","9","0.000000000","0.000000000","0.000000000","0.500000000","none","0","0","Well. This is rock too, but it\'s better","cave","0","⛰️","0.000005000","0","5000","100000000",""),
("10","Spears","5","0.150000000","0.100000000","0.000000000","0.010000000","none","1","1","Sharp and pierce through anything on its path ","castle","1","🏏","0.000100000","0","1","500000",""),
("11","Sai","0","0.150000000","0.000000000","0.000000000","0.300000000","$attack = function($userhp, $enemyhp, $hit) {\n    if ($userhp < $enemyhp) {\n        return $enemyhp-$userhp;\n    }\n    return $hit\n}\n$defense = function($uesrhp, $enemyhp, $hit) {\n    return $hit;\n}\n// This is just an example\n","1","1","Weapon for skillful and fast assassin ","castle","1","⚔️","0.001000000","0","1","500000",""),
("12","Wooden Helmet","1","0.000000000","0.400000000","0.000000000","0.150000000","none","1","1","It\'s made from organic wood.\n\nLegal note: No trees were injured,","castle","1","🎩","0.000100000","0","1","500000",""),
("13","Wooden Breastplate","2","0.000000000","1.200000000","0.000000000","2.500000000","none","1","1","Made from brand new chest!","castle","1","👕","0.000100000","0","1","500000",""),
("14","Wooden Leg","3","0.020000000","0.600000000","0.000000000","1.000000000","none","1","1","Made from Moon Timber.\nyoutu.be/yrI4ykDSjiQ","castle","1","👖","0.001000000","0","1","500000",""),
("15","Wooden Boots","4","0.020000000","0.020000000","4.000000000","0.020000000","none","1","1","Isn\'t that cool?","castle","1","👞","0.000100000","0","1","500000",""),
("16","Metal Helmet","1","0.000000000","1.000000000","0.000000000","0.050000000","none","1","1","Metal helmet is always better than Woods, hard shell defense","castle","2","⛑","0.010000000","0","1","500000",""),
("17","Iron Helmet","1","0.000000000","2.000000000","0.000000000","0.300000000","none","1","1","It\'s more secure, but it\'s much heavier.\n\nFun fact:\n\nIronMan equals FeMale","castle","3","⛑","0.050000000","0","1","500000",""),
("18","Gold Helmet","1","0.000000000","5.000000000","0.000000000","0.100000000","none","1","1","It\'s shininig!","castle","5","⛑","0.050000000","0","1","500000",""),
("19","Shi Huang Ti\'s crown","1","0.000000000","25.000000000","0.000000000","0.100000000","none","1","1","ges Over 7,000 years ago, it\'s to believe that the concurer Shi Huang Ti spirits still within his Crown that protect him from Any Harm. But because that was kind of ages ago, and legal significance of \"any\" have been redefined many times, powers of this helmet have changed too, but don\'t get me wrong, it\'s still strong.","castle","7","⛑","5.000000000","0","1","500000",""),
("20","Shieldy Helmet","1","25.000000000","45.000000000","0.000000000","1.000000000","none","1","1","Form a Protective Shield around wearer, High Tech, and cause damage when someone attack it.\n\nWhen you compare it with \"Shi Huang Ti\'s crown\" the only words that come to your mind are \"Ok Boomer\"","castle","9","🌐","25.000000000","0","1","500000",""),
("21","Time Helmet","1","0.000000000","100.000000000","0.000000000","1.000000000","none","1","1","Maybe it\'s not high-tech but it can give 💯points protection.","castle","12","🌐","50.000000000","0","1","500000","");



CREATE TABLE `teams` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `emoji` text NOT NULL,
  `name` text NOT NULL,
  `groupid` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

INSERT INTO teams VALUES
("1","🦅","The Eagles","-1001160532056"),
("2","🐺","The Wolfs","-1001243425330"),
("3","🦝","The Raccoons","-1001190679788");



CREATE TABLE `user_achievements` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `achievementid` int(11) NOT NULL,
  `achievedat` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;




CREATE TABLE `user_items` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `in_shop_id` int(11) NOT NULL,
  `in_shop_name` text DEFAULT NULL,
  `owned_by` int(11) NOT NULL,
  `boost_attack` int(11) DEFAULT 0,
  `boost_defense` int(11) DEFAULT 0,
  `boost_speed` int(11) DEFAULT 0,
  `in_eq` int(11) DEFAULT 0,
  `capacity` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1570 DEFAULT CHARSET=utf8mb4;




CREATE TABLE `user_notifications` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` text NOT NULL,
  `message` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `isread` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;




CREATE TABLE `users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TG_id` int(11) DEFAULT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `langcode` text DEFAULT NULL,
  `xp` decimal(19,9) NOT NULL DEFAULT 0.000000000,
  `hp` decimal(19,9) NOT NULL DEFAULT 100.000000000,
  `lvl` int(11) NOT NULL DEFAULT 0,
  `in_guild_id` int(11) NOT NULL DEFAULT 0,
  `teamid` int(11) DEFAULT NULL,
  `fightfor` int(11) NOT NULL DEFAULT 0,
  `canfight` int(11) NOT NULL DEFAULT 1,
  `token` int(11) DEFAULT NULL,
  `inviter` int(11) DEFAULT NULL,
  `depositaddress` text DEFAULT NULL,
  `balance` decimal(19,9) NOT NULL DEFAULT 0.000000000,
  `linked_hash` text DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4;



