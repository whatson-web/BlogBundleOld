<?php

namespace WH\BlogBundle\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use WH\CmsBundle\Form\Backend\BlocType;
use WH\CmsBundle\Entity\Bloc;
use APP\CmsBundle\Entity\TemplateRepository;


class BlocController extends Controller
{

    private $fields = array();


    public function createAction($template, Request $request) {

        $em = $this->getDoctrine()->getManager();

        $Bloc = new Bloc();

        $form = $this->_returnForm($Bloc);

        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            $Bloc->setTemplate($template);
            $Bloc->setDatas(array(
                    'limit'         => $form->get('limit')->getData(),
                    'PostScoreMin'  => $form->get('PostScoreMin')->getData(),
                    'PostScoreMax'  => $form->get('PostScoreMax')->getData(),
                    'Template'      => $form->get('Template')->getData(),
                )
            );

            $em->persist($Bloc);
            $em->flush();

            $response = new JsonResponse();


            $response->setData(
                array(
                    'valid' => true,
                    'redirect' => $this->generateUrl('wh_admin_cms_blocs')
                )
            );

            return $response;


        }

        return $this->render('WHBlogBundle:Backend:Bloc/create.html.twig', array(
                'form'     => $form->createView(),
                'template' => $template
            ));

    }


    public function updateAction($Bloc, Request $request) {

        $em = $this->getDoctrine()->getManager();

        $form = $this->_returnForm($Bloc);

        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            $Bloc->setDatas(array(
                    'limit'         => $form->get('limit')->getData(),
                    'PostScoreMin'  => $form->get('PostScoreMin')->getData(),
                    'PostScoreMax'  => $form->get('PostScoreMax')->getData(),
                    'Template'      => $form->get('Template')->getData(),
                )
            );

            $em->persist($Bloc);
            $em->flush();


            return $this->redirect($this->generateUrl('wh_admin_cms_blocs'));


        }

        return $this->render('WHBlogBundle:Backend:Bloc/update.html.twig', array(
                'form'     => $form->createView(),
                'Bloc'     => $Bloc
            ));

    }



    private function _returnForm($Bloc) {


        $form = $this->createForm(new BlocType(), $Bloc);
        $em = $this->getDoctrine()->getManager();

        $datas = $Bloc->getDatas();

        $form
            ->add(
                'limit', 'text',
                array(
                    'label'         => 'Limit : ',
                    'mapped'        => false,
                    'required'      => false,
                    'data'          => (!empty($datas['limit'])) ? $datas['limit'] : null

                )
            )
            ->add(
                'PostScoreMin', 'number',
                array(
                    'label'         => 'Score min : ',
                    'mapped'        => false,
                    'required'      => false,
                    'data'          => (!empty($datas['PostScoreMin'])) ? $datas['PostScoreMin'] : null

                )
            )
            ->add(
                'PostScoreMax', 'number',
                array(
                    'label'         => 'Score max : ',
                    'mapped'        => false,
                    'required'      => false,
                    'data'          => (!empty($datas['PostScoreMax'])) ? $datas['PostScoreMax'] : null

                )
            )
            ->add(
                'Template', 'entity',
                array(
                    'label'         => 'Type de post : ',
                    'mapped'        => false,
                    'class'         => 'APPCmsBundle:Template',
                    'property'      => 'name',
                    'query_builder' => function (TemplateRepository $er) {

                            return $er->get('query', array('conditions' => array('Template.type' => 'post')));
                        },
                    'required'      => false,
                    'data'          => (!empty($datas['Template'])) ? $em->getReference("APPCmsBundle:Template", $datas['Template']->getId()) : null

                )
            )

        ;

        return $form;


    }

    private function _listField() {





    }




}