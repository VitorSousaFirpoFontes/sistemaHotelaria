-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 18/02/2025 às 17:27
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `hotel`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `despesas`
--

CREATE TABLE `despesas` (
  `id` int(11) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens`
--

CREATE TABLE `itens` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `quantidade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `relatoriosfinanceiros`
--

CREATE TABLE `relatoriosfinanceiros` (
  `id` int(11) NOT NULL,
  `id_reserva` int(11) NOT NULL,
  `valor_diario` decimal(10,2) NOT NULL,
  `valor_total` decimal(10,2) NOT NULL,
  `lucro_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `data_pagamento` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `relatoriosfinanceiros`
--

INSERT INTO `relatoriosfinanceiros` (`id`, `id_reserva`, `valor_diario`, `valor_total`, `lucro_total`, `data_pagamento`) VALUES
(1, 1, 33.00, 264.00, 0.00, '2025-02-14'),
(2, 2, 30.00, 30.00, 0.00, '2025-02-14');

-- --------------------------------------------------------

--
-- Estrutura para tabela `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `nome_cliente` varchar(255) NOT NULL,
  `data_checkin` date NOT NULL,
  `data_checkout` date NOT NULL,
  `numero_quarto` int(11) NOT NULL,
  `tipo_quarto` varchar(100) NOT NULL,
  `valor_diaria` decimal(10,2) NOT NULL,
  `observacoes` text DEFAULT NULL,
  `valor_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `reservas`
--

INSERT INTO `reservas` (`id`, `nome_cliente`, `data_checkin`, `data_checkout`, `numero_quarto`, `tipo_quarto`, `valor_diaria`, `observacoes`, `valor_total`) VALUES
(1, 'scscs', '2025-10-02', '2025-10-10', 3, 'standard', 33.00, '', 264.00),
(2, 'da', '2025-10-09', '2025-10-10', 10, 'standard', 30.00, '', 30.00),
(3, 'cc', '2025-10-02', '2026-02-10', 3, 'standard', 333.00, '', 43623.00),
(4, 'dddd', '2025-02-10', '2025-02-15', 8, 'standard', 333.00, '', 1665.00);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `despesas`
--
ALTER TABLE `despesas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `itens`
--
ALTER TABLE `itens`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `relatoriosfinanceiros`
--
ALTER TABLE `relatoriosfinanceiros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_reserva` (`id_reserva`);

--
-- Índices de tabela `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `despesas`
--
ALTER TABLE `despesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `itens`
--
ALTER TABLE `itens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `relatoriosfinanceiros`
--
ALTER TABLE `relatoriosfinanceiros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `relatoriosfinanceiros`
--
ALTER TABLE `relatoriosfinanceiros`
  ADD CONSTRAINT `relatoriosfinanceiros_ibfk_1` FOREIGN KEY (`id_reserva`) REFERENCES `reservas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
