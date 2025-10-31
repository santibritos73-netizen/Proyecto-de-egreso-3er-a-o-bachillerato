# 📊 Base de Datos - Proyecto

## 🎯 Instalación Simplificada

### ✅ Archivo Único de Instalación

El proyecto ahora utiliza un **único archivo SQL** para la instalación completa:

```
📁 Base de datos/
  ├── instalacion_completa.sql ⭐ (ARCHIVO ÚNICO)
  └── README_BD.md (documentación)
```

---

## 🚀 Cómo Instalar la Base de Datos

### Opción 1: phpMyAdmin (Recomendado)
1. Abre **phpMyAdmin** (http://localhost/phpmyadmin)
2. Clic en **"Importar"**
3. Selecciona el archivo `instalacion_completa.sql`
4. Clic en **"Continuar"**
5. ✅ ¡Listo! Base de datos `proyecto` creada con todas las tablas

### Opción 2: Línea de comandos MySQL
```bash
mysql -u root -p < "c:\xampp\htdocs\Proyecto\Base de datos\instalacion_completa.sql"
```

---

## 📋 Estructura de la Base de Datos

### Base de Datos: `proyecto`
**Encoding:** UTF8MB4  
**Collation:** utf8mb4_general_ci

### 8 Tablas Incluidas:

| # | Tabla | Descripción | Relaciones |
|---|-------|-------------|------------|
| 1 | **usuarios** | Sistema de autenticación | - |
| 2 | **empresas** | Proveedores de servicios | → usuarios |
| 3 | **clientes** | Consumidores de servicios | → usuarios |
| 4 | **categorias** | Clasificación de servicios | - |
| 5 | **servicios** | Catálogo de servicios | → empresas, categorias |
| 6 | **contrataciones** | Gestión de contrataciones | → servicios, clientes, empresas |
| 7 | **mensajes** | Sistema de mensajería | → contrataciones |
| 8 | **resenas** | Reseñas y calificaciones | → contrataciones, servicios, clientes, empresas |

---

## 📦 Datos de Ejemplo Incluidos

El archivo `instalacion_completa.sql` viene con datos de ejemplo para probar el sistema:

### ✅ **8 Categorías**
- Tecnología, Salud, Educación, Limpieza, Construcción, Transporte, Entretenimiento, Consultoría

### ✅ **3 Empresas**
- TechSolutions Inc. (Tecnología)
- Salud Total (Salud)
- EduPlus Academy (Educación)

### ✅ **3 Clientes**
- Juan Pérez
- María González
- Carlos Rodríguez

### ✅ **4 Servicios**
- Desarrollo de Aplicación Móvil ($5,000)
- Consulta Médica General ($50)
- Curso de Python Avanzado ($299)
- Mantenimiento de Sitio Web ($150)

### ✅ **3 Contrataciones**
- App para gestión de inventario (en progreso)
- Control rutinario (completado)
- Curso Python (aceptado)

### ✅ **4 Mensajes de Ejemplo**
### ✅ **1 Reseña 5 Estrellas**

---

## 🔄 Cambios Recientes

### ✨ Actualización: 9 de Octubre 2025

**Optimización de archivos SQL:**
- ✅ Creado `instalacion_completa.sql` con las 8 tablas
- ✅ Incluidos todos los datos de ejemplo
- ✅ Corregido nombre de base de datos: `proyecto`
---

## 🔐 Diagrama de Relaciones

```
usuarios
  ├─→ empresas (usuario_id)
  └─→ clientes (usuario_id)

categorias
  └─→ servicios (categoria_id)

empresas
  └─→ servicios (empresa_id)

servicios
  └─→ contrataciones (servicio_id)

clientes
  └─→ contrataciones (cliente_id)

empresas
  └─→ contrataciones (empresa_id)

contrataciones
  ├─→ mensajes (contratacion_id)
  └─→ resenas (contratacion_id)
```

---

## ⚙️ Configuración en el Proyecto

El archivo de configuración está en:
```
programacion/Modelo/config.php
```

**Asegúrate de que contenga:**
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'proyecto');  // ← Nombre correcto
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');
```

---

## ✅ Verificación Post-Instalación

Después de importar `instalacion_completa.sql`, verifica:

```sql
-- Verificar que existen las 8 tablas
SHOW TABLES FROM proyecto;

-- Verificar datos de ejemplo
SELECT COUNT(*) FROM categorias;  -- Debe ser 8
SELECT COUNT(*) FROM empresas;    -- Debe ser 3
SELECT COUNT(*) FROM clientes;    -- Debe ser 3
SELECT COUNT(*) FROM servicios;   -- Debe ser 4
```

---

## 📌 Notas Importantes

1. **Archivo único**: Solo se necesita `instalacion_completa.sql`
2. **Idempotente**: Usa `IF NOT EXISTS`, puedes ejecutarlo múltiples veces
3. **Foreign Keys**: Todas las relaciones están configuradas con CASCADE
4. **Datos de prueba**: Incluye datos para testing inmediato

---

## 🆘 Solución de Problemas

### Error: "Database already exists"
**Solución:** El script usa `CREATE DATABASE IF NOT EXISTS`, no hay problema.

### Error: "Table already exists"
**Solución:** El script usa `CREATE TABLE IF NOT EXISTS`, no hay problema.

### Error: "Cannot add foreign key constraint"
**Solución:** Verifica que las tablas se creen en orden. El script ya está ordenado correctamente.

### Quiero reinstalar desde cero
```sql
DROP DATABASE IF EXISTS proyecto;
```
Luego ejecuta nuevamente `instalacion_completa.sql`.

---

**Fecha de actualización:** 9 de Octubre 2025  
**Versión de la base de datos:** 2.0  
**Tablas:** 8  
**Archivos SQL necesarios:** 1 ✅
