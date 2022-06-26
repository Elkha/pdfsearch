<?php

// https://stackoverflow.com/questions/34190464/php-scandir-recursively
function scanAllDir($dir) {
  $result = [];
  foreach(scandir($dir) as $filename) {
    if ($filename[0] === '.') continue;
    $filePath = $dir . '/' . $filename;
    if (is_dir($filePath)) {
      foreach (scanAllDir($filePath) as $childFilename) {
        $result[] = $filename . '/' . $childFilename;
      }
    } else {
      $result[] = $filename;
    }
  }
  return $result;
}

// Usage: pdf2html [options] -in inputfile -out outputfile
$exe = __DIR__ . '\\_bin\\pdf2html.exe'; // https://www.pdftron.com/downloads/pdf2html.zip
$dir = __DIR__ . '\\_data';
$result = scanAllDir($dir);

$buff_list = array();
foreach($result as $val)
{
	if(!preg_match('/\.pdf\.html\.txt/i', $val))
	{
		continue;
	}
	$args = new stdClass();
	$args->buff = file_get_contents($dir . '\\' . $val);
	$args->filename = $val;
	$buff_list[] = $args;
}

foreach($buff_list as $val)
{
	$find = FALSE;
	foreach($argv as $v)
	{
		if(($find = mb_stripos($val->buff, $v)) !== FALSE)
		{
			break;
		}
	}
	if($find!==FALSE)
	{
		echo $val->filename;
		echo "\n";
		$start = $find - 10;
		if($start < 0) $start = 0;
		echo str_replace("\n", ' ', mb_substr($val->buff, $start, 200));
	}
}