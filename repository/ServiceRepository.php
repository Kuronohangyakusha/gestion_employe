<?php

namespace Repository;

use Entity\Service;
use Entity\Employee;
use PDO;

class ServiceRepository {
    private PDO $db;

    public function __construct() {
        $this->db = \Database::getInstance();
    }

    public function insert(Service $service): int {
        $sql = "INSERT INTO service (nom, manager_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        $managerId = $service->getManager() ? $service->getManager()->getId() : null;
        $stmt->execute([$service->getNom(), $managerId]);
        return $this->db->lastInsertId();
    }

    public function selectAll(): array {
        $sql = "SELECT * FROM service";
        $stmt = $this->db->query($sql);
        $services = [];
        
        while ($row = $stmt->fetch()) {
            $service = new Service($row['id'], $row['nom']);
            $services[] = $service;
        }
        
        return $services;
    }

    public function selectNoManager(): array {
        $sql = "SELECT * FROM service WHERE manager_id IS NULL";
        $stmt = $this->db->query($sql);
        $services = [];
        
        while ($row = $stmt->fetch()) {
            $service = new Service($row['id'], $row['nom']);
            $services[] = $service;
        }
        
        return $services;
    }

    public function updateManager(int $serviceId, int $managerId): int {
        $sql = "UPDATE service SET manager_id = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$managerId, $serviceId]);
        return $stmt->rowCount();
    }

    public function findById(int $id): ?Service {
        $sql = "SELECT * FROM service WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        
        if ($row) {
            return new Service($row['id'], $row['nom']);
        }
        
        return null;
    }

    public function delete(int $id): bool {
        $sql = "DELETE FROM service WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}