<?php

namespace Entity;

class Manager extends Employee {
    private float $prime;

    public function __construct(int $id, string $nom, string $tel, Type $type, float $prime) {
        parent::__construct($id, $nom, $tel, $type);
        $this->prime = $prime;
    }

    public function calculSalaire(): float {
        return 2000 + $this->prime;
    }

     // Ajoutez ces getters :
    public function getPrime(): float {
        return $this->prime;
    }

    public function setPrime(float $prime): void {
        $this->prime = $prime;
    }
}