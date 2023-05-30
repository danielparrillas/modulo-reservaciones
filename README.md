# Módulo Reservaciones

> **Sistema PortalDEB** <br/> Este desarrollo forma parte del sistema PortalDEB de la Dirección General de Ecosistemas y Biodiversidad del Ministerio del Medio Ambiente y Recursos Naturales.

![Alt text](/src/assets/vista-lugares.png "Vista lugares")

## Tecnologias utilizadas

El proyecto cuenta con un desarrollo de backend y frontend. La lógica principal de este proyecto consiste en un backend que proporciona servicios para ser consumidos por el frontend.

#### **Fronted**

Para la parte de desarrollo se optó por utilizar el framework react para el diseño de las vistas haciendo uso de una variante de Javascript llamada Typescript que es muy recomendada para proyectos grandes que utilizan javascript. Y para los estilos css se utilizó la biblioteca de estilos TailwindCSS.<br>

> ![React](https://img.shields.io/badge/react-%2320232a.svg?style=for-the-badge&logo=react&logoColor=%2361DAFB) ![TailwindCSS](https://img.shields.io/badge/tailwindcss-%2338B2AC.svg?style=for-the-badge&logo=tailwind-css&logoColor=white) ![TypeScript](https://img.shields.io/badge/typescript-%23007ACC.svg?style=for-the-badge&logo=typescript&logoColor=white)<br>

Los archivos de producción finales son los de siempre (html, css y javascript), esto se consigue compilando la version fronted de desarrollo, esto para tener un paquete liviano y optimizado para ser consumido por el cliente.<br>

> ![HTML5](https://img.shields.io/badge/html5-%23E34F26.svg?style=for-the-badge&logo=html5&logoColor=white) ![CSS3](https://img.shields.io/badge/css3-%231572B6.svg?style=for-the-badge&logo=css3&logoColor=white) ![JavaScript](https://img.shields.io/badge/javascript-%23323330.svg?style=for-the-badge&logo=javascript&logoColor=%23F7DF1E)<br>

#### Backend

Se utilizó php sin frameworks para las conexiones a la base de datos en MySQL, validaciones y la programación de los servicios que consume el cliente.<br>

> ![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white) ![MySQL](https://img.shields.io/badge/mysql-%2300f.svg?style=for-the-badge&logo=mysql&logoColor=white) ![Apache](https://img.shields.io/badge/apache-%23D42029.svg?style=for-the-badge&logo=apache&logoColor=white)<br>

## Estructura del proyecto

> El proyecto es un mono repositorio, este módulo se recomienda añadirse en la carpeta raíz del proyecto del sistema.

```
portaldev
├── ...
└── reservaciones           # carpeta que contiene este módulo
    ├── api                 # servicios para terceros
    ├── app                 # archivos fuentes del módulo
    ├── src                 # codigos extra
    ├── lugares-dev.php     # vista de lugares en modo desarrollo
    ├── lugares.php         # vista de lugares
    ├── servicios-dev.php   # vista de lugares en modo desarrollo
    ├── servicios.php       # vista de servicios
    └── README.mdd

```

## Comentarios

> Se recomientda dar un vistazo a estos puntos para facilitar la comprension de la estructura de este proyecto.

- Puedes encontrar un archivo `README.md` en cada nivel de carpeta que documenta y explica específica y detalladamente cada sección.
- Este módulo se trata como monorepositorio ya que cuenta con un proyecto con React.js o Typescript. En el `README.md` se encuentra como se integra a PHP tanton en modo desarrollo y producción.

```
portaldev
├── ...
└── reservaciones
    ├── ...
    └── src                   # codigos extra
        ├── ...
        └── view-with-react   # carpeta que contiene un proyecto en React

```

> Entender este proyecto como un generador de vistas (html, css, js)

- Otra carpeta muy importante es la que contiene el código de la base de datos. Esta contiene no solo el código SQL de lo que se ha agregado y cambiado a la base de datos con la que ya cuenta el sistema PortalDEB, si no que también incluye el archivo `README.md` que contiene un paso a paso para instalar los nuevos cambios.

```
portaldev
├── ...
└── reservaciones
    ├── ...
    └── src                   # codigos extra
        ├── ...
        └── database          # carpeta de los codigos sql

```
