<?PHP

/* backup the db OR just a table */
function backup_tables($host,$user,$pass,$name,$exportpath,$tables = '*')
{
  
  $link = mysql_connect($host,$user,$pass);
  mysql_select_db($name,$link);
  
  //get all of the tables
  if($tables == '*')
  {
    $tables = array();
    $result = mysql_query('SHOW TABLES');
    while($row = mysql_fetch_row($result))
    {
      $tables[] = $row[0];
    }
  }
  else
  {
    $tables = is_array($tables) ? $tables : explode(',',$tables);
  }
  
  //cycle through
  foreach($tables as $table)
  {
    $result = mysql_query('SELECT * FROM '.$table);
    $num_fields = mysql_num_fields($result);
    
    $return.= 'DROP TABLE '.$table.';';
    $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
    $return.= "\n\n".$row2[1].";\n\n";
    
    for ($i = 0; $i < $num_fields; $i++) 
    {
      while($row = mysql_fetch_row($result))
      {
        $return.= 'INSERT INTO '.$table.' VALUES(';
        for($j=0; $j<$num_fields; $j++) 
        {
          $row[$j] = addslashes($row[$j]);
          $row[$j] = preg_replace("/\n/","\\n",$row[$j]);
          if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
          if ($j<($num_fields-1)) { $return.= ','; }
        }
        $return.= ");\n";
      }
    }
    $return.="\n\n\n";
  }
  
  //save file
  $handle = fopen($exportpath,'w+');
  fwrite($handle,$return);
  fclose($handle);
}

print 'Archiv wird erstellt...';

//// config
$username = 'rstore';
$password = 'scout74';
$tableName  = 'rstore';
$backupFile = $tableName . '_backup.sql';
$path = '/var/www/virtual/raystorm.com/rstore/';
$backupfilename = 'backup_' . date('d.m.Y') . '.zip';
/// end config

ini_set('max_execution_time', 300);

if(file_exists($path . 'htdocs/' . $backupfilename))
	unlink($path . 'htdocs/' . $backupfilename);

$zip = new ZipArchive();

if ($zip->open($backupfilename, ZIPARCHIVE::CREATE) !== TRUE) {
    die ("Could not open archive");
}

backup_tables('localhost',$username, $password, $tableName, $path . $backupFile);

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));

foreach ($iterator as $key=>$value) {
    if(!in_array($key, array($path . '..', $path . '.')))
       $zip->addFile(realpath($key), $key) or die ("ERROR: Could not add file: $key");        
}

$zip->addFile(realpath($path . '/' . $backupFile), $path . '/' . $backupFile);

$zip->close();

unlink($path . '/' . $backupFile);

echo "<br />Archiv wurde erfolgreich erstellt.";    
?>