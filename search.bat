_bin\php.exe extract.php

:REDO
set /p search=검색어를 입력해 주세요:
if "%search%" == "" goto REDO

_bin\php.exe search.php %search%