# 📦 Proyecto Laravel 8 con JWT

API RESTful construida con **Laravel 8**, autenticación **JWT**, y desplegada en la nube con **Render.com**. Utiliza **Supabase** como backend de base de datos (PostgreSQL) y **Swagger** para la documentación de endpoints.

---

## 🚀 Despliegue en Producción

Este proyecto está desplegado en:

-   🔗 **API Base URL:** [https://api-rest-laravel-8-jwt.onrender.com](https://api-rest-laravel-8-jwt.onrender.com)
-   📄 **Documentación Swagger:** [https://api-rest-laravel-8-jwt.onrender.com/api/documentation](https://api-rest-laravel-8-jwt.onrender.com/api/documentation)

---

## ⚙️ Stack Tecnológico

### Backend

-   **PHP** - 8.2 (Docker)
-   **Laravel** - 8.83.29
-   **JWT Auth** - [tymon/jwt-auth](https://github.com/tymondesigns/jwt-auth) (`dev-develop`)
-   **Swagger Docs** - [l5-swagger](https://github.com/DarkaOnLine/L5-Swagger) (`v8.6.5`)
-   **PostgreSQL** - Base de datos en la nube a través de [Supabase](https://supabase.com)
-   **Docker** - Para contenerización del proyecto
-   **Render** - Plataforma de despliegue

---

## ☁️ Hosting y Servicios

### 🔧 Render

El backend Laravel está desplegado en [Render](https://render.com), usando:

-   Imagen Docker personalizada
-   Plan gratuito
-   Variables de entorno definidas en `render.yaml`
-   Apache + PHP 8.2

### 🗄️ Supabase

[Supabase](https://supabase.com/) es usado como proveedor de base de datos PostgreSQL, con la siguiente configuración (protegida por variables de entorno en producción):

```env
DB_CONNECTION=pgsql
DB_HOST=aws-0-us-east-2.pooler.supabase.com
DB_PORT=6543
DB_DATABASE=postgres
DB_USERNAME=...
DB_PASSWORD=...
```

---

## 🔐 Autenticación JWT

Este proyecto usa el paquete tymon/jwt-auth para autenticación basada en tokens:

-   Registro
-   Inicio de sesión
-   Generación de token
-   Protección de rutas con middleware auth:api

## 📄 Documentación Swagger

Los endpoints de la API están documentados automáticamente con Swagger a través del paquete l5-swagger.

URL de la documentación:

> https://api-rest-laravel-8-jwt.onrender.com/api/documentation

## 🐳 Docker

Se utiliza una imagen Docker personalizada basada en php:8.2-apache con las extensiones necesarias para Laravel y PostgreSQL.

---

## 🖥️ Cómo empezar en Windows (modo local)

### ✅ Requisitos Previos

Antes de comenzar, asegúrate de tener instalado lo siguiente en tu entorno Windows:

-   [XAMPP](https://www.apachefriends.org/index.html) – Para ejecutar Apache y MySQL de forma local.
-   [Composer](https://getcomposer.org/) – Para gestionar dependencias PHP.
-   [Git](https://git-scm.com/) – Para clonar el repositorio.
-   [Visual Studio Code](https://code.visualstudio.com/) (opcional) – Editor de código recomendado.

### ⚙️ Bases de Datos Compatibles

Este proyecto es compatible con múltiples motores de bases de datos. Puedes elegir el que mejor se adapte a tus necesidades:

#### 🔹 MySQL (versión recomendada: 8.0.40)

Puedes usar una instalación estándar de MySQL o la versión incluida en XAMPP.

-   [MySQL (instalación oficial)](https://dev.mysql.com/downloads/installer/)
-   [XAMPP (incluye MySQL)](https://www.apachefriends.org/es/index.html)

#### 🔹 Supabase (opcional, en la nube)

También puedes optar por usar Supabase, una solución en línea basada en PostgreSQL, ideal para entornos de desarrollo rápido y despliegues en la nube.

-   [Crear cuenta en Supabase](https://supabase.com/)

> 💡 Asegúrate de configurar correctamente las variables de conexión según el motor de base de datos que elijas.

---

### 🛠️ Pasos para instalar y correr el proyecto

1. **Clona este repositorio:**

    ```bash
    git clone https://github.com/david99cartagena/api-rest-laravel-8-jwt.git
    cd api-rest-laravel-8-jwt
    ```

2. **Copia el archivo de entorno .env.example a** .env

    ```bash
    cp .env.example .env
    ```

3. **Instala las dependencias de Laravel:**

    ```bash
    composer install
    ```

4. **Genera la clave de aplicación:**

    ```bash
    php artisan key:generate
    ```

5. **Configura la base de datos en el archivo** .env

    - Usa las credenciales Base de datos local o de tu cuenta Supabase.
    - Asegúrate de completar: DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD.
    - Agrega tu JWT_SECRET también.

6. **Ejecuta las migraciones de base de datos:** (si aplica)

    ```bash
    php artisan migrate
    ```

7. **Levanta el servidor local:**
    ```bash
     php artisan serve
    ```
    - Esto levantará la API en:
        > `http://127.0.0.1:8000` o `http://localhost:8000/`

ℹ️ Notas adicionales

-   Si tienes conflictos con puertos (por ejemplo con XAMPP), puedes cambiar el puerto con:

    ```bash
     php artisan serve --port=8080
    ```

## 📷 Imagenes de la Aplicacion

![](https://raw.githubusercontent.com/david99cartagena/api-rest-laravel-8-jwt/refs/heads/main/media/Screenshot_1.png)

![](https://raw.githubusercontent.com/david99cartagena/api-rest-laravel-8-jwt/refs/heads/main/media/Screenshot_2.png)

![](https://raw.githubusercontent.com/david99cartagena/api-rest-laravel-8-jwt/refs/heads/main/media/Screenshot_3.png)

**Products**

-   Listar todos los productos
    ![](https://raw.githubusercontent.com/david99cartagena/api-rest-laravel-8-jwt/refs/heads/main/media/Screenshot_4.png)

-   Obtener un producto por ID
    ![](https://raw.githubusercontent.com/david99cartagena/api-rest-laravel-8-jwt/refs/heads/main/media/Screenshot_5.png)

-   Actualizar un producto por ID
    ![](https://raw.githubusercontent.com/david99cartagena/api-rest-laravel-8-jwt/refs/heads/main/media/Screenshot_6.png)

    ![](https://raw.githubusercontent.com/david99cartagena/api-rest-laravel-8-jwt/refs/heads/main/media/Screenshot_7.png)

**Users**

-   Iniciar sesión de usuario

    ![](https://raw.githubusercontent.com/david99cartagena/api-rest-laravel-8-jwt/refs/heads/main/media/Screenshot_8.png)

    ![](https://raw.githubusercontent.com/david99cartagena/api-rest-laravel-8-jwt/refs/heads/main/media/Screenshot_9.png)

-   🔴Iniciar sesión de usuario - Credenciales Invalidas

    ![](https://raw.githubusercontent.com/david99cartagena/api-rest-laravel-8-jwt/refs/heads/main/media/Screenshot_10.png)

    ![](https://raw.githubusercontent.com/david99cartagena/api-rest-laravel-8-jwt/refs/heads/main/media/Screenshot_11.png)

-   Obtener usuario autenticado

    ![](https://raw.githubusercontent.com/david99cartagena/api-rest-laravel-8-jwt/refs/heads/main/media/Screenshot_12.png)

    ![](https://raw.githubusercontent.com/david99cartagena/api-rest-laravel-8-jwt/refs/heads/main/media/Screenshot_13.png)

-   Actualizar un usuario

    ![](https://raw.githubusercontent.com/david99cartagena/api-rest-laravel-8-jwt/refs/heads/main/media/Screenshot_14.png)

    ![](https://raw.githubusercontent.com/david99cartagena/api-rest-laravel-8-jwt/refs/heads/main/media/Screenshot_15.png)
