<?php
$visitas = $_COOKIE["visitas"] ?? 0;
$visitas = $visitas + 1;
setcookie("visitas", $visitas, time() + 3600);
echo "Você visitou esta página $visitas vez(es).";
