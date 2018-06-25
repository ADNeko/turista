#!/bin/sh
echo "Instalando dependencias"
composer install

echo "Copiando componentes"
cp -r components/ web/

echo "Descargando tema"
wget -c https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/flatly/bootstrap.min.css
mv bootstrap.min.css  web/components/bootstrap/css/bootstrap-flatly.min.css


echo "Instalando assets"
php app/console assets:install
php app/console assetic:dump

echo "Optimizando autoloader"
composer dump-autoload --optimize

echo "Borrando la cache antigua"
php app/console cache:clear --env=dev
php app/console cache:clear --env=prod

echo "Generando la cache nueva"
php app/console cache:warmup --env=prod

echo "Done!"
