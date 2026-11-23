# Saldo · Finanzas personales

Aplicación en español construida con Laravel 12, Blade, Bootstrap 5, CSS y JavaScript. Usa MySQL o MariaDB y mantiene los datos separados por cuenta y moneda.

## Abrir en Laragon

Requiere PHP 8.2 o posterior con `pdo_mysql` y Composer.

1. Inicia Apache y MySQL desde Laragon.
2. Abre la terminal de Laragon en `C:\laragon\www\Finanzas`. Si abres una copia nueva, ejecuta `composer install`, copia `.env.example` a `.env` y ejecuta `php artisan key:generate`.
3. Revisa los valores `DB_*` de `.env`. La configuración inicial usa `127.0.0.1:3306`, la cuenta local `root` y la base `finanzas_personales`.
4. Ejecuta `php artisan migrate`. Si Laravel pregunta si puede crear la base, confirma. Si tu cuenta de MySQL carece de permiso de creación, crea primero la base desde HeidiSQL o el gestor de Laragon.
5. Abre [http://localhost/Finanzas/public/](http://localhost/Finanzas/public/) y crea una cuenta desde la pantalla de acceso.

Con `php artisan serve`, abre [http://127.0.0.1:8000/](http://127.0.0.1:8000/). Si usas un dominio de Laragon, configura su raíz en `C:/laragon/www/Finanzas/public` y ajusta `APP_URL` al dominio. Los recursos frontend se sirven localmente.

## Funcionamiento

- **Movimientos.** Registra, edita y elimina ingresos y gastos. El historial admite filtros de tipo, categoría y fechas, búsqueda por texto y paginación.
- **Presupuestos.** Define un límite por categoría, moneda y mes. El consumo se recalcula con los gastos del periodo. Se advierte desde el 80% y se indica el exceso cuando se rebasa el límite.
- **Metas.** Define un monto y una fecha límite. Cada abono actualiza el capital acumulado de la meta. Los abonos son asignaciones de ahorro y el saldo de ingresos y gastos conserva su valor.
- **Reportes.** Consulta los totales, el ahorro neto, el flujo acumulado diario y el gasto por categoría del mes. El ahorro neto es ingresos menos gastos.
- **Cuenta.** Actualiza tus datos o contraseña, cambia la moneda activa, consulta inicios de sesión recientes o elimina tu cuenta. MXN y USD conservan historiales independientes.

## Arquitectura y datos

Los modelos Eloquent están en `app/Models`, los controladores en `app/Http/Controllers`, las reglas de validación en `app/Http/Requests` y las vistas Blade en `resources/views`. El cálculo de reportes vive en `App\Services\FinancialSummary`. Las consultas parten de la cuenta autenticada y una política de Laravel controla el acceso directo a cada registro.

Las migraciones crean usuarios, registros de acceso, categorías, movimientos, presupuestos, metas y abonos. El tipo de un movimiento proviene de su categoría. Los importes se guardan en centavos enteros. La base aplica llaves foráneas, índices de consulta y una restricción de unicidad para cada presupuesto por usuario, moneda, categoría y mes. Las fechas y horas se muestran en la zona `America/Mexico_City`.
# Finanzas
# Finanzas
