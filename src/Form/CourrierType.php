<?php

namespace App\Form;

use App\Entity\Courrier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CourrierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reference', TextType::class, [
                'label' => 'Numéro',
                'required' => false,
                'attr' => ['class' => 'form-control'], // Ajout de la classe Bootstrap
            ])
            ->add('objet', TextType::class, [
                'label' => 'Objet',
                'required' => false,
                /*'constraints' => [
                    new NotBlank([
                        'message' => 'L’objet du courrier est obligatoire.',
                    ]),
                ],*/
                'attr' => ['class' => 'form-control'],
            ])
            ->add('contenu', TextareaType::class, [
                'label' => 'Contenu du courrier',
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 5, 'placeholder' => 'Saisissez le contenu du courrier...'],
            ])
            ->add('expediteur', TextType::class, [
                'label' => 'Émetteur',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('destinataire', ChoiceType::class, [
                'label' => 'Destinataire',
                'required' => false,
                'choices' => [
                    'Direction Générale' => 'Direction Générale',
                    'Direction Financière' => 'Direction Financière',
                    'Direction RH' => 'Direction RH',
                    'Direction Équipements' => 'Direction Équipements',
                ],
                'placeholder' => 'Sélectionnez...',
                'attr' => ['class' => 'form-select'], // Ajout de la classe Bootstrap
            ])
            ->add('statut', ChoiceType::class, [ // "Type" in the mockup
                'label' => 'Type',
                'required' => false,
                'choices' => [
                    'Entrant' => 'entrant',
                    'Sortant' => 'sortant',
                    'Interne' => 'interne',
                ],
                'attr' => ['class' => 'form-select'],
            ])
            ->add('dateReception', DateTimeType::class, [
                'label' => 'Date de signature',
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('nature', ChoiceType::class, [
                'label' => 'Nature',
                'required' => false,
                'choices'  => [
                    'Demande' => 'demande',
                    'Facture' => 'facture',
                    'Bon de commande' => 'bon-commande',
                    'Note de service' => 'note',
                    'Invitation' => 'invitation',
                    'Rapport' => 'rapport',
                ],
                'placeholder' => 'Sélectionnez...',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('gestionnaire', ChoiceType::class, [
                'label' => 'Gestionnaire',
                'required' => false,
                'choices' => [
                    'Aziza Moumbaga' => 'Aziza Moumbaga',
                    'Pierre Nziengui' => 'Pierre Nziengui',
                    'Laure Mboumba' => 'Laure Mboumba',
                    'Serge Obiang' => 'Serge Obiang',
                ],
                'placeholder' => 'Sélectionnez...',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('responsable', ChoiceType::class, [
                'label' => 'Responsable administratif',
                'required' => false,
                'choices' => [
                    'M. Pierre Nziengui – Directeur Général' => 'M. Pierre Nziengui – Directeur Général',
                    'Mme. Marie Obame – Directrice Financière' => 'Mme. Marie Obame – Directrice Financière',
                    'M. Jean Koumba – Directeur RH' => 'M. Jean Koumba – Directeur RH',
                ],
                'placeholder' => 'Sélectionnez...',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('pieceJointes', CollectionType::class, [
                'entry_type' => PieceJointeType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Courrier::class,
        ]);
    }
}
