DROP TABLE IF EXISTS 
invoices_empresas, 
invoices_empresascargos,
invoices_empresasclientes,
invoices_empresasclientescontactos,
invoices_empresasclientescontactostipos,
invoices_empresasclientesmedidas,
invoices_empresasempleados,
invoices_empresasimpuestos,
invoices_empresaslocalidades,
invoices_estados,
invoices_facturasdetalles,
invoices_facturasencabezados,
invoices_facturaspagos,
invoices_impuestosunidades,
invoices_inventarios,
invoices_paises,
invoices_paisesdepartamentos,
invoices_paisesdepartamentosciudades,
invoices_personas,
invoices_productos,
invoices_productoscomponentes,
invoices_productoscomponentesvalores,
invoices_productosimpuestos,
invoices_productosmedidas,
invoices_productosreferencias,
invoices_productosreferenciasdetalles,
invoices_vistas_campos,
invoices_vistas_campos_master,
invoices_vistas_links,
invoices_vistas_menu,
invoices_vistas_operadores,
invoices_vistas_tablas,
invoices_vistas_tablas_master;

DROP VIEW IF EXISTS  `invoices_campos_select`;
DROP VIEW IF EXISTS  `invoices_campos_select_master`;
DROP VIEW IF EXISTS  `invoices_campos_totales`;
DROP VIEW IF EXISTS  `invoices_campos_validos`;
DROP VIEW IF EXISTS  `invoices_empresascargosjf`;
DROP VIEW IF EXISTS  `invoices_menu_original`;

DROP PROCEDURE IF EXISTS `crear_campos`;
DROP PROCEDURE IF EXISTS `sp_getmenu`;




















