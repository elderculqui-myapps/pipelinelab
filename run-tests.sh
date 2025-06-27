#!/bin/bash

# Script para ejecutar pruebas PHPUnit en Laravel
# Configuración y ejecución de pruebas

echo "🧪 Ejecutando Pruebas PHPUnit para Laravel API"
echo "=============================================="

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo ""
echo -e "${BLUE}1. Verificando configuración de pruebas...${NC}"
echo "-------------------------------------------"

# Verificar que existe el archivo de configuración
if [ ! -f "phpunit.xml" ]; then
    echo -e "${RED}❌ Error: No se encontró phpunit.xml${NC}"
    exit 1
fi

# Verificar que existe la carpeta de pruebas
if [ ! -d "tests" ]; then
    echo -e "${RED}❌ Error: No se encontró la carpeta tests${NC}"
    exit 1
fi

echo -e "${GREEN}✅ Configuración de pruebas encontrada${NC}"

echo ""
echo -e "${BLUE}2. Ejecutando todas las pruebas...${NC}"
echo "--------------------------------"

# Ejecutar todas las pruebas
./vendor/bin/phpunit

RESULT=$?

echo ""
echo -e "${BLUE}3. Comandos adicionales disponibles:${NC}"
echo "-----------------------------------"
echo "• Ejecutar solo pruebas Feature:"
echo "  ./vendor/bin/phpunit --testsuite=Feature"
echo ""
echo "• Ejecutar solo pruebas Unit:"
echo "  ./vendor/bin/phpunit --testsuite=Unit"
echo ""
echo "• Ejecutar una prueba específica:"
echo "  ./vendor/bin/phpunit tests/Feature/UserApiTest.php"
echo ""
echo "• Ejecutar con reporte de cobertura:"
echo "  ./vendor/bin/phpunit --coverage-html coverage"
echo ""
echo "• Ejecutar en modo verbose:"
echo "  ./vendor/bin/phpunit --verbose"
echo ""
echo "• Ejecutar con filtro:"
echo "  ./vendor/bin/phpunit --filter test_can_create_user"

echo ""
if [ $RESULT -eq 0 ]; then
    echo -e "${GREEN}🎉 ¡Todas las pruebas pasaron exitosamente!${NC}"
else
    echo -e "${RED}💥 Algunas pruebas fallaron. Revisa los errores arriba.${NC}"
fi

echo ""
echo -e "${YELLOW}💡 Tip: Usa 'php artisan test' como alternativa a phpunit${NC}"
