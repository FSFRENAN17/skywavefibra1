<?php

namespace Source\Models\App;

use Source\Core\Model;

/**
 * Class Plan
 * Representa um plano de internet (velocidades, franquia e preço)
 */
class Plan extends Model
{
    /**
     * Plan constructor.
     */
    public function __construct()
    {
        parent::__construct(
            "plan",                   // Nome da tabela
            ["id"],                   // Chave primária
            ["name", "download_speed", "upload_speed", "price"] // Campos obrigatórios
        );
    }

    /**
     * Inicializa o plano
     *
     * @param string $name
     * @param int $downloadSpeed
     * @param int $uploadSpeed
     * @param float $price
     * @param int|null $dataCap
     * @param string|null $description
     * @return Plan
     */
    public function bootstrap(
        string $name,
        int $downloadSpeed,
        int $uploadSpeed,
        float $price,
        ?int $dataCap = null,
        ?string $description = null
    ): Plan {
        $this->name           = $name;
        $this->download_speed = $downloadSpeed;
        $this->upload_speed   = $uploadSpeed;
        $this->price          = $price;
        $this->data_cap       = $dataCap;
        $this->description    = $description;

        return $this;
    }

    /**
     * Retorna o preço formatado
     */
    public function priceFormatted(): string
    {
        return "R$ " . number_format($this->price, 2, ',', '.');
    }

    /**
     * Retorna a velocidade formatada
     */
    public function speedFormatted(): string
    {
        return "{$this->download_speed} / {$this->upload_speed} Mbps";
    }

    /**
     * Retorna uma breve descrição do plano
     */
    public function summary(): string
    {
        $cap = $this->data_cap ? "{$this->data_cap} GB" : "Ilimitado";
        return "{$this->name} ({$this->speedFormatted()}, {$cap})";
    }
}
