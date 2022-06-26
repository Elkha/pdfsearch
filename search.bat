@echo off
chcp 65001
_bin\php.exe extract.php

:CONFIRM
set /p search=검색어를 입력해 주세요 (제외할 검색어는 ! 문자를 앞에 붙입니다):

_bin\php.exe search.php %search%

GOTO CONFIRM