# TPE Web 2 - Fast Track - Tercera Entrega

##  Integrantes
- **Jeremias Rodriguez Miranda** (jeremias.rod.m@gmail.com)  
- **Candela Schneider D'Amico** (schneider.candela@gmail.com)

---

##  DER


##  URL Base de Ejemplo
```
TPE_FASTTRACK_API/api/
```

---

#  Endpoints

#  Reviews (Reseñas)

## **GET /reviews**
Obtiene todas las reseñas, permitiendo **ordenamiento**, **filtrado** y **paginación**.

###  Query Params

#### **Ordenamiento**
- `sort`: Campo por el cual ordenar (`puntuacion`, `fecha`)
- `order`: `asc` (por defecto) o `desc`

**Ejemplo:**
```
GET TPE_FASTTRACK_API/api/reviews?sort=puntuacion&order=asc
```

#### **Paginación**
- `page`: Número de página  
- `limit`: Cantidad máxima de reseñas por página  

**Ejemplo:**
```
GET TPE_FASTTRACK_API/api/reviews?sort=fecha&order=desc&page=2&limit=5
```

#### **Filtrado**
- `field`: Campo a filtrar  
- `value`: Valor exacto  

**Ejemplo:**
```
GET TPE_FASTTRACK_API/api/reviews?field=puntuacion&value=5
```

---

## **GET /reviews/:ID**
Devuelve una reseña específica según su ID.

**Ejemplo:**
```
GET TPE_FASTTRACK_API/api/reviews/1
```

---


## **POST /reviews**
Crea una nueva reseña.  
 **Requiere autenticación** (token JWT).

###  JSON esperado
```json
{
  "id_producto": 9,
  "nombre_usuario": "Ana Torres",
  "comentario": "Short cómodo pero se achicó un poco",
  "puntuacion": 3
}
```

### ✔ Condiciones:
- `puntuacion`: número entre 1 y 10
- Todos los campos son obligatorios
- Errores → `400 Bad Request`

---

## **PUT /reviews/:ID**
Modifica la reseña indicada.  
 **Requiere autenticación**.

###  Ejemplo JSON de actualización
```json
{
  "comentario": "Nuevo comentario actualizado",
  "puntuacion": 8
}
```

### ✔ Reglas:
- `puntuacion` entre 1 y 10
- Ambos campos son obligatorios
- Si el ID no existe → `404 Not Found`

---

#  Endpoints de Reviews

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/reviews` | Lista todas las reviews |
| GET | `/reviews/:ID` | Devuelve una review específica |
| POST | `/reviews` | Crea una review (requiere autenticación) |
| PUT | `/reviews/:ID` | Modifica una review (requiere autenticación) |

---

# Autenticación

## **GET /usuarios/token**
Obtiene un token JWT para usar en endpoints protegidos.

### ✔ Credenciales:
- **Usuario:** webadmin
- **Contraseña:** admin

### ✔ Encabezado requerido:
```
Authorization: Basic d2ViYWRtaW46YWRtaW4=
```

---

##  Notas importantes
- Todos los endpoints que requieren autenticación deben incluir el token JWT en el header:
  ```
  Authorization: Bearer <token>
  ```
- Los códigos de respuesta HTTP siguen el estándar REST:
  - `200 OK` - Petición exitosa
  - `201 Created` - Recurso creado exitosamente
  - `400 Bad Request` - Error en la petición
  - `401 Unauthorized` - No autorizado
  - `404 Not Found` - Recurso no encontrado
  - `500 Internal Server Error` - Error del servidor