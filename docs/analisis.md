# Análisis del Sistema E-Commerce

Este documento detalla el análisis del sistema para la plataforma de comercio electrónico, incluyendo los actores, requisitos funcionales y no funcionales, así como el alcance y los supuestos del proyecto.

---

## 👥 3.1 Actores del Sistema

| Actor | Descripción |
| :--- | :--- |
| **Cliente** | Usuario registrado que navega el catálogo, realiza compras, gestiona sus direcciones, guarda productos en favoritos y deja reseñas. |
| **Administrador** | Usuario con privilegios elevados encargado de gestionar el catálogo (productos y categorías) y administrar el flujo de estados de los pedidos. |
| **Sistema** | El propio motor de la aplicación que realiza cálculos automáticos y aplica reglas de negocio. |

---

## ⚙️ 3.2 Requisitos Funcionales (RF)

| ID | Requisito | Actor |
| :---: | :--- | :--- |
| **RF01** | El sistema debe permitir a un visitante registrarse como cliente ingresando nombre, email y contraseña. | Cliente |
| **RF02** | El sistema debe permitir a los usuarios (clientes y administradores) iniciar y cerrar sesión. | Cliente / Admin |
| **RF03** | El sistema debe calcular automáticamente el total de un pedido en base a sus ítems. | Sistema |
| **RF04** | El sistema debe permitir al cliente agregar, visualizar y quitar productos de un carrito de compras temporal. | Cliente |
| **RF05** | El sistema debe permitir al cliente seleccionar una dirección de envío previamente registrada o ingresar una nueva al momento de confirmar la compra. | Cliente |
| **RF06** | Al finalizar el checkout, el sistema debe registrar el pedido y asignarle por defecto el estado inicial de "pendiente". | Sistema |
| **RF07** | El sistema debe permitir al administrador crear, leer, actualizar y eliminar (CRUD) productos del catálogo. | Administrador |
| **RF08** | El sistema debe permitir al administrador crear, leer, actualizar y eliminar (CRUD) categorías. | Administrador |
| **RF09** | El sistema debe permitir al cliente dejar una reseña (calificación y comentario) únicamente en productos que haya comprado y cuyo pedido esté en estado "entregado". | Cliente |
| **RF10** | El sistema debe permitir al administrador modificar el estado de un pedido siguiendo el flujo estricto: pendiente, pagado, enviado, entregado o cancelado. | Administrador |
| **RF11** | El sistema debe permitir al cliente agregar y quitar productos de su lista de deseos (wishlist). | Cliente |

---

## 🛡️ 3.3 Requisitos No Funcionales (RNF)

| ID | Requisito | Categoría |
| :---: | :--- | :---: |
| **RNF01** | La interfaz de usuario debe ser responsive, garantizando su correcta visualización y usabilidad en dispositivos móviles y de escritorio. | Usabilidad |
| **RNF02** | El sistema debe proteger las rutas de administración utilizando un middleware que valide el rol del usuario autenticado. | Seguridad |
| **RNF03** | El sistema debe validar todos los datos de entrada en el backend mediante Form Requests, mostrando mensajes de error claros en las vistas. | Robustez |
| **RNF04** | Las contraseñas de los usuarios deben almacenarse obligatoriamente de forma encriptada mediante algoritmos de hashing provistos por el framework. | Seguridad |
| **RNF05** | Los endpoints de la API REST deben responder exclusivamente en formato JSON y utilizar códigos de estado HTTP estándar (200, 201, 400, 404, etc.). | Interoperabilidad |

---

## 🎯 3.5 Alcance y Supuestos

### 🚫 Fuera de Alcance
> [!WARNING]
> **No se integrará una pasarela de pagos real** (MercadoPago, Stripe, etc.). Todo el flujo de pago será simulado y gestionado de forma manual por el administrador mediante los cambios de estado.

> [!WARNING]
> **No se implementará envío de correos electrónicos reales**. Las notificaciones (si las hubiere) se registrarán en los logs del sistema (`MAIL_MAILER=log`).

### 📌 Supuestos
* **Verificación de correo:** Se asume que los clientes registrados no requieren un proceso de verificación de correo electrónico para comenzar a operar en la plataforma.
* **Categorías y stock:** Un producto puede pertenecer a múltiples categorías simultáneamente, pero no tendrá gestión de stock o inventario dinámico para simplificar el modelo.