<?php

namespace Entity;

class Admin extends Employee {
    private string $login;
    private string $password;

    public function __construct(int $id, string $nom, string $tel, Type $type, string $login, string $password) {
        parent::__construct($id, $nom, $tel, $type);
        $this->login = $login;
        $this->password = $password;
    }

     public function getLogin(): string {
        return $this->login;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setLogin(string $login): void {
        $this->login = $login;
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }

    public function calculSalaire(): float {
        return 4000; // Salaire de base pour un admin
    }
}