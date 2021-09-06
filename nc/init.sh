#!/bin/bash
export PHP_MEMORY_LIMIT="512M"
php console.php maintenance:install --admin-user einstein --admin-pass relativity
php console.php status
php console.php app:enable sciencemesh
echo configured
