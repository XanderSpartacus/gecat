<?php

namespace App\DataFixtures;

use App\Entity\Courrier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Random\RandomException;

class CourrierFixtures extends Fixture
{
    /**
     * @throws RandomException
     */
    public function load(ObjectManager $manager): void
    {
        $objets = [
            'Demande de validation budgétaire',
            'Transmission de facture fournisseur',
            'Bon de commande équipements informatiques',
            'Note de service interne',
            'Invitation à une réunion de coordination',
            'Rapport mensuel des activités',
        ];

        $contenus = [
            'Veuillez trouver ci-joint le document relatif à la demande.',
            'Ce courrier concerne une opération administrative en cours.',
            'Merci de bien vouloir traiter ce dossier dans les meilleurs délais.',
            'Ce document est transmis pour information et suivi.',
            'Une suite favorable est attendue pour ce courrier.',
        ];

        $destinataires = [
            'Direction Générale',
            'Direction des Finances Publiques',
            'Direction des Ressources Humaines',
            'Direction des Équipements et du Patrimoine',
        ];

        $statuts = ['entrant', 'sortant', 'interne'];

        $natures = [
            'Demande',
            'Facture',
            'Bon de commande',
            'Note de service',
            'Invitation',
            'Rapport',
        ];

        $gestionnaires = [
            'Aziza Moumbaga',
            'Pierre Nziengui',
            'Laure Mboumba',
            'Serge Obiang',
        ];

        $responsables = [
            'M. Pierre Nziengui – Directeur Général',
            'Mme Marie Obame – Directrice Financière',
            'M. Jean Koumba – Directeur des Ressources Humaines',
        ];

        for ($i = 0; $i < 20; $i++) {
            $reference = sprintf(
                '2026-%04d-%03d',
                1000 + $i,
                random_int(1, 999)
            );

            $courrier = new Courrier();
            $courrier->setReference($reference);
            $courrier->setObjet($objets[array_rand($objets)]);
            $courrier->setContenu($contenus[array_rand($contenus)]);
            $courrier->setExpediteur('Trésor Public du Gabon');
            $courrier->setDestinataire($destinataires[array_rand($destinataires)]);
            $courrier->setStatut($statuts[array_rand($statuts)]);
            $courrier->setNature($natures[array_rand($natures)]);
            $courrier->setGestionnaire($gestionnaires[array_rand($gestionnaires)]);
            $courrier->setResponsable($responsables[array_rand($responsables)]);
            $courrier->setDateReception(new \DateTimeImmutable(sprintf(
                '2026-%02d-%02d %02d:%02d',
                random_int(1, 12),
                random_int(1, 28),
                random_int(8, 17),
                random_int(0, 59)
            )));

            $manager->persist($courrier);
        }

        $manager->flush();
    }
}
