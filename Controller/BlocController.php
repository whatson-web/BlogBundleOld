<?php

namespace WH\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class BlocController extends Controller
{

	public function viewAction($slug)
    {

        $em = $this->getDoctrine()->getManager();

        $bloc = $em->getRepository('WHCmsBundle:Bloc')->findOneBySlug($slug);

        $datas = $bloc->getDatas();
        $limit = (empty($datas['limit'])) ? false : $datas['limit'];

        unset($datas['limit']);

        $datas = array(
            'limit' => $limit,
            'conditions' => $datas

        );

        $Posts = $em->getRepository('APPBlogBundle:Post')->get('all', $datas);

        return $this->render($bloc->getView(), array(
                'Posts' => $Posts
            ));

    }


}