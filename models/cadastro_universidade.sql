-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 03-Nov-2025 às 14:55
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
(3, 'Lucas Oliveira', 'A2025002', 'lucas.oliveira@aluno.senai.br'),
(4, 'Beatriz Souza', 'A2025003', 'beatriz.souza@aluno.senai.br'),
(5, 'Gabriel Lima', 'A2025004', 'gabriel.lima@aluno.senai.br'),
(6, 'Maria Eduarda Ferreira', 'A2025005', 'maria.ferreira@aluno.senai.br'),
(7, 'João Pedro Nunes', 'A2025006', 'joao.nunes@aluno.senai.br'),
(8, 'Ana Clara Silva', 'A2025007', 'ana.silva@aluno.senai.br'),
(9, 'Pedro Henrique Rocha', 'A2025008', 'pedro.rocha@aluno.senai.br'),
(10, 'Julia Martins', 'A2025009', 'julia.martins@aluno.senai.br'),
(11, 'Caio Almeida', 'A2025010', 'caio.almeida@aluno.senai.br'),
(17, 'Vinicius Gabriel Bezerra', 'A1234143', 'viniciusg21bezerra@gmail.com');

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
(3, 26, 'Pasta2.xlsx', '6908b66b0ab74_Pasta2.xlsx', 'xlsx', 15116, '', '2025-11-03 14:04:27');

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
(6, 'Prof. João Victor Fernandes', 'joao.fernandes@senai.br'),
(7, 'Aluno novo', 'aluno@novo.com');

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
(26, 'Teste de Projeto do vini', 'Teste de Projeto, talvez o site ja esteja pronto!!!!!1', 2, 5, 7, '2025-11-03 14:04:27', '6908b66b09976.png');

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
(26, 17);

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
(3, 9, '59a96dd6f4dc5b1c1d4ea4b753ebe2e41670179bfecf9cbcd1f35eae19a9b469', '2025-12-03 13:56:04', '2025-11-03 13:56:04');

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
(9, 'Vinicius Gabriel Bezerra', 'viniciusg21bezerra@gmail.com', 'perfil_9_1762178598.png', '$2y$10$X4w0p/MzQx7GYGZHDSs2Yuu53dQ5PLVrc9bAlF082S7vhCPGtXvbq', 'aluno');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `areas`
--
ALTER TABLE `areas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `documentos`
--
ALTER TABLE `documentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `imagens`
--
ALTER TABLE `imagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `orientadores`
--
ALTER TABLE `orientadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `projetos`
--
ALTER TABLE `projetos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
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
