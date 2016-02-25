<?php

namespace WH\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use WH\CmsBundle\Model\PageBloc as BasePageBloc;

/**
 * BlogBloc
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class PostBloc extends BasePageBloc
{


    /**
     * @ORM\ManyToOne(targetEntity="APP\BlogBundle\Entity\Post", inversedBy="postBlocs")
     */
    protected $post;

    /**
     * Set post
     *
     * @param \APP\BlogBundle\Entity\Post $post
     * @return PageBloc
     */
    public function setPost(\APP\BlogBundle\Entity\Post $post = null)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return \APP\BlogBundle\Entity\Post
     */
    public function getPost()
    {
        return $this->post;
    }
}
