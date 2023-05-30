# Módulo Reservaciones

> **Sistema PortalDEB** <br/> Este desarrollo forma parte del sistema PortalDEB de la Dirección General de Ecosistemas y Biodiversidad del Ministerio del Medio Ambiente y Recursos Naturales.

![Alt text](/src/assets/vista-lugares.png "Vista lugares")

## Tecnologias utilizadas

El proyecto cuenta con un desarrollo de backend y frontend. La lógica principal de este proyecto consiste en un backend que proporciona servicios para ser consumidos por el frontend.

#### **Fronted**

Para la parte de desarrollo se optó por utilizar el framework react para el diseño de las vistas haciendo uso de una variante de Javascript llamada Typescript que es muy recomendada para proyectos grandes que utilizan javascript. Y para los estilos css se utilizó la biblioteca de estilos TailwindCSS.<br>
![React](https://img.shields.io/badge/react-%2320232a.svg?style=for-the-badge&logo=react&logoColor=%2361DAFB) ![TailwindCSS](https://img.shields.io/badge/tailwindcss-%2338B2AC.svg?style=for-the-badge&logo=tailwind-css&logoColor=white) ![TypeScript](https://img.shields.io/badge/typescript-%23007ACC.svg?style=for-the-badge&logo=typescript&logoColor=white)<br>

> Los archivos de producción finales son los de siempre (html, css y javascript), esto se consigue compilando la version fronted de desarrollo, esto para tener un paquete liviano y optimizado para ser consumido por el cliente.<br>
> ![HTML5](https://img.shields.io/badge/html5-%23E34F26.svg?style=for-the-badge&logo=html5&logoColor=white) ![CSS3](https://img.shields.io/badge/css3-%231572B6.svg?style=for-the-badge&logo=css3&logoColor=white) ![JavaScript](https://img.shields.io/badge/javascript-%23323330.svg?style=for-the-badge&logo=javascript&logoColor=%23F7DF1E)<br>

#### Backend

Se utilizó php sin frameworks para las conexiones a la base de datos en MySQL, validaciones y la programación de los servicios que consume el cliente.<br>
![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white) ![MySQL](https://img.shields.io/badge/mysql-%2300f.svg?style=for-the-badge&logo=mysql&logoColor=white) ![Apache](https://img.shields.io/badge/apache-%23D42029.svg?style=for-the-badge&logo=apache&logoColor=white)<br>

## Estructura del proyecto

El proyecto es un mono repositorio, este módulo se recomienda añadirse en la carpeta raíz del proyecto del sistema.

```
portaldev
├── ...
└── reservaciones           # carpeta que contiene este módulo
    ├── api                 # servicios para terceros
    ├── app                 # archivos fuentes del módulo
    ├── src                 # codigos de base de datos y proyecto del fronted del modulo
    ├── lugares-dev.php     # vista de lugares en modo desarrollo
    ├── lugares.php
    ├── servicios-dev.php   # vista de lugares en modo desarrollo
    ├── servicios.php
    └── README.md

```

### Carpeta api
