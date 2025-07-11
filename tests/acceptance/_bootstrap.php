<?php

echo "Running pre-test command...\n";
$output = shell_exec('npx spack entry=/src/AI_Style/ai-style.js_src/ai-style.js output=/src/AI_Style');
echo $output;
global $Assistant_rap;
global $Assistant_pw;
global $Assistant_un;
$Assistant_rap = "";
$Assistant_pw = "password";
$Assistant_un = "Assistant";
