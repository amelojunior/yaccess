#!/bin/bash
echo "Limpando pastas..."
rm -rf pub/static/*
rm -rf var/view_preprocessed/*
rm -rf generated/*
rm -rf var/cache/*
rm -rf var/page_cache/*
bin/magento cache:flush
echo "Deploy finalizado..."

