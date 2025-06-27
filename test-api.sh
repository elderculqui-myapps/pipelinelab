#!/bin/bash

# Script para probar la API REST de Laravel
# Asegúrate de que el servidor esté corriendo: php artisan serve

BASE_URL="http://localhost:8000/api"

echo "🚀 Probando API REST de Laravel"
echo "================================="

echo ""
echo "1. Health Check"
echo "---------------"
curl -X GET "$BASE_URL/health" \
  -H "Accept: application/json" \
  -w "\n\nStatus Code: %{http_code}\n" \
  -s

echo ""
echo "2. API Info"
echo "-----------"
curl -X GET "$BASE_URL/info" \
  -H "Accept: application/json" \
  -w "\n\nStatus Code: %{http_code}\n" \
  -s

echo ""
echo "3. Listar usuarios (puede estar vacío)"
echo "-------------------------------------"
curl -X GET "$BASE_URL/v1/users" \
  -H "Accept: application/json" \
  -w "\n\nStatus Code: %{http_code}\n" \
  -s

echo ""
echo "4. Crear un usuario de prueba"
echo "-----------------------------"
curl -X POST "$BASE_URL/v1/users" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123"
  }' \
  -w "\n\nStatus Code: %{http_code}\n" \
  -s

echo ""
echo "5. Listar usuarios después de crear uno"
echo "---------------------------------------"
curl -X GET "$BASE_URL/v1/users" \
  -H "Accept: application/json" \
  -w "\n\nStatus Code: %{http_code}\n" \
  -s

echo ""
echo "✅ Pruebas completadas"
echo ""
echo "Para iniciar el servidor Laravel:"
echo "php artisan serve"
echo ""
echo "Para ver la documentación completa:"
echo "cat API_DOCUMENTATION.md"
