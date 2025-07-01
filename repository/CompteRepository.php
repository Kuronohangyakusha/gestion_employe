<?php

namespace Repository;

use Entity\Employee;
use Entity\Admin;
use Entity\Manager;
use Entity\Developpeur;
use Entity\Type;
use Entity\Specialite;
use PDO;

class CompteRepository {
    private PDO $db;

    public function __construct() {
        $this->db = \Database::getInstance();
    }

    public function selectAll(): array {
        $sql = "SELECT * FROM employe";
        $stmt = $this->db->query($sql);
        $employes = [];
        
        while ($row = $stmt->fetch()) {
            $employe = $this->createEmployeeFromRow($row);
            $employes[] = $employe;
        }
        
        return $employes;
    }

    public function insert(Employee $employe): int {
        $sql = "INSERT INTO employe (nom, telephone, salaire, service_id, prime, specialite, login, password, type) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        
        $serviceId = $employe->getService() ? $employe->getService()->getId() : null;
        $salaire = $employe->calculSalaire();
        $prime = null;
        $specialite = null;
        $login = null;
        $password = null;
        
        // Gestion des attributs spécifiques selon le type
        if ($employe instanceof Manager) {
            $prime = $employe->calculSalaire() - 2000; // Récupérer la prime
        } elseif ($employe instanceof Developpeur) {
            $specialite = $this->mapSpecialiteToDb($employe->getSpecialite());
        } elseif ($employe instanceof Admin) {
            // Vous devrez ajouter des getters pour login et password dans Admin
            // $login = $employe->getLogin();
            // $password = $employe->getPassword();
        }
        
        $stmt->execute([
            $employe->getNom(),
            $employe->getTel(),
            $salaire,
            $serviceId,
            $prime,
            $specialite,
            $login,
            $password,
            $employe->getType()->value
        ]);
        
        return $this->db->lastInsertId();
    }

    public function selectByFilter(array $filter): array {
        $sql = "SELECT * FROM employe WHERE 1=1";
        $params = [];
        
        if (isset($filter['type'])) {
            $sql .= " AND type = ?";
            $params[] = $filter['type'];
        }
        
        if (isset($filter['service_id'])) {
            $sql .= " AND service_id = ?";
            $params[] = $filter['service_id'];
        }
        
        if (isset($filter['specialite'])) {
            $sql .= " AND specialite = ?";
            $params[] = $filter['specialite'];
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $employes = [];
        
        while ($row = $stmt->fetch()) {
            $employe = $this->createEmployeeFromRow($row);
            $employes[] = $employe;
        }
        
        return $employes;
    }

    private function createEmployeeFromRow(array $row): Employee {
        $type = Type::from($row['type']);
        
        switch ($type) {
            case Type::Admin:
                return new Admin(
                    $row['id'],
                    $row['nom'],
                    $row['telephone'],
                    $type,
                    $row['login'] ?? '',
                    $row['password'] ?? ''
                );
                
            case Type::Manager:
                return new Manager(
                    $row['id'],
                    $row['nom'],
                    $row['telephone'],
                    $type,
                    $row['prime'] ?? 0.0
                );
                
            case Type::Developpeur:
                $specialite = $this->mapDbToSpecialite($row['specialite']);
                return new Developpeur(
                    $row['id'],
                    $row['nom'],
                    $row['telephone'],
                    $type,
                    $specialite
                );
                
            default:
                throw new \InvalidArgumentException("Type d'employé non reconnu: " . $type->value);
        }
    }

    private function mapSpecialiteToDb(Specialite $specialite): string {
        return match($specialite) {
            Specialite::FullStack => 'FS',
            Specialite::FrontEnd => 'FE',
            Specialite::BackEnd => 'BE'
        };
    }

    private function mapDbToSpecialite(?string $dbSpecialite): Specialite {
        return match($dbSpecialite) {
            'FS' => Specialite::FullStack,
            'FE' => Specialite::FrontEnd,
            'BE' => Specialite::BackEnd,
            default => Specialite::FullStack
        };
    }

    public function findById(int $id): ?Employee {
        $sql = "SELECT * FROM employe WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        
        if ($row) {
            return $this->createEmployeeFromRow($row);
        }
        
        return null;
    }

    public function update(Employee $employe): bool {
        $sql = "UPDATE employe SET nom = ?, telephone = ?, salaire = ?, service_id = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        
        $serviceId = $employe->getService() ? $employe->getService()->getId() : null;
        
        return $stmt->execute([
            $employe->getNom(),
            $employe->getTel(),
            $employe->calculSalaire(),
            $serviceId,
            $employe->getId()
        ]);
    }

    public function delete(int $id): bool {
        $sql = "DELETE FROM employe WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}