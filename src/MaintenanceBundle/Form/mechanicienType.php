<?php

namespace MaintenanceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Gregwar\CaptchaBundle\Type\CaptchaType;
class mechanicienType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')->add('captcha', CaptchaType::class)->
        add('mail')->
        add('prix')->
        add('numTel')->
        add('description',TextareaType::class,array('attr'=>array('cols'=>'10','rows'=>'5')))->
        add('experience')->
        add('region', ChoiceType::class ,[
          'choices' =>  ['tunis'=>'tunis',
                'bardo '=>'bardo',
                'ariena'=>'ariena',
                'morneg'=>'morneg',

            ]])->
        add('city',ChoiceType::class ,[
            'choices' =>  ['tunis'=>'tunis',
                'bardo '=>'bardo',
                'ariena'=>'ariena',
                'morneg'=>'morneg',

            ]])
            ->add('imageFile',VichImageType::class)
        ->add('adomicile',ChoiceType::class,array(
            'choices' => array(
                'oui' => '1',
                'non' => '0'
            )
        ));
   
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MaintenanceBundle\Entity\mechanicien'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'maintenancebundle_mechanicien';
    }


}
