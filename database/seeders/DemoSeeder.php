<?php

namespace Database\Seeders;

use App\Models\Clinica;
use App\Models\Consulta;
use App\Models\Exame;
use App\Models\Funcionario;
use App\Models\Pet;
use App\Models\Tutor;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    /**
     * Cria uma clínica única com equipe, clientes, pets, consultas e exames
     * coerentes para demonstrar a aplicação.
     */
    public function run(): void
    {
        $senha = Hash::make('senha12345');

        $dono = User::updateOrCreate(
            ['email' => 'dono@arizonapetcare.test'],
            [
                'name' => 'Aline Prado',
                'password' => $senha,
                'perfil' => 'dono',
            ]
        );

        $clinica = Clinica::updateOrCreate(
            ['nome' => 'Arizona Pet Care'],
            ['responsavel_id' => $dono->id]
        );

        $equipe = [
            ['nome' => 'Helena Torres', 'email' => 'funcionario@arizonapetcare.test', 'cpf' => '39053344705', 'telefone' => '11988887777', 'especialidade' => 'Dermatologia', 'crmv' => 'CRMV-SP 48120'],
            ['nome' => 'Bruno Salles', 'email' => 'bruno@arizonapetcare.test', 'cpf' => '52998224725', 'telefone' => '11977776666', 'especialidade' => 'Cardiologia', 'crmv' => 'CRMV-SP 39211'],
            ['nome' => 'Igor Menezes', 'email' => 'igor@arizonapetcare.test', 'cpf' => '16899535009', 'telefone' => '11966665555', 'especialidade' => 'Ortopedia', 'crmv' => 'CRMV-SP 51002'],
        ];

        $funcionarios = [];

        foreach ($equipe as $dados) {
            $conta = User::updateOrCreate(
                ['email' => $dados['email']],
                [
                    'name' => $dados['nome'],
                    'password' => $senha,
                    'perfil' => 'funcionario',
                ]
            );

            $funcionarios[$dados['nome']] = Funcionario::updateOrCreate(
                ['nm_email_funcionario' => $dados['email']],
                [
                    'nm_funcionario' => $dados['nome'],
                    'cd_cpf_funcionario' => $dados['cpf'],
                    'cd_telefone_funcionario' => $dados['telefone'],
                    'nm_especialidade_funcionario' => $dados['especialidade'],
                    'cd_crmv_funcionario' => $dados['crmv'],
                    'ic_ativo_funcionario' => true,
                    'user_id' => $conta->id,
                    'clinica_id' => $clinica->id,
                ]
            );
        }

        $clientes = [
            ['nome' => 'Marina Alves', 'cpf' => '11111111111', 'telefone' => '11987654321', 'email' => 'marina@exemplo.test', 'endereco' => 'Rua das Acácias, 120'],
            ['nome' => 'Rafael Nogueira', 'cpf' => '22222222222', 'telefone' => '11976543210', 'email' => 'rafael@exemplo.test', 'endereco' => 'Av. Central, 45'],
            ['nome' => 'Camila Duarte', 'cpf' => '33333333333', 'telefone' => '11965432109', 'email' => 'camila@exemplo.test', 'endereco' => 'Rua do Bosque, 310'],
            ['nome' => 'Joana Ribeiro', 'cpf' => '44444444444', 'telefone' => '11954321098', 'email' => 'joana@exemplo.test', 'endereco' => 'Alameda das Flores, 88'],
            ['nome' => 'Pedro Lima', 'cpf' => '55555555555', 'telefone' => '11943210987', 'email' => 'pedro@exemplo.test', 'endereco' => 'Rua Ipê Amarelo, 21'],
            ['nome' => 'Lúcia Ferraz', 'cpf' => '66666666666', 'telefone' => '11932109876', 'email' => 'lucia@exemplo.test', 'endereco' => 'Rua dos Ipês, 9'],
        ];

        $tutores = [];

        foreach ($clientes as $cliente) {
            $tutores[$cliente['nome']] = Tutor::updateOrCreate(
                ['cd_cpf_tutor' => $cliente['cpf']],
                [
                    'nm_tutor' => $cliente['nome'],
                    'nm_endereco_tutor' => $cliente['endereco'],
                    'nm_email_tutor' => $cliente['email'],
                    'cd_telefone_tutor' => $cliente['telefone'],
                    'ic_ativo_tutor' => true,
                ]
            );
        }

        $pets = [
            ['nome' => 'Thor', 'especie' => 'Cão', 'raca' => 'Golden Retriever', 'peso' => 28.50, 'tutor' => 'Marina Alves'],
            ['nome' => 'Nina', 'especie' => 'Gato', 'raca' => 'Siamês', 'peso' => 4.20, 'tutor' => 'Marina Alves'],
            ['nome' => 'Bento', 'especie' => 'Cão', 'raca' => 'Poodle', 'peso' => 6.80, 'tutor' => 'Rafael Nogueira'],
            ['nome' => 'Lola', 'especie' => 'Cão', 'raca' => 'Buldogue Francês', 'peso' => 11.30, 'tutor' => 'Camila Duarte'],
            ['nome' => 'Simba', 'especie' => 'Gato', 'raca' => 'Persa', 'peso' => 5.10, 'tutor' => 'Camila Duarte'],
            ['nome' => 'Kiara', 'especie' => 'Cão', 'raca' => 'Border Collie', 'peso' => 17.40, 'tutor' => 'Joana Ribeiro'],
            ['nome' => 'Pingo', 'especie' => 'Ave', 'raca' => 'Calopsita', 'peso' => 0.10, 'tutor' => 'Pedro Lima'],
            ['nome' => 'Amora', 'especie' => 'Coelho', 'raca' => 'Mini Lop', 'peso' => 1.80, 'tutor' => 'Lúcia Ferraz'],
        ];

        $registrosPets = [];

        foreach ($pets as $pet) {
            $registrosPets[$pet['nome']] = Pet::updateOrCreate(
                [
                    'nm_pet' => $pet['nome'],
                    'id_tutor' => $tutores[$pet['tutor']]->id_tutor,
                ],
                [
                    'nm_especie_pet' => $pet['especie'],
                    'nm_raca_pet' => $pet['raca'],
                    'qt_peso_pet' => $pet['peso'],
                    'ic_ativo_pet' => true,
                ]
            );
        }

        $hoje = now()->toDateString();
        $ontem = now()->subDay()->toDateString();
        $anteontem = now()->subDays(2)->toDateString();

        $consultas = [
            ['pet' => 'Thor', 'funcionario' => 'Bruno Salles', 'data' => $hoje, 'hora' => '09:00', 'motivo' => 'Retorno cardiológico', 'status' => 'Confirmada'],
            ['pet' => 'Bento', 'funcionario' => 'Helena Torres', 'data' => $hoje, 'hora' => '10:30', 'motivo' => 'Coceira e queda de pelo', 'status' => 'Pendente'],
            ['pet' => 'Lola', 'funcionario' => 'Igor Menezes', 'data' => $hoje, 'hora' => '14:00', 'motivo' => 'Avaliação de claudicação', 'status' => 'Confirmada'],
            ['pet' => 'Nina', 'funcionario' => 'Helena Torres', 'data' => $hoje, 'hora' => '16:30', 'motivo' => 'Check-up anual', 'status' => 'Concluída'],
            ['pet' => 'Kiara', 'funcionario' => 'Bruno Salles', 'data' => $ontem, 'hora' => '11:00', 'motivo' => 'Exame de rotina', 'status' => 'Concluída'],
            ['pet' => 'Simba', 'funcionario' => 'Igor Menezes', 'data' => $anteontem, 'hora' => '15:15', 'motivo' => 'Lambedura de patas', 'status' => 'Cancelada'],
            ['pet' => 'Pingo', 'funcionario' => 'Helena Torres', 'data' => $anteontem, 'hora' => '08:45', 'motivo' => 'Avaliação de penas', 'status' => 'Concluída'],
        ];

        foreach ($consultas as $consulta) {
            Consulta::updateOrCreate(
                [
                    'PET_id_pet' => $registrosPets[$consulta['pet']]->id_pet,
                    'dt_atendimento' => $consulta['data'],
                    'hr_atendimento' => $consulta['hora'],
                ],
                [
                    'FUNCIONARIO_id_funcionario' => $funcionarios[$consulta['funcionario']]->id_funcionario,
                    'clinica_id' => $clinica->id,
                    'ds_motivo' => $consulta['motivo'],
                    'ic_status_atendimento' => $consulta['status'],
                ]
            );
        }

        $exames = [
            ['pet' => 'Nina', 'nome' => 'Hemograma completo', 'arquivo' => 'hemograma-nina.pdf', 'obs' => 'Valores dentro da referência.'],
            ['pet' => 'Kiara', 'nome' => 'Raio-X de quadril', 'arquivo' => 'raio-x-kiara.pdf', 'obs' => 'Sem alterações ósseas.'],
        ];

        foreach ($exames as $exame) {
            $consulta = Consulta::query()
                ->whereHas('pet', fn ($query) => $query->where('nm_pet', $exame['pet']))
                ->where('ic_status_atendimento', 'Concluída')
                ->latest('dt_atendimento')
                ->first();

            if (! $consulta) {
                continue;
            }

            $this->copiarPlaceholder($exame['arquivo']);

            Exame::updateOrCreate(
                [
                    'id_atendimento' => $consulta->id_atendimento,
                    'nm_exame' => $exame['nome'],
                ],
                [
                    'nm_arquivo' => $exame['arquivo'],
                    'nm_arquivo_original' => $exame['nome'].'.pdf',
                    'ds_observacao' => $exame['obs'],
                ]
            );
        }
    }

    private function copiarPlaceholder(string $arquivo): void
    {
        $origem = public_path('uploads/exames/exame-exemplo.pdf');
        $destino = public_path('uploads/exames/'.$arquivo);

        if (! is_dir(dirname($destino))) {
            mkdir(dirname($destino), 0755, true);
        }

        if (is_file($origem) && ! is_file($destino)) {
            copy($origem, $destino);
        }
    }
}
