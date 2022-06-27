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

if(!is_dir($dir = __DIR__ . '\\_data'))
{
	mkdir($dir, 0777);
}
$dir_pdf = __DIR__ . '\\pdf';
$result = scanAllDir($dir_pdf);

foreach($result as $val)
{
	if(!preg_match('/\.pdf$/i', $val))
	{
		continue;
	}

	$source_file = $dir_pdf . '\\' . str_replace('/', '\\', $val);
	$target_file = $dir . '\\' . preg_replace('/\.pdf/iu', '.content.txt', str_replace('/', '\\', $val));

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
	else if(file_exists($target_file))
	{
		echo "error: $val\n";
		continue;
	}

	$_source_file = '"' . $source_file . '"';
	$_target_dir = '"' . dirname($target_file) . '"';

	echo "extracting pdf file: $val\n";
	exec(__DIR__ . "\\_bin\\node_modules\\.bin\\pdf2json.cmd -i -c -f $_source_file -o $_target_dir");

	if(file_exists($target_file))
	{
	}
	else
	{
		echo "error: $val\n";
	}

/*
	file_put_contents("$target_file.txt", $val . "\n" . preg_replace('/[\s\t\v\r\n]+/u', " ", htmlspecialchars_decode(strip_tags(preg_replace(
		['/^[\s\S].*?(<html[^>]*>)/s','#<(head|script)[^>]*>[\s\S]*?</\1>#i','/(<\?[\s\S]*?\?>|<\s*(script|style|xmp|pre|textarea|input|option)[^>]*(?:>.*?<\s*\/\s*\2|\/)\s*>)|(\s){2,}/ius']
		, ['\1','','\1\3']
		,@file_get_contents($target_file)
	)))), LOCK_EX);
*/
}
