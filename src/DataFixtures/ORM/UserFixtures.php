<?php

namespace App\DataFixtures\ORM;

use App\Entity\Matching;
use App\Entity\Offer;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function generateQuestionnaryForStudent(): array
    {
        $questionnary = [];

        $tags = [
            'structure' => [
                "Agence (communication, marketing, RP, évenementielle...)" => "Agence",
                "Entreprise TPE (< 19 salariés)" => "TPE",
                "Entreprise PME (20 < 249 salariés)" => "PME",
                "Entreprise ETI (250 < 5000 salariés)" => "ETI",
                "Entreprise GE (> 5000 salariés)" => "GE",
                "Groupe International" => "International",
                "Start-up" => "Start-up",
                "Indépendant" => "Indépendant",
                "Média" => "Média",
                "Organisme public" => "Public",
                "Association" => "Association",
            ],
            'sector' => [
                "Agroalimentaire" => "Agroalimentaire",
                "Aéronautique" => "Aéronautique",
                "Automobile équipement machines" => "Automobile",
                "Bâtiment" => "Bâtiment",
                "Banque assurances finance" => "Banque",
                "Beauté cosmétiques" => "Beauté",
                "Commerce distribution" => "Commerce",
                "Communication édition multimédia" => "Communication",
                "Design" => "Design",
                "Digital" => "Digital",
                "Environnement RSE" => "Environnement",
                "Études" => "Études",
                "Événementiel" => "Événementiel",
                "Industrie musicale" => "Musical",
                "Informatique télécoms jeux vidéos" => "Informatique",
                "Marketing" => "Marketing",
                "Mode luxe" => "Mode",
                "Relations Presse" => "Relations",
                "Secteur associatif (humanitaire)" => "Associatif",
                "Secteur Public (collectivités territoriales)" => "Public",
                "Transport et logistique" => "Transport",
            ],
            'languages' => [
                "Allemand" => "Allemand",
                "Anglais" => "Anglais",
                "Arabe" => "Arabe",
                "Chinois" => "Chinois",
                "Coréen" => "Coréen",
                "Espagnol" => "Espagnol",
                "Italien" => "Italien",
                "Japonais" => "Japonais",
                "Néerlandais" => "Néerlandais",
                "Portugais" => "Portugais",
                "Russe" => "Russe",
                "Uniquement français" => "Français",
            ],
            'mainSkills' => [
                "Coordination et animation CRM" => "Coordination",
                "Création de supports de communication" => "Création supports",
                "Gestion de projets de communication à l'international" => "Communication",
                "Gestion de projets marketing et analyses du marché" => "Maketing",
                "Gestion et suivi de campagnes de communication internes ou externes" => "Suivi campagnes",
                "Gestion et suivi de projets digitaux" => "Suivi projets",
                "Ogranisation et suivi de projets évenementiels" => "Évenements",
                "Participation aux réflexions stratégiques" => "Stratégie",
                "Planning stratégique : tendances benchmarks études..." => "Planning",
                "Production et Rédaction de contenus pour les réseaux sociaux et les RP" => "Rédaction",
            ],
            'softSkills' => [
                "Autonomie" => "Autonomie",
                "Curiosité" => "Curiosité",
                "Esprit d'équipe" => "Équipe",
                "Feedback" => "Feedback",
                "Flexibilité" => "Flexibilité",
                "Force de proposition" => "Proposition",
                "Gestion du temps" => "Temps",
                "Orthographe et rédaction" => "Orthographe",
            ],
        ];

        $tags = array_values($tags);

        $periods = [
            "Septembre/Octobre (pour 1 an) - 5e année en alternance" => "Période 01",
            "Septembre/Octobre (pour 4 à 5 mois) - 3e année, missions internationales requises" => "Période 02",
            "Novembre (pour 6 semaines) - BTS 2e année" => "Période 03",
            "Novembre (pour 3 mois) - 2e année" => "Période 04",
            "Janvier (pour 2 mois) - 1re année" => "Période 05",
            "Janvier (pour 2 mois) - 2e année spécialisation création publicitaire" => "Période 06",
            "Janvier (pour 3 mois) - 2e année classe internationale" => "Période 07",
            "Février/Mars (pour 6 mois) - 4e année" => "Période 08",
            "Février/Mars (pour 4 à 5 mois) - 3e année, missions internationales requises" => "Période 09",
            "Mars/Avril (pour 6 mois) - 4e année spécialisation création publicitaire" => "Période 10",
            "Avril (pour 3 mois) - 3e année spécialisation création publicitaire" => "Période 11",
            "Mai/Juin (pour 2 mois) - BTS 1re année" => "Période 12",
            "Juin (pour 2 mois) - 1re année" => "Période 13",
            "Juillet/Août (pour 2 mois) - 2e année et 3e année" => "Période 14",
        ];

        $periods = array_values($periods);

        $lonelyTags = [
            'abroad' => [
                'Oui' => 'Abroad',
                'Non' => 'Not-abroad'
            ],
            'remote' => [
                'Télétravail uniquement' => 'Remote-only',
                'Présentiel et télétravail' => 'Remote-yes',
                'Présentiel' => 'Remote-no'
            ],
            'places' => [
                "Paris" => "75",
                "Hauts de Seine" => "92",
                "Seine Saint Denis" => "93",
                "Val de Marne" => "94",
            ],
        ];

        $lonelyTags = array_values($lonelyTags);

        foreach ($tags as $tag) {
            $tag = array_values($tag);
            for ($m = 0; $m < 6; ++$m) {
                $questionnary[] = $tag[random_int(0, count($tag) - 1)];
            }
        }

        foreach ($lonelyTags as $tag) {
            $tag = array_values($tag);
            $questionnary[] = $tag[random_int(0, count($tag) - 1)];
        }

        $periods = array_values($periods);

        for ($m = 0; $m < random_int(1,2); ++$m) {
            $questionnary[] = $periods[random_int(0, count($periods) - 1)];
        }

        return array_unique($questionnary);
    }

    public function generateQuestionnaryForCompany(): array
    {
        $questionnary = [];

        $tags = [
            'structure' => [
                "Agence (communication, marketing, RP, évenementielle...)" => "Agence",
                "Entreprise TPE (< 19 salariés)" => "TPE",
                "Entreprise PME (20 < 249 salariés)" => "PME",
                "Entreprise ETI (250 < 5000 salariés)" => "ETI",
                "Entreprise GE (> 5000 salariés)" => "GE",
                "Groupe International" => "International",
                "Start-up" => "Start-up",
                "Indépendant" => "Indépendant",
                "Média" => "Média",
                "Organisme public" => "Public",
                "Association" => "Association",
            ],
            'sector' => [
                "Agroalimentaire" => "Agroalimentaire",
                "Aéronautique" => "Aéronautique",
                "Automobile équipement machines" => "Automobile",
                "Bâtiment" => "Bâtiment",
                "Banque assurances finance" => "Banque",
                "Beauté cosmétiques" => "Beauté",
                "Commerce distribution" => "Commerce",
                "Communication édition multimédia" => "Communication",
                "Design" => "Design",
                "Digital" => "Digital",
                "Environnement RSE" => "Environnement",
                "Études" => "Études",
                "Événementiel" => "Événementiel",
                "Industrie musicale" => "Musical",
                "Informatique télécoms jeux vidéos" => "Informatique",
                "Marketing" => "Marketing",
                "Mode luxe" => "Mode",
                "Relations Presse" => "Relations",
                "Secteur associatif (humanitaire)" => "Associatif",
                "Secteur Public (collectivités territoriales)" => "Public",
                "Transport et logistique" => "Transport",
            ],
            'languages' => [
                "Allemand" => "Allemand",
                "Anglais" => "Anglais",
                "Arabe" => "Arabe",
                "Chinois" => "Chinois",
                "Coréen" => "Coréen",
                "Espagnol" => "Espagnol",
                "Italien" => "Italien",
                "Japonais" => "Japonais",
                "Néerlandais" => "Néerlandais",
                "Portugais" => "Portugais",
                "Russe" => "Russe",
                "Uniquement français" => "Français",
            ],
            'mainSkills' => [
                "Coordination et animation CRM" => "Coordination",
                "Création de supports de communication" => "Création supports",
                "Gestion de projets de communication à l'international" => "Communication",
                "Gestion de projets marketing et analyses du marché" => "Maketing",
                "Gestion et suivi de campagnes de communication internes ou externes" => "Suivi campagnes",
                "Gestion et suivi de projets digitaux" => "Suivi projets",
                "Ogranisation et suivi de projets évenementiels" => "Évenements",
                "Participation aux réflexions stratégiques" => "Stratégie",
                "Planning stratégique : tendances benchmarks études..." => "Planning",
                "Production et Rédaction de contenus pour les réseaux sociaux et les RP" => "Rédaction",
            ],
            'softSkills' => [
                "Autonomie" => "Autonomie",
                "Curiosité" => "Curiosité",
                "Esprit d'équipe" => "Équipe",
                "Feedback" => "Feedback",
                "Flexibilité" => "Flexibilité",
                "Force de proposition" => "Proposition",
                "Gestion du temps" => "Temps",
                "Orthographe et rédaction" => "Orthographe",
            ],
        ];

        $tags = array_values($tags);

        $periods = [
            "Septembre/Octobre (pour 1 an) - 5e année en alternance" => "Période 01",
            "Septembre/Octobre (pour 4 à 5 mois) - 3e année, missions internationales requises" => "Période 02",
            "Novembre (pour 6 semaines) - BTS 2e année" => "Période 03",
            "Novembre (pour 3 mois) - 2e année" => "Période 04",
            "Janvier (pour 2 mois) - 1re année" => "Période 05",
            "Janvier (pour 2 mois) - 2e année spécialisation création publicitaire" => "Période 06",
            "Janvier (pour 3 mois) - 2e année classe internationale" => "Période 07",
            "Février/Mars (pour 6 mois) - 4e année" => "Période 08",
            "Février/Mars (pour 4 à 5 mois) - 3e année, missions internationales requises" => "Période 09",
            "Mars/Avril (pour 6 mois) - 4e année spécialisation création publicitaire" => "Période 10",
            "Avril (pour 3 mois) - 3e année spécialisation création publicitaire" => "Période 11",
            "Mai/Juin (pour 2 mois) - BTS 1re année" => "Période 12",
            "Juin (pour 2 mois) - 1re année" => "Période 13",
            "Juillet/Août (pour 2 mois) - 2e année et 3e année" => "Période 14",
        ];

        $periods = array_values($periods);

        $lonelyTags = [
            'abroad' => [
                'Oui' => 'Abroad',
                'Non' => 'Not-abroad'
            ],
            'remote' => [
                'Télétravail uniquement' => 'Remote-only',
                'Présentiel et télétravail' => 'Remote-yes',
                'Présentiel' => 'Remote-no'
            ],
            'places' => [
                "Paris" => "75",
                "Hauts de Seine" => "92",
                "Seine Saint Denis" => "93",
                "Val de Marne" => "94",
            ],
        ];

        $lonelyTags = array_values($lonelyTags);

        foreach ($tags as $tag) {
            $tag = array_values($tag);
            for ($m = 0; $m < random_int(1,2); ++$m) {
                $questionnary[] = $tag[random_int(0, count($tag) - 1)];
            }
        }

        foreach ($lonelyTags as $tag) {
            $tag = array_values($tag);
            $questionnary[] = $tag[random_int(0, count($tag) - 1)];
        }

        $periods = array_values($periods);

        $questionnary[] = $periods[random_int(0, count($periods) - 1)];

        return array_unique($questionnary);
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 300; ++$i) {
            $user = new User();
            $user
                ->setFirstName('Etudiant '.$i)
                ->setLastName('Nom')
                ->setEmail('etudiant'.$i.'@email.fr')
                ->setRoles(['ROLE_USER', 'ROLE_STUDENT'])
                ->setPassword($this->passwordEncoder->encodePassword($user, 'mdp'))
                ->setPhone('0612345678')
                ->setType(User::TYPE_STUDENT)
                ->setQuestionnary($this->generateQuestionnaryForStudent())
            ;

            $manager->persist($user);
        }

        $manager->flush();


        for ($j = 0; $j < 50; ++$j) {
            $user = new User();
            $user
                ->setFirstName('Entreprise '.$j)
                ->setLastName('Nom')
                ->setEmail('entreprise'.$j.'@email.fr')
                ->setRoles(['ROLE_USER', 'ROLE_COMPANY'])
                ->setCompanyName('Entreprise '.$j)
                ->setPassword($this->passwordEncoder->encodePassword($user, 'mdp'))
                ->setPhone('0612345678')
                ->setType(User::TYPE_COMPANY);

            $manager->persist($user);

            for($k = 0; $k < 3; ++$k) {
                $tags = $this->generateQuestionnaryForCompany();
                $offer = new Offer();
                $offer->setProvided(0)
                    ->setCompany($user)
                    ->setPublishedAt(new \DateTime('now'))
                    ->setLink('https://google.fr')
                    ->setTitle("Title de l'offre".$j.$k)
                    ->setQuestionnary($tags)
                ;

                if (in_array('Période 01', $tags)) {
                    $offer->setType(Offer::TYPE_WORKSTUDY);
                } else {
                    $offer->setType(Offer::TYPE_INTERNSHIP);
                }

                $students = $manager->getRepository(User::class)->findBy([
                    'type' => 'student',
                ]);

                foreach ($students as $student) {
                    $matchCount = 0;

                    if (null != $student->getQuestionnary()) {
                        foreach ($student->getQuestionnary() as $tag) {
                            if (in_array($tag, $offer->getQuestionnary())) {
                                $matchCount++;
                            }
                        }

                        $matchPercentage = ($matchCount / count($offer->getQuestionnary())) * 100;

                        if ($matchPercentage >= 75) {
                            $matching = new Matching();
                            $matching->setOffer($offer)
                                ->setStudent($student)
                                ->setPercentage($matchPercentage);

                            $manager->persist($matching);
                        }
                    }
                }

                $manager->persist($offer);
            }

            $manager->persist($user);
        }

        $user = new User();
        $user
            ->setFirstName('Admin')
            ->setLastName('Istrateur')
            ->setEmail('admin@email.fr')
            ->setRoles(['ROLE_USER', 'ROLE_ISCOM'])
            ->setCompanyName('Entreprise '.$j)
            ->setPassword($this->passwordEncoder->encodePassword($user, 'mdp'))
            ->setPhone('0612345678')
            ->setType(User::TYPE_ISCOM);

        $manager->persist($user);
        $manager->flush();
    }
}
