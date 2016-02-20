<?php

namespace WH\BlogBundle\Form\Backend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use APP\BlogBundle\entity\Post;
use WH\CmsBundle\Model\Content;
use WH\CmsBundle\Model\TemplateRepository;
use WH\CmsBundle\Model\PageRepository;
use WH\CmsBundle\Form\FileType;
use WH\CmsBundle\Form\SeoType;

class PostUpdateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('name', 'text')
            ->add('score', 'number', array('label' => 'Scores : ', 'required' =>false))
            ->add('status', 'choice', array('label' => 'Etat : ', 'choices' => Content::getStatusChoices()))
            ->add('title', 'text', array('label' => 'Titre de la page', 'required' => false))
            ->add('slugReplace', 'text', array('label' => '', 'required' => false, 'attr' => array('class' => 'form-control sm', 'placeholder' => 'Nouvelle valeur de l’url')))
            ->add('slugTechnique', 'text', array('label' => 'Slug technique', 'required' => false))
            ->add('resume', 'textarea',  array('label' => 'Résumé', 'required' => false))
            ->add('body', 'text', array('label' => 'Corp de texte', 'required' => false, 'attr' => array('class' => 'tinymce')))
            ->add('thumb', new FileType(), array('label' => 'Miniature', 'required' => false))
            ->add('Seo', new SeoType())

            ->add('page', 'entity', array(
                    'required' => false,
                    'label' => 'Page parente',
                    'class' => 'APPCmsBundle:Page',
                    'empty_value' => 'Pas de page parente',
                    'property' => 'indentedName',
                    'multiple' => false,
                    'expanded' => false,
                    'query_builder' => function(PageRepository $er) {

                            return $er->getChildrenQueryBuilder(null, null, 'root', 'asc', false);

                        }

                ))
            ->add('save_and_stay', 'submit', array('label' => 'Enregistrer', 'attr' => array('class' => 'btn btn-success')))
            ->add('save_and_back', 'submit', array('label' => 'Enregistrer et quitter ', 'attr' => array('class' => 'btn btn-primary')))
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
        return 'wh_blogbundle_post';
    }
}
