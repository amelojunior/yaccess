#!/bin/bash
bin/magento maintenance:enable
echo "Limpando pastas..."
rm -rf pub/static/*
rm -rf var/view_preprocessed/*
rm -rf generated/*
rm -rf var/cache/*
rm -rf var/page_cache/*
echo "Recriando static..."
bin/magento setup:static-content:deploy pt_BR
echo " "
echo "Compilando..."
bin/magento setup:di:compile
bin/magento cache:flush
bin/magento maintenance:disable
echo " "
echo "Deploy finalizado..."
