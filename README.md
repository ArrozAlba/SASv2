SASv2
Implementación del S.A.S. bajo el Framework KumbiaPHP utilizando como Backend Postgresql

Incorporado el KumbiaPHP Backend
Proyecto base con la parte administrativa lista para usar en apps con kumbiaphp Beta2

Usuario: admin , clave: 123

Descripción

EL backend es el módulo de administración y configuración de una aplicación, a traves de el podremos gestionar las diferentes partes de la misma, desde el control de los usuarios de sistemas y sus privilegios, hasta llevar auditorias sobre las acciones que realizan los mismos dentro de la aplicación.

Gestión de Usuarios

Permite la creación edición y eliminación de usuarios de la aplicación.

Los Usuarios tienen perfiles asociados, con ello se puede controlar que puede hacer cada usuario dependiendo de los perfiles que posea.

Gestión de Roles (Perfiles)

Permite la creación edición y eliminación de Roles de la aplicación.

Los roles son un identificador de que tipo de papel juega un usuario dentro de la aplicacion.

Ejemplo: usuarios visitantes, moderadores, administradores, etc.

Gestión de Recursos

Los recursos son cada uno de los módulos ( páginas ) que tiene la aplicación.

Cada recurso está identificado por una url.

Ejemplos de recursos Validos:

admin/usuarios/crear especificamos el modulo controlador y acción.
articulos/crear controlador y acción.
inicio/* controlador y todas las acciones del mismo.
modulo/controlador/* Modulo controlador y todas las acciones del mismo.
modulo// Modulo todos los controladores y acciones del mismo.
Gestión de Privilegios ( Permisos de roles a recursos )

Permite establecer a que recursos tiene acceso cada rol en la aplicacion.

Gestión de Menus

Permite la creación edición y eliminación de Menus de la aplicación.

Cada menu está asociado a un recurso, esto con el fin de poder tener menus inteligentes que solo carguen los items a los que un rol tenga acceso.

Ademas los items pueden tener items padres asociados para crear menus hijos.
