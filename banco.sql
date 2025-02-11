-- Criação do banco de dados
CREATE DATABASE hotel;

-- Seleção do banco de dados para uso
USE hotel;

-- Criação da tabela 'reservas'
CREATE TABLE reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,  -- Chave primária única para cada reserva
    id_hospede INT,  -- Identificador do hospede
     /* nome_cliente VARCHAR(255) NOT NULL,  -- Nome do cliente (não pode ser nulo)*/
    data_checkin DATE NOT NULL,         -- Data de check-in (não pode ser nula)
    data_checkout DATE NOT NULL,        -- Data de check-out (não pode ser nula)
    numero_quarto INT NOT NULL,         -- Número do quarto (não pode ser nulo)
    tipo_quarto ENUM('standard','luxo',
          'suite', 'familia') NOT NULL, -- Tipo de quarto (com valores limitados)
    observacoes TEXT,                   -- Observações adicionais (campo opcional)
    CONSTRAINT tipo_quarto_check CHECK (tipo_quarto IN ('standard', 'luxo', 'suite', 'familia')),
    FOREIGN KEY (numero_quarto) REFERENCES quartos(numero),
    FOREIGN KEY (id_hospede) REFERENCES hospedes(id)
);

                                             /*** VER SE PRECISA MUDAR ***/
 -- tabela para os usuarios 2 tipos, 
 -- 1 - comun (relativos a atendimento a cliente)
 -- 2 - adm (excluir contas, alterar senhas, etc)
   
  CREATE TABLE login ( -- o login só pode ser feito apos o funcionario ser cadastrado pelo adm
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_funcionario INT NOT NULL,
    nome VARCHAR(255) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    tipo ENUM('comun', 'adm') NOT NULL,
    FOREGIN KEY (id_funcionario) REFERENCES funcionarios(id)
);

-- tabela hospedes para os cadastros de clientes
CREATE TABLE hospedes ( 
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL unique, -- não é possivel ter dois registros com o mesmo email, se possivel usar o email para enviar atualizações da reserva
    data_nascimento DATE NOT NULL, -- para usar no dashboard 
    );

-- tabela para gestão checkout
CREATE TABLE checkout (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_reserva INT NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    forma_pagamento ENUM('dinheiro', 'cartão', 'pix', 'outros') NOT NULL,
    data_pagamento DATE NOT NULL,
    danos_quarto text default ('não houveram danos') , -- campo para registrar danos no quarto 
    multa DECIMAL(10,2) default (0.00), -- campo para registrar valor das multas
    FOREIGN KEY (id_reserva) REFERENCES reservas(id)
    );


-- tabela para gestão de quartos (quais quartos estão disponíveis) USAR TRIGGER PARA MUDAR disponivel, quando o quarto for reservado ou liberado 
 CREATE TABLE quartos (
id INT AUTO_INCREMENT PRIMARY KEY,
numero INT NOT NULL, -- Número do quarto ou sigla a depender da regra de negocio 
andar INT NOT NULL, -- Andar do quarto *** SE PRECISA NO SEU CASO ***
bloco INT NOT NULL, -- Bloco do quarto *** SE PRECISA NO SEU CASO *** 
tipo ENUM('standard', 'luxo', 'suite', 'familia') NOT NULL, 
disponivel TINYINT(1) NOT NULL DEFAULT 1, -- 0 = ocupado
observacoes text
);


-- tabela para gestão de funcionarios dados pessoais e contatos, salario
CREATE TABLE funcionarios (
id INT AUTO_INCREMENT PRIMARY KEY,
nome VARCHAR(255) NOT NULL,
data_admissao DATE NOT NULL,
data_demissao DATE default (NULL), -- campo para registrar data de demissao atualizado na demissao
motivo_demissao text default (null), -- campo para registrar motivo de demissao
telefone VARCHAR(20) NOT NULL,
email VARCHAR(100) NOT NULL,
cargo VARCHAR(100) NOT NULL,
salario_bruto DECIMAL(10,2) NOT NULL 
);


-- tabela para despesas fixas mensais (alugueis, etc) pegar  USAR PROCEDURE PARA CRIAR UM RELACIONAMENTO ENTRE AS DESPESAS E O MÊS
/* 
 CREATE TABLE despesas ( -- tem q ver as que se vocês têm
*/

-- tabela folha de pagamento
CREATE TABLE folha_pagamento (
  id_folha_pagamento INT PRIMARY KEY,
  id_funcionario INT,
  data_pagamento DATE,
  salario_bruto DECIMAL(10, 2), 
  desconto_inss DECIMAL(10, 2), -- esses valores são calculados pelo sistema com base no salario do funcionario
  desconto_irpf DECIMAL(10, 2), --
  desconto_fgts DECIMAL(10, 2), --
  desconto_vale_refeicao DECIMAL(10, 2), -- ver como se calcula isso
  desconto_vale_transporte DECIMAL(10, 2), -- ver como se calcula isso
  salario_liquido DECIMAL(10, 2),
  FOREIGN KEY (id_funcionario) REFERENCES funcionario(id_funcionario)
);

-- tabela para  outros descontos no salarios 
CREATE TABLE descontos (  -- esse valores vem do front end e mudam o valor do salario liquido antes de salvar no banco
  id_desconto INT PRIMARY KEY,
  id_folha_pagamento INT,
  tipo_desconto VARCHAR(50), -- tipo de desconto
  valor_desconto DECIMAL(10, 2), -- valor do desconto
  FOREIGN KEY (id_folha_pagamento) REFERENCES folha_pagamento(id_folha_pagamento)
);

-- tabela para beneficios oferecidos aos funcionarios
CREATE TABLE beneficios ( -- esse valores vem do front end e mudam o valor do salario liquido antes de salvar no banco
  id_beneficio INT PRIMARY KEY,
  id_folha_pagamento INT,
  tipo_beneficio VARCHAR(50), -- tipo de beneficio
  valor_beneficio DECIMAL(10, 2), -- valor do beneficio
  FOREIGN KEY (id_folha_pagamento) REFERENCES folha_pagamento(id_folha_pagamento)
);

-- tabela para financeiro (check-out, salario, etc) ao mes, USAR VIEW PARA EXIBIR LUCRO E DESPESAS POR MES/ANO
    -- não sei bem como fazer isso, mas acho que seria criar uma view que pegue os dados da folha e check out
    -- retornando isso em um dashboard


    