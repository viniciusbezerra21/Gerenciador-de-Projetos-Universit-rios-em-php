-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 03-Nov-2025 às 20:39
-- Versão do servidor: 5.7.11
-- PHP Version: 7.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cadastro_universidade`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `alunos`
--

CREATE TABLE `alunos` (
  `id` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `matricula` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `alunos`
--

INSERT INTO `alunos` (`id`, `nome`, `matricula`, `email`) VALUES
(1, 'Renata Nascimento', 'A2025101', 'renata.nascimento@aluno.senai.br'),
(2, 'Pedro Souza', 'A2025102', 'pedro.souza@aluno.senai.br'),
(3, 'Felipe Queiroz', 'A2025103', 'felipe.queiroz@aluno.senai.br'),
(4, 'Gabriel Cavalcanti', 'A2025104', 'gabriel.cavalcanti@aluno.senai.br'),
(5, 'Murilo Oliveira', 'A2025105', 'murilo.oliveira@aluno.senai.br'),
(6, 'Mateus Santos', 'A2025106', 'mateus.santos@aluno.senai.br'),
(7, 'Clara Medeiros', 'A2025107', 'clara.medeiros@aluno.senai.br'),
(8, 'Paula Costa', 'A2025108', 'paula.costa@aluno.senai.br'),
(9, 'Igor Moreira', 'A2025109', 'igor.moreira@aluno.senai.br'),
(10, 'Eduardo Dantas', 'A2025110', 'eduardo.dantas@aluno.senai.br'),
(11, 'Victor Barbosa', 'A2025111', 'victor.barbosa@aluno.senai.br'),
(12, 'Gustavo Pinto', 'A2025112', 'gustavo.pinto@aluno.senai.br'),
(13, 'Isabela Medeiros', 'A2025113', 'isabela.medeiros@aluno.senai.br'),
(14, 'Daniel Costa', 'A2025114', 'daniel.costa@aluno.senai.br'),
(15, 'Sofia Souza', 'A2025115', 'sofia.souza@aluno.senai.br'),
(16, 'Beatriz Queiroz', 'A2025116', 'beatriz.queiroz@aluno.senai.br'),
(17, 'Patrícia Andrade', 'A2025117', 'patrícia.andrade@aluno.senai.br'),
(18, 'Bianca Siqueira', 'A2025118', 'bianca.siqueira@aluno.senai.br'),
(19, 'Mariana Barros', 'A2025119', 'mariana.barros@aluno.senai.br'),
(20, 'Fernanda Assis', 'A2025120', 'fernanda.assis@aluno.senai.br'),
(21, 'Lorena Ribeiro', 'A2025121', 'lorena.ribeiro@aluno.senai.br'),
(22, 'Vitor Santana', 'A2025122', 'vitor.santana@aluno.senai.br'),
(23, 'Marcos Viana', 'A2025123', 'marcos.viana@aluno.senai.br'),
(24, 'Renata Santana', 'A2025124', 'renata.santana@aluno.senai.br'),
(25, 'Luana Siqueira', 'A2025125', 'luana.siqueira@aluno.senai.br'),
(26, 'Patrícia Azevedo', 'A2025126', 'patrícia.azevedo@aluno.senai.br'),
(27, 'Rodrigo Gomes', 'A2025127', 'rodrigo.gomes@aluno.senai.br'),
(28, 'Tatiana Medeiros', 'A2025128', 'tatiana.medeiros@aluno.senai.br'),
(29, 'Felipe Rodrigues', 'A2025129', 'felipe.rodrigues@aluno.senai.br'),
(30, 'Samuel Queiroz', 'A2025130', 'samuel.queiroz@aluno.senai.br'),
(31, 'Ricardo Pires', 'A2025131', 'ricardo.pires@aluno.senai.br'),
(32, 'Vitor Barbosa', 'A2025132', 'vitor.barbosa@aluno.senai.br'),
(33, 'Mateus Azevedo', 'A2025133', 'mateus.azevedo@aluno.senai.br'),
(34, 'Arthur Maciel', 'A2025134', 'arthur.maciel@aluno.senai.br'),
(35, 'Bruno Rocha', 'A2025135', 'bruno.rocha@aluno.senai.br'),
(36, 'Aline Mendes', 'A2025136', 'aline.mendes@aluno.senai.br'),
(37, 'André Moreira', 'A2025137', 'andré.moreira@aluno.senai.br'),
(38, 'Lucas Fernandes', 'A2025138', 'lucas.fernandes@aluno.senai.br'),
(39, 'Bianca Lacerda', 'A2025139', 'bianca.lacerda@aluno.senai.br'),
(40, 'Arthur Lopes', 'A2025140', 'arthur.lopes@aluno.senai.br'),
(41, 'Lorena Gomes', 'A2025141', 'lorena.gomes@aluno.senai.br'),
(42, 'Igor Almeida', 'A2025142', 'igor.almeida@aluno.senai.br'),
(43, 'Ana Santos', 'A2025143', 'ana.santos@aluno.senai.br'),
(44, 'Diego Santana', 'A2025144', 'diego.santana@aluno.senai.br'),
(45, 'Pedro Almeida', 'A2025145', 'pedro.almeida@aluno.senai.br'),
(46, 'André Mendes', 'A2025146', 'andré.mendes@aluno.senai.br'),
(47, 'Aline Cavalcanti', 'A2025147', 'aline.cavalcanti@aluno.senai.br'),
(48, 'Eduardo Carvalho', 'A2025148', 'eduardo.carvalho@aluno.senai.br'),
(49, 'Carolina Souza', 'A2025149', 'carolina.souza@aluno.senai.br'),
(50, 'Estela Pereira', 'A2025150', 'estela.pereira@aluno.senai.br'),
(51, 'Vinicius Bezerra', '20250055', 'viniciusg21bezerra@gmail.com');

-- --------------------------------------------------------

--
-- Estrutura da tabela `areas`
--

CREATE TABLE `areas` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `areas`
--

INSERT INTO `areas` (`id`, `nome`) VALUES
(5, 'Ciências Agrárias'),
(3, 'Ciências Biológicas'),
(4, 'Ciências da Saúde'),
(1, 'Ciências Exatas e da Terra'),
(6, 'Ciências Humanas'),
(7, 'Ciências Sociais Aplicadas'),
(2, 'Engenharias'),
(8, 'Linguística, Letras e Artes'),
(9, 'Tecnologia da Informação');

-- --------------------------------------------------------

--
-- Estrutura da tabela `documentos`
--

CREATE TABLE `documentos` (
  `id` int(11) NOT NULL,
  `id_projeto` int(11) NOT NULL,
  `nome_original` varchar(255) NOT NULL,
  `nome_arquivo` varchar(255) NOT NULL,
  `tipo_arquivo` varchar(50) NOT NULL,
  `tamanho` int(11) NOT NULL COMMENT 'Tamanho em bytes',
  `descricao` varchar(500) DEFAULT NULL,
  `data_upload` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `documentos`
--

INSERT INTO `documentos` (`id`, `id_projeto`, `nome_original`, `nome_arquivo`, `tipo_arquivo`, `tamanho`, `descricao`, `data_upload`) VALUES
(1, 1, 'documento_projeto_1.pdf', 'doc_proj_1.pdf', 'pdf', 206304, 'Documento técnico do projeto 1', '2025-11-03 19:33:28'),
(2, 2, 'documento_projeto_2.pdf', 'doc_proj_2.pdf', 'pdf', 214385, 'Documento técnico do projeto 2', '2025-11-03 19:33:28'),
(3, 3, 'documento_projeto_3.pdf', 'doc_proj_3.pdf', 'pdf', 259165, 'Documento técnico do projeto 3', '2025-11-03 19:33:28'),
(4, 4, 'documento_projeto_4.pdf', 'doc_proj_4.pdf', 'pdf', 338434, 'Documento técnico do projeto 4', '2025-11-03 19:33:28'),
(5, 5, 'documento_projeto_5.pdf', 'doc_proj_5.pdf', 'pdf', 320263, 'Documento técnico do projeto 5', '2025-11-03 19:33:28'),
(6, 6, 'documento_projeto_6.pdf', 'doc_proj_6.pdf', 'pdf', 62439, 'Documento técnico do projeto 6', '2025-11-03 19:33:28'),
(7, 7, 'documento_projeto_7.pdf', 'doc_proj_7.pdf', 'pdf', 324979, 'Documento técnico do projeto 7', '2025-11-03 19:33:28'),
(8, 8, 'documento_projeto_8.pdf', 'doc_proj_8.pdf', 'pdf', 351561, 'Documento técnico do projeto 8', '2025-11-03 19:33:28'),
(9, 9, 'documento_projeto_9.pdf', 'doc_proj_9.pdf', 'pdf', 391609, 'Documento técnico do projeto 9', '2025-11-03 19:33:28'),
(10, 10, 'documento_projeto_10.pdf', 'doc_proj_10.pdf', 'pdf', 136250, 'Documento técnico do projeto 10', '2025-11-03 19:33:28'),
(11, 11, 'documento_projeto_11.pdf', 'doc_proj_11.pdf', 'pdf', 342195, 'Documento técnico do projeto 11', '2025-11-03 19:33:28'),
(12, 12, 'documento_projeto_12.pdf', 'doc_proj_12.pdf', 'pdf', 138799, 'Documento técnico do projeto 12', '2025-11-03 19:33:28'),
(13, 13, 'documento_projeto_13.pdf', 'doc_proj_13.pdf', 'pdf', 229693, 'Documento técnico do projeto 13', '2025-11-03 19:33:28'),
(14, 14, 'documento_projeto_14.pdf', 'doc_proj_14.pdf', 'pdf', 497908, 'Documento técnico do projeto 14', '2025-11-03 19:33:28'),
(15, 15, 'documento_projeto_15.pdf', 'doc_proj_15.pdf', 'pdf', 94554, 'Documento técnico do projeto 15', '2025-11-03 19:33:28'),
(16, 16, 'documento_projeto_16.pdf', 'doc_proj_16.pdf', 'pdf', 108886, 'Documento técnico do projeto 16', '2025-11-03 19:33:28'),
(17, 17, 'documento_projeto_17.pdf', 'doc_proj_17.pdf', 'pdf', 50450, 'Documento técnico do projeto 17', '2025-11-03 19:33:28'),
(18, 18, 'documento_projeto_18.pdf', 'doc_proj_18.pdf', 'pdf', 338211, 'Documento técnico do projeto 18', '2025-11-03 19:33:28'),
(19, 19, 'documento_projeto_19.pdf', 'doc_proj_19.pdf', 'pdf', 467213, 'Documento técnico do projeto 19', '2025-11-03 19:33:28'),
(20, 20, 'documento_projeto_20.pdf', 'doc_proj_20.pdf', 'pdf', 282985, 'Documento técnico do projeto 20', '2025-11-03 19:33:28'),
(21, 21, 'documento_projeto_21.pdf', 'doc_proj_21.pdf', 'pdf', 224179, 'Documento técnico do projeto 21', '2025-11-03 19:33:28'),
(22, 22, 'documento_projeto_22.pdf', 'doc_proj_22.pdf', 'pdf', 160902, 'Documento técnico do projeto 22', '2025-11-03 19:33:28'),
(23, 23, 'documento_projeto_23.pdf', 'doc_proj_23.pdf', 'pdf', 492901, 'Documento técnico do projeto 23', '2025-11-03 19:33:28'),
(24, 24, 'documento_projeto_24.pdf', 'doc_proj_24.pdf', 'pdf', 411747, 'Documento técnico do projeto 24', '2025-11-03 19:33:28'),
(25, 25, 'documento_projeto_25.pdf', 'doc_proj_25.pdf', 'pdf', 263494, 'Documento técnico do projeto 25', '2025-11-03 19:33:28'),
(26, 26, 'documento_projeto_26.pdf', 'doc_proj_26.pdf', 'pdf', 479368, 'Documento técnico do projeto 26', '2025-11-03 19:33:28'),
(27, 27, 'documento_projeto_27.pdf', 'doc_proj_27.pdf', 'pdf', 245986, 'Documento técnico do projeto 27', '2025-11-03 19:33:28'),
(28, 28, 'documento_projeto_28.pdf', 'doc_proj_28.pdf', 'pdf', 240472, 'Documento técnico do projeto 28', '2025-11-03 19:33:28'),
(29, 29, 'documento_projeto_29.pdf', 'doc_proj_29.pdf', 'pdf', 114138, 'Documento técnico do projeto 29', '2025-11-03 19:33:29'),
(30, 30, 'documento_projeto_30.pdf', 'doc_proj_30.pdf', 'pdf', 255815, 'Documento técnico do projeto 30', '2025-11-03 19:33:29'),
(31, 31, 'documento_projeto_31.pdf', 'doc_proj_31.pdf', 'pdf', 143270, 'Documento técnico do projeto 31', '2025-11-03 19:33:29'),
(32, 32, 'documento_projeto_32.pdf', 'doc_proj_32.pdf', 'pdf', 345998, 'Documento técnico do projeto 32', '2025-11-03 19:33:29'),
(33, 33, 'documento_projeto_33.pdf', 'doc_proj_33.pdf', 'pdf', 108609, 'Documento técnico do projeto 33', '2025-11-03 19:33:29'),
(34, 34, 'documento_projeto_34.pdf', 'doc_proj_34.pdf', 'pdf', 261740, 'Documento técnico do projeto 34', '2025-11-03 19:33:29'),
(35, 35, 'documento_projeto_35.pdf', 'doc_proj_35.pdf', 'pdf', 289816, 'Documento técnico do projeto 35', '2025-11-03 19:33:29'),
(36, 36, 'documento_projeto_36.pdf', 'doc_proj_36.pdf', 'pdf', 72082, 'Documento técnico do projeto 36', '2025-11-03 19:33:29'),
(37, 37, 'documento_projeto_37.pdf', 'doc_proj_37.pdf', 'pdf', 126098, 'Documento técnico do projeto 37', '2025-11-03 19:33:29'),
(38, 38, 'documento_projeto_38.pdf', 'doc_proj_38.pdf', 'pdf', 397657, 'Documento técnico do projeto 38', '2025-11-03 19:33:29'),
(39, 39, 'documento_projeto_39.pdf', 'doc_proj_39.pdf', 'pdf', 426100, 'Documento técnico do projeto 39', '2025-11-03 19:33:29'),
(40, 40, 'documento_projeto_40.pdf', 'doc_proj_40.pdf', 'pdf', 179840, 'Documento técnico do projeto 40', '2025-11-03 19:33:29'),
(41, 41, 'documento_projeto_41.pdf', 'doc_proj_41.pdf', 'pdf', 487267, 'Documento técnico do projeto 41', '2025-11-03 19:33:29'),
(42, 42, 'documento_projeto_42.pdf', 'doc_proj_42.pdf', 'pdf', 318281, 'Documento técnico do projeto 42', '2025-11-03 19:33:29'),
(43, 43, 'documento_projeto_43.pdf', 'doc_proj_43.pdf', 'pdf', 357047, 'Documento técnico do projeto 43', '2025-11-03 19:33:29'),
(44, 44, 'documento_projeto_44.pdf', 'doc_proj_44.pdf', 'pdf', 455920, 'Documento técnico do projeto 44', '2025-11-03 19:33:29'),
(45, 45, 'documento_projeto_45.pdf', 'doc_proj_45.pdf', 'pdf', 282916, 'Documento técnico do projeto 45', '2025-11-03 19:33:29'),
(46, 46, 'documento_projeto_46.pdf', 'doc_proj_46.pdf', 'pdf', 384916, 'Documento técnico do projeto 46', '2025-11-03 19:33:29'),
(47, 47, 'documento_projeto_47.pdf', 'doc_proj_47.pdf', 'pdf', 287010, 'Documento técnico do projeto 47', '2025-11-03 19:33:29'),
(48, 48, 'documento_projeto_48.pdf', 'doc_proj_48.pdf', 'pdf', 440038, 'Documento técnico do projeto 48', '2025-11-03 19:33:29'),
(49, 49, 'documento_projeto_49.pdf', 'doc_proj_49.pdf', 'pdf', 351199, 'Documento técnico do projeto 49', '2025-11-03 19:33:29'),
(50, 50, 'documento_projeto_50.pdf', 'doc_proj_50.pdf', 'pdf', 348472, 'Documento técnico do projeto 50', '2025-11-03 19:33:29');

-- --------------------------------------------------------

--
-- Estrutura da tabela `imagens`
--

CREATE TABLE `imagens` (
  `id` int(11) NOT NULL,
  `id_projeto` int(11) NOT NULL,
  `caminho` varchar(255) NOT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `imagens`
--

INSERT INTO `imagens` (`id`, `id_projeto`, `caminho`, `descricao`, `data_cadastro`) VALUES
(1, 1, 'imagens/proj_1_img_1.jpg', 'Imagem 1 do projeto 1', '2025-11-03 19:33:25'),
(2, 1, 'imagens/proj_1_img_2.jpg', 'Imagem 2 do projeto 1', '2025-11-03 19:33:25'),
(3, 2, 'imagens/proj_2_img_1.jpg', 'Imagem 1 do projeto 2', '2025-11-03 19:33:25'),
(4, 2, 'imagens/proj_2_img_2.jpg', 'Imagem 2 do projeto 2', '2025-11-03 19:33:25'),
(5, 3, 'imagens/proj_3_img_1.jpg', 'Imagem 1 do projeto 3', '2025-11-03 19:33:25'),
(6, 3, 'imagens/proj_3_img_2.jpg', 'Imagem 2 do projeto 3', '2025-11-03 19:33:25'),
(7, 4, 'imagens/proj_4_img_1.jpg', 'Imagem 1 do projeto 4', '2025-11-03 19:33:25'),
(8, 4, 'imagens/proj_4_img_2.jpg', 'Imagem 2 do projeto 4', '2025-11-03 19:33:25'),
(9, 5, 'imagens/proj_5_img_1.jpg', 'Imagem 1 do projeto 5', '2025-11-03 19:33:25'),
(10, 5, 'imagens/proj_5_img_2.jpg', 'Imagem 2 do projeto 5', '2025-11-03 19:33:25'),
(11, 6, 'imagens/proj_6_img_1.jpg', 'Imagem 1 do projeto 6', '2025-11-03 19:33:25'),
(12, 6, 'imagens/proj_6_img_2.jpg', 'Imagem 2 do projeto 6', '2025-11-03 19:33:25'),
(13, 7, 'imagens/proj_7_img_1.jpg', 'Imagem 1 do projeto 7', '2025-11-03 19:33:25'),
(14, 7, 'imagens/proj_7_img_2.jpg', 'Imagem 2 do projeto 7', '2025-11-03 19:33:25'),
(15, 8, 'imagens/proj_8_img_1.jpg', 'Imagem 1 do projeto 8', '2025-11-03 19:33:25'),
(16, 8, 'imagens/proj_8_img_2.jpg', 'Imagem 2 do projeto 8', '2025-11-03 19:33:25'),
(17, 9, 'imagens/proj_9_img_1.jpg', 'Imagem 1 do projeto 9', '2025-11-03 19:33:25'),
(18, 9, 'imagens/proj_9_img_2.jpg', 'Imagem 2 do projeto 9', '2025-11-03 19:33:25'),
(19, 10, 'imagens/proj_10_img_1.jpg', 'Imagem 1 do projeto 10', '2025-11-03 19:33:25'),
(20, 10, 'imagens/proj_10_img_2.jpg', 'Imagem 2 do projeto 10', '2025-11-03 19:33:25'),
(21, 11, 'imagens/proj_11_img_1.jpg', 'Imagem 1 do projeto 11', '2025-11-03 19:33:25'),
(22, 11, 'imagens/proj_11_img_2.jpg', 'Imagem 2 do projeto 11', '2025-11-03 19:33:25'),
(23, 12, 'imagens/proj_12_img_1.jpg', 'Imagem 1 do projeto 12', '2025-11-03 19:33:25'),
(24, 12, 'imagens/proj_12_img_2.jpg', 'Imagem 2 do projeto 12', '2025-11-03 19:33:25'),
(25, 13, 'imagens/proj_13_img_1.jpg', 'Imagem 1 do projeto 13', '2025-11-03 19:33:25'),
(26, 13, 'imagens/proj_13_img_2.jpg', 'Imagem 2 do projeto 13', '2025-11-03 19:33:25'),
(27, 14, 'imagens/proj_14_img_1.jpg', 'Imagem 1 do projeto 14', '2025-11-03 19:33:25'),
(28, 14, 'imagens/proj_14_img_2.jpg', 'Imagem 2 do projeto 14', '2025-11-03 19:33:25'),
(29, 15, 'imagens/proj_15_img_1.jpg', 'Imagem 1 do projeto 15', '2025-11-03 19:33:25'),
(30, 15, 'imagens/proj_15_img_2.jpg', 'Imagem 2 do projeto 15', '2025-11-03 19:33:25'),
(31, 16, 'imagens/proj_16_img_1.jpg', 'Imagem 1 do projeto 16', '2025-11-03 19:33:25'),
(32, 16, 'imagens/proj_16_img_2.jpg', 'Imagem 2 do projeto 16', '2025-11-03 19:33:25'),
(33, 17, 'imagens/proj_17_img_1.jpg', 'Imagem 1 do projeto 17', '2025-11-03 19:33:26'),
(34, 17, 'imagens/proj_17_img_2.jpg', 'Imagem 2 do projeto 17', '2025-11-03 19:33:26'),
(35, 18, 'imagens/proj_18_img_1.jpg', 'Imagem 1 do projeto 18', '2025-11-03 19:33:26'),
(36, 18, 'imagens/proj_18_img_2.jpg', 'Imagem 2 do projeto 18', '2025-11-03 19:33:26'),
(37, 19, 'imagens/proj_19_img_1.jpg', 'Imagem 1 do projeto 19', '2025-11-03 19:33:26'),
(38, 19, 'imagens/proj_19_img_2.jpg', 'Imagem 2 do projeto 19', '2025-11-03 19:33:26'),
(39, 20, 'imagens/proj_20_img_1.jpg', 'Imagem 1 do projeto 20', '2025-11-03 19:33:26'),
(40, 20, 'imagens/proj_20_img_2.jpg', 'Imagem 2 do projeto 20', '2025-11-03 19:33:26'),
(41, 21, 'imagens/proj_21_img_1.jpg', 'Imagem 1 do projeto 21', '2025-11-03 19:33:26'),
(42, 21, 'imagens/proj_21_img_2.jpg', 'Imagem 2 do projeto 21', '2025-11-03 19:33:26'),
(43, 22, 'imagens/proj_22_img_1.jpg', 'Imagem 1 do projeto 22', '2025-11-03 19:33:26'),
(44, 22, 'imagens/proj_22_img_2.jpg', 'Imagem 2 do projeto 22', '2025-11-03 19:33:26'),
(45, 23, 'imagens/proj_23_img_1.jpg', 'Imagem 1 do projeto 23', '2025-11-03 19:33:26'),
(46, 23, 'imagens/proj_23_img_2.jpg', 'Imagem 2 do projeto 23', '2025-11-03 19:33:26'),
(47, 24, 'imagens/proj_24_img_1.jpg', 'Imagem 1 do projeto 24', '2025-11-03 19:33:26'),
(48, 24, 'imagens/proj_24_img_2.jpg', 'Imagem 2 do projeto 24', '2025-11-03 19:33:26'),
(49, 25, 'imagens/proj_25_img_1.jpg', 'Imagem 1 do projeto 25', '2025-11-03 19:33:26'),
(50, 25, 'imagens/proj_25_img_2.jpg', 'Imagem 2 do projeto 25', '2025-11-03 19:33:26'),
(51, 26, 'imagens/proj_26_img_1.jpg', 'Imagem 1 do projeto 26', '2025-11-03 19:33:26'),
(52, 26, 'imagens/proj_26_img_2.jpg', 'Imagem 2 do projeto 26', '2025-11-03 19:33:26'),
(53, 27, 'imagens/proj_27_img_1.jpg', 'Imagem 1 do projeto 27', '2025-11-03 19:33:26'),
(54, 27, 'imagens/proj_27_img_2.jpg', 'Imagem 2 do projeto 27', '2025-11-03 19:33:26'),
(55, 28, 'imagens/proj_28_img_1.jpg', 'Imagem 1 do projeto 28', '2025-11-03 19:33:26'),
(56, 28, 'imagens/proj_28_img_2.jpg', 'Imagem 2 do projeto 28', '2025-11-03 19:33:26'),
(57, 29, 'imagens/proj_29_img_1.jpg', 'Imagem 1 do projeto 29', '2025-11-03 19:33:26'),
(58, 29, 'imagens/proj_29_img_2.jpg', 'Imagem 2 do projeto 29', '2025-11-03 19:33:26'),
(59, 30, 'imagens/proj_30_img_1.jpg', 'Imagem 1 do projeto 30', '2025-11-03 19:33:26'),
(60, 30, 'imagens/proj_30_img_2.jpg', 'Imagem 2 do projeto 30', '2025-11-03 19:33:26'),
(61, 31, 'imagens/proj_31_img_1.jpg', 'Imagem 1 do projeto 31', '2025-11-03 19:33:26'),
(62, 31, 'imagens/proj_31_img_2.jpg', 'Imagem 2 do projeto 31', '2025-11-03 19:33:26'),
(63, 32, 'imagens/proj_32_img_1.jpg', 'Imagem 1 do projeto 32', '2025-11-03 19:33:26'),
(64, 32, 'imagens/proj_32_img_2.jpg', 'Imagem 2 do projeto 32', '2025-11-03 19:33:26'),
(65, 33, 'imagens/proj_33_img_1.jpg', 'Imagem 1 do projeto 33', '2025-11-03 19:33:26'),
(66, 33, 'imagens/proj_33_img_2.jpg', 'Imagem 2 do projeto 33', '2025-11-03 19:33:27'),
(67, 34, 'imagens/proj_34_img_1.jpg', 'Imagem 1 do projeto 34', '2025-11-03 19:33:27'),
(68, 34, 'imagens/proj_34_img_2.jpg', 'Imagem 2 do projeto 34', '2025-11-03 19:33:27'),
(69, 35, 'imagens/proj_35_img_1.jpg', 'Imagem 1 do projeto 35', '2025-11-03 19:33:27'),
(70, 35, 'imagens/proj_35_img_2.jpg', 'Imagem 2 do projeto 35', '2025-11-03 19:33:27'),
(71, 36, 'imagens/proj_36_img_1.jpg', 'Imagem 1 do projeto 36', '2025-11-03 19:33:27'),
(72, 36, 'imagens/proj_36_img_2.jpg', 'Imagem 2 do projeto 36', '2025-11-03 19:33:27'),
(73, 37, 'imagens/proj_37_img_1.jpg', 'Imagem 1 do projeto 37', '2025-11-03 19:33:27'),
(74, 37, 'imagens/proj_37_img_2.jpg', 'Imagem 2 do projeto 37', '2025-11-03 19:33:27'),
(75, 38, 'imagens/proj_38_img_1.jpg', 'Imagem 1 do projeto 38', '2025-11-03 19:33:27'),
(76, 38, 'imagens/proj_38_img_2.jpg', 'Imagem 2 do projeto 38', '2025-11-03 19:33:27'),
(77, 39, 'imagens/proj_39_img_1.jpg', 'Imagem 1 do projeto 39', '2025-11-03 19:33:27'),
(78, 39, 'imagens/proj_39_img_2.jpg', 'Imagem 2 do projeto 39', '2025-11-03 19:33:27'),
(79, 40, 'imagens/proj_40_img_1.jpg', 'Imagem 1 do projeto 40', '2025-11-03 19:33:27'),
(80, 40, 'imagens/proj_40_img_2.jpg', 'Imagem 2 do projeto 40', '2025-11-03 19:33:27'),
(81, 41, 'imagens/proj_41_img_1.jpg', 'Imagem 1 do projeto 41', '2025-11-03 19:33:27'),
(82, 41, 'imagens/proj_41_img_2.jpg', 'Imagem 2 do projeto 41', '2025-11-03 19:33:27'),
(83, 42, 'imagens/proj_42_img_1.jpg', 'Imagem 1 do projeto 42', '2025-11-03 19:33:27'),
(84, 42, 'imagens/proj_42_img_2.jpg', 'Imagem 2 do projeto 42', '2025-11-03 19:33:27'),
(85, 43, 'imagens/proj_43_img_1.jpg', 'Imagem 1 do projeto 43', '2025-11-03 19:33:27'),
(86, 43, 'imagens/proj_43_img_2.jpg', 'Imagem 2 do projeto 43', '2025-11-03 19:33:27'),
(87, 44, 'imagens/proj_44_img_1.jpg', 'Imagem 1 do projeto 44', '2025-11-03 19:33:27'),
(88, 44, 'imagens/proj_44_img_2.jpg', 'Imagem 2 do projeto 44', '2025-11-03 19:33:27'),
(89, 45, 'imagens/proj_45_img_1.jpg', 'Imagem 1 do projeto 45', '2025-11-03 19:33:27'),
(90, 45, 'imagens/proj_45_img_2.jpg', 'Imagem 2 do projeto 45', '2025-11-03 19:33:27'),
(91, 46, 'imagens/proj_46_img_1.jpg', 'Imagem 1 do projeto 46', '2025-11-03 19:33:27'),
(92, 46, 'imagens/proj_46_img_2.jpg', 'Imagem 2 do projeto 46', '2025-11-03 19:33:27'),
(93, 47, 'imagens/proj_47_img_1.jpg', 'Imagem 1 do projeto 47', '2025-11-03 19:33:27'),
(94, 47, 'imagens/proj_47_img_2.jpg', 'Imagem 2 do projeto 47', '2025-11-03 19:33:27'),
(95, 48, 'imagens/proj_48_img_1.jpg', 'Imagem 1 do projeto 48', '2025-11-03 19:33:27'),
(96, 48, 'imagens/proj_48_img_2.jpg', 'Imagem 2 do projeto 48', '2025-11-03 19:33:27'),
(97, 49, 'imagens/proj_49_img_1.jpg', 'Imagem 1 do projeto 49', '2025-11-03 19:33:28'),
(98, 49, 'imagens/proj_49_img_2.jpg', 'Imagem 2 do projeto 49', '2025-11-03 19:33:28'),
(99, 50, 'imagens/proj_50_img_1.jpg', 'Imagem 1 do projeto 50', '2025-11-03 19:33:28'),
(100, 50, 'imagens/proj_50_img_2.jpg', 'Imagem 2 do projeto 50', '2025-11-03 19:33:28');

-- --------------------------------------------------------

--
-- Estrutura da tabela `orientadores`
--

CREATE TABLE `orientadores` (
  `id` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `email` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `orientadores`
--

INSERT INTO `orientadores` (`id`, `nome`, `email`) VALUES
(1, 'Aline Gomes', 'aline.gomes@senai.br'),
(2, 'Estela Silva', 'estela.silva@senai.br'),
(3, 'Thiago Lacerda', 'thiago.lacerda@senai.br'),
(4, 'Bruno Andrade', 'bruno.andrade@senai.br'),
(5, 'Igor Nascimento', 'igor.nascimento@senai.br'),
(6, 'Marcos Azevedo', 'marcos.azevedo@senai.br'),
(7, 'Marcos Cardoso', 'marcos.cardoso@senai.br'),
(8, 'Rafael Nascimento', 'rafael.nascimento@senai.br'),
(9, 'Arthur Nascimento', 'arthur.nascimento@senai.br'),
(10, 'Larissa Gomes', 'larissa.gomes@senai.br'),
(11, 'Igor Bezerra', 'igor.bezerra@senai.br'),
(12, 'Priscila Coelho', 'priscila.coelho@senai.br'),
(13, 'Eduardo Rodrigues', 'eduardo.rodrigues@senai.br'),
(14, 'Daniel Lopes', 'daniel.lopes@senai.br'),
(15, 'Tatiana Rodrigues', 'tatiana.rodrigues@senai.br'),
(16, 'Thiago Azevedo', 'thiago.azevedo@senai.br'),
(17, 'Julia Coelho', 'julia.coelho@senai.br'),
(18, 'Mariana Nascimento', 'mariana.nascimento@senai.br'),
(19, 'Ana Medeiros', 'ana.medeiros@senai.br'),
(20, 'Fernanda Mendes', 'fernanda.mendes@senai.br'),
(21, 'Vitor Nunes', 'vitor.nunes@senai.br'),
(22, 'Marcos Viana', 'marcos.viana@senai.br'),
(23, 'Beatriz Maciel', 'beatriz.maciel@senai.br'),
(24, 'Luana Teixeira', 'luana.teixeira@senai.br'),
(25, 'Paula Oliveira', 'paula.oliveira@senai.br'),
(26, 'Tatiana Ribeiro', 'tatiana.ribeiro@senai.br'),
(27, 'Carolina Gonçalves', 'carolina.gonçalves@senai.br'),
(28, 'Renata Fernandes', 'renata.fernandes@senai.br'),
(29, 'Julia Moura', 'julia.moura@senai.br'),
(30, 'Henrique Maciel', 'henrique.maciel@senai.br'),
(31, 'Fernanda Lopes', 'fernanda.lopes@senai.br'),
(32, 'Lucas Cardoso', 'lucas.cardoso@senai.br'),
(33, 'Eduardo Azevedo', 'eduardo.azevedo@senai.br'),
(34, 'Vitor Assis', 'vitor.assis@senai.br'),
(35, 'Arthur Sampaio', 'arthur.sampaio@senai.br'),
(36, 'Gabriel Nunes', 'gabriel.nunes@senai.br'),
(37, 'Arthur Santos', 'arthur.santos@senai.br'),
(38, 'Tatiana Souza', 'tatiana.souza@senai.br'),
(39, 'Marcos Castro', 'marcos.castro@senai.br'),
(40, 'Patrícia Santana', 'patrícia.santana@senai.br'),
(41, 'Felipe Nascimento', 'felipe.nascimento@senai.br'),
(42, 'Felipe Cardoso', 'felipe.cardoso@senai.br'),
(43, 'Felipe Silva', 'felipe.silva@senai.br'),
(44, 'Letícia Mendes', 'letícia.mendes@senai.br'),
(45, 'Daniel Barbosa', 'daniel.barbosa@senai.br'),
(46, 'Luana Martins', 'luana.martins@senai.br'),
(47, 'Bruno Silva', 'bruno.silva@senai.br'),
(48, 'Larissa Maciel', 'larissa.maciel@senai.br'),
(49, 'Nathalia Brito', 'nathalia.brito@senai.br'),
(50, 'Diego Almeida', 'diego.almeida@senai.br');

-- --------------------------------------------------------

--
-- Estrutura da tabela `projetos`
--

CREATE TABLE `projetos` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `resumo` text,
  `id_orientador` int(11) NOT NULL,
  `id_area` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `imagem` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `projetos`
--

INSERT INTO `projetos` (`id`, `titulo`, `resumo`, `id_orientador`, `id_area`, `status`, `data_cadastro`, `imagem`) VALUES
(1, 'Projeto 1 - Catálogo Digital', 'Resumo do Projeto 1 - Catálogo Digital. Desenvolvimento e resultados experimentais aplicados.', 1, 1, 8, '2025-11-03 19:33:23', 'proj_1_thumb.png'),
(2, 'Projeto 2 - Aplicativo Mobile', 'Resumo do Projeto 2 - Aplicativo Mobile. Desenvolvimento e resultados experimentais aplicados.', 2, 2, 7, '2025-11-03 19:33:23', 'proj_2_thumb.png'),
(3, 'Projeto 3 - Catálogo Digital', 'Resumo do Projeto 3 - Catálogo Digital. Desenvolvimento e resultados experimentais aplicados.', 3, 3, 6, '2025-11-03 19:33:23', 'proj_3_thumb.png'),
(4, 'Projeto 4 - Visão Computacional', 'Resumo do Projeto 4 - Visão Computacional. Desenvolvimento e resultados experimentais aplicados.', 4, 4, 8, '2025-11-03 19:33:23', 'proj_4_thumb.png'),
(5, 'Projeto 5 - Plataforma Web', 'Resumo do Projeto 5 - Plataforma Web. Desenvolvimento e resultados experimentais aplicados.', 5, 5, 10, '2025-11-03 19:33:23', 'proj_5_thumb.png'),
(6, 'Projeto 6 - Análise de Dados', 'Resumo do Projeto 6 - Análise de Dados. Desenvolvimento e resultados experimentais aplicados.', 6, 6, 9, '2025-11-03 19:33:23', 'proj_6_thumb.png'),
(7, 'Projeto 7 - IoT', 'Resumo do Projeto 7 - IoT. Desenvolvimento e resultados experimentais aplicados.', 7, 7, 6, '2025-11-03 19:33:23', 'proj_7_thumb.png'),
(8, 'Projeto 8 - Visão Computacional', 'Resumo do Projeto 8 - Visão Computacional. Desenvolvimento e resultados experimentais aplicados.', 8, 8, 6, '2025-11-03 19:33:23', 'proj_8_thumb.png'),
(9, 'Projeto 9 - Aplicativo Mobile', 'Resumo do Projeto 9 - Aplicativo Mobile. Desenvolvimento e resultados experimentais aplicados.', 9, 9, 8, '2025-11-03 19:33:23', 'proj_9_thumb.png'),
(10, 'Projeto 10 - NLP', 'Resumo do Projeto 10 - NLP. Desenvolvimento e resultados experimentais aplicados.', 10, 1, 7, '2025-11-03 19:33:23', 'proj_10_thumb.png'),
(11, 'Projeto 11 - Análise de Dados', 'Resumo do Projeto 11 - Análise de Dados. Desenvolvimento e resultados experimentais aplicados.', 11, 2, 10, '2025-11-03 19:33:23', 'proj_11_thumb.png'),
(12, 'Projeto 12 - NLP', 'Resumo do Projeto 12 - NLP. Desenvolvimento e resultados experimentais aplicados.', 12, 3, 6, '2025-11-03 19:33:23', 'proj_12_thumb.png'),
(13, 'Projeto 13 - IoT', 'Resumo do Projeto 13 - IoT. Desenvolvimento e resultados experimentais aplicados.', 13, 4, 10, '2025-11-03 19:33:23', 'proj_13_thumb.png'),
(14, 'Projeto 14 - NLP', 'Resumo do Projeto 14 - NLP. Desenvolvimento e resultados experimentais aplicados.', 14, 5, 7, '2025-11-03 19:33:23', 'proj_14_thumb.png'),
(15, 'Projeto 15 - NLP', 'Resumo do Projeto 15 - NLP. Desenvolvimento e resultados experimentais aplicados.', 15, 6, 8, '2025-11-03 19:33:23', 'proj_15_thumb.png'),
(16, 'Projeto 16 - NLP', 'Resumo do Projeto 16 - NLP. Desenvolvimento e resultados experimentais aplicados.', 16, 7, 7, '2025-11-03 19:33:23', 'proj_16_thumb.png'),
(17, 'Projeto 17 - Análise de Dados', 'Resumo do Projeto 17 - Análise de Dados. Desenvolvimento e resultados experimentais aplicados.', 17, 8, 6, '2025-11-03 19:33:24', 'proj_17_thumb.png'),
(18, 'Projeto 18 - NLP', 'Resumo do Projeto 18 - NLP. Desenvolvimento e resultados experimentais aplicados.', 18, 9, 8, '2025-11-03 19:33:24', 'proj_18_thumb.png'),
(19, 'Projeto 19 - Visão Computacional', 'Resumo do Projeto 19 - Visão Computacional. Desenvolvimento e resultados experimentais aplicados.', 19, 1, 10, '2025-11-03 19:33:24', 'proj_19_thumb.png'),
(20, 'Projeto 20 - Análise de Dados', 'Resumo do Projeto 20 - Análise de Dados. Desenvolvimento e resultados experimentais aplicados.', 20, 2, 9, '2025-11-03 19:33:24', 'proj_20_thumb.png'),
(21, 'Projeto 21 - Análise de Dados', 'Resumo do Projeto 21 - Análise de Dados. Desenvolvimento e resultados experimentais aplicados.', 21, 3, 9, '2025-11-03 19:33:24', 'proj_21_thumb.png'),
(22, 'Projeto 22 - IoT', 'Resumo do Projeto 22 - IoT. Desenvolvimento e resultados experimentais aplicados.', 22, 4, 10, '2025-11-03 19:33:24', 'proj_22_thumb.png'),
(23, 'Projeto 23 - Visão Computacional', 'Resumo do Projeto 23 - Visão Computacional. Desenvolvimento e resultados experimentais aplicados.', 23, 5, 8, '2025-11-03 19:33:24', 'proj_23_thumb.png'),
(24, 'Projeto 24 - Análise de Dados', 'Resumo do Projeto 24 - Análise de Dados. Desenvolvimento e resultados experimentais aplicados.', 24, 6, 7, '2025-11-03 19:33:24', 'proj_24_thumb.png'),
(25, 'Projeto 25 - Análise de Dados', 'Resumo do Projeto 25 - Análise de Dados. Desenvolvimento e resultados experimentais aplicados.', 25, 7, 6, '2025-11-03 19:33:24', 'proj_25_thumb.png'),
(26, 'Projeto 26 - Aplicativo Mobile', 'Resumo do Projeto 26 - Aplicativo Mobile. Desenvolvimento e resultados experimentais aplicados.', 26, 8, 9, '2025-11-03 19:33:24', 'proj_26_thumb.png'),
(27, 'Projeto 27 - NLP', 'Resumo do Projeto 27 - NLP. Desenvolvimento e resultados experimentais aplicados.', 27, 9, 7, '2025-11-03 19:33:24', 'proj_27_thumb.png'),
(28, 'Projeto 28 - Plataforma Web', 'Resumo do Projeto 28 - Plataforma Web. Desenvolvimento e resultados experimentais aplicados.', 28, 1, 8, '2025-11-03 19:33:24', 'proj_28_thumb.png'),
(29, 'Projeto 29 - Análise de Dados', 'Resumo do Projeto 29 - Análise de Dados. Desenvolvimento e resultados experimentais aplicados.', 29, 2, 6, '2025-11-03 19:33:24', 'proj_29_thumb.png'),
(30, 'Projeto 30 - Análise de Dados', 'Resumo do Projeto 30 - Análise de Dados. Desenvolvimento e resultados experimentais aplicados.', 30, 3, 8, '2025-11-03 19:33:24', 'proj_30_thumb.png'),
(31, 'Projeto 31 - Energia Solar', 'Resumo do Projeto 31 - Energia Solar. Desenvolvimento e resultados experimentais aplicados.', 31, 4, 10, '2025-11-03 19:33:24', 'proj_31_thumb.png'),
(32, 'Projeto 32 - Catálogo Digital', 'Resumo do Projeto 32 - Catálogo Digital. Desenvolvimento e resultados experimentais aplicados.', 32, 5, 9, '2025-11-03 19:33:24', 'proj_32_thumb.png'),
(33, 'Projeto 33 - Aplicativo Mobile', 'Resumo do Projeto 33 - Aplicativo Mobile. Desenvolvimento e resultados experimentais aplicados.', 33, 6, 7, '2025-11-03 19:33:24', 'proj_33_thumb.png'),
(34, 'Projeto 34 - NLP', 'Resumo do Projeto 34 - NLP. Desenvolvimento e resultados experimentais aplicados.', 34, 7, 7, '2025-11-03 19:33:24', 'proj_34_thumb.png'),
(35, 'Projeto 35 - Energia Solar', 'Resumo do Projeto 35 - Energia Solar. Desenvolvimento e resultados experimentais aplicados.', 35, 8, 6, '2025-11-03 19:33:24', 'proj_35_thumb.png'),
(36, 'Projeto 36 - Aplicativo Mobile', 'Resumo do Projeto 36 - Aplicativo Mobile. Desenvolvimento e resultados experimentais aplicados.', 36, 9, 7, '2025-11-03 19:33:24', 'proj_36_thumb.png'),
(37, 'Projeto 37 - Catálogo Digital', 'Resumo do Projeto 37 - Catálogo Digital. Desenvolvimento e resultados experimentais aplicados.', 37, 1, 8, '2025-11-03 19:33:24', 'proj_37_thumb.png'),
(38, 'Projeto 38 - Energia Solar', 'Resumo do Projeto 38 - Energia Solar. Desenvolvimento e resultados experimentais aplicados.', 38, 2, 8, '2025-11-03 19:33:24', 'proj_38_thumb.png'),
(39, 'Projeto 39 - Aplicativo Mobile', 'Resumo do Projeto 39 - Aplicativo Mobile. Desenvolvimento e resultados experimentais aplicados.', 39, 3, 9, '2025-11-03 19:33:24', 'proj_39_thumb.png'),
(40, 'Projeto 40 - Aplicativo Mobile', 'Resumo do Projeto 40 - Aplicativo Mobile. Desenvolvimento e resultados experimentais aplicados.', 40, 4, 10, '2025-11-03 19:33:24', 'proj_40_thumb.png'),
(41, 'Projeto 41 - Plataforma Web', 'Resumo do Projeto 41 - Plataforma Web. Desenvolvimento e resultados experimentais aplicados.', 41, 5, 7, '2025-11-03 19:33:24', 'proj_41_thumb.png'),
(42, 'Projeto 42 - IoT', 'Resumo do Projeto 42 - IoT. Desenvolvimento e resultados experimentais aplicados.', 42, 6, 10, '2025-11-03 19:33:24', 'proj_42_thumb.png'),
(43, 'Projeto 43 - Energia Solar', 'Resumo do Projeto 43 - Energia Solar. Desenvolvimento e resultados experimentais aplicados.', 43, 7, 10, '2025-11-03 19:33:24', 'proj_43_thumb.png'),
(44, 'Projeto 44 - Energia Solar', 'Resumo do Projeto 44 - Energia Solar. Desenvolvimento e resultados experimentais aplicados.', 44, 8, 10, '2025-11-03 19:33:24', 'proj_44_thumb.png'),
(45, 'Projeto 45 - NLP', 'Resumo do Projeto 45 - NLP. Desenvolvimento e resultados experimentais aplicados.', 45, 9, 8, '2025-11-03 19:33:24', 'proj_45_thumb.png'),
(46, 'Projeto 46 - Análise de Dados', 'Resumo do Projeto 46 - Análise de Dados. Desenvolvimento e resultados experimentais aplicados.', 46, 1, 9, '2025-11-03 19:33:24', 'proj_46_thumb.png'),
(47, 'Projeto 47 - Aplicativo Mobile', 'Resumo do Projeto 47 - Aplicativo Mobile. Desenvolvimento e resultados experimentais aplicados.', 47, 2, 9, '2025-11-03 19:33:24', 'proj_47_thumb.png'),
(48, 'Projeto 48 - Aplicativo Mobile', 'Resumo do Projeto 48 - Aplicativo Mobile. Desenvolvimento e resultados experimentais aplicados.', 48, 3, 9, '2025-11-03 19:33:24', 'proj_48_thumb.png'),
(49, 'Projeto 49 - IoT', 'Resumo do Projeto 49 - IoT. Desenvolvimento e resultados experimentais aplicados.', 49, 4, 6, '2025-11-03 19:33:24', 'proj_49_thumb.png'),
(50, 'Projeto 50 - Visão Computacional', 'Resumo do Projeto 50 - Visão Computacional. Desenvolvimento e resultados experimentais aplicados.', 50, 5, 9, '2025-11-03 19:33:25', 'proj_50_thumb.png'),
(56, 'rwe', 're', 41, 9, 10, '2025-11-03 20:22:31', '69090f07ca970.png'),
(57, 'fw', 'rwfwera', 43, 7, 8, '2025-11-03 20:23:31', '69090f43e3c28.jpeg'),
(58, 're', 'frewa', 41, 5, 9, '2025-11-03 20:28:39', '690910775d9c1.png'),
(59, 'fwefw', 'fwefw', 43, 6, 8, '2025-11-03 20:29:59', '690910c7999bd.png');

-- --------------------------------------------------------

--
-- Estrutura da tabela `projetos_alunos`
--

CREATE TABLE `projetos_alunos` (
  `id_projeto` int(11) NOT NULL,
  `id_aluno` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `projetos_alunos`
--

INSERT INTO `projetos_alunos` (`id_projeto`, `id_aluno`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10),
(11, 11),
(12, 12),
(13, 13),
(14, 14),
(15, 15),
(16, 16),
(17, 17),
(18, 18),
(19, 19),
(20, 20),
(21, 21),
(22, 22),
(23, 23),
(24, 24),
(25, 25),
(26, 26),
(27, 27),
(28, 28),
(29, 29),
(30, 30),
(31, 31),
(32, 32),
(33, 33),
(34, 34),
(35, 35),
(36, 36),
(37, 37),
(38, 38),
(39, 39),
(40, 40),
(41, 41),
(42, 42),
(43, 43),
(44, 44),
(45, 45),
(46, 46),
(47, 47),
(48, 48),
(49, 49),
(50, 50);

-- --------------------------------------------------------

--
-- Estrutura da tabela `remember_tokens`
--

CREATE TABLE `remember_tokens` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expira_em` datetime NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `remember_tokens`
--

INSERT INTO `remember_tokens` (`id`, `usuario_id`, `token`, `expira_em`, `criado_em`) VALUES
(1, 1, 'token_1_example_abcdef1234567890', '2025-12-03 19:30:53', '2025-11-03 22:30:53'),
(2, 2, 'token_2_example_abcdef1234567890', '2025-12-03 19:30:53', '2025-11-03 22:30:53'),
(3, 3, 'token_3_example_abcdef1234567890', '2025-12-03 19:30:53', '2025-11-03 22:30:53'),
(4, 4, 'token_4_example_abcdef1234567890', '2025-12-03 19:30:53', '2025-11-03 22:30:53'),
(5, 5, 'token_5_example_abcdef1234567890', '2025-12-03 19:30:53', '2025-11-03 22:30:53'),
(7, 52, '7c5658ddf61515abb1c275121bd5749c338ac410aa6367838a89a5d7b7d1c79f', '2025-12-03 19:40:23', '2025-11-03 19:40:23'),
(10, 55, 'b9169fc7245ea2b694ca5d1d9892ccfaf815d0d059cd33cdb2ca34876d9fbdb3', '2025-12-03 20:35:59', '2025-11-03 20:35:59');

-- --------------------------------------------------------

--
-- Estrutura da tabela `status`
--

CREATE TABLE `status` (
  `id` int(11) NOT NULL,
  `descricao` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `status`
--

INSERT INTO `status` (`id`, `descricao`) VALUES
(8, 'Aprovado'),
(7, 'Concluído'),
(10, 'Em análise'),
(6, 'Em andamento'),
(9, 'Reprovado');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `email` varchar(150) NOT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `foto_perfil`, `senha`, `tipo`) VALUES
(1, 'Lorena Pinto', 'lorena.pinto@senai.br', 'perfil_1_xyz.png', '@Senha123', 'aluno'),
(2, 'Tatiana Gonçalves', 'tatiana.gonçalves@senai.br', 'perfil_2_xyz.png', '@Senha123', 'aluno'),
(3, 'Camila Freitas', 'camila.freitas@senai.br', 'perfil_3_xyz.png', '@Senha123', 'aluno'),
(4, 'Aline Barbosa', 'aline.barbosa@senai.br', 'perfil_4_xyz.png', '@Senha123', 'aluno'),
(5, 'Renata Araújo', 'renata.araújo@senai.br', 'perfil_5_xyz.png', '@Senha123', 'aluno'),
(6, 'Amanda Pinto', 'amanda.pinto@senai.br', 'perfil_6_xyz.png', '@Senha123', 'aluno'),
(7, 'Priscila Mendes', 'priscila.mendes@senai.br', 'perfil_7_xyz.png', '@Senha123', 'aluno'),
(8, 'Tatiana Lopes', 'tatiana.lopes@senai.br', 'perfil_8_xyz.png', '@Senha123', 'aluno'),
(9, 'Fernanda Assis', 'fernanda.assis@senai.br', 'perfil_9_xyz.png', '@Senha123', 'aluno'),
(10, 'Mariana Fernandes', 'mariana.fernandes@senai.br', 'perfil_10_xyz.png', '@Senha123', 'aluno'),
(11, 'Ricardo Viana', 'ricardo.viana@senai.br', 'perfil_11_xyz.png', '@Senha123', 'aluno'),
(12, 'Sofia Rezende', 'sofia.rezende@senai.br', 'perfil_12_xyz.png', '@Senha123', 'aluno'),
(13, 'Eduardo Santos', 'eduardo.santos@senai.br', 'perfil_13_xyz.png', '@Senha123', 'aluno'),
(14, 'Carolina Campos', 'carolina.campos@senai.br', 'perfil_14_xyz.png', '@Senha123', 'aluno'),
(15, 'Eduardo Maciel', 'eduardo.maciel@senai.br', 'perfil_15_xyz.png', '@Senha123', 'aluno'),
(16, 'Felipe Queiroz', 'felipe.queiroz@senai.br', 'perfil_16_xyz.png', '@Senha123', 'aluno'),
(17, 'Bruno Sampaio', 'bruno.sampaio@senai.br', 'perfil_17_xyz.png', '@Senha123', 'aluno'),
(18, 'Henrique Ribeiro', 'henrique.ribeiro@senai.br', 'perfil_18_xyz.png', '@Senha123', 'aluno'),
(19, 'João Bezerra', 'joão.bezerra@senai.br', 'perfil_19_xyz.png', '@Senha123', 'aluno'),
(20, 'Gustavo Coelho', 'gustavo.coelho@senai.br', 'perfil_20_xyz.png', '@Senha123', 'aluno'),
(21, 'Priscila Assis', 'priscila.assis@senai.br', 'perfil_21_xyz.png', '@Senha123', 'aluno'),
(22, 'Eduardo Rocha', 'eduardo.rocha@senai.br', 'perfil_22_xyz.png', '@Senha123', 'aluno'),
(23, 'Amanda Furtado', 'amanda.furtado@senai.br', 'perfil_23_xyz.png', '@Senha123', 'aluno'),
(24, 'Patrícia Rocha', 'patrícia.rocha@senai.br', 'perfil_24_xyz.png', '@Senha123', 'aluno'),
(25, 'Victor Furtado', 'victor.furtado@senai.br', 'perfil_25_xyz.png', '@Senha123', 'aluno'),
(26, 'Vitor Souza', 'vitor.souza@senai.br', 'perfil_26_xyz.png', '@Senha123', 'aluno'),
(27, 'Fernanda Dantas', 'fernanda.dantas@senai.br', 'perfil_27_xyz.png', '@Senha123', 'aluno'),
(28, 'Isabela Nunes', 'isabela.nunes@senai.br', 'perfil_28_xyz.png', '@Senha123', 'aluno'),
(29, 'André Carvalho', 'andré.carvalho@senai.br', 'perfil_29_xyz.png', '@Senha123', 'aluno'),
(30, 'Nathalia Freitas', 'nathalia.freitas@senai.br', 'perfil_30_xyz.png', '@Senha123', 'aluno'),
(31, 'Tatiana Pereira', 'tatiana.pereira@senai.br', 'perfil_31_xyz.png', '@Senha123', 'aluno'),
(32, 'Carolina Rodrigues', 'carolina.rodrigues@senai.br', 'perfil_32_xyz.png', '@Senha123', 'aluno'),
(33, 'Lorena Moura', 'lorena.moura@senai.br', 'perfil_33_xyz.png', '@Senha123', 'aluno'),
(34, 'Clara Souza', 'clara.souza@senai.br', 'perfil_34_xyz.png', '@Senha123', 'aluno'),
(35, 'Ricardo Bezerra', 'ricardo.bezerra@senai.br', 'perfil_35_xyz.png', '@Senha123', 'aluno'),
(36, 'Fernanda Santos', 'fernanda.santos@senai.br', 'perfil_36_xyz.png', '@Senha123', 'aluno'),
(37, 'João Lacerda', 'joão.lacerda@senai.br', 'perfil_37_xyz.png', '@Senha123', 'aluno'),
(38, 'Vitor Medeiros', 'vitor.medeiros@senai.br', 'perfil_38_xyz.png', '@Senha123', 'aluno'),
(39, 'Igor Teixeira', 'igor.teixeira@senai.br', 'perfil_39_xyz.png', '@Senha123', 'aluno'),
(40, 'Clara Fernandes', 'clara.fernandes@senai.br', 'perfil_40_xyz.png', '@Senha123', 'aluno'),
(41, 'Rodrigo Lima', 'rodrigo.lima@senai.br', 'perfil_41_xyz.png', '@Senha123', 'orientador'),
(42, 'Luana Souza', 'luana.souza@senai.br', 'perfil_42_xyz.png', '@Senha123', 'orientador'),
(43, 'Paula Rodrigues', 'paula.rodrigues@senai.br', 'perfil_43_xyz.png', '@Senha123', 'orientador'),
(44, 'Luana Gomes', 'luana.gomes@senai.br', 'perfil_44_xyz.png', '@Senha123', 'orientador'),
(45, 'Patrícia Moura', 'patrícia.moura@senai.br', 'perfil_45_xyz.png', '@Senha123', 'orientador'),
(46, 'Camila Carvalho', 'camila.carvalho@senai.br', 'perfil_46_xyz.png', '@Senha123', 'orientador'),
(47, 'Renata Moura', 'renata.moura@senai.br', 'perfil_47_xyz.png', '@Senha123', 'orientador'),
(48, 'Renata Castro', 'renata.castro@senai.br', 'perfil_48_xyz.png', '@Senha123', 'orientador'),
(49, 'Rodrigo Gomes', 'rodrigo.gomes@senai.br', 'perfil_49_xyz.png', '@Senha123', 'orientador'),
(50, 'Marcos Moura', 'marcos.moura@senai.br', 'perfil_50_xyz.png', '@Senha123', 'orientador'),
(52, 'Rabinson Grings', 'rabinson@email.com', NULL, '$2y$10$d/d0/XiiAMdK8CJ1gHGBAOOw7WiaTSQ1QDu4Fs/jKn4RTMxfH4yIO', 'aluno'),
(55, 'Vinicius Bezerra', 'viniciusg21bezerra@gmail.com', NULL, '$2y$10$GN95IUkEUSLKnXODaMevxeaXExWDPKpc4.zM2u89VuWQF9YYhysE6', 'aluno');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alunos`
--
ALTER TABLE `alunos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `matricula` (`matricula`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Indexes for table `documentos`
--
ALTER TABLE `documentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_projeto` (`id_projeto`);

--
-- Indexes for table `imagens`
--
ALTER TABLE `imagens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_projeto` (`id_projeto`);

--
-- Indexes for table `orientadores`
--
ALTER TABLE `orientadores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `projetos`
--
ALTER TABLE `projetos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_projetos_orientador` (`id_orientador`),
  ADD KEY `idx_projetos_area` (`id_area`),
  ADD KEY `idx_projetos_status` (`status`);

--
-- Indexes for table `projetos_alunos`
--
ALTER TABLE `projetos_alunos`
  ADD PRIMARY KEY (`id_projeto`,`id_aluno`),
  ADD KEY `idx_projetos_alunos_projeto` (`id_projeto`),
  ADD KEY `idx_projetos_alunos_aluno` (`id_aluno`);

--
-- Indexes for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `expira_em` (`expira_em`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `descricao` (`descricao`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alunos`
--
ALTER TABLE `alunos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
--
-- AUTO_INCREMENT for table `areas`
--
ALTER TABLE `areas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `documentos`
--
ALTER TABLE `documentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
--
-- AUTO_INCREMENT for table `imagens`
--
ALTER TABLE `imagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;
--
-- AUTO_INCREMENT for table `orientadores`
--
ALTER TABLE `orientadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT for table `projetos`
--
ALTER TABLE `projetos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;
--
-- AUTO_INCREMENT for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `documentos`
--
ALTER TABLE `documentos`
  ADD CONSTRAINT `documentos_ibfk_1` FOREIGN KEY (`id_projeto`) REFERENCES `projetos` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `imagens`
--
ALTER TABLE `imagens`
  ADD CONSTRAINT `imagens_ibfk_1` FOREIGN KEY (`id_projeto`) REFERENCES `projetos` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `projetos`
--
ALTER TABLE `projetos`
  ADD CONSTRAINT `projetos_ibfk_1` FOREIGN KEY (`id_orientador`) REFERENCES `orientadores` (`id`),
  ADD CONSTRAINT `projetos_ibfk_2` FOREIGN KEY (`id_area`) REFERENCES `areas` (`id`),
  ADD CONSTRAINT `projetos_ibfk_3` FOREIGN KEY (`status`) REFERENCES `status` (`id`);

--
-- Limitadores para a tabela `projetos_alunos`
--
ALTER TABLE `projetos_alunos`
  ADD CONSTRAINT `projetos_alunos_ibfk_1` FOREIGN KEY (`id_projeto`) REFERENCES `projetos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `projetos_alunos_ibfk_2` FOREIGN KEY (`id_aluno`) REFERENCES `alunos` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD CONSTRAINT `remember_tokens_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
