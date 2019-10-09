/*1.- Crear las siguientes tablas en la base UTN.
Provedores (Numero(PK) entero Not Null, Nombre cadena (30), Domicilio cadena (50),
Localidad cadena (80))
Productos (pNumero (PK) entero Not Null, pNombre cadena (30), Precio flotante, Tamaño
cadena (20))
Envios (Numero(PK) entero Not Null, pNumero(PK) entero Not Null, Cantidad entero Not
Null)*/

/*2.- Ingresar los siguientes datos en cada tabla.
Utilizar el administrador de bases de datos de MySql
Proveedores
Numero Nombre Domicilio Localidad
100 Perez Perón 876 Quilmes
101 Gimenez Mitre 750 Avellaneda
102 Aguirre Boedo 634 Bernal*/
INSERT INTO `proveedores`
VALUES 	(100, "Perez", "Perón 876", "Quilmes"), 
		(101, "Gimenez", "Mitre 750", "Avellaneda"), 
		(102, "Aguirre", "Boedo 634", "Bernal")

/*Productos
pNumero pNombre Precio Tamaño
1 Caramelos 1,5 Chico
2 Cigarrillos 45,89 Mediano
3 Gaseosa 15,80 Grande*/
INSERT INTO `productos`
VALUES 	(1, "Caramelos", 1, 5, "Chico"), 
		(2, "Cigarrillos", 45, 89, "Mediano"), 
        (3, "Gaseosa", 15, 80, "Grande")

/*Envios
Numero pNumero Cantidad
100 1 500
100 2 1500
100 3 100
101 2 55
101 3 225
102 1 600
102 3 300*/
INSERT INTO `envios`
VALUES 	(100, 1, 500), 
		(100, 2, 1500), 
        (100, 3, 100), 
        (101, 2, 55), 
        (101, 3, 225), 
        (102, 1, 600), 
        (102, 3, 300)

/*3.- Realizar las siguientes consultas.
1. Obtener los detalles completos de todos los productos, ordenados alfabéticamente.*/
SELECT * 
FROM `productos` 
ORDER BY pNombre ASC

/*2. Obtener los detalles completos de todos los proveedores de ‘Quilmes’.*/
SELECT * 
FROM `proveedores` 
WHERE localidad = "quilmes"

/*3. Obtener todos los envíos en los cuales la cantidad este entre 200 y 300 inclusive.*/
SELECT *
FROM `envios` 
WHERE Cantidad BETWEEN 200 AND 300

/*4. Obtener la cantidad total de todos los productos enviados.*/
SELECT SUM(Cantidad) 
FROM `envios`

/*5. Mostrar los primeros 3 números de productos que se han enviado.*/
SELECT pNumero 
FROM `envios`
LIMIT 3

/*6. Mostrar los nombres de proveedores y los nombres de los productos enviados.*/
SELECT proveedores.Nombre, productos.pNombre 
FROM envios 
INNER JOIN proveedores 
ON envios.Numero = proveedores.Numero 
INNER JOIN productos 
ON envios.pNumero = productos.pNumero

/*7. Indicar el monto (cantidad * precio) de todos los envíos.*/
SELECT ROUND(Cantidad*Precio, 2)
FROM envios
INNER JOIN productos
ON envios.pNumero = productos.pNumero

/*8. Obtener la cantidad total del producto 1 enviado por el proveedor 102.
9. Obtener todos los números de los productos suministrados por algún proveedor de
‘Avellaneda’.
10. Obtener los domicilios y localidades de los proveedores cuyos nombres contengan la
letra ‘I’.
11. Agregar el producto número 4, llamado ‘Chocolate’, de tamaño chico y con un precio
de 25,35.
12. Insertar un nuevo proveedor (únicamente con los campos obligatorios).
13. Insertar un nuevo proveedor (107), donde el nombre y la localidad son ‘Rosales’ y ‘La
Plata’.
14. Cambiar los precios de los productos de tamaño ‘grande’ a 97,50.
15. Cambiar el tamaño de ‘Chico’ a ‘Mediano’ de todos los productos cuyas cantidades
sean mayores a 300 inclusive.
16. Eliminar el producto número 1.
17. Eliminar a todos los proveedores que no han enviado productos.*/