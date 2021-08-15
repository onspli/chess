<?php
/**
* Generates README.md from README.template.md
*/
require __DIR__ . '/../vendor/autoload.php';

$template = __DIR__ . '/../README.template.md';
$output = __DIR__ . '/../README.md';

$handle_in = fopen($template, "r");
$handle_out = fopen($output, "w");

if (!$handle_in) {
  throw new \RuntimeException('Could not open ' . $template . ' for reading.');
}

if (!$handle_out) {
  throw new \RuntimeException('Could not open ' . $output . ' for writing.');
}

$parsing_php_code = false;

while (($line = fgets($handle_in)) !== false) {
  if (trim($line) == '``` php') {
    $parsing_php_code = true;
    fputs($handle_out, $line);
    continue;
  } else if (trim($line) == '```') {
    $parsing_php_code = false;
  }

  if (!$parsing_php_code) {
    fputs($handle_out, $line);
    continue;
  }

  ob_start();
  eval($line);
  $out = ob_get_contents();
  ob_end_clean();


  fputs($handle_out, $line);
  if ($out !== '') {
    if (strstr($out, PHP_EOL)) {
      fputs($handle_out, "/*" . PHP_EOL . $out . PHP_EOL . "*/" . PHP_EOL);
    } else {
      fputs($handle_out, "// " . $out . PHP_EOL);
    }

  }
}

fclose($handle_in);
fclose($handle_out);

echo "README.md has been updated" . PHP_EOL;
