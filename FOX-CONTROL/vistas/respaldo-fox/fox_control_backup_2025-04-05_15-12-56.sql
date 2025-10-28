

CREATE TABLE `anuladas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigoGeneracion` text NOT NULL,
  `fecEmi` date NOT NULL,
  `horEmi` time NOT NULL,
  `facturaRelacionada` text NOT NULL,
  `motivoAnulacion` text NOT NULL,
  `firmaDigital` text NOT NULL,
  `sello` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO anuladas VALUES('35', '35', '82069998-E2D9-95B3-AC48-33B3F0ABB198', '82069998-E2D9-95B3-AC48-33B3F0ABB198', '2025-04-05', '2025-04-05', '15:12:55', '15:12:55', '1', '1', 'No se realizó la venta', 'No se realizó la venta', '', '', '', '');


CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` text NOT NULL,
  `descripcion` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO categorias VALUES('18', '18', 'Vehículo', 'Vehículo', 'Vehículos', 'Vehículos');


CREATE TABLE `clientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` text NOT NULL,
  `NIT` text NOT NULL,
  `DUI` text NOT NULL,
  `NRC` text NOT NULL,
  `direccion` text NOT NULL,
  `departamento` text NOT NULL,
  `municipio` text NOT NULL,
  `correo` text NOT NULL,
  `telefono` text NOT NULL,
  `tipo_cliente` text NOT NULL,
  `codActividad` text NOT NULL,
  `descActividad` text NOT NULL,
  `codPais` text NOT NULL,
  `nombrePais` text NOT NULL,
  `tipoPersona` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO clientes VALUES('26', '26', 'Cliente genérico', 'Cliente genérico', '00000000000000', '00000000000000', '000000000', '000000000', '', '', 'Por ahi', 'Por ahi', '06', '06', '23', '23', 'cliente@gmail.com', 'cliente@gmail.com', '00000000', '00000000', '00', '00', '00000', '00000', '00000', '00000', 'SV', 'SV', 'El Salvador', 'El Salvador', '1', '1');
INSERT INTO clientes VALUES('27', '27', 'JC SEWING SUPPLY, S.A. DE C.V.', 'JC SEWING SUPPLY, S.A. DE C.V.', '06142111021059', '06142111021059', '', '', '1465156', '1465156', 'Zona franca, local 19', 'Zona franca, local 19', '06', '06', '22', '22', 'hernandez.albertds@gmail.com', 'hernandez.albertds@gmail.com', '00000000', '00000000', '01', '01', '46597', '46597', 'Venta al por mayor de maquinaria, equipo. accesorios y partes para la industria textil. confecciones y cuero', 'Venta al por mayor de maquinaria, equipo. accesorios y partes para la industria textil. confecciones y cuero', 'SV', 'SV', 'El Salvador', 'El Salvador', '2', '2');


CREATE TABLE `compras` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `clase_documento` text NOT NULL,
  `tipo_documento` text NOT NULL,
  `numero_resolucion` text NOT NULL,
  `numero_documento` text NOT NULL,
  `nit_nrc` text NOT NULL,
  `nombre_proveedor` text NOT NULL,
  `compras_internas_exentas` double NOT NULL,
  `internaciones_exentas_y_no_sujetas` double NOT NULL,
  `importaciones_exentas_y_no_sujetas` double NOT NULL,
  `compras_internas_gravadas` double NOT NULL,
  `internaciones_gravadas_de_bienes` double NOT NULL,
  `importaciones_gravadas_de_bienes` double NOT NULL,
  `importaciones_gravadas_de_servicios` double NOT NULL,
  `credito_fiscal` double NOT NULL,
  `total_de_compras` double NOT NULL,
  `dui_del_proveedor` text NOT NULL,
  `tipo_de_operacion` text NOT NULL,
  `clasificacion` text NOT NULL,
  `sector` text NOT NULL,
  `tipo` text NOT NULL,
  `anexo` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `contingencias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `tipo_contingencia` int(11) NOT NULL,
  `motivo_contingencia` text NOT NULL,
  `ids_facturas` text NOT NULL,
  `codigoGeneracion` text NOT NULL,
  `firmaDigital` text NOT NULL,
  `sello` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `cortes_caja` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids_facturas` text NOT NULL,
  `cuadrada` text NOT NULL,
  `autorizacion` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `comentarios` text NOT NULL,
  `total` double NOT NULL,
  `id_facturador` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `eliminadas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero_control` text NOT NULL,
  `codigo_generacion` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `emisor` (
  `nit` text NOT NULL,
  `nrc` text NOT NULL,
  `passwordPri` text NOT NULL,
  `nombre` text NOT NULL,
  `codActividad` text NOT NULL,
  `desActividad` text NOT NULL,
  `tipoEstablecimiento` text NOT NULL,
  `departamento` text NOT NULL,
  `municipio` text NOT NULL,
  `direccion` text NOT NULL,
  `telefono` text NOT NULL,
  `correo` text NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numeroControlGeneral` text NOT NULL,
  `ancho` int(11) NOT NULL,
  `contra_descuentos` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO emisor VALUES('06142903851130', '06142903851130', '2006635', '2006635', 'DISTRIBUIDORAILOPANGO25', 'DISTRIBUIDORAILOPANGO25', 'RUBEN ABINOAM RIVAS AGUILAR', 'RUBEN ABINOAM RIVAS AGUILAR', '45100', '45100', 'Venta de vehículos automotores', 'Venta de vehículos automotores', '01', '01', '06', '06', '22', '22', 'PJS N, COL. LAS CAÑAS, # 286', 'PJS N, COL. LAS CAÑAS, # 286', '00000000', '00000000', 'rrivas2@hotmail.com', 'rrivas2@hotmail.com', '1', '1', 'DTE-01-S001P001-000000000000002', 'DTE-01-S001P001-000000000000002', '45', '45', 'descuentos', 'descuentos');


CREATE TABLE `facturas_locales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cliente` text NOT NULL,
  `productos` text NOT NULL,
  `firmaDigital` text NOT NULL,
  `total` double NOT NULL,
  `totalSinIva` double NOT NULL,
  `abonado` double NOT NULL,
  `tipoDte` text NOT NULL,
  `horEmi` time NOT NULL,
  `fecEmi` date NOT NULL,
  `condicionOperacion` int(11) NOT NULL,
  `recintoFiscal` text NOT NULL,
  `regimen` text NOT NULL,
  `modoTransporte` int(11) NOT NULL,
  `seguro` double NOT NULL,
  `flete` double NOT NULL,
  `idMotorista` int(11) NOT NULL,
  `idFacturaRelacionada` text NOT NULL,
  `numeroControl` text NOT NULL,
  `codigoGeneracion` text NOT NULL,
  `sello` text NOT NULL,
  `notaRemision` text NOT NULL,
  `estado` text NOT NULL,
  `modo` text NOT NULL,
  `tipo_contingencia` int(11) NOT NULL,
  `motivo_contingencia` text NOT NULL,
  `evento_contingencia` text NOT NULL,
  `id_vendedor` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `json_guardado` text NOT NULL,
  `orden_compra` text NOT NULL,
  `incoterm` text NOT NULL,
  `origen` text NOT NULL,
  `gran_contribuyente` text NOT NULL,
  `venta_cif` text NOT NULL,
  `venta_fob` text NOT NULL,
  `arancel` text NOT NULL,
  `periodo` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO facturas_locales VALUES('1', '1', '26', '26', '[{\"idProducto\":34,\"codigo\":1111,\"cantidad\":1,\"precioSinImpuestos\":7,\"precioConIva\":7.91,\"totalProducto\":7.91,\"descuento\":0,\"descuentoConIva\":\"0.00\"}]', '[{\"idProducto\":34,\"codigo\":1111,\"cantidad\":1,\"precioSinImpuestos\":7,\"precioConIva\":7.91,\"totalProducto\":7.91,\"descuento\":0,\"descuentoConIva\":\"0.00\"}]', 'eyJhbGciOiJSUzUxMiJ9.ew0KICAiaWRlbnRpZmljYWNpb24iIDogew0KICAgICJ2ZXJzaW9uIiA6IDEsDQogICAgImFtYmllbnRlIiA6ICIwMSIsDQogICAgInRpcG9EdGUiIDogIjAxIiwNCiAgICAibnVtZXJvQ29udHJvbCIgOiAiRFRFLTAxLVMwMDFQMDAxLTAwMDAwMDAwMDAwMDAwMSIsDQogICAgImNvZGlnb0dlbmVyYWNpb24iIDogIjM4QUFGMDU2LUQxNTktRDRFMC1BRTNBLUM3ODk3NTY3NzcyMCIsDQogICAgInRpcG9Nb2RlbG8iIDogMSwNCiAgICAidGlwb09wZXJhY2lvbiIgOiAxLA0KICAgICJ0aXBvQ29udGluZ2VuY2lhIiA6IG51bGwsDQogICAgIm1vdGl2b0NvbnRpbiIgOiBudWxsLA0KICAgICJmZWNFbWkiIDogIjIwMjUtMDQtMDUiLA0KICAgICJob3JFbWkiIDogIjE1OjEyOjIxIiwNCiAgICAidGlwb01vbmVkYSIgOiAiVVNEIg0KICB9LA0KICAiZW1pc29yIiA6IHsNCiAgICAibml0IiA6ICIwNjE0MjkwMzg1MTEzMCIsDQogICAgIm5yYyIgOiAiMjAwNjYzNSIsDQogICAgIm5vbWJyZSIgOiAiUlVCRU4gQUJJTk9BTSBSSVZBUyBBR1VJTEFSIiwNCiAgICAiY29kQWN0aXZpZGFkIiA6ICI0NTEwMCIsDQogICAgImRlc2NBY3RpdmlkYWQiIDogIlZlbnRhIGRlIHZlaMOtY3Vsb3MgYXV0b21vdG9yZXMiLA0KICAgICJub21icmVDb21lcmNpYWwiIDogbnVsbCwNCiAgICAidGlwb0VzdGFibGVjaW1pZW50byIgOiAiMDEiLA0KICAgICJkaXJlY2Npb24iIDogew0KICAgICAgImRlcGFydGFtZW50byIgOiAiMDYiLA0KICAgICAgIm11bmljaXBpbyIgOiAiMjIiLA0KICAgICAgImNvbXBsZW1lbnRvIiA6ICJQSlMgTiwgQ09MLiBMQVMgQ0HDkUFTLCAjIDI4NiINCiAgICB9LA0KICAgICJ0ZWxlZm9ubyIgOiAiMDAwMDAwMDAiLA0KICAgICJjb2RFc3RhYmxlIiA6IG51bGwsDQogICAgImNvZEVzdGFibGVNSCIgOiBudWxsLA0KICAgICJjb2RQdW50b1ZlbnRhTUgiIDogbnVsbCwNCiAgICAiY29kUHVudG9WZW50YSIgOiBudWxsLA0KICAgICJjb3JyZW8iIDogInJyaXZhczJAaG90bWFpbC5jb20iDQogIH0sDQogICJyZWNlcHRvciIgOiB7DQogICAgInRpcG9Eb2N1bWVudG8iIDogIjEzIiwNCiAgICAibnVtRG9jdW1lbnRvIiA6ICIwMDAwMDAwMC0wIiwNCiAgICAibnJjIiA6IG51bGwsDQogICAgIm5vbWJyZSIgOiAiQ2xpZW50ZSBnZW7DqXJpY28iLA0KICAgICJjb2RBY3RpdmlkYWQiIDogbnVsbCwNCiAgICAiZGVzY0FjdGl2aWRhZCIgOiBudWxsLA0KICAgICJkaXJlY2Npb24iIDogew0KICAgICAgImRlcGFydGFtZW50byIgOiAiMDYiLA0KICAgICAgIm11bmljaXBpbyIgOiAiMjMiLA0KICAgICAgImNvbXBsZW1lbnRvIiA6ICJQb3IgYWhpIg0KICAgIH0sDQogICAgInRlbGVmb25vIiA6ICIwMDAwMDAwMCIsDQogICAgImNvcnJlbyIgOiAiY2xpZW50ZUBnbWFpbC5jb20iDQogIH0sDQogICJvdHJvc0RvY3VtZW50b3MiIDogbnVsbCwNCiAgImRvY3VtZW50b1JlbGFjaW9uYWRvIiA6IG51bGwsDQogICJ2ZW50YVRlcmNlcm8iIDogbnVsbCwNCiAgImN1ZXJwb0RvY3VtZW50byIgOiBbIHsNCiAgICAibnVtSXRlbSIgOiAxLA0KICAgICJ0aXBvSXRlbSIgOiAxLA0KICAgICJudW1lcm9Eb2N1bWVudG8iIDogbnVsbCwNCiAgICAiY2FudGlkYWQiIDogMSwNCiAgICAiY29kaWdvIiA6ICIxMTExIiwNCiAgICAiY29kVHJpYnV0byIgOiBudWxsLA0KICAgICJ1bmlNZWRpZGEiIDogNTksDQogICAgImRlc2NyaXBjaW9uIiA6ICJQcm9kdWN0byBkZSBlamVtcGxvIiwNCiAgICAicHJlY2lvVW5pIiA6IDcuOTEsDQogICAgIm1vbnRvRGVzY3UiIDogMCwNCiAgICAidHJpYnV0b3MiIDogbnVsbCwNCiAgICAicHN2IiA6IDAsDQogICAgIm5vR3JhdmFkbyIgOiAwLA0KICAgICJpdmFJdGVtIiA6IDAuOTEsDQogICAgInZlbnRhTm9TdWoiIDogMCwNCiAgICAidmVudGFFeGVudGEiIDogMCwNCiAgICAidmVudGFHcmF2YWRhIiA6IDcuOTENCiAgfSBdLA0KICAicmVzdW1lbiIgOiB7DQogICAgInRvdGFsTm9TdWoiIDogMCwNCiAgICAidG90YWxFeGVudGEiIDogMCwNCiAgICAidG90YWxHcmF2YWRhIiA6IDcuOTEsDQogICAgInN1YlRvdGFsVmVudGFzIiA6IDcuOTEsDQogICAgImRlc2N1Tm9TdWoiIDogMCwNCiAgICAiZGVzY3VFeGVudGEiIDogMCwNCiAgICAiZGVzY3VHcmF2YWRhIiA6IDAsDQogICAgInBvcmNlbnRhamVEZXNjdWVudG8iIDogMCwNCiAgICAidG90YWxEZXNjdSIgOiAwLA0KICAgICJ0cmlidXRvcyIgOiBudWxsLA0KICAgICJzdWJUb3RhbCIgOiA3LjkxLA0KICAgICJpdmFSZXRlMSIgOiAwLA0KICAgICJyZXRlUmVudGEiIDogMCwNCiAgICAibW9udG9Ub3RhbE9wZXJhY2lvbiIgOiA3LjkxLA0KICAgICJ0b3RhbE5vR3JhdmFkbyIgOiAwLA0KICAgICJ0b3RhbFBhZ2FyIiA6IDcuOTEsDQogICAgInRvdGFsTGV0cmFzIiA6ICJTSUVURSA5MS8xMDAiLA0KICAgICJ0b3RhbEl2YSIgOiAwLjkxLA0KICAgICJzYWxkb0Zhdm9yIiA6IDAsDQogICAgImNvbmRpY2lvbk9wZXJhY2lvbiIgOiAxLA0KICAgICJwYWdvcyIgOiBudWxsLA0KICAgICJudW1QYWdvRWxlY3Ryb25pY28iIDogbnVsbA0KICB9LA0KICAiZXh0ZW5zaW9uIiA6IHsNCiAgICAibm9tYkVudHJlZ2EiIDogbnVsbCwNCiAgICAiZG9jdUVudHJlZ2EiIDogbnVsbCwNCiAgICAibm9tYlJlY2liZSIgOiBudWxsLA0KICAgICJkb2N1UmVjaWJlIiA6IG51bGwsDQogICAgIm9ic2VydmFjaW9uZXMiIDogbnVsbCwNCiAgICAicGxhY2FWZWhpY3VsbyIgOiBudWxsDQogIH0sDQogICJhcGVuZGljZSIgOiBudWxsDQp9.NY1bFFT7OMKLDbsqDANiKniz0pkf6OysIn3a5v6VYuAlevMsxqNLdCq4pCdp_AjZEyfGdZ24cGW4___WszcIv0PVZfGQO-o1pbXpmbdiExdkKfwCNAUEhLq7UYoEEsaVy2gvOQqeY_r8Za0oq4kc6EQ6yOO03IuCn4RgexmFAmhDx2px_-YTE2AlNA83_66SFbKujgGr-kAGCK2gUmJtRZVJ2wGc-LdFQSwxNFO4-28FVJhhzhcPPnlhyIR0NHmth7TUb4BxT63nQvxTIbtw2xlDk7MFL613Ka7jNGNTg59Z82ZeUHzApKPvMCWRknRhIzCU0TCEeE3PslQl65DYWg', 'eyJhbGciOiJSUzUxMiJ9.ew0KICAiaWRlbnRpZmljYWNpb24iIDogew0KICAgICJ2ZXJzaW9uIiA6IDEsDQogICAgImFtYmllbnRlIiA6ICIwMSIsDQogICAgInRpcG9EdGUiIDogIjAxIiwNCiAgICAibnVtZXJvQ29udHJvbCIgOiAiRFRFLTAxLVMwMDFQMDAxLTAwMDAwMDAwMDAwMDAwMSIsDQogICAgImNvZGlnb0dlbmVyYWNpb24iIDogIjM4QUFGMDU2LUQxNTktRDRFMC1BRTNBLUM3ODk3NTY3NzcyMCIsDQogICAgInRpcG9Nb2RlbG8iIDogMSwNCiAgICAidGlwb09wZXJhY2lvbiIgOiAxLA0KICAgICJ0aXBvQ29udGluZ2VuY2lhIiA6IG51bGwsDQogICAgIm1vdGl2b0NvbnRpbiIgOiBudWxsLA0KICAgICJmZWNFbWkiIDogIjIwMjUtMDQtMDUiLA0KICAgICJob3JFbWkiIDogIjE1OjEyOjIxIiwNCiAgICAidGlwb01vbmVkYSIgOiAiVVNEIg0KICB9LA0KICAiZW1pc29yIiA6IHsNCiAgICAibml0IiA6ICIwNjE0MjkwMzg1MTEzMCIsDQogICAgIm5yYyIgOiAiMjAwNjYzNSIsDQogICAgIm5vbWJyZSIgOiAiUlVCRU4gQUJJTk9BTSBSSVZBUyBBR1VJTEFSIiwNCiAgICAiY29kQWN0aXZpZGFkIiA6ICI0NTEwMCIsDQogICAgImRlc2NBY3RpdmlkYWQiIDogIlZlbnRhIGRlIHZlaMOtY3Vsb3MgYXV0b21vdG9yZXMiLA0KICAgICJub21icmVDb21lcmNpYWwiIDogbnVsbCwNCiAgICAidGlwb0VzdGFibGVjaW1pZW50byIgOiAiMDEiLA0KICAgICJkaXJlY2Npb24iIDogew0KICAgICAgImRlcGFydGFtZW50byIgOiAiMDYiLA0KICAgICAgIm11bmljaXBpbyIgOiAiMjIiLA0KICAgICAgImNvbXBsZW1lbnRvIiA6ICJQSlMgTiwgQ09MLiBMQVMgQ0HDkUFTLCAjIDI4NiINCiAgICB9LA0KICAgICJ0ZWxlZm9ubyIgOiAiMDAwMDAwMDAiLA0KICAgICJjb2RFc3RhYmxlIiA6IG51bGwsDQogICAgImNvZEVzdGFibGVNSCIgOiBudWxsLA0KICAgICJjb2RQdW50b1ZlbnRhTUgiIDogbnVsbCwNCiAgICAiY29kUHVudG9WZW50YSIgOiBudWxsLA0KICAgICJjb3JyZW8iIDogInJyaXZhczJAaG90bWFpbC5jb20iDQogIH0sDQogICJyZWNlcHRvciIgOiB7DQogICAgInRpcG9Eb2N1bWVudG8iIDogIjEzIiwNCiAgICAibnVtRG9jdW1lbnRvIiA6ICIwMDAwMDAwMC0wIiwNCiAgICAibnJjIiA6IG51bGwsDQogICAgIm5vbWJyZSIgOiAiQ2xpZW50ZSBnZW7DqXJpY28iLA0KICAgICJjb2RBY3RpdmlkYWQiIDogbnVsbCwNCiAgICAiZGVzY0FjdGl2aWRhZCIgOiBudWxsLA0KICAgICJkaXJlY2Npb24iIDogew0KICAgICAgImRlcGFydGFtZW50byIgOiAiMDYiLA0KICAgICAgIm11bmljaXBpbyIgOiAiMjMiLA0KICAgICAgImNvbXBsZW1lbnRvIiA6ICJQb3IgYWhpIg0KICAgIH0sDQogICAgInRlbGVmb25vIiA6ICIwMDAwMDAwMCIsDQogICAgImNvcnJlbyIgOiAiY2xpZW50ZUBnbWFpbC5jb20iDQogIH0sDQogICJvdHJvc0RvY3VtZW50b3MiIDogbnVsbCwNCiAgImRvY3VtZW50b1JlbGFjaW9uYWRvIiA6IG51bGwsDQogICJ2ZW50YVRlcmNlcm8iIDogbnVsbCwNCiAgImN1ZXJwb0RvY3VtZW50byIgOiBbIHsNCiAgICAibnVtSXRlbSIgOiAxLA0KICAgICJ0aXBvSXRlbSIgOiAxLA0KICAgICJudW1lcm9Eb2N1bWVudG8iIDogbnVsbCwNCiAgICAiY2FudGlkYWQiIDogMSwNCiAgICAiY29kaWdvIiA6ICIxMTExIiwNCiAgICAiY29kVHJpYnV0byIgOiBudWxsLA0KICAgICJ1bmlNZWRpZGEiIDogNTksDQogICAgImRlc2NyaXBjaW9uIiA6ICJQcm9kdWN0byBkZSBlamVtcGxvIiwNCiAgICAicHJlY2lvVW5pIiA6IDcuOTEsDQogICAgIm1vbnRvRGVzY3UiIDogMCwNCiAgICAidHJpYnV0b3MiIDogbnVsbCwNCiAgICAicHN2IiA6IDAsDQogICAgIm5vR3JhdmFkbyIgOiAwLA0KICAgICJpdmFJdGVtIiA6IDAuOTEsDQogICAgInZlbnRhTm9TdWoiIDogMCwNCiAgICAidmVudGFFeGVudGEiIDogMCwNCiAgICAidmVudGFHcmF2YWRhIiA6IDcuOTENCiAgfSBdLA0KICAicmVzdW1lbiIgOiB7DQogICAgInRvdGFsTm9TdWoiIDogMCwNCiAgICAidG90YWxFeGVudGEiIDogMCwNCiAgICAidG90YWxHcmF2YWRhIiA6IDcuOTEsDQogICAgInN1YlRvdGFsVmVudGFzIiA6IDcuOTEsDQogICAgImRlc2N1Tm9TdWoiIDogMCwNCiAgICAiZGVzY3VFeGVudGEiIDogMCwNCiAgICAiZGVzY3VHcmF2YWRhIiA6IDAsDQogICAgInBvcmNlbnRhamVEZXNjdWVudG8iIDogMCwNCiAgICAidG90YWxEZXNjdSIgOiAwLA0KICAgICJ0cmlidXRvcyIgOiBudWxsLA0KICAgICJzdWJUb3RhbCIgOiA3LjkxLA0KICAgICJpdmFSZXRlMSIgOiAwLA0KICAgICJyZXRlUmVudGEiIDogMCwNCiAgICAibW9udG9Ub3RhbE9wZXJhY2lvbiIgOiA3LjkxLA0KICAgICJ0b3RhbE5vR3JhdmFkbyIgOiAwLA0KICAgICJ0b3RhbFBhZ2FyIiA6IDcuOTEsDQogICAgInRvdGFsTGV0cmFzIiA6ICJTSUVURSA5MS8xMDAiLA0KICAgICJ0b3RhbEl2YSIgOiAwLjkxLA0KICAgICJzYWxkb0Zhdm9yIiA6IDAsDQogICAgImNvbmRpY2lvbk9wZXJhY2lvbiIgOiAxLA0KICAgICJwYWdvcyIgOiBudWxsLA0KICAgICJudW1QYWdvRWxlY3Ryb25pY28iIDogbnVsbA0KICB9LA0KICAiZXh0ZW5zaW9uIiA6IHsNCiAgICAibm9tYkVudHJlZ2EiIDogbnVsbCwNCiAgICAiZG9jdUVudHJlZ2EiIDogbnVsbCwNCiAgICAibm9tYlJlY2liZSIgOiBudWxsLA0KICAgICJkb2N1UmVjaWJlIiA6IG51bGwsDQogICAgIm9ic2VydmFjaW9uZXMiIDogbnVsbCwNCiAgICAicGxhY2FWZWhpY3VsbyIgOiBudWxsDQogIH0sDQogICJhcGVuZGljZSIgOiBudWxsDQp9.NY1bFFT7OMKLDbsqDANiKniz0pkf6OysIn3a5v6VYuAlevMsxqNLdCq4pCdp_AjZEyfGdZ24cGW4___WszcIv0PVZfGQO-o1pbXpmbdiExdkKfwCNAUEhLq7UYoEEsaVy2gvOQqeY_r8Za0oq4kc6EQ6yOO03IuCn4RgexmFAmhDx2px_-YTE2AlNA83_66SFbKujgGr-kAGCK2gUmJtRZVJ2wGc-LdFQSwxNFO4-28FVJhhzhcPPnlhyIR0NHmth7TUb4BxT63nQvxTIbtw2xlDk7MFL613Ka7jNGNTg59Z82ZeUHzApKPvMCWRknRhIzCU0TCEeE3PslQl65DYWg', '7.91', '7.91', '7', '7', '0', '0', '01', '01', '15:12:21', '15:12:21', '2025-04-05', '2025-04-05', '1', '1', '', '', '', '', '0', '0', '0', '0', '0', '0', '0', '0', '', '', 'DTE-01-S001P001-000000000000001', 'DTE-01-S001P001-000000000000001', '38AAF056-D159-D4E0-AE3A-C78975677720', '38AAF056-D159-D4E0-AE3A-C78975677720', '202527A72026F285417CBEBEEA92B57BE6C6COBR', '202527A72026F285417CBEBEEA92B57BE6C6COBR', '', '', 'Anulada', 'Anulada', 'Normal', 'Normal', '0', '0', '', '', '', '', '1', '1', '1', '1', '{\"contentType\":\"application\\/JSON\",\"nit\":\"06142903851130\",\"activo\":true,\"passwordPri\":\"DISTRIBUIDORAILOPANGO25\",\"dteJson\":{\"identificacion\":{\"version\":1,\"ambiente\":\"01\",\"tipoDte\":\"01\",\"numeroControl\":\"DTE-01-S001P001-000000000000001\",\"codigoGeneracion\":\"38AAF056-D159-D4E0-AE3A-C78975677720\",\"tipoModelo\":1,\"tipoOperacion\":1,\"tipoContingencia\":null,\"motivoContin\":null,\"fecEmi\":\"2025-04-05\",\"horEmi\":\"15:12:21\",\"tipoMoneda\":\"USD\"},\"emisor\":{\"nit\":\"06142903851130\",\"nrc\":\"2006635\",\"nombre\":\"RUBEN ABINOAM RIVAS AGUILAR\",\"codActividad\":\"45100\",\"descActividad\":\"Venta de veh\\u00edculos automotores\",\"nombreComercial\":null,\"tipoEstablecimiento\":\"01\",\"direccion\":{\"departamento\":\"06\",\"municipio\":\"22\",\"complemento\":\"PJS N, COL. LAS CA\\u00d1AS, # 286\"},\"telefono\":\"00000000\",\"codEstable\":null,\"codEstableMH\":null,\"codPuntoVentaMH\":null,\"codPuntoVenta\":null,\"correo\":\"rrivas2@hotmail.com\"},\"receptor\":{\"tipoDocumento\":\"13\",\"numDocumento\":\"00000000-0\",\"nrc\":null,\"nombre\":\"Cliente gen\\u00e9rico\",\"codActividad\":null,\"descActividad\":null,\"direccion\":{\"departamento\":\"06\",\"municipio\":\"23\",\"complemento\":\"Por ahi\"},\"telefono\":\"00000000\",\"correo\":\"cliente@gmail.com\"},\"otrosDocumentos\":null,\"documentoRelacionado\":null,\"ventaTercero\":null,\"cuerpoDocumento\":[{\"numItem\":1,\"tipoItem\":1,\"numeroDocumento\":null,\"cantidad\":1,\"codigo\":\"1111\",\"codTributo\":null,\"uniMedida\":59,\"descripcion\":\"Producto de ejemplo\",\"precioUni\":7.91,\"montoDescu\":0,\"tributos\":null,\"psv\":0,\"noGravado\":0,\"ivaItem\":0.91,\"ventaNoSuj\":0,\"ventaExenta\":0,\"ventaGravada\":7.91}],\"resumen\":{\"totalNoSuj\":0,\"totalExenta\":0,\"totalGravada\":7.91,\"subTotalVentas\":7.91,\"descuNoSuj\":0,\"descuExenta\":0,\"descuGravada\":0,\"porcentajeDescuento\":0,\"totalDescu\":0,\"tributos\":null,\"subTotal\":7.91,\"ivaRete1\":0,\"reteRenta\":0,\"montoTotalOperacion\":7.91,\"totalNoGravado\":0,\"totalPagar\":7.91,\"totalLetras\":\"SIETE 91\\/100\",\"totalIva\":0.91,\"saldoFavor\":0,\"condicionOperacion\":1,\"pagos\":null,\"numPagoElectronico\":null},\"extension\":{\"nombEntrega\":null,\"docuEntrega\":null,\"nombRecibe\":null,\"docuRecibe\":null,\"observaciones\":null,\"placaVehiculo\":null},\"apendice\":null}}', '{\"contentType\":\"application\\/JSON\",\"nit\":\"06142903851130\",\"activo\":true,\"passwordPri\":\"DISTRIBUIDORAILOPANGO25\",\"dteJson\":{\"identificacion\":{\"version\":1,\"ambiente\":\"01\",\"tipoDte\":\"01\",\"numeroControl\":\"DTE-01-S001P001-000000000000001\",\"codigoGeneracion\":\"38AAF056-D159-D4E0-AE3A-C78975677720\",\"tipoModelo\":1,\"tipoOperacion\":1,\"tipoContingencia\":null,\"motivoContin\":null,\"fecEmi\":\"2025-04-05\",\"horEmi\":\"15:12:21\",\"tipoMoneda\":\"USD\"},\"emisor\":{\"nit\":\"06142903851130\",\"nrc\":\"2006635\",\"nombre\":\"RUBEN ABINOAM RIVAS AGUILAR\",\"codActividad\":\"45100\",\"descActividad\":\"Venta de veh\\u00edculos automotores\",\"nombreComercial\":null,\"tipoEstablecimiento\":\"01\",\"direccion\":{\"departamento\":\"06\",\"municipio\":\"22\",\"complemento\":\"PJS N, COL. LAS CA\\u00d1AS, # 286\"},\"telefono\":\"00000000\",\"codEstable\":null,\"codEstableMH\":null,\"codPuntoVentaMH\":null,\"codPuntoVenta\":null,\"correo\":\"rrivas2@hotmail.com\"},\"receptor\":{\"tipoDocumento\":\"13\",\"numDocumento\":\"00000000-0\",\"nrc\":null,\"nombre\":\"Cliente gen\\u00e9rico\",\"codActividad\":null,\"descActividad\":null,\"direccion\":{\"departamento\":\"06\",\"municipio\":\"23\",\"complemento\":\"Por ahi\"},\"telefono\":\"00000000\",\"correo\":\"cliente@gmail.com\"},\"otrosDocumentos\":null,\"documentoRelacionado\":null,\"ventaTercero\":null,\"cuerpoDocumento\":[{\"numItem\":1,\"tipoItem\":1,\"numeroDocumento\":null,\"cantidad\":1,\"codigo\":\"1111\",\"codTributo\":null,\"uniMedida\":59,\"descripcion\":\"Producto de ejemplo\",\"precioUni\":7.91,\"montoDescu\":0,\"tributos\":null,\"psv\":0,\"noGravado\":0,\"ivaItem\":0.91,\"ventaNoSuj\":0,\"ventaExenta\":0,\"ventaGravada\":7.91}],\"resumen\":{\"totalNoSuj\":0,\"totalExenta\":0,\"totalGravada\":7.91,\"subTotalVentas\":7.91,\"descuNoSuj\":0,\"descuExenta\":0,\"descuGravada\":0,\"porcentajeDescuento\":0,\"totalDescu\":0,\"tributos\":null,\"subTotal\":7.91,\"ivaRete1\":0,\"reteRenta\":0,\"montoTotalOperacion\":7.91,\"totalNoGravado\":0,\"totalPagar\":7.91,\"totalLetras\":\"SIETE 91\\/100\",\"totalIva\":0.91,\"saldoFavor\":0,\"condicionOperacion\":1,\"pagos\":null,\"numPagoElectronico\":null},\"extension\":{\"nombEntrega\":null,\"docuEntrega\":null,\"nombRecibe\":null,\"docuRecibe\":null,\"observaciones\":null,\"placaVehiculo\":null},\"apendice\":null}}', '', '', '', '', '', '', 'No', 'No', '', '', '', '', '', '', '', '');


CREATE TABLE `formas_pago` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_factura` int(11) NOT NULL,
  `forma_abono` text NOT NULL,
  `fecha_abono` datetime NOT NULL,
  `gestion_abono` text NOT NULL,
  `banco` text NOT NULL,
  `monto` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `ingreso_stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cantidad` int(11) NOT NULL,
  `proveedor` text NOT NULL,
  `fecha` datetime NOT NULL,
  `comentarios` text NOT NULL,
  `id_producto` int(11) NOT NULL,
  `precio_compra` double NOT NULL,
  `precio_venta` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO ingreso_stock VALUES('19', '19', '1000', '1000', '', '', '2025-04-05 14:04:40', '2025-04-05 14:04:40', 'Stock Inicial', 'Stock Inicial', '34', '34', '7000', '7000', '7000.0000', '7000.0000');


CREATE TABLE `inventario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` text NOT NULL,
  `tipo` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `precio_compra` double NOT NULL,
  `precio_venta` double NOT NULL,
  `stock` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `codigo` text NOT NULL,
  `imagen` text NOT NULL,
  `unidadMedida` int(11) NOT NULL,
  `peso` text NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `exento_iva` text NOT NULL,
  `origen` text NOT NULL,
  `marca` text NOT NULL,
  `modelo` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO inventario VALUES('34', '34', 'Producto de ejemplo', 'Producto de ejemplo', '1', '1', '18', '18', '7', '7', '7', '7', '806', '806', 'Producto de ejemplo', 'Producto de ejemplo', '1111', '1111', 'vistas/img/anonimo.jpg', 'vistas/img/anonimo.jpg', '59', '59', '', '', '0000-00-00', '0000-00-00', '2025-04-05 15:12:55', '2025-04-05 15:12:55', 'no', 'no', '', '', '', '', '', '');


CREATE TABLE `monitoreo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` text NOT NULL,
  `fecha_pago` date NOT NULL,
  `monto_pago` double NOT NULL,
  `estado` text NOT NULL,
  `localizacion` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `motoristas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` text NOT NULL,
  `duiMotorista` text NOT NULL,
  `placaMotorista` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `ordenes_compra` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `productos` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `proveedores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` text NOT NULL,
  `nit` text NOT NULL,
  `telefono` text NOT NULL,
  `correo` text NOT NULL,
  `condicion_pago` text NOT NULL,
  `direccion` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` text NOT NULL,
  `usuario` text NOT NULL,
  `password` text NOT NULL,
  `rol` text NOT NULL,
  `ultimo_login` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `foto` text NOT NULL,
  `estado` text NOT NULL,
  `correo` text NOT NULL,
  `numero` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO usuarios VALUES('1', '1', 'Administrador', 'Administrador', 'admin', 'admin', '$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG', '$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG', 'Admin', 'Admin', '2025-04-05 13:55:54', '2025-04-05 13:55:54', '', '', '1', '1', 'admin@gmail.com', 'admin@gmail.com', '00000000', '00000000');
