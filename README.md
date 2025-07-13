# Backend Challenge | Patient Registration API

Aplicación Laravel para registrar pacientes, con validación, almacenamiento en PostgreSQL, y notificaciones por email simuladas vía MailHog.

---

## Requisitos

- Docker 
- Docker Compose 
- Make (opcional, pero recomendado para comandos simplificados)

---

## Instalación y ejecución

### 1. Clonar el repositorio

```bash
git clone https://github.com/tuusuario/patient-registration.git
cd patient-registration
```

### 2. Copiar el .env
```bash
cp .env.example .env
```

### 3. Construir y levantar los contenedores
```bash
docker-compose up -d --build
```

Esto levantará los siguientes servicios:

- app (Laravel en PHP-FPM)

- db (PostgreSQL)

- mail (MailHog para capturar emails de prueba)

### 4. Instalar dependencias y preparar Laravel
```bash
docker exec -it backend-challenge_patient-registration-app-1 composer install
docker exec -it backend-challenge_patient-registration-app-1 php artisan key:generate
docker exec -it backend-challenge_patient-registration-app-1 php artisan migrate
```

---

## Pruebas de funcionamiento

### 1. Verificar conexión SMTP y envío de correo
```bash
docker exec -it backend-challenge_patient-registration-app-1 php artisan mail:test-connection
```
Este comando:
- Verifica si Laravel puede conectarse a MailHog (mail:1025)
- Envía un correo de prueba a ```test@lightit.com```

Revisá el correo en: http://localhost:8025

### 2. Registrar un paciente (vía curl)
```bash
curl -X POST http://localhost:9000/api/patients/register \
  -H "Accept: application/json" \
  -F "name=John Doe" \
  -F "email=john@example.com" \
  -F "phone=123456789" \
  -F "photo=@/ruta/a/una/imagen.jpg"
```
Esto debería:

- Validar los datos

- Guardar el paciente en la base de datos

- Enviar un correo de confirmación (capturado por MailHog)
