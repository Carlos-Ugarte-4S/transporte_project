<p align="center">
  <h1 align="center">Sistema de Información para la Gestión del Transporte Público en Camiri</h1>
  <p align="center">
    <strong>Proyecto Académico - INF342 (Sistemas de Información I)</strong><br>
    Universidad Autónoma "Gabriel René Moreno" — Facultad Integral del Chaco
  </p>
</p>

---

## 📌 Descripción del Proyecto

Este proyecto consiste en el desarrollo de un sistema de información web centralizado diseñado para la **Facultad Integral del Chaco (UAGRM - FICH)** en Camiri, Santa Cruz, Bolivia. 

El objetivo principal es automatizar y optimizar la gestión operativa del transporte público urbano de la ciudad de Camiri, integrando procesos fragmentados como el registro de personal (choferes y propietarios), parque automotor, líneas, rutas, paradas intermedias, turnos y asignaciones operativas diarias.

---

## 👥 Equipo de Trabajo

- **Autor:**
  - Carlos Daniel Ugarte
---

## 🚀 Módulos del Sistema

El sistema está estructurado bajo la metodología **PUDS (Proceso Unificado de Desarrollo de Software)** y contempla los siguientes módulos funcionales:

| Módulo | Descripción / Alcance |
| :--- | :--- |
| **Seguridad y Usuarios** | Control de acceso basado en roles (Administrador, Operadores, Consultas). |
| **Personas** | Registro unificado de perfiles (Propietarios, Choferes, Administradores). |
| **Gestión de Flota** | Hoja de vida de vehículos, tipos de transporte, capacidad y asignación de líneas. |
| **Infraestructura Vial** | Definición de Sindicatos, Líneas, Rutas completas y Paradas con geolocalización. |
| **Planificación Operativa** | Control de Horarios, Turnos y Asignación Diaria (*Chofer + Vehículo + Línea + Horario*). |

---

## 🛠️ Tecnologías Utilizadas

- **Framework Backend:** [Laravel](https://laravel.com/) (PHP)
- **Frontend:** Blade Templates / Tailwind CSS / Bootstrap
- **Base de Datos:** PostgreSQL / MySQL (Modelo Relacional Centralizado)
- **Metodología y Modelado:** PUDS & UML (Lenguaje Unificado de Modelado)

---

## 💻 Instalación y Configuración Local

Sigue estos pasos para clonar e ejecutar el proyecto localmente:

1. **Clonar el repositorio:**
   ```bash
   git clone [https://github.com/tu-usuario/nombre-del-repositorio.git](https://github.com/tu-usuario/nombre-del-repositorio.git)
   cd nombre-del-repositorio
