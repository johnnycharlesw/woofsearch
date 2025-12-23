<?php
if ($_SERVER['REQUEST_METHOD'] == "BREW") {
    header("X-Coffee-Strength: 0 (because this is WoofSearch, not even a teapot, actually)");
    include '418.php';
    exit(0);
}
include 'vendor/autoload.php';
include 'pre-config.php';
include 'config.php';