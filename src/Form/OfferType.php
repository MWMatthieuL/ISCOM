<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class OfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => "Veuillez écrire le titre de l'offre ici"
                ]
            ])
            ->add('offerFile', VichFileType::class, [
                'label' => false,
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Supprimer',
                'download_label' => 'Télécharger',
                'download_uri' => true,
            ])
            ->add('link', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => "Copiez le lien de votre offre"
                ]
            ])
            ->add('structure', ChoiceType::class, [
                'choices' => [
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
                'expanded' => 'true',
                'multiple' => 'true',
                'required' => true,
//                'mapped' => false,
                'label' => 'Type de structure recherche',
            ])
            ->add('sector', ChoiceType::class, [
                'choices' => [
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
                'expanded' => 'true',
                'multiple' => 'true',
                'required' => true,
//                'mapped' => false,
                'label' => 'Secteurs recherchées',
            ])
            ->add('mainSkills', ChoiceType::class, [
                'choices' => [
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
                'expanded' => 'true',
                'multiple' => 'true',
                'required' => true,
//                'mapped' => false,
                'label' => 'Les principales compétences recherchées pour le poste',
            ])
            ->add('softSkills', ChoiceType::class, [
                'choices' => [
                    "Autonomie" => "Autonomie",
                    "Curiosité" => "Curiosité",
                    "Esprit d'équipe" => "Équipe",
                    "Feedback" => "Feedback",
                    "Flexibilité" => "Flexibilité",
                    "Force de proposition" => "Proposition",
                    "Gestion du temps" => "Temps",
                    "Orthographe et rédaction" => "Orthographe",
                ],
                'expanded' => 'true',
                'multiple' => 'true',
                'required' => true,
//                'mapped' => false,
                'label' => 'Les principales soft skills recherchés pour le poste',
            ])
            ->add('places', ChoiceType::class, [
                'choices' => [
                    "Ain" => "01",
                    "Aisne" => "02",
                    "Allier" => "03",
                    "Alpes de Haute Provence" => "04",
                    "Hautes Alpes" => "05",
                    "Alpes Maritimes" => "06",
                    "Ardèche" => "07",
                    "Ardennes" => "08",
                    "Ariége" => "09",
                    "Aube" => "10",
                    "Aude" => "11",
                    "Averyon" => "12",
                    "Bouche du Rhône" => "13",
                    "Calvados" => "14",
                    "Cantal" => "15",
                    "Charente" => "16",
                    "Charente Maritime" => "17",
                    "Cher" => "18",
                    "Corrèze" => "19",
                    "Corse du Sud" => "2a",
                    "Haute Corse" => "2b",
                    "Côte d'Or" => "21",
                    "Côtes d'Armor" => "22",
                    "Creuse" => "23",
                    "Dordogne" => "24",
                    "Doubs" => "25",
                    "Drôme" => "26",
                    "Eure" => "27",
                    "Eure et Loire" => "28",
                    "Finistère" => "29",
                    "Gard" => "30",
                    "Haute Garonne" => "31",
                    "Gers" => "32",
                    "Gironde" => "33",
                    "Herault" => "34",
                    "Ille et Vilaine" => "35",
                    "Indre" => "36",
                    "Indre et Loire" => "37",
                    "Isère" => "38",
                    "Jura" => "39",
                    "Landes" => "40",
                    "Loir et Cher" => "41",
                    "Loire" => "42",
                    "Haute Loire" => "43",
                    "Loire Atlantique" => "44",
                    "Loiret" => "45",
                    "Lot" => "46",
                    "Lot et Garonne" => "47",
                    "Lozère" => "48",
                    "Maine et Loire" => "49",
                    "Manche" => "50",
                    "Marne" => "51",
                    "Haute Marne" => "52",
                    "Mayenne" => "53",
                    "Meurthe et Moselle" => "54",
                    "Meuse" => "55",
                    "Morbihan" => "56",
                    "Moselle" => "57",
                    "Nièvre;" => "58",
                    "Nord" => "59",
                    "Oise" => "60",
                    "Orne" => "61",
                    "Pas de Calais" => "62",
                    "Puy de Dôme" => "63",
                    "Pyrenées Atlantiques" => "64",
                    "Hautes Pyrenées" => "65",
                    "Pyrenées orientales" => "66",
                    "Bas Rhin" => "67",
                    "Haut Rhin" => "68",
                    "Rhône" => "69",
                    "Haute Saône" => "70",
                    "Saône et Loire" => "71",
                    "Sarthe" => "72",
                    "Savoie" => "73",
                    "Haute Savoie" => "74",
                    "Paris" => "75",
                    "Seine Maritime" => "76",
                    "Seine et Marne" => "77",
                    "Yvelines" => "78",
                    "Deux Sèvres" => "79",
                    "Somme" => "80",
                    "Tarn" => "81",
                    "Tarn et Garonne" => "82",
                    "Var" => "83",
                    "Vaucluse" => "84",
                    "Vendée" => "85",
                    "Vienne" => "86",
                    "Haute Vienne" => "87",
                    "Vosges" => "88",
                    "Yonne" => "89",
                    "Territoire de Belfort" => "90",
                    "Essonne" => "91",
                    "Hauts de Seine" => "92",
                    "Seine Saint Denis" => "93",
                    "Val de Marne" => "94",
                    "Val d'Oise" => "95",
                ],
                'expanded' => false,
                'multiple' => false,
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Selection parmis les departements français'
                ]
            ])
            ->add('abroad', ChoiceType::class, [
                'choices' => [
                    'Oui' => 'Abroad',
                    'Non' => 'Not-abroad'
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Etranger'
            ])
            ->add('remote', ChoiceType::class, [
                'choices' => [
                    'Télétravail uniquement' => 'Remote-only',
                    'Présentiel et télétravail' => 'Remote-yes',
                    'Présentiel' => 'Remote-no'
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Mode de travail'
            ])
            ->add('languages', ChoiceType::class, [
                'choices' => [
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
                'expanded' => 'true',
                'multiple' => 'true',
                'required' => true,
//                'mapped' => false,
                'label' => 'Les principales soft skills recherchés pour le poste',
            ])
            ->add('period', ChoiceType::class, [
                'choices' => [
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
                ],
                'expanded' => 'true',
                'multiple' => 'true',
                'required' => true,
//                'mapped' => false,
                'label' => 'Les principales soft skills recherchés pour le poste',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}