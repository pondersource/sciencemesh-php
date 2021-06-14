#!/bin/bash
export PHP_MEMORY_LIMIT="512M"
php console.php maintenance:install --admin-user alice --admin-pass alice123
php console.php status
cp -r /sciencemesh apps/
php console.php app:enable sciencemesh
echo configured
