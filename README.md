# TicketBuzz Web App

TicketBuzz is a concert ticketing web application with a Lumen REST API, MySQL database, public React frontend, and admin React frontend.

## Architecture

- `backend`: Lumen 10 REST API, JWT auth, MySQL persistence, Google OAuth callback support, Midtrans payment callback support, and Supabase storage integration.
- `public-fe`: customer-facing React + TypeScript + Vite app.
- `admin-fe`: admin dashboard built with Create React App.
- `db`: MySQL 8.0 when running through Docker Compose.

## Prerequisites

For Docker deployment:

- Docker
- Docker Compose

For local development without Docker:

- PHP 8.1 or newer
- Composer
- Node.js 20 or newer
- npm
- MySQL 8.0 or compatible

## Docker Setup

1. Create a local environment file:

   ```bash
   cp .env.docker.example .env
   ```

2. Edit `.env` and replace the placeholder secrets:

   ```env
   DB_PASSWORD=change-me-db-password
   DB_ROOT_PASSWORD=change-me-root-password
   JWT_SECRET=replace-with-a-long-random-secret
   ```

   Also fill these when the related feature is used:

   ```env
   GOOGLE_CLIENT_ID=
   GOOGLE_CLIENT_SECRET=
   MIDTRANS_SERVER_KEY=
   SUPABASE_URL=
   SUPABASE_KEY=
   SUPABASE_BUCKET=
   ```

3. Build and start the stack:

   ```bash
   docker compose up --build
   ```

4. Open the app:

   - Public frontend: `http://localhost:5173`
   - Admin frontend: `http://localhost:3000`
   - REST API: `http://localhost:5000`

The backend waits for MySQL, runs database migrations, and then starts the API server. The frontends wait for the backend healthcheck before starting.

## Docker Commands

```bash
docker compose ps
docker compose logs -f backend
docker compose down
docker compose down -v
```

Use `docker compose down -v` only when you want to remove the MySQL volume and reset local database data.

## Local Development

### Backend

```bash
cd backend
composer install
cp .env.example .env
```

Configure `backend/.env` for your local MySQL database and required service keys:

```env
APP_URL=http://localhost:5000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jkpl
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
JWT_SECRET=replace-with-a-long-random-secret
FRONTEND_URL=http://localhost:5173
```

Run migrations and start the API:

```bash
php artisan migrate
php -S 0.0.0.0:5000 -t public
```

### Public Frontend

```bash
cd public-fe
npm install
```

Create `public-fe/.env.local`:

```env
VITE_API_BASE_URL=http://localhost:5000
```

Start the dev server:

```bash
npm run dev
```

### Admin Frontend

```bash
cd admin-fe
npm install
```

Create `admin-fe/.env.local`:

```env
REACT_APP_API_BASE_URL=http://localhost:5000
```

Start the dev server:

```bash
npm start
```

## Environment Safety

Do not commit real `.env` files. The repository tracks only example environment files:

- `.env.docker.example`
- `backend/.env.example`

The `.gitignore` files exclude local env files, dependency folders, build output, logs, and local editor/tooling files.
