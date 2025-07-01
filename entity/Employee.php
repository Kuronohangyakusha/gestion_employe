<?php

namespace Entity;

class Employee {
    protected int $id;
    protected string $nom;
    protected string $tel;
    protected ?Service $service = null;
    protected Type $type;

    public function __construct(int $id, string $nom, string $tel, Type $type) {
        $this->id = $id;
        $this->nom = $nom;
        $this->tel = $tel;
        $this->type = $type;
    }

    public function calculSalaire(): float {
        // Surcharge dans les classes filles
        return 0.0;
    }

    public function getService(): ?Service {
        return $this->service;
    }

    public function setService(Service $service): void {
        $this->service = $service;
    }

    public function getType(): Type {
        return $this->type;
    }

    public function setType(Type $type): void {
        $this->type = $type;
    }

     public function getId(): int {
        return $this->id;
    }

    public function getNom(): string {
        return $this->nom;
    }

    public function getTel(): string {
        return $this->tel;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setNom(string $nom): void {
        $this->nom = $nom;
    }

    public function setTel(string $tel): void {
        $this->tel = $tel;
    }
}