<?php

require_once 'Database.php';

// Autoloader simple pour vos classes
spl_autoload_register(function ($class) {
    $paths = [
        'entity/',
        'repository/',
        'service/',
        'view/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . str_replace('\\', '/', $class) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

use Service\CompteService;
use Service\ServiceService;
use Entity\Type;
use Entity\Specialite;

try {
    echo "=== GESTION DES EMPLOYÉS ===\n\n";
    
    $compteService = new CompteService();
    $serviceService = new ServiceService();
    
    // Test 1: Lister tous les employés
    echo "1. Liste de tous les employés:\n";
    $employes = $compteService->listerService();
    if (empty($employes)) {
        echo "Aucun employé trouvé.\n";
    } else {
        foreach ($employes as $employe) {
            echo "- {$employe->getNom()} ({$employe->getType()->value}) - Salaire: {$employe->calculSalaire()}€\n";
        }
    }
    echo "\n";
    
    // Test 2: Créer un nouveau développeur
    echo "2. Création d'un nouveau développeur:\n";
    $success = $compteService->creerDeveloppeur("Jean Dupont", "0123456789", Specialite::FullStack);
    echo $success ? "✓ Développeur créé avec succès\n" : "✗ Erreur lors de la création\n";
    echo "\n";
    
    // Test 3: Lister les services
    echo "3. Liste des services:\n";
    $services = $serviceService->obtenirServices();
    if (empty($services)) {
        echo "Aucun service trouvé.\n";
    } else {
        foreach ($services as $service) {
            echo "- {$service->getNom()} (ID: {$service->getId()})\n";
        }
    }
    echo "\n";
    
    // Test 4: Obtenir les statistiques
    echo "4. Statistiques des employés:\n";
    $stats = $serviceService->obtenirStatistiques();
    echo "Total employés: {$stats['total_employes']}\n";
    echo "Total services: {$stats['total_services']}\n";
    echo "Salaire moyen: " . number_format($stats['salaire_moyen'], 2) . "€\n";
    
    echo "\nEmployés par type:\n";
    foreach ($stats['employes_par_type'] as $type => $count) {
        echo "- $type: $count\n";
    }
    echo "\n";
    
    // Test 5: Lister les développeurs par spécialité
    echo "5. Développeurs FullStack:\n";
    $devs = $compteService->obtenirDeveloppeursParSpecialite(Specialite::FullStack);
    if (empty($devs)) {
        echo "Aucun développeur FullStack trouvé.\n";
    } else {
        foreach ($devs as $dev) {
            echo "- {$dev->getNom()}\n";
        }
    }
    echo "\n";
    
    // Test 6: Calculer la masse salariale
    echo "6. Masse salariale totale: " . number_format($compteService->calculerMasseSalariale(), 2) . "€\n\n";
    
    // Test 7: Créer un nouveau service
    echo "7. Création d'un nouveau service:\n";
    $success = $serviceService->creerService("Marketing Digital");
    echo $success ? "✓ Service créé avec succès\n" : "✗ Erreur lors de la création\n";
    echo "\n";
    
    // Test 8: Créer un manager
    echo "8. Création d'un nouveau manager:\n";
    $success = $compteService->creerManager("Marie Dubois", "0123456793", 800.0);
    echo $success ? "✓ Manager créé avec succès\n" : "✗ Erreur lors de la création\n";
    echo "\n";
    
    echo "=== TESTS TERMINÉS ===\n";
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}