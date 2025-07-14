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
curl -X POST http://localhost:8000/api/patients/register \
  -H "Accept: application/json" \
  -F "name=Luciano Monteiro" \
  -F "email=luciano@ligthit.com" \ 
  -F "phone=123456789" \
  -F "photo=@/ruta/a/una/imagen.jpg"
```
Esto debería:

- Validar los datos

- Guardar el paciente en la base de datos

- Enviar un correo de confirmación (capturado por MailHog)

## Un extra!

La aplicación cuenta con un endpoint para poder ver un json con los datos de los pacientes registrados, pueden acceder al mismo con el siguiente comando.
```bash
curl http://localhost:8000/api/patients
```

---

## Escalabilidad: Envío de SMS

Esta aplicación fue diseñada teniendo en cuenta la futura integración de notificaciones por SMS, por ejemplo, para confirmar el registro del paciente o enviar recordatorios.

### Estructura preparada
El sistema ya utiliza colas (queue) para correos, por lo que integrar el envío de SMS asíncrono será directo y no bloqueará el flujo principal.

Se recomienda centralizar el envío de notificaciones (email y SMS) en una clase NotificationService o similar.

### Pasos para escalar a SMS

1. Crear una notificación genérica
  - Usar Laravel Notifications 
  ```bash 
    php artisan make:notification PatientRegistered 
  ```
  - Implementar canales personalizados para mayor control.

2. Instalar Twilio
  ```bash
    composer requiere twilio/sdk
  ```

5. Usar colas para SMS

Asegurar que los mensajes se encolen igual que los mails:
```php
dispatch(new SendPatientSmsJob($patient));
```

### Aclaración
Ya se incluyó el campo numero, para asi no perder información de los pacientes que se hayan inscripto antes de la implementación del envio de SMS.
