<?php

namespace WH\BlogBundle\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use APP\BlogBundle\Entity\Post;
use WH\BlogBundle\Form\Backend\PostType;
use WH\BlogBundle\Form\Backend\PostUpdateType;
use WH\CmsBundle\Form\Backend\BlocType;
use WH\CmsBundle\Model\TemplateRepository;
use WH\CmsBundle\Entity\Bloc;

/**
 * @Route("/admin/blog/post")
 */
class PostController extends Controller
{

    /**
     * @Route("/{page}", name="wh_admin_blog_posts", requirements={"page" = "\d+"}, defaults={"page" = 1} )
     * @param int $page
     * @param Request $request
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($page = 1, Request $request)
    {

        $session = $this->get('session');

        $em = $this->getDoctrine()->getManager();

        $sessionName = 'dataPostSearch';
        $data = $session->get($sessionName);

        if (!$data) $data = array();

        $form = $this->_returnFormSearch($data);

        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            $data = $form->getData();

            // set and get session attributes
            $session->set($sessionName, $data);

            return $this->redirect($request->headers->get('referer'));

        }

        $max = $request->query->get('max');

        $max = ($max) ? $max : 50;

        //Liste
        $entities = $em->getRepository('APPBlogBundle:Post')->get('paginate', array(
                'limit' => $max,
                'page' => $page,
                'conditions' => $data
            ), true);

        //Pagination
        $pagination = array(
            'page'         => $page,
            'route'        => 'wh_admin_blog_posts',
            'pages_count'  => ceil(count($entities) / $max),
            'route_params' => array(),
            'max'          => $max
        );


        return $this->render(
            'WHBlogBundle:Backend:Post/index.html.twig', array(
                'entities'   => $entities,
                'pagination' => $pagination,
                'form'       => $form->createView()
            )
        );

    }

    private function _returnFormSearch($data)
    {

        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder($data)
            ->add(
                'Template', 'entity',
                array(
                    'label'         => 'Type : ',
                    'class'         => 'APPCmsBundle:Template',
                    'property'      => 'name',
                    'query_builder' => function (TemplateRepository $er) {

                            return $er->get('query', array('conditions' => array('Template.type' => 'post')));
                        },
                    'required'      => false,
                    'data'          => (!empty($data['Template'])) ? $em->getReference("APPCmsBundle:Template", $data['Template']->getId()) : null

                )
            )

            ->getForm();

        return $form;

    }


    /**
     * @Route("/create", name="wh_admin_blog_post_create" )
     * @param Request $request
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $post = new Post();

        $form = $this->createForm(new PostType(), $post);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $em->persist($post);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Opération réussie');


            if ($form->get('editer')->isClicked()) {

                $url = $this->generateUrl('wh_admin_blog_post_update', array('Post' => $post->getId()));
            }else{

                $url = $this->generateUrl('wh_admin_blog_posts');
            }


            $response = new JsonResponse();


            $response->setData(
                array(
                    'valid' => true,
                    'redirect' => $url
                )
            );

            return $response;


        }

        return $this->render('WHBlogBundle:Backend:Post/create.html.twig', array(
            'form'      =>  $form->createView()
        ));

    }

    /**
     * @Route("/update/{Post}", name="wh_admin_blog_post_update" )
     * @param $Post
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @ParamConverter("Post", class="APPBlogBundle:Post")
     */
    public function updateAction($Post, Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new PostUpdateType(), $Post);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $em->persist($Post);

            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Post modifiée');

            if ($form->get('save_and_stay')->isClicked()) return $this->redirect($this->generateUrl('wh_admin_blog_post_update', array('Post' => $Post->getId())));

            return $this->redirect($this->generateUrl('wh_admin_blog_posts'));

        }

        return $this->render('WHBlogBundle:Backend:Post/update.html.twig', array(
            'Post'      => $Post,
            'form'      => $form->createView(),
        ));


    }


    /**
     * @Route("/delete/{Post}", name="wh_admin_blog_post_delete" )
     * @param $Post
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @ParamConverter("Post", class="APPBlogBundle:Post")
     */
    public function deleteAction($Post, Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $em->remove($Post);
        $em->flush();

        $request->getSession()->getFlashBag()->add('success', 'Opération réussie');

        return $this->redirect($request->headers->get('referer'));


    }

    /**
     * @Route("/publish/{Post}", name="wh_admin_blog_post_publish" )
     * @param $Post
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @ParamConverter("Post", class="APPBlogBundle:Post")
     */
    public function publishAction($Post, Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $status = ($Post->getStatus() == 'draft') ? 'published' : 'draft';
        $Post->setStatus($status);

        $em->persist($Post);

        $em->flush();

        $request->getSession()->getFlashBag()->add('success', 'Opération réussie');

        return $this->redirect($this->generateUrl('wh_admin_blog_posts'));


    }


}