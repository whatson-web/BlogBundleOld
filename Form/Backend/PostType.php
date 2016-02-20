<?php

namespace WH\BlogBundle\Form\Backend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use APP\BlogBundle\entity\Post;
use WH\CmsBundle\Model\TemplateRepository;
use WH\CmsBundle\Model\PageRepository;

class PostType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label' => 'Nom de l’article : '))
            ->add('template', 'entity', array(
                    'label' => 'Type de post : ',
                    'class' => 'APPCmsBundle:Template',
                    'property' => 'name',
                    'query_builder' => function(TemplateRepository $er) {

                            return $er->get('query', array('conditions' => array('Template.type' => 'post')));

                        }

                ))
            ->add('editer', 'submit', array('label' => 'Créer et editer', 'attr' => array('class' => 'btn btn-primary')))
            ->add('new', 'submit', array('label' => 'Créer ', 'attr' => array('class' => 'btn btn-success')))

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'APP\BlogBundle\Entity\Post'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wh_blogbundle_post_create';
    }
}
