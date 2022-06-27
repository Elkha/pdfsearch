chcp 65001

mkdir _data
mkdir _bin\php
curl https://windows.php.net/downloads/releases/php-7.4.30-Win32-vc15-x64.zip --output _bin\php-7.4.30-Win32-vc15-x64.zip
tar -xf _bin\php-7.4.30-Win32-vc15-x64.zip --directory _bin\php
copy _bin\php.ini _bin\php\php.ini


curl https://nodejs.org/dist/v16.15.1/node-v16.15.1-win-x64.zip --output _bin\node-v16.15.1-win-x64.zip
tar -xf _bin\node-v16.15.1-win-x64.zip --directory _bin
move _bin\node-v16.15.1-win-x64 _bin\node

cd _bin
del node-v16.15.1-win-x64.zip
del php-7.4.30-Win32-vc15-x64.zip

node\npm install pdf2json
cd ..

PAUSE
