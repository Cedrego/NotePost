# NotePost

NotePost es una aplicación web para la gestión de notas, posts y recordatorios, con funcionalidades sociales como amigos, likes y personalización de usuario. El proyecto está dividido en un backend PHP y un frontend Angular.

## Estructura del Proyecto

- **backend/**: Lógica de negocio y persistencia en PHP.
  - `notepost_schema.sql`: Script para crear la base de datos.
  - `dominio/`: Clases de dominio (Usuario, Post, Like, Tag, etc.).
  - `implementacion/`: Endpoints y scripts PHP para operaciones (crear usuario, crear post, dar like/dislike, gestionar amigos, recordatorios, etc.).
  - `persistencia/`: Conexión a la base de datos y DAOs.
- **Frontend/**: Aplicación Angular.
  - `src/app/`: Componentes, servicios y modelos.
  - `public/`: Recursos públicos.
  - `assets/`: Imágenes y otros recursos.
  - Configuración: `angular.json`, `package.json`, etc.

## Requisitos

- **Backend**: PHP 7+, MySQL/MariaDB
- **Frontend**: Node.js 16+, Angular CLI

## Instalación y Ejecución

### Backend

1. Crea la base de datos ejecutando el script:

   ```sh
   mysql -u usuario -p < backend/notepost_schema.sql
   ```

2. Configura la conexión en `backend/persistencia/conexion.php`.

3. Inicia un servidor local (por ejemplo, XAMPP, WAMP o PHP integrado):

   ```sh
   php -S localhost:8000 -t backend
   ```

### Frontend

1. Instala dependencias:

   ```sh
   cd Frontend
   npm install
   ```

2. Ejecuta la aplicación Angular:

   ```sh
   ng serve
   ```

3. Accede a `http://localhost:4200` en tu navegador.

## Uso

- Regístrate, inicia sesión y crea posts o recordatorios.
- Personaliza tu perfil con avatares y fondos.
- Interactúa con otros usuarios: envía solicitudes de amistad, da likes/dislikes, filtra posts por tags y consulta rankings.

## Créditos

Desarrollado por el equipo NotePost.

---

Si tienes problemas, revisa la configuración de la base de datos y los puertos del servidor. Para más detalles, consulta los archivos `README.md` en cada carpeta.
