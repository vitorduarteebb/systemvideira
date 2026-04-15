<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tecnico;
use App\Models\Cliente;
use App\Models\Proposta;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Criar usuário administrador (se não existir)
        User::firstOrCreate(
            ['email' => 'admin@videira.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Criar alguns técnicos de exemplo (se não existirem)
        Tecnico::firstOrCreate(
            ['email' => 'joao@videira.com'],
            [
                'nome' => 'João Silva',
                'telefone' => '(11) 99999-9999',
                'status' => 'ativo',
            ]
        );

        Tecnico::firstOrCreate(
            ['email' => 'maria@videira.com'],
            [
                'nome' => 'Maria Santos',
                'telefone' => '(11) 88888-8888',
                'status' => 'ativo',
            ]
        );

        // Criar clientes de exemplo (se não existirem)
        $cliente1 = Cliente::firstOrCreate(
            ['email' => 'contato@empresaabc.com'],
            [
                'nome' => 'Empresa ABC Ltda',
                'telefone' => '(11) 3456-7890',
                'empresa' => 'Empresa ABC',
            ]
        );

        $cliente2 = Cliente::firstOrCreate(
            ['email' => 'joao.silva@email.com'],
            [
                'nome' => 'João da Silva',
                'telefone' => '(11) 98765-4321',
                'empresa' => 'Silva & Associados',
            ]
        );

        $cliente3 = Cliente::firstOrCreate(
            ['email' => 'maria.oliveira@email.com'],
            [
                'nome' => 'Maria Oliveira',
                'telefone' => '(11) 91234-5678',
            ]
        );

        // Criar propostas de exemplo (se não existirem)
        Proposta::firstOrCreate(
            ['codigo_proposta' => 'PROP-000001'],
            [
                'cliente_id' => $cliente1->id,
                'responsavel_id' => User::first()->id,
                'valor_final' => 15000.00,
                'estado' => 'em_analise',
                'titulo' => 'Sistema de Refrigeração Industrial',
                'descricao_inicial' => 'Instalação completa de sistema de refrigeração para armazém',
                'data_criacao' => now()->subDays(5),
            ]
        );

        Proposta::firstOrCreate(
            ['codigo_proposta' => 'PROP-000002'],
            [
                'cliente_id' => $cliente2->id,
                'responsavel_id' => User::first()->id,
                'valor_final' => 8500.00,
                'estado' => 'primeiro_contato',
                'titulo' => 'Manutenção Preventiva',
                'descricao_inicial' => 'Contrato de manutenção preventiva anual',
                'data_criacao' => now()->subDays(2),
            ]
        );
    }
}
