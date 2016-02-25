<?php

namespace WH\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


/**
 * @Route("/post")
 */
class PostController extends Controller
{

    /**
     * @Route("/{slug}.html", name="wh_front_post_view" )
     *
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction($slug)
    {

        $em = $this->getDoctrine()->getManager();

        $Post = $em->getRepository('APPBlogBundle:Post');
        $Page = $em->getRepository('APPCmsBundle:Page');

        $post = $Post->findOneBySlug($slug);

        if (!$post) {

            throw $this->createNotFoundException('Cette page n’existe plus ou a été déplacée');
        }

        $path = $Page->getPath($post->getPage());

        $renderVars = array(
            'post' => $post,
            'path' => $path,
        );

        $view = $post->getTemplate()->getTplt();

        if ($this->get('templating')->exists($view)) {

            return $this->render($view, $renderVars);

        } else {

            return $this->render('WHBlogBundle:Post:view.html.twig', $renderVars);

        }

    }


}