@echo off
rem ---------------------------------------------------------------------------
rem Chay ung dung console cua Yii. Vi du:  yiic migrate
rem
rem PHP CLI cua MAMP khong nap php.ini nao, nen cac extension can thiet duoc
rem truyen truc tiep bang co -d. Nho vay khong phai sua cau hinh MAMP.
rem ---------------------------------------------------------------------------

set PHP_DIR=C:\MAMP\bin\php\php8.1.0
set PHP_BIN=%PHP_DIR%\php.exe

if not exist "%PHP_BIN%" (
  echo Khong tim thay PHP tai %PHP_BIN% - dung 'php' trong PATH.
  php "%~dp0protected\yiic.php" %*
  goto :eof
)

"%PHP_BIN%" -d extension_dir=%PHP_DIR%\ext -d extension=mbstring -d extension=openssl -d extension=pdo_mysql -d extension=fileinfo -d extension=gd "%~dp0protected\yiic.php" %*
