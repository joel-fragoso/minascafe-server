# Minas Café - Servidor do Cardápio

![build-test](https://github.com/joel-fragoso/minascafe-server/actions/workflows/ci.yml/badge.svg)

[Como Clonar](#️como-clonar-o-projeto) |
[Como Instalar](#️como-instalar-as-dependências) |
[Como Executar](#️como-executar-a-aplicação) |
[Como Limpar Cache](#️como-limpar-o-cache-do-doctrine-orm) |
[Como Executar as Migrações](#como-executar-as-migrações) |
[Como Executar Analisador](#como-executar-o-analisador-de-código) |
[Como Executar Estilizador](#como-executar-o-estilizador-de-código)
[Como Executar Testes](#como-executar-suite-de-testes)
[Como Executar Coverage](#como-executar-suite-de-testes-com-coverage)

---

### 💿️ Como clonar o projeto:
```bash
# SSH
$ git clone git@github.com:joel-fragoso/minascafe-server.git

# HTTPS
$ git clone https://github.com/joel-fragoso/minascafe-server.git
```

### 🎉️ Como instalar as dependências:
```bash
$ composer install
```

### 🚀️ Como executar a aplicação:
```bash
$ composer dev
```

### ♻️ Como limpar o cache do Doctrine ORM:
```bash
$ composer orm:clear-cache
```

### 💽️ Como executar as migrações:
```bash
$ composer migrations:migrate
```

### 🚀️ Como executar o analisador de código:
```bash
$ composer analyse
```

### 🚀️ Como executar o estilizador de código:
```bash
$ composer cs-check
```

### 🚀️ Como executar suite de testes:
```bash
$ composer test
```

### 🚀️ Como executar suite de testes com coverage:
```bash
$ composer test:coverage
```

---

Desenvolvido com ❤️ por Fragoso Brothers.
