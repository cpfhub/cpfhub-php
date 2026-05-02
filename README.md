# cpfhub/cpfhub-php: SDK for CPFHub.io

**Official PHP SDK for [CPFHub.io](https://cpfhub.io) — Brazilian CPF Lookup API**

> Official SDK for [CPFHub.io](https://cpfhub.io) — CPF lookup API, optimized for developers and AI agents.

[![Packagist Version](https://img.shields.io/packagist/v/cpfhub/cpfhub-php)](https://packagist.org/packages/cpfhub/cpfhub-php)
[![PHP](https://img.shields.io/packagist/php-v/cpfhub/cpfhub-php)](https://packagist.org/packages/cpfhub/cpfhub-php)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)

---

## What is CPFHub.io?

CPFHub.io is a REST API that returns name, gender, and date of birth from any Brazilian CPF number — in ~300ms, with 99.9% uptime and full LGPD compliance.

**10M+ CPFs queried · 1,300+ active companies · 99.9% uptime**

---

## Why use the CPFHub.io PHP SDK?

This SDK is designed to offer a fluid and efficient integration of the CPFHub.io API into PHP projects, focusing on Developer Experience (DX) and compatibility with AI Agents.

### 1. Optimized Developer Experience (DX)

*   **Fast Integration**: Get started in **~5 minutes** with clear and concise code examples.
*   **API Abstraction**: Automatically handles headers, JSON parsing, and error handling, allowing you to focus on business logic.

### 2. Native Compatibility with AI Agents

To facilitate integration with AI agents and LLMs, this SDK and the CPFHub.io API offer:

*   **OpenAPI Specification**: The official API specification is available at [cpfhub-openapi](https://github.com/cpfhub/cpfhub-openapi), allowing agents to automatically understand its structure and typed schemas.
*   **Tool Descriptions**: The API is easily representable as "tool descriptions" for LLMs, facilitating invocation in agent frameworks.
*   **Native MCP Server**: CPFHub.io offers an MCP server that exposes the API directly to AI agents (Claude, Cursor, Windsurf), eliminating the need to write HTTP code.

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

*   [simple_lookup.php](examples/simple_lookup.php)
*   [real_world_onboarding.php](examples/real_world_onboarding.php)

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

---

## License

MIT © [CPFHub.io](https://cpfhub.io)
