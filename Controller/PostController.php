<?php

namespace WH\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use APP\BlogBundle\Entity\Post;

/**
 * @Route("/post")
 */
class PostController extends Controller
{

    /**
     * @Route("/{Slug}.html", name="wh_front_post_view" )
     * @param $Post
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @ParamConverter("Post", class="APPBlogBundle:Post")
     */
    public function viewAction($slug)
    {

        $em = $this->getDoctrine()->getManager();

        $repoPost = $em->getRepository('WHBlogBundle:Post');
        $repoPage = $em->getRepository('WHCmsBundle:Page');

        $Post = $repoPost->findOneBySlug($slug);

        if(!$Post) {

            //Là faudrait aller voir dans l'historique
            throw $this->createNotFoundException('Cette page n’existe plus ou a été déplacée');

        }

        $path = $repoPage->getPath($Post->getPage());


        return $this->render('WHBlogBundle:Post:front/'.$Post->getTemplate().'.html.twig', array(
            'Post' => $Post,
            'path' => $path,
            'Page' => $Post->getPage()
        ));

    }


}