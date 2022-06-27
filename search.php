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
$dir_pdf = __DIR__ . '\\pdf';
$result = scanAllDir($dir);

$count = 1;
unset($argv[0]);
echo "\n검색: " . implode(' AND ', $argv). "\n";

foreach($result as $val)
{
	if(!preg_match('/\.content\.txt$/i', $val))
	{
		continue;
	}
	if(!file_exists($dir_pdf . '\\' . preg_replace('/\.content\.txt$/i', '.pdf', $val))) // 원본 pdf 지웠다면 검색에 포함 안 되게.
	{
		continue;
	}
	$buff = file_get_contents($dir . '\\' . $val);

	$find = FALSE;
	foreach($argv as $v)
	{
		if(!strlen($v))
		{
			continue;
		}
		// 느낌표 키워드의 단어가 발견되면 없음으로 처리, break
		if(preg_match('/^\!/u', $v))
		{
			if(mb_stripos($buff, preg_replace('/^\!/u', '', $v))!==FALSE)
			{
				$find = FALSE;
				break;
			}
		}
		else
		{
			$find = mb_stripos($buff, $v);
			if($find===FALSE)
			{
				break;
			}
		}
	}
	if($find!==FALSE)
	{
		echo "\n";
		echo "[$count] " . preg_replace('/\.content\.txt$/i', '.pdf', $val);
		echo "\n";
		$start = $find - 100;
		if($start < 0) $start = 0;
		echo preg_replace('/[\r\n]+/u', ' ', mb_substr($buff, $start, 200));
		$count++;
		echo "\n";
	}
}

if($count<=1)
{
	echo 'no search results';
}

echo "\n";