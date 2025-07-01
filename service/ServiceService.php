<?php

namespace Service;

use Repository\ServiceRepository;
use Repository\CompteRepository;
use Entity\Service as ServiceEntity;
use Entity\Employee;

class ServiceService {
    private ServiceRepository $serviceRepository;
    private CompteRepository $compteRepository;

    public function __construct() {
        $this->serviceRepository = new ServiceRepository();
        $this->compteRepository = new CompteRepository();
    }

    public function listerEmploye(): array {
        return $this->compteRepository->selectAll();
    }

    public function isterEmploye(array $filter): array {
        return $this->compteRepository->selectByFilter($filter);
    }

    public function enregistrerEmploye(Employee $employe): bool {
        try {
            $id = $this->compteRepository->insert($employe);
            return $id > 0;
        } catch (\Exception $e) {
            error_log("Erreur lors de l'enregistrement de l'employé: " . $e->getMessage());
            return false;
        }
    }

    public function obtenirServices(): array {
        return $this->serviceRepository->selectAll();
    }

    public function obtenirServicesSansManager(): array {
        return $this->serviceRepository->selectNoManager();
    }

    public function assignerManager(int $serviceId, int $managerId): bool {
        try {
            $rowsAffected = $this->serviceRepository->updateManager($serviceId, $managerId);
            return $rowsAffected > 0;
        } catch (\Exception $e) {
            error_log("Erreur lors de l'assignation du manager: " . $e->getMessage());
            return false;
        }
    }

    public function creerService(string $nom): bool {
        try {
            $service = new ServiceEntity(0, $nom);
            $id = $this->serviceRepository->insert($service);
            return $id > 0;
        } catch (\Exception $e) {
            error_log("Erreur lors de la création du service: " . $e->getMessage());
            return false;
        }
    }

    public function obtenirEmployesParService(int $serviceId): array {
        return $this->compteRepository->selectByFilter(['service_id' => $serviceId]);
    }

    public function obtenirStatistiques(): array {
        $employes = $this->listerEmploye();
        $services = $this->obtenirServices();
        
        $stats = [
            'total_employes' => count($employes),
            'total_services' => count($services),
            'employes_par_type' => [],
            'salaire_moyen' => 0
        ];
        
        $totalSalaire = 0;
        foreach ($employes as $employe) {
            $type = $employe->getType()->value;
            if (!isset($stats['employes_par_type'][$type])) {
                $stats['employes_par_type'][$type] = 0;
            }
            $stats['employes_par_type'][$type]++;
            $totalSalaire += $employe->calculSalaire();
        }
        
        if (count($employes) > 0) {
            $stats['salaire_moyen'] = $totalSalaire / count($employes);
        }
        
        return $stats;
    }
}