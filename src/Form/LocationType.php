<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('country')
            ->add('country', ChoiceType::class, array(
                'choices'  => array(
                    'Hrvatska' => 'Hrvatska',
                ),
            ))
            //->add('region')
            ->add('region', ChoiceType::class, array(
                'choices'  => array(
                    'Zagrebačka županija' => 'Zagrebačka županija',
                    'Krapinsko-zagorska županija' => 'Krapinsko-zagorska županija',
                    'Sisačko-moslavačka županija' => 'Sisačko-moslavačka županija',
                    'Karlovačka županija' => 'Karlovačka županija',
                    'Varaždinska županija' => 'Varaždinska županija',
                    'Koprivničko-križevačka županija' => 'Koprivničko-križevačka županija',
                    'Bjelovarsko-bilogorska županija' => 'Bjelovarsko-bilogorska županija',
                    'Primorsko-goranska županija' => 'Primorsko-goranska županija',
                    'Ličko-senjska županija' => 'Ličko-senjska županija',
                    'Virovitičko-podravska županija' => 'Virovitičko-podravska županija',
                    'Požeško-slavonska županija' => 'Požeško-slavonska županija',
                    'Brodsko-posavska županija' => 'Brodsko-posavska županija',
                    'Zadarska županija' => 'Zadarska županija',
                    'Osječko-baranjska županija' => 'Osječko-baranjska županija',
                    'Šibensko-kninska županija' => 'Šibensko-kninska županija',
                    'Vukovarsko-srijemska županija' => 'Vukovarsko-srijemska županija',
                    'Splitsko-dalmatinska županija' => 'Splitsko-dalmatinska županija',
                    'Istarska županija' => 'Istarska županija',
                    'Dubrovačko-neretvanska županija' => 'Dubrovačko-neretvanska županija',
                    'Međimurska županija' => 'Međimurska županija',
                    'Grad Zagreb' => 'Grad Zagreb',
                ),
            ))
            ->add('city')
            ->add('address')
            //->add('postalCode')
            //->add('currency')
            //->add('isHidden')
            //->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
