## Instala dependencias

Se utilizo pnpm, pero puedes utilizar npm o yarn, si elijes uno diferente a pnpm solo sustituyelo en los siguiente comandos
En la consola ejecuta, para instalar todas las dependencias

```
pnpm i
```

## Correr en modo desarrollo

```
pnpm run dev --base reservaciones/views
```

Esto hara que se corra en el puerto 5173 (http://localhost:5173/reservaciones/views)
Pero esto dara un error CORS cuando haga llamadas a la api de php
Por eso deberas ingresar desde (http://localhost/reservaciones/views/lugares)
Esta direccion tiene un html que llama a los scripts expuestos en el puerto 5173,
Con esto queremos lograr correr esta aplicacion react en el mismo servidor de la api
Revisa los archivos en la carpeta views del proyecto en php
Alliencontaras 2 versiones, la desarrollo que llama a los scripts expuestos en (http://localhost:5173/reservaciones/views)
Y la version de produccion que llama a los scripts ya compilados con vite (nuestro proyecto en react)

```mermaid
graph TD;
    A-->B;
    A-->C;
    B-->D;
    C-->D;
```
