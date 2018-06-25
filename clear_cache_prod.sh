#!/bin/sh
echo "Borrando la cache antigua"
php app/console cache:clear --env=prod

echo "Generando la cache nueva"
php app/console cache:warmup --env=prod

echo "Done!"
