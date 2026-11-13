<?php

return [
    'perfis' => [
        'dono' => 'Dono da clínica',
        'funcionario' => 'Funcionário',
    ],

    // [rota, rótulo, ícone, grupo, perfis que enxergam]
    'menu' => [
        ['dashboard',          'Painel',       'painel',       'Operação',      ['dono', 'funcionario']],
        ['tutores.index',      'Clientes',     'clientes',     'Operação',      ['dono', 'funcionario']],
        ['pets.index',         'Pets',         'pet',          'Operação',      ['dono', 'funcionario']],
        ['consultas.index',    'Consultas',    'calendario',   'Operação',      ['dono', 'funcionario']],
        ['exames.index',       'Exames',       'exames',       'Operação',      ['dono', 'funcionario']],
        ['funcionarios.index', 'Funcionários', 'funcionarios', 'Administração', ['dono']],
    ],

    'especies' => ['Cão', 'Gato', 'Coelho', 'Ave', 'Roedor', 'Réptil'],

    'especialidades' => [
        'Cardiologia', 'Dermatologia', 'Ortopedia', 'Oftalmologia', 'Oncologia', 'Anestesia',
        'Endocrinologia', 'Neurologia', 'Infectologia', 'Nefrologia', 'Odontologia',
        'Gastroenterologia', 'Medicina Integrativa', 'Medicina Felina', 'Nutrologia',
        'Silvestres e Exóticos',
    ],

    'status_consulta' => ['Pendente', 'Confirmada', 'Concluída', 'Cancelada'],

    'classe_status' => [
        'Pendente'   => 'pendente',
        'Confirmada' => 'confirmada',
        'Concluída'  => 'concluida',
        'Cancelada'  => 'cancelada',
    ],

    'exames_sugeridos' => ['Hemograma', 'Bioquímico', 'Citologia', 'Ecocardiograma', 'Eletrocardiograma', 'Ultrassonografia', 'Raio-X'],
];