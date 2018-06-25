#!/bin/sh
composer install
echo "Copying components"
cp -r components/ web/

echo "Done!"
