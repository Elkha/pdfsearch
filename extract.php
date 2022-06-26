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
$dir = __DIR__ . '\\pdf';
$result = scanAllDir($dir);

foreach($result as $val)
{
	if(!preg_match('/\.pdf$/i', $val))
	{
		continue;
	}

	$source_file = $dir . '\\' . str_replace('/', '\\', $val);
	$target_file = __DIR__ . '\\_data\\' . str_replace('/', '\\', $val) . '.html';

	if(!is_dir(dirname($target_file)) && !@mkdir(dirname($target_file), 0777, TRUE))
	{
		echo 'Error: ' . dirname($target_file);
		continue;
	}

	$args = new stdClass();
	$args->filename = $val;
	if(file_exists($target_file))
	{
		continue;
	}
	else if(file_exists("$target_file.txt"))
	{
		echo "error: $val\n";
		continue;
	}

	$_source_file = '"' . $source_file . '"';
	$_target_file = '"' . $target_file . '"';

	echo "extracting pdf file: $val\n";
	exec("$exe -i $_source_file -o $_target_file");

	file_put_contents("$target_file.txt", $val . "\n" . preg_replace('/[\s\t\v\r\n]+/u', " ", htmlspecialchars_decode(strip_tags(preg_replace(
		['/^[\s\S].*?(<html[^>]*>)/s','#<(head|script)[^>]*>[\s\S]*?</\1>#i','/(<\?[\s\S]*?\?>|<\s*(script|style|xmp|pre|textarea|input|option)[^>]*(?:>.*?<\s*\/\s*\2|\/)\s*>)|(\s){2,}/ius']
		, ['\1','','\1\3']
		,@file_get_contents($target_file)
	)))), LOCK_EX);
}
