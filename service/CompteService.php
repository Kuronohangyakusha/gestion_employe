<?php

namespace Service;

use Repository\CompteRepository;
use Repository\ServiceRepository;
use Entity\Employee;
use Entity\Admin;
use Entity\Manager;
use Entity\Developpeur;
use Entity\Type;
use Entity\Specialite;

class CompteService {
    private CompteRepository $compteRepository;
    private ServiceRepository $serviceRepository;

    public function __construct() {
        $this->compteRepository = new CompteRepository();
        $this->serviceRepository = new ServiceRepository();
    }

    public function creerEmploye(Employee $employe): bool {
        try {
            $id = $this->compteRepository->insert($employe);
            return $id > 0;
        } catch (\Exception $e) {
            error_log("Erreur lors de la création de l'employé: " . $e->getMessage());
            return false;
        }
    }

    public function listerService(): array {
        return $this->compteRepository->selectAll();
    }

    public function listerManagersSansService(): array {
        return $this->compteRepository->selectByFilter(['type' => 'Manager']);
    }

    public function creerAdmin(string $nom, string $tel, string $login, string $password): bool {
        $admin = new Admin(0, $nom, $tel, Type::Admin, $login, $password);
        return $this->creerEmploye($admin);
    }

    public function creerManager(string $nom, string $tel, float $prime): bool {
        $manager = new Manager(0, $nom, $tel, Type::Manager, $prime);
        return $this->creerEmploye($manager);
    }

    public function creerDeveloppeur(string $nom, string $tel, Specialite $specialite): bool {
        $developpeur = new Developpeur(0, $nom, $tel, Type::Developpeur, $specialite);
        return $this->creerEmploye($developpeur);
    }

    public function obtenirEmployeParId(int $id): ?Employee {
        return $this->compteRepository->findById($id);
    }

    public function modifierEmploye(Employee $employe): bool {
        try {
            return $this->compteRepository->update($employe);
        } catch (\Exception $e) {
            error_log("Erreur lors de la modification de l'employé: " . $e->getMessage());
            return false;
        }
    }

    public function supprimerEmploye(int $id): bool {
        try {
            return $this->compteRepository->delete($id);
        } catch (\Exception $e) {
            error_log("Erreur lors de la suppression de l'employé: " . $e->getMessage());
            return false;
        }
    }

    public function obtenirEmployesParType(Type $type): array {
        return $this->compteRepository->selectByFilter(['type' => $type->value]);
    }

    public function obtenirDeveloppeursParSpecialite(Specialite $specialite): array {
        $specialiteDb = match($specialite) {
            Specialite::FullStack => 'FS',
            Specialite::FrontEnd => 'FE',
            Specialite::BackEnd => 'BE'
        };
        
        return $this->compteRepository->selectByFilter([
            'type' => 'Developpeur',
            'specialite' => $specialiteDb
        ]);
    }

    public function assignerService(int $employeId, int $serviceId): bool {
        try {
            $employe = $this->obtenirEmployeParId($employeId);
            $service = $this->serviceRepository->findById($serviceId);
            
            if ($employe && $service) {
                $employe->setService($service);
                return $this->modifierEmploye($employe);
            }
            
            return false;
        } catch (\Exception $e) {
            error_log("Erreur lors de l'assignation du service: " . $e->getMessage());
            return false;
        }
    }

    public function calculerMasseSalariale(): float {
        $employes = $this->listerService();
        $total = 0;
        
        foreach ($employes as $employe) {
            $total += $employe->calculSalaire();
        }
        
        return $total;
    }

    public function obtenirStatistiquesParType(): array {
        $employes = $this->listerService();
        $stats = [];
        
        foreach ($employes as $employe) {
            $type = $employe->getType()->value;
            if (!isset($stats[$type])) {
                $stats[$type] = [
                    'count' => 0,
                    'salaire_total' => 0,
                    'salaire_moyen' => 0
                ];
            }
            
            $stats[$type]['count']++;
            $stats[$type]['salaire_total'] += $employe->calculSalaire();
        }
        
        // Calculer les moyennes
        foreach ($stats as $type => &$stat) {
            if ($stat['count'] > 0) {
                $stat['salaire_moyen'] = $stat['salaire_total'] / $stat['count'];
            }
        }
        
        return $stats;
    }
}