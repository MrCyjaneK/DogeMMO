# DogeMMO - From classic OldPC series.

Work in progress, come to @Chat_With_Cyjan, or @EarnWalletChat on telegram to help.

# Installation

To install dogemmo on the production server you need to install
 
 * PHP-7.4
 * MySQL (sqlite3 work in progress)
 * Nginx as the webserver

Then you need to clone this repository somewhere, it is fine to run in a subdirectory.

Then adjust following files:
 
 * inc/config.php
 * config.json (copy config.example.json)
 * inc/db.production.php (see db.production.example.php) || forDevs/db.dev.php not really works.
 * Import forDevs/db.scheme.sql to database
