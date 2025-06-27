# API Documentation

## Descripción
Esta es la documentación de la API REST para el proyecto PipelineLab desarrollado en Laravel.

## URL Base
```
http://localhost:8000/api
```

## Endpoints Disponibles

### Información de la API
- **GET** `/api/info` - Información general de la API
- **GET** `/api/health` - Health check de la API

### Usuarios (v1)
Todas las rutas de usuarios están bajo el prefijo `/api/v1/users`

#### Listar todos los usuarios
```http
GET /api/v1/users
```

**Respuesta exitosa (200):**
```json
{
    "success": true,
    "message": "Users retrieved successfully",
    "data": [
        {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "created_at": "2025-06-27T15:00:00.000000Z"
        }
    ]
}
```

#### Crear un nuevo usuario
```http
POST /api/v1/users
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123"
}
```

**Respuesta exitosa (201):**
```json
{
    "success": true,
    "message": "User created successfully",
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2025-06-27T15:00:00.000000Z"
    }
}
```

#### Obtener un usuario específico
```http
GET /api/v1/users/{id}
```

#### Actualizar un usuario
```http
PUT /api/v1/users/{id}
Content-Type: application/json

{
    "name": "Jane Doe",
    "email": "jane@example.com"
}
```

#### Eliminar un usuario
```http
DELETE /api/v1/users/{id}
```

## Formato de Respuestas

### Respuesta Exitosa
```json
{
    "success": true,
    "message": "Mensaje descriptivo",
    "data": {
        // Datos de respuesta
    }
}
```

### Respuesta de Error
```json
{
    "success": false,
    "message": "Mensaje de error",
    "errors": {
        // Detalles de errores de validación (si aplica)
    }
}
```

## Códigos de Estado HTTP

- `200` - OK
- `201` - Created
- `400` - Bad Request
- `404` - Not Found
- `422` - Unprocessable Entity (Validation Error)
- `500` - Internal Server Error

## Headers Requeridos

```
Content-Type: application/json
Accept: application/json
```

## Autenticación

Para endpoints que requieren autenticación, usar Laravel Sanctum:
```
Authorization: Bearer {token}
```

## Ejemplos con cURL

### Health Check
```bash
curl -X GET http://localhost:8000/api/health \
  -H "Accept: application/json"
```

### Crear Usuario
```bash
curl -X POST http://localhost:8000/api/v1/users \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123"
  }'
```

### Listar Usuarios
```bash
curl -X GET http://localhost:8000/api/v1/users \
  -H "Accept: application/json"
```
