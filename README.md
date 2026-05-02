# cpfhub/cpfhub-php: PHP SDK for CPFHub.io

🇺🇸 **English** | [🇧🇷 Português](#português)

**Official PHP SDK for [CPFHub.io](https://cpfhub.io) — Brazilian CPF Lookup API**

[![Packagist Version](https://img.shields.io/packagist/v/cpfhub/cpfhub-php)](https://packagist.org/packages/cpfhub/cpfhub-php)
[![PHP](https://img.shields.io/packagist/php-v/cpfhub/cpfhub-php)](https://packagist.org/packages/cpfhub/cpfhub-php)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)

---

## What is CPFHub.io?

CPFHub.io is a REST API that returns name, gender, and date of birth from any Brazilian CPF number — in ~300ms, with 99.9% uptime and full LGPD compliance.

**10M+ CPFs queried · 1,300+ active companies · 99.9% uptime**

---

## Installation

```bash
composer require cpfhub/cpfhub-php
```

---

## Quick Start

```php
<?php

use CPFHub\Client;

$client = new Client("YOUR_API_KEY");

$result = $client->lookup("00000000000");

echo $result->name;      // "Fulano de Tal"
echo $result->gender;    // "M"
echo $result->birthDate; // "15/06/1990"
```

Get your free API key at [app.cpfhub.io](https://app.cpfhub.io) — no credit card required.

---

## curl Example

```bash
curl -X GET "https://api.cpfhub.io/cpf/12345678909" \
  -H "x-api-key: YOUR_API_KEY"
```

**Response:**

```json
{
  "success": true,
  "data": {
    "cpf": "12345678909",
    "name": "Fulano de Tal",
    "nameUpper": "FULANO DE TAL",
    "gender": "M",
    "birthDate": "15/06/1990",
    "day": 15,
    "month": 6,
    "year": 1990
  }
}
```

---

## API Reference

### `new Client(string $apiKey, array $options = [])`

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `timeout` | `int` | `10` | Request timeout in seconds |
| `base_url` | `string` | `https://api.cpfhub.io` | API base URL |

### `$client->lookup(string $cpf): CPFResult`

Looks up a CPF and returns the associated identity data.

Accepts CPF with or without formatting (`000.000.000-00` or `00000000000`).

#### `CPFResult` properties

| Property | Type | Description |
|----------|------|-------------|
| `cpf` | `string` | CPF number (digits only) |
| `name` | `string` | Full name — `"Fulano de Tal"` |
| `nameUpper` | `string` | Full name in uppercase |
| `gender` | `string` | `"M"` or `"F"` |
| `birthDate` | `string` | Date of birth — `"DD/MM/YYYY"` |
| `day` | `int` | Birth day |
| `month` | `int` | Birth month |
| `year` | `int` | Birth year |

---

## Error Handling

```php
<?php

use CPFHub\Client;
use CPFHub\Exceptions\CPFHubException;

$client = new Client("YOUR_API_KEY");

try {
    $result = $client->lookup("00000000000");
    echo $result->name;
} catch (CPFHubException $e) {
    echo "Error {$e->getStatusCode()}: {$e->getMessage()}";
    // 400 — Invalid CPF format
    // 401 — Invalid or missing API key
    // 404 — CPF not found
    // 429 — Rate limit exceeded
    // 500 — Server error
    // 503 — Service temporarily unavailable
}
```

---

## Examples

Check the `examples/` directory for sample usage:

- [simple_lookup.php](examples/simple_lookup.php)
- [real_world_onboarding.php](examples/real_world_onboarding.php)

### Vanilla PHP

```php
<?php

use CPFHub\Client;

$client = new Client($_ENV["CPFHUB_API_KEY"], ["timeout" => 5]);
$result = $client->lookup("00000000000");

echo $result->name;
```

### Laravel

```php
// config/services.php
'cpfhub' => [
    'key' => env('CPFHUB_API_KEY'),
],
```

```php
// app/Services/CPFService.php
use CPFHub\Client;

class CPFService
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client(config('services.cpfhub.key'));
    }

    public function lookup(string $cpf)
    {
        return $this->client->lookup($cpf);
    }
}
```

```php
// In a controller
use App\Services\CPFService;

class OnboardingController extends Controller
{
    public function verify(Request $request, CPFService $cpf)
    {
        $result = $cpf->lookup($request->input('cpf'));
        return response()->json(['name' => $result->name]);
    }
}
```

### Symfony

```php
// src/Service/CPFService.php
use CPFHub\Client;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class CPFService
{
    private Client $client;

    public function __construct(
        #[Autowire('%env(CPFHUB_API_KEY)%')] string $apiKey
    ) {
        $this->client = new Client($apiKey);
    }

    public function lookup(string $cpf)
    {
        return $this->client->lookup($cpf);
    }
}
```

---

## Rate Limits

| Plan | Limit |
|---|---|
| Free | 1 request every 2 seconds · 50 requests/month |
| Pro | 1 request per second · 1,000 requests/month |
| Corporate | Custom |

The SDK automatically retries on `429` with exponential backoff (up to 3 attempts).

---

## Plans & Pricing

| Plan | Price | Included | Extra |
|------|-------|----------|-------|
| **Free** | R$ 0/month | 50 lookups | — |
| **Pro** | R$ 149/month | 1,000 lookups | R$ 0,15/lookup |
| **Corporate** | Custom | Custom | Custom |

[View full pricing at cpfhub.io →](https://cpfhub.io#pricing)

---

## Requirements

- PHP 8.1+
- `guzzlehttp/guzzle` — installed automatically

---

## Links

- [Documentation](https://cpfhub.io/documentacao)
- [Dashboard](https://app.cpfhub.io)
- [Status Page](https://app.cpfhub.io/status)
- [Pricing](https://cpfhub.io#pricing)
- [LGPD Compliance](https://cpfhub.io/lgpd)
- [OpenAPI Specification](https://github.com/cpfhub/cpfhub-openapi/blob/main/openapi.yaml)
- [MCP Server (AI Agents)](https://github.com/cpfhub/cpfhub-mcp)

---

## License

MIT © [CPFHub.io](https://cpfhub.io)

---

# Português

[🇺🇸 English](#cpfhubcpfhub-php-php-sdk-for-cpfhubio) | 🇧🇷 **Português**

**SDK PHP oficial para [CPFHub.io](https://cpfhub.io) — API de Consulta de CPF Brasileiro**

---

## O que é o CPFHub.io?

O CPFHub.io é uma API REST que retorna nome, gênero e data de nascimento de qualquer CPF brasileiro — em ~300ms, com 99,9% de uptime e total conformidade com a LGPD.

**10M+ CPFs consultados · 1.300+ empresas ativas · 99,9% uptime**

---

## Instalação

```bash
composer require cpfhub/cpfhub-php
```

---

## Início Rápido

```php
<?php

use CPFHub\Client;

$client = new Client("SUA_CHAVE_DE_API");

$result = $client->lookup("00000000000");

echo $result->name;      // "Fulano de Tal"
echo $result->gender;    // "M"
echo $result->birthDate; // "15/06/1990"
```

Obtenha sua chave de API gratuita em [app.cpfhub.io](https://app.cpfhub.io) — sem cartão de crédito.

---

## Exemplo curl

```bash
curl -X GET "https://api.cpfhub.io/cpf/12345678909" \
  -H "x-api-key: SUA_CHAVE_DE_API"
```

**Resposta:**

```json
{
  "success": true,
  "data": {
    "cpf": "12345678909",
    "name": "Fulano de Tal",
    "nameUpper": "FULANO DE TAL",
    "gender": "M",
    "birthDate": "15/06/1990",
    "day": 15,
    "month": 6,
    "year": 1990
  }
}
```

---

## Referência da API

### `new Client(string $apiKey, array $options = [])`

| Opção | Tipo | Padrão | Descrição |
|-------|------|--------|-----------|
| `timeout` | `int` | `10` | Timeout da requisição em segundos |
| `base_url` | `string` | `https://api.cpfhub.io` | URL base da API |

### `$client->lookup(string $cpf): CPFResult`

Consulta um CPF e retorna os dados de identidade associados.

Aceita CPF com ou sem formatação (`000.000.000-00` ou `00000000000`).

#### Propriedades de `CPFResult`

| Propriedade | Tipo | Descrição |
|-------------|------|-----------|
| `cpf` | `string` | CPF (apenas dígitos) |
| `name` | `string` | Nome completo — `"Fulano de Tal"` |
| `nameUpper` | `string` | Nome completo em maiúsculas |
| `gender` | `string` | `"M"` ou `"F"` |
| `birthDate` | `string` | Data de nascimento — `"DD/MM/YYYY"` |
| `day` | `int` | Dia de nascimento |
| `month` | `int` | Mês de nascimento |
| `year` | `int` | Ano de nascimento |

---

## Tratamento de Erros

```php
<?php

use CPFHub\Client;
use CPFHub\Exceptions\CPFHubException;

$client = new Client("SUA_CHAVE_DE_API");

try {
    $result = $client->lookup("00000000000");
    echo $result->name;
} catch (CPFHubException $e) {
    echo "Erro {$e->getStatusCode()}: {$e->getMessage()}";
    // 400 — Formato de CPF inválido
    // 401 — Chave de API inválida ou ausente
    // 404 — CPF não encontrado
    // 429 — Limite de requisições excedido
    // 500 — Erro no servidor
    // 503 — Serviço temporariamente indisponível
}
```

---

## Exemplos

Veja o diretório `examples/` para exemplos de uso:

- [simple_lookup.php](examples/simple_lookup.php)
- [real_world_onboarding.php](examples/real_world_onboarding.php)

### PHP puro

```php
<?php

use CPFHub\Client;

$client = new Client($_ENV["CPFHUB_API_KEY"], ["timeout" => 5]);
$result = $client->lookup("00000000000");

echo $result->name;
```

### Laravel

```php
// config/services.php
'cpfhub' => [
    'key' => env('CPFHUB_API_KEY'),
],
```

```php
// app/Services/CPFService.php
use CPFHub\Client;

class CPFService
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client(config('services.cpfhub.key'));
    }

    public function lookup(string $cpf)
    {
        return $this->client->lookup($cpf);
    }
}
```

```php
// Em um controller
use App\Services\CPFService;

class OnboardingController extends Controller
{
    public function verify(Request $request, CPFService $cpf)
    {
        $result = $cpf->lookup($request->input('cpf'));
        return response()->json(['name' => $result->name]);
    }
}
```

### Symfony

```php
// src/Service/CPFService.php
use CPFHub\Client;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class CPFService
{
    private Client $client;

    public function __construct(
        #[Autowire('%env(CPFHUB_API_KEY)%')] string $apiKey
    ) {
        $this->client = new Client($apiKey);
    }

    public function lookup(string $cpf)
    {
        return $this->client->lookup($cpf);
    }
}
```

---

## Limites de Requisição

| Plano | Limite |
|---|---|
| Gratuito | 1 requisição a cada 2 segundos · 50 requisições/mês |
| Pro | 1 requisição por segundo · 1.000 requisições/mês |
| Corporativo | Personalizado |

O SDK faz retry automático no erro `429` com backoff exponencial (até 3 tentativas).

---

## Planos e Preços

| Plano | Preço | Incluído | Extra |
|-------|-------|----------|-------|
| **Gratuito** | R$ 0/mês | 50 consultas | — |
| **Pro** | R$ 149/mês | 1.000 consultas | R$ 0,15/consulta |
| **Corporativo** | Personalizado | Personalizado | Personalizado |

[Ver preços completos em cpfhub.io →](https://cpfhub.io#pricing)

---

## Requisitos

- PHP 8.1+
- `guzzlehttp/guzzle` — instalado automaticamente

---

## Links

- [Documentação](https://cpfhub.io/documentacao)
- [Dashboard](https://app.cpfhub.io)
- [Página de Status](https://app.cpfhub.io/status)
- [Preços](https://cpfhub.io#pricing)
- [Conformidade LGPD](https://cpfhub.io/lgpd)
- [Especificação OpenAPI](https://github.com/cpfhub/cpfhub-openapi/blob/main/openapi.yaml)
- [Servidor MCP (Agentes de IA)](https://github.com/cpfhub/cpfhub-mcp)

---

## Licença

MIT © [CPFHub.io](https://cpfhub.io)
