<?php
$it = new RecursiveDirectoryIterator(".");

// Loop through files
foreach(new RecursiveIteratorIterator($it) as $file) {
    if ($file->getExtension() == 'php') {
        if (php_check_syntax($file)) {
            $out = php_check_syntax($file);
            if ($out == 1) {
                $errors[] = "[CRITICAL] Syntax error in file: '$file'";
                continue;
            }
            $errors[] = "[CRITICAL] Syntax error in file: '$file'\n    > Line: ".$out['line']."\n    >Message:".$out['msg'];
        }
        //echo $file."\n";
    }
}

//print_r($errors);
// Stolen from: https://www.php.net/manual/en/function.php-check-syntax.php#87762
function php_check_syntax( $php, $isFile=false ) {
    # Get the string tokens
    $tokens = token_get_all( '<?php '.trim( $php  ));
   
    array_shift( $tokens );
    
    # Prevent output
    ob_start();
    system('php -l < '.$php.' 2>&1', $ret );
    $output = ob_get_clean();
    if( $ret !== 0 ) {
        # Parse error to report?
        if( (bool)preg_match( '/Parse error:\s*syntax error,(.+?)\s+in\s+.+?\s*line\s+(\d+)/', $output, $match ) )
        {
            return array(
                'line'    =>    (int)$match[2],
                'msg'    =>    $match[1]
            );
        }
        return true;
    }
    return false;
}