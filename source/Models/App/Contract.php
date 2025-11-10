<?php

namespace Source\Models\App;

use Source\Core\Model;
use Source\Models\App\Plan; // Garante que a classe Plan seja encontrada no método plan()

/**
 * Class Contract
 * Representa o contrato de serviço (a ligação Cliente -> Plano)
 */
class Contract extends Model
{
    /**
     * Contract constructor.
     */
  public function __construct()
    {
        // Corrigido para 3 argumentos (Nome da Tabela, Chave Primária, Campos Obrigatórios)
        parent::__construct(
            "contract", 
            ["id"], // <--- CHAVE PRIMÁRIA
            ["customer_id", "plan_id", "start_date"]
        );
    }
    /**
     * Retorna o Plano de Serviço (Model Plan) associado ao contrato
     * @return Plan|null
     */
    public function plan(): ?Plan
    {
        // Acessa a classe Plan, que está no mesmo namespace
        return (new Plan())->findById($this->plan_id);
    }
}