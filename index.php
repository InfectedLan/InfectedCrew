<?php
// Which version should we use?
$version = '2';

header('Location: https://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] . 'v' . $version);
?>