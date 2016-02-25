<?php

namespace WH\BlogBundle\Form\Backend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use WH\CmsBundle\Form\Backend\PageBlocType;

class PostBlocType extends PageBlocType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WH\BlogBundle\Entity\PostBloc'
         ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wh_cmsbundle_page_bloc';
    }
}
