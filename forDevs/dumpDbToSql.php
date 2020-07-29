<?php
$target = 'forDevs/db.scheme.sql';
define('MAKE_MYSQLI_PLEASE', true);
include "inc/db.production.php";
include "inc/lecho.php";

// Tables that have content.
$content_table = ['achievements','guilds_info','item_cat_info','levels','quests','quest_texts','shop_items','teams'];

$content = '';
lecho("SHOWING TABLES",'dumpDbToSql',0);
$queryTables = $db->query('SHOW TABLES');
while($row = $queryTables->fetch_row()) {
    //lecho($row[0],'dumpDbToSql',1);
    $tables[] = $row[0];
}
lecho("Processing all tables...",'dumpDbToSql',0);
foreach($tables as $table) {
    lecho("$table",'dumpDbToSql',1);
    $result = $db->query('SELECT * FROM '.$table);
    $fields_amount = $result->field_count;
    $rows_num = $db->affected_rows;
    $res = $db->query('SHOW CREATE TABLE '.$table);
    $TableMLine = $res->fetch_row();
    $content = (!isset($content) ?  '' : $content) . "\n".$TableMLine[1].";\n";
    for ($i = 0, $st_counter = 0; $i < $fields_amount; $i++, $st_counter=0) {
        if (!in_array($table,$content_table)) {
            lecho("$table is not in \$content_table, not dumping it's content.","dumpDbToSql",2);
            break;
        }
        lecho("$table is in \$content_table, dumping it's content.","dumpDbToSql",2);
        while($row = $result->fetch_row()) {
            //when started (and every after 100 command cycle):
            if ($st_counter%100 == 0 || $st_counter == 0 ) {
                $content .= "\nINSERT INTO ".$table." VALUES";
            }
            $content .= "\n(";
            for($j=0; $j<$fields_amount; $j++) {
                $row[$j] = str_replace("\n","\\n", addslashes($row[$j]) );
                if (isset($row[$j])) {
                    $content .= '"'.$row[$j].'"';
                } else {
                    $content .= '""';
                }
                if ($j<($fields_amount-1)) {
                         $content.= ',';
                }
            }
            $content .=")";
            //every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
            if ( (($st_counter+1)%100==0 && $st_counter!=0) || $st_counter+1==$rows_num) {
                $content .= ";";
            } else {
                $content .= ",";
            }
            $st_counter=$st_counter+1;
        }
    }
    $content .="\n\n\n";
}
echo "\n";
file_put_contents($target,$content);
echo "Done!\n";
