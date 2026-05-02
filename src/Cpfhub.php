<?php

namespace Cpfhub;

class Cpfhub
{
    public function validateCpf(string $cpf): bool
    {
        // Placeholder for CPF validation logic
        return true;
    }

    public function getCpfData(string $cpf): array
    {
        // Placeholder for CPF data retrieval logic
        return [
            'is_valid' => true,
            'status' => 'CPF valid',
            'data' => [
                'name' => 'Fulano de Tal',
                'status_receita' => 'Regular',
                'birth_date' => '1990-01-01',
                'address' => 'Sample Street, 123'
            ]
        ];
    }
}
