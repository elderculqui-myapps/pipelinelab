#!/bin/bash

# Script para verificar el proyecto antes de CI/CD
echo "🔍 Verificando proyecto Laravel para CI/CD"
echo "=========================================="

# Colores
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# Flag para errores
ERRORS=0

echo ""
echo -e "${BLUE}1. Verificando dependencias de Composer...${NC}"
echo "---------------------------------------------"
if composer validate --no-check-all --strict; then
    echo -e "${GREEN}✅ composer.json válido${NC}"
else
    echo -e "${RED}❌ Problemas en composer.json${NC}"
    ERRORS=1
fi

echo ""
echo -e "${BLUE}2. Instalando dependencias...${NC}"
echo "-----------------------------"
composer install --no-progress --prefer-dist --optimize-autoloader

echo ""
echo -e "${BLUE}3. Verificando dependencias de NPM...${NC}"
echo "-----------------------------------"
if npm audit --audit-level=high; then
    echo -e "${GREEN}✅ Dependencias NPM seguras${NC}"
else
    echo -e "${YELLOW}⚠️  Vulnerabilidades encontradas en NPM${NC}"
fi

echo ""
echo -e "${BLUE}4. Instalando dependencias NPM...${NC}"
echo "--------------------------------"
npm install

echo ""
echo -e "${BLUE}5. Ejecutando análisis estático (PHP Stan)...${NC}"
echo "--------------------------------------------"
if command -v phpstan &> /dev/null; then
    phpstan analyse app
else
    echo -e "${YELLOW}⚠️  PHPStan no instalado, saltando análisis estático${NC}"
fi

echo ""
echo -e "${BLUE}6. Ejecutando pruebas con cobertura...${NC}"
echo "------------------------------------"
./vendor/bin/phpunit --coverage-clover=coverage.xml --log-junit=test-results.xml
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Todas las pruebas pasaron${NC}"
else
    echo -e "${RED}❌ Algunas pruebas fallaron${NC}"
    ERRORS=1
fi

echo ""
echo -e "${BLUE}7. Construyendo assets...${NC}"
echo "------------------------"
if npm run build; then
    echo -e "${GREEN}✅ Assets construidos exitosamente${NC}"
else
    echo -e "${RED}❌ Error al construir assets${NC}"
    ERRORS=1
fi

echo ""
echo -e "${BLUE}8. Verificando archivos para SonarQube...${NC}"
echo "----------------------------------------"

# Verificar archivos requeridos
if [ -f "sonar-project.properties" ]; then
    echo -e "${GREEN}✅ sonar-project.properties encontrado${NC}"
else
    echo -e "${RED}❌ sonar-project.properties no encontrado${NC}"
    ERRORS=1
fi

if [ -f "coverage.xml" ]; then
    echo -e "${GREEN}✅ coverage.xml generado${NC}"
else
    echo -e "${RED}❌ coverage.xml no generado${NC}"
    ERRORS=1
fi

if [ -f "test-results.xml" ]; then
    echo -e "${GREEN}✅ test-results.xml generado${NC}"
else
    echo -e "${RED}❌ test-results.xml no generado${NC}"
    ERRORS=1
fi

echo ""
echo "=========================================="
if [ $ERRORS -eq 0 ]; then
    echo -e "${GREEN}🎉 ¡Proyecto listo para CI/CD!${NC}"
    echo ""
    echo "Pasos siguientes:"
    echo "1. git add ."
    echo "2. git commit -m 'Configure CI/CD with SonarQube'"
    echo "3. git push origin develop"
else
    echo -e "${RED}💥 Se encontraron errores. Por favor, corrígelos antes de hacer push.${NC}"
fi

echo ""
echo -e "${BLUE}Archivos de configuración creados:${NC}"
echo "• .github/workflows/develop.yml"
echo "• sonar-project.properties"
echo "• .env.testing"
echo "• phpunit.xml (actualizado)"

exit $ERRORS
