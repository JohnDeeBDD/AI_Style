<?php

echo "Running pre-test command...\n";
$output = shell_exec('npx spack entry=/src/AI_Style/ai-style.js_src/ai-style.js output=/src/AI_Style');
echo $output;