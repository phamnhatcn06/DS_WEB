@echo off
rem Chay ung dung console cua Yii (vd: yiic migrate)
set PHP_BIN=C:\MAMP\bin\php\php8.1.0\php.exe
if not exist "%PHP_BIN%" set PHP_BIN=php
"%PHP_BIN%" "%~dp0protected\yiic.php" %*
