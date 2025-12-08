# FertiGyn – Sistema web de gestión de expedientes clínicos

Aplicación web desarrollada en Laravel para la gestión de pacientes, consultas
y archivos clínicos del consultorio de ginecología.

## Tecnologías

- Laravel 10 / PHP 8.2
- MySQL
- Bootstrap 5
- reCAPTCHA v2/v3 (Google)
- Autenticación con roles: ADMIN, ENFERMERIA

## Instalación local

```bash
git clone https://github.com/AleJasso/fertigyn
cd fertigyn

cp .env.example .env
composer install
npm install
php artisan key:generate

# configurar credenciales de DB en .env
php artisan migrate --seed
npm run dev
php artisan serve
