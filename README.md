necessario criar db, seguindo esses codigos:

usuarios:
CREATE TABLE `usuarios` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`nome` VARCHAR(100) NOT NULL COLLATE 'latin1_swedish_ci',
	`sobrenome` VARCHAR(100) NOT NULL COLLATE 'latin1_swedish_ci',
	`email` VARCHAR(150) NOT NULL COLLATE 'latin1_swedish_ci',
	`senha` VARCHAR(255) NOT NULL COLLATE 'latin1_swedish_ci',
	`telefone` VARCHAR(20) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`tipo` ENUM('cliente','admin') NULL DEFAULT 'cliente' COLLATE 'latin1_swedish_ci',
	`data_criacao` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
	PRIMARY KEY (`id`) USING BTREE
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=2
;

produtos:
CREATE TABLE `produtos` (
	`id` INT(11) NOT NULL,
	`nome` VARCHAR(100) NOT NULL COLLATE 'latin1_swedish_ci',
	`descricao` TEXT NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`preco` DECIMAL(10,2) NOT NULL,
	`imagem` VARCHAR(255) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`categoria` VARCHAR(50) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`disponivel` TINYINT(1) NULL DEFAULT '1',
	`data_criacao` TIMESTAMP NOT NULL DEFAULT current_timestamp()
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;


pedidos:
CREATE TABLE `pedidos` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`usuario_id` INT(11) NOT NULL,
	`total` DECIMAL(10,2) NOT NULL,
	`status` ENUM('pendente','preparando','pronto','entregue','cancelado') NULL DEFAULT 'pendente' COLLATE 'latin1_swedish_ci',
	`endereco_entrega` TEXT NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`observacoes` TEXT NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`data_pedido` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
	PRIMARY KEY (`id`) USING BTREE,
	INDEX `FK_pedidos_usuarios` (`usuario_id`) USING BTREE,
	CONSTRAINT `FK_pedidos_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON UPDATE RESTRICT ON DELETE RESTRICT
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=2
;
