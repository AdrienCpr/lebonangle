<?php

namespace App\Form;

use App\Entity\Advert;
use App\Entity\Category;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (!$options['is_edit']) {
            $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $advert = $event->getData();
                $form = $event->getForm();

                if (!$advert || null === $advert->getId()) {
                    $advert->setState("draft");
                    $advert->setCreatedAt(new \DateTime());
                }

                $form->add('title')
                    ->add('content')
                    ->add('author')
                    ->add('email')
                    ->add('price')
                    ->add('category', EntityType::class, [
                        'class' => Category::class,
                        'choice_label' => 'name',
                    ]);
            });
        } else {
            $builder->add('state');

            $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                $advert = $event->getData();

                if ($form->has('state') && $form->get('state')->getData() === 'published') {
                    $advert->setPublishedAt(new \DateTime());
                } else {
                    $advert->setPublishedAt(null);
                }
            });
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Advert::class,
            'is_edit' => false,
        ]);
    }
}
