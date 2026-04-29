# cpfhub/cpfhub-php

**Official PHP SDK for [CPFHub.io](https://cpfhub.io) — Brazilian CPF Lookup API**

> SDK oficial PHP para a [CPFHub.io](https://cpfhub.io) — API de consulta de CPF

[![Packagist Version](https://img.shields.io/packagist/v/cpfhub/cpfhub-php)](https://packagist.org/packages/cpfhub/cpfhub-php)
[![PHP](https://img.shields.io/packagist/php-v/cpfhub/cpfhub-php)](https://packagist.org/packages/cpfhub/cpfhub-php)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)

---

## What is CPFHub.io?

CPFHub.io is a REST API that returns name, gender, and date of birth from any Brazilian CPF number — in ~300ms, with 99.9% uptime, and full LGPD compliance.

> CPFHub.io é uma API REST que retorna nome, gênero e data de nascimento a partir de qualquer CPF brasileiro — em ~300ms, com 99,9% de uptime e total conformidade com a LGPD.

**10M+ CPFs queried · 1,300+ active companies · 99.9% uptime**

---

## Installation / Instalação

```bash
composer require cpfhub/cpfhub-php
```

---

## Quick Start

```php
<?php

use CPFHub\Client;

$client = new Client('YOUR_API_KEY');

$result = $client->lookup('00000000000');

echo $result->name;      // "Fulano de Tal"
echo $result->gender;    // "M"
echo $result->birthDate; // "15/06/1990"
```

Get your free API key at [app.cpfhub.io](https://app.cpfhub.io) — no credit card required.

> Obtenha sua chave gratuita em [app.cpfhub.io](https://app.cpfhub.io) — sem cartão de crédito.

---

## API Reference

### `new Client(string $apiKey, array $options = [])`

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `timeout` | `int` | `10` | Request timeout in seconds |
| `base_url` | `string` | `https://api.cpfhub.io` | API base URL |

### `$client->lookup(string $cpf): CPFResult`

Looks up a CPF and returns the associated data.

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

$client = new Client('YOUR_API_KEY');

try {
    $result = $client->lookup('00000000000');
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

### Vanilla PHP

```php
<?php

use CPFHub\Client;

$client = new Client($_ENV['CPFHUB_API_KEY'], ['timeout' => 5]);
$result = $client->lookup('00000000000');

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

## Rate Limits / Limites de Requisição

| Plan / Plano | Limit / Limite |
|---|---|
| Free / Grátis | 1 request every 2 seconds · 50 requests/month |
| Pro | 1 request per second · 1,000 requests/month |
| Corporate / Corporativo | Custom / Personalizado |

The SDK automatically retries on `429` with exponential backoff (up to 3 attempts).

> O SDK faz retry automático em `429` com backoff exponencial (até 3 tentativas).

---

## Plans & Pricing / Planos e Preços

| Plan | Price | Included | Extra |
|------|-------|----------|-------|
| **Free** | R$ 0/month | 50 lookups | — |
| **Pro** | R$ 149/month | 1,000 lookups | R$ 0,15/lookup |
| **Corporate** | Custom | Custom | Custom |

[View full pricing at cpfhub.io →](https://cpfhub.io#pricing)

---

## Requirements / Requisitos

- PHP 8.1+
- `guzzlehttp/guzzle` — installed automatically

---

## Links

- [Documentation / Documentação](https://cpfhub.io/documentacao)
- [Dashboard / Painel](https://app.cpfhub.io)
- [Status Page](https://app.cpfhub.io/status)
- [Pricing / Preços](https://cpfhub.io#pricing)
- [LGPD Compliance](https://cpfhub.io/lgpd)

---

## License / Licença

MIT © [CPFHub.io](https://cpfhub.io)
