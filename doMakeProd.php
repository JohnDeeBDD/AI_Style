<?php

//This script zips and ships the production version
//It only works from John Dee's personal computer

$version = readline('Version to create: ');
update_version_in_files($version);

shell_exec("sudo rm -fr /var/www/html/wp-content/themes/ai_style/ai_style");
shell_exec("sudo mkdir /var/www/html/wp-content/themes/ai_style/ai_style");
copy("/var/www/html/wp-content/themes/ai_style/functions.php", "/var/www/html/wp-content/themes/ai_style/ai_style/functions.php");
copy("/var/www/html/wp-content/themes/ai_style/header.php", "/var/www/html/wp-content/themes/ai_style/ai_style/header.php");
copy("/var/www/html/wp-content/themes/ai_style/footer.php", "/var/www/html/wp-content/themes/ai_style/ai_style/footer.php");
copy("/var/www/html/wp-content/themes/ai_style/index.php", "/var/www/html/wp-content/themes/ai_style/ai_style/index.php");
copy("/var/www/html/wp-content/themes/ai_style/style.css", "/var/www/html/wp-content/themes/ai_style/ai_style/style.css");
copy("/var/www/html/wp-content/themes/ai_style/screenshot.png", "/var/www/html/wp-content/themes/ai_style/ai_style/screenshot.png");
shell_exec("sudo rsync -r src ai_style");
shell_exec("sudo zip -r ai_style-$version.zip ai_style");
shell_exec("sudo rm ai_style.zip");
shell_exec("sudo cp ai_style-$version.zip ai_style.zip");
shell_exec("sudo rm -fr ai_style");

$command = "scp -i /home/johndee/sportsman.pem ai_style.zip ubuntu@3.13.139.91:/var/www/cacbot.com/wp-content/uploads/ai_style.zip";
echo ($command . PHP_EOL);shell_exec($command);

$command = "scp -i /home/johndee/sportsman.pem ai_style_details.json ubuntu@3.13.139.91:/var/www/cacbot.com/wp-content/uploads/ai_style_details.json";
echo ($command . PHP_EOL);shell_exec($command);

$command = "scp -i /home/johndee/sportsman.pem ai_style_info.json ubuntu@3.13.139.91:/var/www/cacbot.com/wp-content/uploads/ai_style_info.json";
echo ($command . PHP_EOL);shell_exec($command);


function update_version_in_files($new_version) {
    $files = [
        'functions.php',
        'index.php',
        'header.php',
        'footer.php',
        'ai_style_info.json',
        'ai_style_details.json',
        'style.css'
    ];

    foreach ($files as $file) {
        // Read the file contents
        $contents = file_get_contents($file);

        if ($contents === false) {
            echo "Could not read the file: $file\n";
            continue;
        }

        // Replace version in each file based on the file type
        switch ($file) {
            case 'style.css':
                // Replace the version in the plugin header in cacbot.php
                $updated_contents = preg_replace(
                    '/(Version:\s*)(\d+)/i',
                    '${1}' . $new_version,
                    $contents
                );
                break;
            case 'info.json':
            case 'functions.php':
            case 'index.php':
            case 'header.php':
            case 'footer.php':
            case 'ai_style_info.json':
            case 'style.css':
            case 'ai_style_details.json':
                // Replace the version in JSON files
                $updated_contents = preg_replace(
                    '/("version"\s*:\s*")[^"]*(")/i',
                    '${1}' . $new_version . '${2}',
                    $contents
                );
                break;
            default:
                echo "Unsupported file type: $file\n";
                continue 2;
        }

        // Check if replacements were successful and write the file back
        if ($updated_contents !== null && $updated_contents !== $contents) {
            $result = file_put_contents($file, $updated_contents);

            if ($result === false) {
                echo "Could not write the file: $file\n";
            } else {
                echo "Updated version in $file to $new_version\n";
            }
        } else {
            echo "No version change detected in $file\n";
        }
    }
}
