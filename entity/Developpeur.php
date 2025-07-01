<?php

namespace Entity;

class Developpeur extends Employee {
    private Specialite $specialite;

    public function __construct(int $id, string $nom, string $tel, Type $type, Specialite $specialite) {
        parent::__construct($id, $nom, $tel, $type);
        $this->specialite = $specialite;
    }

    public function getSpecialite(): Specialite {
        return $this->specialite;
    }

    public function setSpecialite(Specialite $specialite): void {
        $this->specialite = $specialite;
    }

    public function calculSalaire(): float {
        return 1800;
    }
}