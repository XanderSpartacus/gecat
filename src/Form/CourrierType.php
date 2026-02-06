<?php

namespace App\Form;

use App\Entity\Courrier;
use App\Enum\CourrierStatut;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourrierType extends AbstractType
{
    public function __construct(
        private Security $security
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Courrier $courrier */
        $courrier = $options['data'] ?? null;
        $user = $this->security->getUser();

        // Droits d'édition complets : Admin ou Propriétaire du courrier
        $canEditAll = $this->security->isGranted('ROLE_ADMIN') ||
            ($courrier && $courrier->getGestionnaire() === $user?->getUserIdentifier());

        $builder
            ->add('reference', TextType::class, [
                'label' => 'Numéro',
                'required' => false,
                'disabled' => !$canEditAll,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('objet', TextType::class, [
                'label' => 'Objet',
                'required' => false,
                'disabled' => !$canEditAll,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('contenu', TextareaType::class, [
                'label' => 'Contenu du courrier',
                'required' => false,
                'disabled' => !$canEditAll,
                'attr' => ['class' => 'form-control', 'rows' => 5],
            ])
            ->add('expediteur', TextType::class, [
                'label' => 'Émetteur',
                'required' => false,
                'disabled' => !$canEditAll,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('destinataire', ChoiceType::class, [
                'label' => 'Destinataire',
                'required' => false,
                'disabled' => !$canEditAll,
                'choices' => [
                    'Direction Générale' => 'Direction Générale',
                    'Direction Financière' => 'Direction Financière',
                    'Direction RH' => 'Direction RH',
                    'Direction Équipements' => 'Direction Équipements',
                ],
                'attr' => ['class' => 'form-select'],
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type',
                'required' => false,
                'disabled' => !$canEditAll,
                'choices' => [
                    'Entrant' => 'entrant',
                    'Sortant' => 'sortant',
                    'Interne' => 'interne',
                ],
                'attr' => ['class' => 'form-select'],
            ])
            ->add('statut', EnumType::class, [
                'class' => CourrierStatut::class,
                'label' => 'Statut du traitement',
                'choice_label' => fn (CourrierStatut $choice) => $choice->getLabel(),
                'attr' => ['class' => 'form-select'],
            ])
            ->add('dateReception', DateTimeType::class, [
                'label' => 'Date de signature',
                'widget' => 'single_text',
                'disabled' => !$canEditAll,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('nature', ChoiceType::class, [
                'label' => 'Nature',
                'required' => false,
                'disabled' => !$canEditAll,
                'choices'  => [
                    'Demande' => 'demande',
                    'Facture' => 'facture',
                    'Bon de commande' => 'bon-commande',
                    'Note de service' => 'note',
                    'Invitation' => 'invitation',
                    'Rapport' => 'rapport',
                ],
                'attr' => ['class' => 'form-select'],
            ])
            ->add('gestionnaire', ChoiceType::class, [
                'label' => 'Gestionnaire',
                'required' => false,
                'disabled' => !$this->security->isGranted('ROLE_ADMIN'),
                'choices' => [
                    'Admin' => 'admin@gecat.ga',
                    'Gestionnaire' => 'gestionnaire@gecat.ga',
                ],
                'attr' => ['class' => 'form-select'],
            ])
            ->add('responsable', ChoiceType::class, [
                'label' => 'Responsable administratif',
                'required' => false,
                'disabled' => !$canEditAll,
                'choices' => [
                    'M. Pierre Nziengui – Directeur Général' => 'M. Pierre Nziengui – Directeur Général',
                    'Mme. Marie Obame – Directrice Financière' => 'Mme. Marie Obame – Directrice Financière',
                    'M. Jean Koumba – Directeur RH' => 'M. Jean Koumba – Directeur RH',
                ],
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
