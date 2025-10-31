-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 31-Out-2025 às 00:54
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
(1, 'Vinicius Bezerra', '3241', 'vini@email.com'),
(2, 'Vinícius Andrade', 'A2025001', 'vinicius.andrade@aluno.senai.br'),
(3, 'Lucas Oliveira', 'A2025002', 'lucas.oliveira@aluno.senai.br'),
(4, 'Beatriz Souza', 'A2025003', 'beatriz.souza@aluno.senai.br'),
(5, 'Gabriel Lima', 'A2025004', 'gabriel.lima@aluno.senai.br'),
(6, 'Maria Eduarda Ferreira', 'A2025005', 'maria.ferreira@aluno.senai.br'),
(7, 'João Pedro Nunes', 'A2025006', 'joao.nunes@aluno.senai.br'),
(8, 'Ana Clara Silva', 'A2025007', 'ana.silva@aluno.senai.br'),
(9, 'Pedro Henrique Rocha', 'A2025008', 'pedro.rocha@aluno.senai.br'),
(10, 'Julia Martins', 'A2025009', 'julia.martins@aluno.senai.br'),
(11, 'Caio Almeida', 'A2025010', 'caio.almeida@aluno.senai.br');

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
-- Estrutura da tabela `imagens`
--

CREATE TABLE `imagens` (
  `id` int(11) NOT NULL,
  `id_projeto` int(11) NOT NULL,
  `caminho` varchar(255) NOT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
(1, 'Professor', 'professor@email.com'),
(2, 'Prof. Carlos Henrique da Silva', 'carlos.silva@senai.br'),
(3, 'Profa. Marina Alves Pereira', 'marina.pereira@senai.br'),
(4, 'Prof. Eduardo Lima Santos', 'eduardo.santos@senai.br'),
(5, 'Profa. Fernanda Souza Ribeiro', 'fernanda.ribeiro@senai.br'),
(6, 'Prof. João Victor Fernandes', 'joao.fernandes@senai.br');

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

-- --------------------------------------------------------

--
-- Estrutura da tabela `projetos_alunos`
--

CREATE TABLE `projetos_alunos` (
  `id_projeto` int(11) NOT NULL,
  `id_aluno` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `senha` varchar(255) NOT NULL,
  `tipo` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `tipo`) VALUES
(1, 'Vinicius Bezerra', 'vini@email.com', '$2y$10$UTy0gR5hk8e4Aa.S6IYGve0NXWEvOrijQbbtqX0UyX4tSF9KFOiyq', 'aluno'),
(2, 'Professor', 'professor@email.com', '$2y$10$Ikr0B8idls8cebc7J0peCuegLbMfowTfvXrO49yPZriPBFg510RiK', 'orientador');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `areas`
--
ALTER TABLE `areas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `imagens`
--
ALTER TABLE `imagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `orientadores`
--
ALTER TABLE `orientadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `projetos`
--
ALTER TABLE `projetos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Constraints for dumped tables
--

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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
