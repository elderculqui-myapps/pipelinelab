# CI/CD Workflow Documentation

Este documento describe el pipeline de CI/CD configurado para el proyecto Laravel PipelineLab.

## 🔄 Workflow Overview

El pipeline está dividido en **3 jobs principales** que se ejecutan secuencialmente:

### 1. **Install Dependencies** 
- ⚡ **Propósito**: Instalar y cachear dependencias
- 🏃 **Se ejecuta en**: Todos los pushes y PRs a `develop`
- ⏱️ **Duración estimada**: 2-3 minutos

**Pasos:**
- Checkout del código
- Setup PHP 8.2 con extensiones necesarias
- Cache de dependencias Composer
- Instalación de paquetes Composer
- Setup Node.js 20
- Instalación de paquetes NPM
- Cache de directorios vendor/ y node_modules/

### 2. **Run Tests**
- ⚡ **Propósito**: Ejecutar pruebas y generar reportes de cobertura
- 🏃 **Se ejecuta**: Después de `install-dependencies`
- ⏱️ **Duración estimada**: 3-5 minutos
- 🗄️ **Base de datos**: MySQL 8.0 (service container)

**Pasos:**
- Configuración del entorno Laravel
- Migración de base de datos
- Construcción de assets (npm run build)
- Ejecución de PHPUnit con coverage
- Upload de artefactos (coverage.xml, test-results.xml)

### 3. **SonarQube Analysis**
- ⚡ **Propósito**: Análisis de calidad de código
- 🏃 **Se ejecuta**: Después de `run-tests`
- ⏱️ **Duración estimada**: 2-4 minutos

**Pasos:**
- Download de reportes de pruebas
- Scan de SonarQube
- Verificación de Quality Gate

## 📋 Requisitos Previos

### Secrets de GitHub requeridos:
- `SONAR_TOKEN`: Token de autenticación para SonarQube
- `SONAR_HOST_URL`: URL de tu instancia de SonarQube

### Environment configurado:
- `DEVELOP`: Environment en GitHub con las variables necesarias

## 📁 Archivos de Configuración

```
├── .github/workflows/develop.yml     # Workflow principal
├── sonar-project.properties          # Configuración SonarQube
├── phpunit.xml                       # Configuración PHPUnit con coverage
├── .env.testing                      # Variables de entorno para testing
├── verify-ci.sh                      # Script de verificación local
└── run-tests.sh                      # Script para ejecutar pruebas localmente
```

## 🚀 Ejecución Local

### Verificar todo antes de push:
```bash
./verify-ci.sh
```

### Ejecutar solo las pruebas:
```bash
./run-tests.sh
```

### Generar cobertura manualmente:
```bash
./vendor/bin/phpunit --coverage-clover=coverage.xml --log-junit=test-results.xml
```

## 📊 Reportes Generados

- **coverage.xml**: Reporte de cobertura en formato Clover para SonarQube
- **test-results.xml**: Resultados de pruebas en formato JUnit
- **coverage-html/**: Reporte HTML de cobertura (solo local)

## 🎯 Métricas de SonarQube

El proyecto está configurado para analizar:
- **Cobertura de código**: Mínimo recomendado 80%
- **Duplicación**: Máximo 3%
- **Maintainability Rating**: A
- **Reliability Rating**: A
- **Security Rating**: A

## 🔧 Personalización

### Cambiar versión de PHP:
Edita el archivo `.github/workflows/develop.yml`:
```yaml
php-version: '8.3'  # Cambiar de 8.2 a 8.3
```

### Agregar más bases de datos:
```yaml
services:
  redis:
    image: redis:alpine
    ports:
      - 6379:6379
```

### Modificar exclusiones de SonarQube:
Edita `sonar-project.properties`:
```properties
sonar.exclusions=**/*Test.php,**/vendor/**,**/mi-carpeta-custom/**
```

## 🐛 Troubleshooting

### Error común: "Quality Gate failed"
- Revisa los reportes en SonarQube
- Asegúrate de que la cobertura sea suficiente
- Corrige los code smells reportados

### Error: "PHPUnit tests failed"
- Ejecuta `./run-tests.sh` localmente
- Revisa los logs en GitHub Actions
- Verifica la configuración de base de datos

### Error: "Composer install failed"
- Verifica que `composer.lock` esté committeado
- Revisa las dependencias en `composer.json`

## 📈 Optimizaciones Implementadas

- ✅ **Cache de dependencias**: Reduce tiempo de instalación
- ✅ **Jobs paralelos**: Cuando sea posible
- ✅ **Artifacts**: Reutilización de reportes entre jobs
- ✅ **Health checks**: Para servicios de base de datos
- ✅ **Timeouts**: Para evitar jobs colgados

## 📝 Siguientes Pasos

1. Configurar notifications (Slack, email)
2. Agregar deployment automático
3. Implementar semantic versioning
4. Configurar dependabot para updates automáticos
