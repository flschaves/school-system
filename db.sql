CREATE TABLE usuarios (
    ID INT(11) NOT NULL,
    usuario VARCHAR(40) NOT NULL,
    senha VARCHAR(64) NOT NULL,
    tipo VARCHAR(20) NOT NULL,
    nome VARCHAR(45) NOT NULL,
    nascimento DATE NOT NULL,
    RG VARCHAR(20) NOT NULL,
    CPF VARCHAR(20) NOT NULL,
    endereco VARCHAR(200) NOT NULL,
    bairro VARCHAR(50),
    cidade VARCHAR(100) NOT NULL,
    complemento VARCHAR(200),
    telefone VARCHAR(20) NOT NULL,
    curso INT(11),
    tempo_esp INT(11),
    PRIMARY KEY (ID)
);

CREATE TABLE cursos (
    ID INT(11) NOT NULL,
    professor INT(11) NOT NULL,
    nome VARCHAR(45) NOT NULL,
    descricao VARCHAR(200),
    numero_sala VARCHAR(10),
    valor_mensalidade VARCHAR(20),
    PRIMARY KEY (ID),
    FOREIGN KEY (professor) REFERENCES usuarios(ID)
);

CREATE TABLE notas (
    ID INT(11) NOT NULL,
    usuario_id INT(11) NOT NULL,
    curso_id INT(11) NOT NULL,
    data_avaliacao DATE NOT NULL,
    nota DOUBLE(2,1) NOT NULL,
    PRIMARY KEY (ID),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(ID),
    FOREIGN KEY (curso_id) REFERENCES cursos(ID)
);