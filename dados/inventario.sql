-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 04-Jul-2016 às 22:38
-- Versão do servidor: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `inventario`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `material`
--

CREATE TABLE IF NOT EXISTS `material` (
  `idmaterial` bigint(20) NOT NULL AUTO_INCREMENT,
  `tombamento` bigint(20) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `detalhes_material` longtext,
  `setor_instalado` int(11) NOT NULL,
  `data_instalacao` varchar(50) DEFAULT NULL,
  `data_recolhimento` varchar(50) DEFAULT NULL,
  `tipoequipamento_idtipoequipamento` int(11) NOT NULL,
  `data_ultima_alteracao` varchar(255) DEFAULT NULL,
  `usuario_ultima_alteracao` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idmaterial`),
  KEY `fk_material_tipoequipamento1_idx` (`tipoequipamento_idtipoequipamento`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Extraindo dados da tabela `material`
--

INSERT INTO `material` (`idmaterial`, `tombamento`, `descricao`, `detalhes_material`, `setor_instalado`, `data_instalacao`, `data_recolhimento`, `tipoequipamento_idtipoequipamento`, `data_ultima_alteracao`, `usuario_ultima_alteracao`) VALUES
(1, 2011252654, 'Notebook CCE Celeron', NULL, 0, NULL, NULL, 1, NULL, 0),
(3, 2009654321, 'No-Break APC 01', '''- Motivo do recolhimento: Término do prazo de garantia.''', 0, NULL, NULL, 4, '1463414919', 2),
(4, 2012548789, 'Mouse Optico CCE', NULL, 0, '1467647952', NULL, 23, NULL, 0),
(5, 2013547962, 'Mouse Optico CCE', NULL, 0, '1467647952', NULL, 23, NULL, 0),
(6, 2014326598, 'HP L190hb', '''Poucas unidades disponíveis.''', 0, '', '', 2, NULL, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `material_geral`
--

CREATE TABLE IF NOT EXISTS `material_geral` (
  `idmaterialgeral` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `detalhes_material` longtext COLLATE utf8_unicode_ci,
  `data_ult_instalacao` int(11) DEFAULT NULL,
  `quant_total_instalada` int(11) NOT NULL,
  `data_ult_recolhimento` int(11) DEFAULT NULL,
  `quant_total_recolhida` int(11) NOT NULL,
  `tipoequipamento_idtipoequipamento` int(11) NOT NULL,
  `data_ultima_alteracao` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `usuario_ultima_alteracao` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idmaterialgeral`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17 ;

--
-- Extraindo dados da tabela `material_geral`
--

INSERT INTO `material_geral` (`idmaterialgeral`, `descricao`, `detalhes_material`, `data_ult_instalacao`, `quant_total_instalada`, `data_ult_recolhimento`, `quant_total_recolhida`, `tipoequipamento_idtipoequipamento`, `data_ultima_alteracao`, `usuario_ultima_alteracao`) VALUES
(5, 'Mouse Optico CCE', '''- Validade: Dezembro/2016.''', NULL, 0, NULL, 0, 23, NULL, 0),
(14, 'Notebook CCE Celeron', NULL, NULL, 0, NULL, 0, 1, NULL, 0),
(15, 'No-Break APC 01', NULL, NULL, 0, NULL, 0, 4, '1463414919', 2),
(16, 'HP L190hb', NULL, NULL, 0, NULL, 0, 2, NULL, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `movimentacaogeral`
--

CREATE TABLE IF NOT EXISTS `movimentacaogeral` (
  `idmovimentacaoGeral` int(11) NOT NULL AUTO_INCREMENT,
  `data` varchar(50) NOT NULL,
  `usuario_idusuario` int(11) NOT NULL,
  PRIMARY KEY (`idmovimentacaoGeral`),
  KEY `fk_movimentacaoGeral_usuario1_idx` (`usuario_idusuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `movimentacaoitens`
--

CREATE TABLE IF NOT EXISTS `movimentacaoitens` (
  `idmovimentacaoItens` int(11) NOT NULL AUTO_INCREMENT,
  `movimentacaoGeral_idmovimentacaoGeral` int(11) NOT NULL,
  `setor_antigo` int(11) DEFAULT NULL,
  `setor_novo` int(11) NOT NULL,
  `material_idmaterial` bigint(20) NOT NULL,
  `material_tomb` bigint(20) DEFAULT NULL,
  `status_idstatus` int(11) NOT NULL,
  PRIMARY KEY (`idmovimentacaoItens`),
  KEY `fk_movimentacaoItens_setor1_idx` (`setor_antigo`),
  KEY `fk_movimentacaoItens_setor2_idx` (`setor_novo`),
  KEY `fk_movimentacaoItens_material1_idx` (`material_idmaterial`),
  KEY `fk_movimentacaoItens_status1_idx` (`status_idstatus`),
  KEY `fk_movimentacaoItens_movimentacaoGeral1_idx` (`movimentacaoGeral_idmovimentacaoGeral`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `movimentacaotemp`
--

CREATE TABLE IF NOT EXISTS `movimentacaotemp` (
  `idmovimentacaoTemp` int(11) NOT NULL AUTO_INCREMENT,
  `data` varchar(50) NOT NULL,
  `setor_antigo` int(11) DEFAULT NULL,
  `setor_novo` int(11) DEFAULT NULL,
  `material_idmaterial` bigint(20) NOT NULL,
  `material_tomb` bigint(20) NOT NULL,
  `quantidade` int(11) DEFAULT NULL,
  `status_idstatus` int(11) DEFAULT NULL,
  `usuario_idusuario` int(11) NOT NULL,
  PRIMARY KEY (`idmovimentacaoTemp`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `setor`
--

CREATE TABLE IF NOT EXISTS `setor` (
  `idsetor` int(11) NOT NULL AUTO_INCREMENT,
  `codigosetor` varchar(50) NOT NULL,
  `nomesetor` varchar(50) NOT NULL,
  PRIMARY KEY (`idsetor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Extraindo dados da tabela `setor`
--

INSERT INTO `setor` (`idsetor`, `codigosetor`, `nomesetor`) VALUES
(1, 'SA', 'Secretaria Administrativa'),
(2, 'CBS', 'Coordenação das Bibliotecas Setoriais'),
(3, 'CPD', 'Centro de Processamento de Dados'),
(4, 'SIR', 'Seção de Informação e Referência'),
(5, 'DST', 'Divisão de Serviços Técnicos'),
(6, 'SCIRC', 'Seção de Circulação'),
(7, 'DAU', 'Divisão de Apoio ao Usuário'),
(8, 'SCE', 'Secao de Colecoes Especiais'),
(9, 'ALMOXA', 'Almoxarifado'),
(10, 'SC', 'Seção de Compras'),
(11, 'SDI', 'Setor de Doação e Intercâmbio'),
(12, 'SR', 'Setor de Restauração'),
(13, 'SRD', 'Setor de Repositórios Digitais'),
(14, 'RDM_ST', 'random_setor');

-- --------------------------------------------------------

--
-- Estrutura da tabela `setoritens`
--

CREATE TABLE IF NOT EXISTS `setoritens` (
  `idsetoritens` int(11) NOT NULL AUTO_INCREMENT,
  `setor_idsetor` int(11) NOT NULL,
  `material_idmaterial` int(11) NOT NULL,
  PRIMARY KEY (`idsetoritens`),
  KEY `fk_setoritens_setor_idx` (`setor_idsetor`),
  KEY `fk_setoritens_material_idx` (`material_idmaterial`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `status`
--

CREATE TABLE IF NOT EXISTS `status` (
  `idstatus` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(50) NOT NULL,
  PRIMARY KEY (`idstatus`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `status`
--

INSERT INTO `status` (`idstatus`, `status`) VALUES
(1, 'Instalação'),
(2, 'Recolhimento'),
(3, 'Transferência');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipoequipamento`
--

CREATE TABLE IF NOT EXISTS `tipoequipamento` (
  `idtipoequipamento` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(50) NOT NULL,
  PRIMARY KEY (`idtipoequipamento`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- Extraindo dados da tabela `tipoequipamento`
--

INSERT INTO `tipoequipamento` (`idtipoequipamento`, `tipo`) VALUES
(1, 'Computador'),
(2, 'Monitor'),
(3, 'Estabilizador'),
(4, 'No-Break'),
(5, 'Impressora'),
(6, 'Scanner'),
(7, 'Terminal Leve'),
(8, 'Access Point'),
(9, 'Multifuncional'),
(10, 'Máquina Virtual'),
(11, 'Rack'),
(12, 'Switch'),
(13, 'Totem'),
(14, 'Notebook'),
(15, 'Projetor'),
(16, 'Casa de HD'),
(17, 'Cofre'),
(18, 'HD Externo'),
(19, 'Servidor'),
(20, 'Webcam'),
(22, 'random_categoria'),
(23, 'Mouse');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `idusuario` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `login` varchar(50) NOT NULL,
  `senha` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `setor_idsetor` int(11) NOT NULL,
  PRIMARY KEY (`idusuario`),
  KEY `fk_usuario_setor_idx` (`setor_idsetor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`idusuario`, `nome`, `login`, `senha`, `email`, `setor_idsetor`) VALUES
(1, 'sst', 'sst', '0360962b109877cc94d8a9a31532717a', 'sst@gmail.com', 5),
(2, 'Bruno Teonácio dos Santos', 'teonacio', 'e10adc3949ba59abbe56e057f20f883e', 'bruno_gta@yahoo.com.br', 5);

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `material`
--
ALTER TABLE `material`
  ADD CONSTRAINT `fk_material_tipoequipamento1` FOREIGN KEY (`tipoequipamento_idtipoequipamento`) REFERENCES `tipoequipamento` (`idtipoequipamento`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `movimentacaogeral`
--
ALTER TABLE `movimentacaogeral`
  ADD CONSTRAINT `fk_movimentacaoGeral_usuario1` FOREIGN KEY (`usuario_idusuario`) REFERENCES `usuario` (`idusuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `movimentacaoitens`
--
ALTER TABLE `movimentacaoitens`
  ADD CONSTRAINT `fk_movimentacaoItens_material1` FOREIGN KEY (`material_idmaterial`) REFERENCES `material` (`idmaterial`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_movimentacaoItens_movimentacaoGeral1` FOREIGN KEY (`movimentacaoGeral_idmovimentacaoGeral`) REFERENCES `movimentacaogeral` (`idmovimentacaoGeral`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_movimentacaoItens_setor1` FOREIGN KEY (`setor_antigo`) REFERENCES `setor` (`idsetor`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_movimentacaoItens_setor2` FOREIGN KEY (`setor_novo`) REFERENCES `setor` (`idsetor`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_movimentacaoItens_status1` FOREIGN KEY (`status_idstatus`) REFERENCES `status` (`idstatus`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
