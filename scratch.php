<?php
$content = file_get_contents("patch-visual-debut.php");
$content = str_replace("]\n    [\n        \"file\" => \"vendor/bagistoplus/basic-blocks/src/Tailwind.php\"", "],\n    [\n        \"file\" => \"vendor/bagistoplus/basic-blocks/src/Tailwind.php\"", $content);
file_put_contents("patch-visual-debut.php", $content);

