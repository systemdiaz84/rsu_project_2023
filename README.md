# RSU Arborización USAT

# Instalación
1. Instala las dependencias de Composer:

```bash
composer install
```

2. Instala las dependencias de NPM:

```bash
npm install
```

3. Copia el archivo de configuración de Laravel (añadir key google maps y la configuración de la base de datos en el archivo):

```bash
cp .env.example .env
```

4. Genera la clave de la aplicación:

```bash
php artisan key:generate
```

5. Ejecutar las migraciones:

```bash
php artisan migrate
```

6. Ejecutar la creación de permisos:

```bash
php artisan permission:create-permission-routes
```

7. Ejecutar los seeders (antes se debe crear el usuario admin):

```bash
php artisan db:seed --class=AddDepartamentSeeder
php artisan db:seed --class=AddProvincesSeeder
php artisan db:seed --class=AddDistrictsSeeder
php artisan db:seed --class=AddFamiliesSeeder
php artisan db:seed --class=AddSpeciesSeeder
php artisan db:seed --class=CreateAdminUserSeeder
```