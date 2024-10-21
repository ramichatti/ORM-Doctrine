<?php

// src/Form/AuthorType.php

namespace App\Form;

use App\Entity\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType; // Pour le champ email
use Symfony\Component\Form\Extension\Core\Type\TextType; // Pour le champ texte
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Nom d\'utilisateur',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('email', EmailType::class, [ // Utilisez EmailType pour le champ email
                'label' => 'Email',
                'attr' => ['class' => 'form-control'],
            ]);
            //->add('Add author', SubmitType::class)
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Author::class, // Lien vers l'entité Author
        ]);
    }
}


