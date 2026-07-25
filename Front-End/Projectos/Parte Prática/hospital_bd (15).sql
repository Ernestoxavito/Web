-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 16-Jun-2025 às 01:58
-- Versão do servidor: 10.4.28-MariaDB
-- versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `hospital_bd`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `consulta`
--

CREATE TABLE `consulta` (
  `id_consulta` int(11) NOT NULL,
  `nif_paciente` varchar(14) NOT NULL,
  `nome_paciente` varchar(100) NOT NULL,
  `dataa` date DEFAULT NULL,
  `genero` varchar(20) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `alergias` varchar(100) DEFAULT NULL,
  `condicoes` varchar(100) DEFAULT NULL,
  `medicacoes` varchar(100) DEFAULT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `especialidade` varchar(100) DEFAULT NULL,
  `autorizacao` tinyint(1) DEFAULT 0,
  `foto_bi1` varchar(255) DEFAULT NULL,
  `foto_bi2` varchar(255) DEFAULT NULL,
  `status` enum('pendente','confirmada','realizada','cancelada') DEFAULT 'pendente',
  `data_consulta` date DEFAULT NULL,
  `hora_consulta` time DEFAULT NULL,
  `nif_medico` varchar(14) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `consulta`
--

INSERT INTO `consulta` (`id_consulta`, `nif_paciente`, `nome_paciente`, `dataa`, `genero`, `telefone`, `email`, `endereco`, `alergias`, `condicoes`, `medicacoes`, `motivo`, `especialidade`, `autorizacao`, `foto_bi1`, `foto_bi2`, `status`, `data_consulta`, `hora_consulta`, `nif_medico`) VALUES
(9, '00LA0038998098', 'cristina', '2025-05-29', 'masculino', '923546789', 'mucana744@gmail.com', 'camama', 'não', 'não', 'não', 'confusa', 'Clínica Geral', 1, 'uploads/nolove.jpg', 'uploads/nolove.jpg', 'confirmada', '2025-06-19', '20:39:00', '00LA0038933433'),
(10, '00LA0038998098', 'cristina', '2025-05-29', 'masculino', '923546789', 'mucana744@gmail.com', 'camama', 'não', 'não', 'não', 'dor de cabeca', 'Clínica Geral', 1, 'uploads/WhatsApp Image 2025-05-28 at 19.52.53.jpeg', 'uploads/transferir.jpg', 'realizada', '2025-06-15', '01:17:00', '00LA0038933433'),
(11, '00LA0038998098', 'cristina', '2025-05-29', 'masculino', '923546789', 'mucana744@gmail.com', 'camama', 'não', 'não', 'não', 'dor de cabeca e transtornos', 'Clínica Geral', 1, 'uploads/transferir.jpg', 'uploads/WhatsApp Image 2025-05-28 at 19.52.53.jpeg', 'realizada', '2025-06-15', '01:30:00', '00LA0038933433'),
(12, '00LA0038998098', 'cristina', '2025-05-29', 'feminino', '923546789', 'mucana744@gmail.com', 'camama', 'não', 'não', 'não', 'confusa e decepcionada', 'Clínica Geral', 1, 'uploads/transferir.jpg', 'uploads/WhatsApp Image 2025-05-28 at 19.52.53.jpeg', 'confirmada', '2025-06-15', '01:35:00', '00LA0038933433'),
(13, '00LA0038998098', 'cristina', '2025-05-29', 'masculino', '923546789', 'mucana744@gmail.com', 'camama', 'não', 'não', 'não', 'confu', 'Clínica Geral', 1, 'uploads/transferir.jpg', 'uploads/nolove.jpg', 'confirmada', '2025-06-17', '05:35:00', '00LA0038933433'),
(14, '00LA0038998098', 'cristina', '2025-05-29', 'masculino', '923546789', 'mucana744@gmail.com', 'camama', 'não', 'não', 'não', 'shjgf', 'Pediatria', 1, 'uploads/transferir.jpg', 'uploads/nolove.jpg', 'confirmada', '2025-06-17', '04:38:00', '123456789LA129');

-- --------------------------------------------------------

--
-- Estrutura da tabela `funcionarios`
--

CREATE TABLE `funcionarios` (
  `nif` varchar(14) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `cargo` varchar(50) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `dataa` date DEFAULT NULL,
  `genero` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `funcionarios`
--

INSERT INTO `funcionarios` (`nif`, `nome`, `email`, `telefone`, `cargo`, `endereco`, `dataa`, `genero`) VALUES
('00LA0038353457', 'Kelton', 'kg@gmail.com', '999999994535234', 'Recepcionista', 'camama', '2025-05-28', 'Masculino');

-- --------------------------------------------------------

--
-- Estrutura da tabela `horarios`
--

CREATE TABLE `horarios` (
  `id` int(11) NOT NULL,
  `nif_medico` varchar(14) NOT NULL,
  `horario` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `horarios`
--

INSERT INTO `horarios` (`id`, `nif_medico`, `horario`) VALUES
(1, '00LA0038999999', '22:53:00'),
(2, '00LA0038977899', '20:39:00'),
(3, '123456789LA129', '22:49:00'),
(4, '00LA0038933433', '05:22:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `medico`
--

CREATE TABLE `medico` (
  `nif` varchar(14) NOT NULL,
  `especialidade` varchar(100) NOT NULL,
  `crm` varchar(20) NOT NULL,
  `contato` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `turno` varchar(20) DEFAULT NULL,
  `genero` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `medico`
--

INSERT INTO `medico` (`nif`, `especialidade`, `crm`, `contato`, `email`, `endereco`, `turno`, `genero`) VALUES
('00LA0038933433', 'Clínica Geral', '999A989', '999999800', 'testem@gmail.com', 'Luanda-Patriota-Honga', 'noturno', 'Masculino'),
('00LA0038977899', 'Clínica Geral', '99hoo9u', '923546789', 'kentrel@gmail.com', 'Luanda-Patriota-Honga', 'ambos', 'Masculino'),
('00LA0038999999', 'Clínica Geral', '999A9S0', '999999999', 'cristianomucana@icloud.com', 'camama', 'noturno', 'Masculino'),
('123456789LA129', 'Pediatria', '999A9S1', '999999999', 'cristianomu@icloud.com', 'camama', 'noturno', 'Femenino');

-- --------------------------------------------------------

--
-- Estrutura da tabela `paciente`
--

CREATE TABLE `paciente` (
  `nif` varchar(14) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `dataa` date DEFAULT NULL,
  `genero` varchar(20) DEFAULT NULL,
  `contato` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `alergias` varchar(100) DEFAULT NULL,
  `condicoes` varchar(100) DEFAULT NULL,
  `deficiencia` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `paciente`
--

INSERT INTO `paciente` (`nif`, `nome`, `dataa`, `genero`, `contato`, `email`, `endereco`, `alergias`, `condicoes`, `deficiencia`, `foto`) VALUES
('00LA0038989999', 'aurio menezes', '2006-03-01', 'masculino', '999999999', 'auriomenezes8@gmail.com', 'Luanda-Patriota-Honga', 'sim', 'sim', 'sim', '684d900f76b3a_nolove.jpg'),
('00LA0038998098', 'cristina', '2025-05-29', 'masculino', '923546789', 'cristina@gmail.com', 'camama', 'nao', 'nao', 'nao', '684e075abb400_WhatsApp Image 2025-05-28 at 19.52.53.jpeg');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tokens_recuperacao`
--

CREATE TABLE `tokens_recuperacao` (
  `id` int(11) NOT NULL,
  `nif` varchar(14) NOT NULL,
  `token` varchar(64) NOT NULL,
  `criado_em` datetime NOT NULL,
  `expirado_em` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `tokens_recuperacao`
--

INSERT INTO `tokens_recuperacao` (`id`, `nif`, `token`, `criado_em`, `expirado_em`) VALUES
(1, '00LA003898t9t9', '9758ffb8ffd69d0c7fabb05293a0b6012ce30c72de48178a37799d3b9d77ce50', '2025-06-14 15:12:22', '2025-06-14 17:12:22'),
(2, '00LA003898t9t9', '24f99cc8b140c4ba3c079774990d8c404d8e6df62ab4c7b6890507e8ad5ac5b2', '2025-06-14 15:17:12', '2025-06-14 17:17:12'),
(3, '00LA003898t9t9', 'c974f7ff8c9ab32c4f1655955724e4725429428cc8ee3eaa82069bb7a9feecf5', '2025-06-14 15:18:17', '2025-06-14 17:18:17'),
(4, '00LA003898t9t9', '475224f7fc70126a6d88d09cabe3fa4bcae7929fa1bf2182ce55b4e4626a9cfc', '2025-06-14 15:25:58', '2025-06-14 17:25:58'),
(5, '00LA003898t9t9', 'f85638a617c87dc4dc07a3ccbb80280060ab1787b4c32858dcdd925bef750143', '2025-06-14 15:29:57', '2025-06-14 17:29:57');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `nif` varchar(14) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `dataa` date DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `genero` varchar(20) DEFAULT NULL,
  `nivel_acesso` enum('admin','recepcionista','medico','paciente') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`nif`, `nome`, `email`, `senha`, `endereco`, `dataa`, `telefone`, `genero`, `nivel_acesso`) VALUES
('00000000000000', 'Administrador', 'admin@hospital.com', '$2y$10$wH8w8v8Qw8v8w8v8w8v8wO8w8v8w8v8w8v8w8v8w8v8w8v8w8v8w', 'Endereço do Hospital', '1980-01-01', '999999999', 'masculino', 'admin'),
('00000000000001', 'cristiano', 'admin2@hospital.com', '$2y$10$3FjcgeiET82MqjL5e/bvNOYaELDHti7Q8vzZ1dJDrJPJzMa4IMG8S', 'Endereço do Hospital', '1980-01-01', '999999999', 'masculino', 'admin'),
('00LA0038353457', 'Kelton', 'kg@gmail.com', '$2y$10$LbW9npzVbmThlBEO5GmEF.OUc27G2UkUPz7ZYja/f5FFxKNWl76g.', 'camama', NULL, '999999994535234', 'Masculino', 'recepcionista'),
('00LA0038933433', 'delson', 'testem@gmail.com', '$2y$10$kAnLReGKdg9w.FDkubwVneIFJi3.5MoGbNioVfahK44CcO//YuomS', 'Luanda-Patriota-Honga', NULL, '999999800', 'Masculino', 'medico'),
('00LA0038968476', 'Kelton', 'kelton20@gmail.com', '$2y$10$zvC6qj74k7dREyj.ZaCtserqPu8wN6n0lXtpEeaJlYFGsCe1OP386', 'camama', '2025-05-29', '9235467855', 'masculino', 'recepcionista'),
('00LA0038973969', 'Kelton', 'kelton23@gmail.com', '$2y$10$sHeiTSHEhbNf5InApLgzFuSAmOZhhPGPZ5FrnQnQ6mAka/wsA8M1e', 'Luanda-Belas-Cabolombo', '2007-09-16', '999999999999999', 'masculino', 'recepcionista'),
('00LA0038976395', 'Kelton', 'keltong@gmail.com', '$2y$10$icwbcbw6bqBeOYTH2uagCekKVSjl5nJudTdsIosD9mQvO8zO8wVtq', 'camama', NULL, '9999999934', 'Masculino', 'recepcionista'),
('00LA0038977563', 'Kelton', 'k@gmail.com', '$2y$10$wajDbQXmnA8iE9pzGjaGVuti5sVE.BHlxA/d2Swsz72BV.uidzSB2', 'jkjj', '2025-06-04', '9999999993', 'masculino', 'recepcionista'),
('00LA0038977899', 'zelma', 'kentrel@gmail.com', '$2y$10$OaI/OpQ0F8IHrslwxvzepeEhp6JtHNjXxnc1YYII7zrZLjkTym8eK', 'Luanda-Patriota-Honga', NULL, '923546789', 'Masculino', 'medico'),
('00LA0038978756', 'colina', 'tester@gmail.com', '$2y$10$wx6j9pfvwEEPQ8bI3Jwiqelt8p7HOywOL9IjehcniBX7G2/K2VJcm', 'camama', '2025-05-28', '348234899', 'masculino', 'recepcionista'),
('00LA0038989999', 'aurio menezes', 'auriomenezes8@gmail.com', '$2y$10$biToJy2ub58w1Er4e1BRVufOeSfysEKko5gpRpU18hKD6OD7HZsRK', 'Luanda-Patriota-Honga', '2006-03-01', '999999999', 'masculino', 'paciente'),
('00LA003898t9t9', 'kentrel', 'mucana744@gmail.com', '$2y$10$3J44U.RaFWcpcFlMmYIVCeYyaLoGW.pzY.SG2SVhZNGRRodV5YqWW', 'Luanda-Belas-Cabolombo', '2025-05-27', '92354678800', 'masculino', 'recepcionista'),
('00LA0038990080', 'Kelton', 'kelton@gmail.com', '$2y$10$ZSiA83cq9Nkd1pabaJUOi.srRz.RyGukiNQtj51u9PGQ/oxtUbY66', 'camama', '2025-05-28', '999999999', 'masculino', 'recepcionista'),
('00LA0038998098', 'cristina', 'cristina@gmail.com', '$2y$10$8g./BtujduLffO0HhBJcq.C05jxAXgbkVmIFB8YSbdLinOyRLxyk.', 'camama', '2025-05-29', '923546789', 'masculino', 'paciente'),
('00LA0038999999', 'cristiano', 'cristianomucana@icloud.com', '$2y$10$.JbYPi51OdMVbICbu6ZUbOoqV4Qm67o9VqsFX7hhH/24SfltqJpCm', 'camama', NULL, '999999999', 'Masculino', 'medico'),
('123456789LA129', 'zelma', 'cristianomu@icloud.com', '$2y$10$DEPsEjyzznvhnAcfOViuRuCU7IqP9rsvuFMkXrzJMh4tLL76nlzW.', 'camama', NULL, '999999999', 'Femenino', 'medico');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `consulta`
--
ALTER TABLE `consulta`
  ADD PRIMARY KEY (`id_consulta`),
  ADD KEY `nif_paciente` (`nif_paciente`),
  ADD KEY `nif_medico` (`nif_medico`);

--
-- Índices para tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  ADD PRIMARY KEY (`nif`);

--
-- Índices para tabela `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nif_medico` (`nif_medico`);

--
-- Índices para tabela `medico`
--
ALTER TABLE `medico`
  ADD PRIMARY KEY (`nif`);

--
-- Índices para tabela `paciente`
--
ALTER TABLE `paciente`
  ADD PRIMARY KEY (`nif`);

--
-- Índices para tabela `tokens_recuperacao`
--
ALTER TABLE `tokens_recuperacao`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`nif`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `consulta`
--
ALTER TABLE `consulta`
  MODIFY `id_consulta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `tokens_recuperacao`
--
ALTER TABLE `tokens_recuperacao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `consulta`
--
ALTER TABLE `consulta`
  ADD CONSTRAINT `consulta_ibfk_1` FOREIGN KEY (`nif_paciente`) REFERENCES `paciente` (`nif`) ON DELETE CASCADE,
  ADD CONSTRAINT `consulta_ibfk_2` FOREIGN KEY (`nif_medico`) REFERENCES `medico` (`nif`) ON DELETE SET NULL;

--
-- Limitadores para a tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  ADD CONSTRAINT `funcionarios_ibfk_1` FOREIGN KEY (`nif`) REFERENCES `usuarios` (`nif`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `horarios`
--
ALTER TABLE `horarios`
  ADD CONSTRAINT `horarios_ibfk_1` FOREIGN KEY (`nif_medico`) REFERENCES `medico` (`nif`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `medico`
--
ALTER TABLE `medico`
  ADD CONSTRAINT `medico_ibfk_1` FOREIGN KEY (`nif`) REFERENCES `usuarios` (`nif`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `paciente`
--
ALTER TABLE `paciente`
  ADD CONSTRAINT `paciente_ibfk_1` FOREIGN KEY (`nif`) REFERENCES `usuarios` (`nif`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
