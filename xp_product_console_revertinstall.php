<?php
/**
@usage:
php xp_product_console_revertinstall.php
*/

$basepath = "_xp_files/product_revertinstall/";

copy($basepath."/public/admin.php", "public/admin.php");
copy($basepath."/public/install.php", "public/install.php");

unlink("application/admin/command/Install/install.lock");
unlink("public/manage.php");

echo "Revert Install Success!";