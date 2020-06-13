<?php

namespace EventsBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\DateTime;
use Gregwar\CaptchaBundle\Type\CaptchaType;
class EventsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $today = new \DateTime('now');
        $builder->add('name')
            ->add('location')->add('prix')->add('quantity')
            ->add('description',TextareaType::class,array('attr' => array('cols' => '30', 'rows' => '15')))
            ->add('start', DateTimeType::class, array(
                'widget' => 'single_text',
                'data' => new \DateTime(),
                'attr' => ['min' => $today->format('Y-m-d H:i:s')]))
            ->add('end', DateTimeType::class, array(
                'widget' => 'single_text',
                'data' => new \DateTime(),
                'attr' => ['min' => $today->format('Y-m-d H:i:s')]))
            ->add('imageFile',VichImageType::class)
            ->add('theme',EntityType::class, array('class'=> 'EventsBundle\Entity\Theme', 'choice_label'=>'nom'))->add('captcha', CaptchaType::class)
            ->add('Publier',SubmitType::class);
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EventsBundle\Entity\Events'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'eventsbundle_events';
    }


}
