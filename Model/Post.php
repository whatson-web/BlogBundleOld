<?php

namespace WH\BlogBundle\Model;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use WH\CmsBundle\Model\Content as WHCmsContent;


/**
 * Post
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="WH\BlogBundle\Entity\PostRepository")
 */
abstract class Post extends WHCmsContent
{

    /**
     * @var string
     *
     * @ORM\Column(name="score", type="integer", nullable=true)
     */
    protected $score;


    /**
     * @ORM\ManyToOne(targetEntity="APP\CmsBundle\Entity\Page")
     */
    protected $page;

    /**
     * @ORM\ManyToOne(targetEntity="APP\CmsBundle\Entity\Template")
     */
    protected $template;

    /**
     * @ORM\OneToOne(targetEntity="APP\CmsBundle\Entity\File", cascade={"persist", "remove"})
     */
    protected $thumb;

    /**
     * @ORM\OneToMany(targetEntity="WH\BlogBundle\Entity\PostBloc", mappedBy="post", cascade={"persist", "remove"})
     */
    protected $postBlocs;


    public function __construct() {

        parent::__construct();

        $this->score = 0;


    }


    /**
     * Set page
     *
     * @param \APP\CmsBundle\Entity\Page $page
     * @return Post
     */
    public function setPage(\APP\CmsBundle\Entity\Page $page = null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \APP\CmsBundle\Entity\Page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set score
     *
     * @param integer $score
     * @return Post
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return integer
     */
    public function getScore()
    {
        return $this->score;
    }


    /**
     * Set template
     *
     * @param \APP\CmsBundle\Entity\Template $template
     * @return Post
     */
    public function setTemplate(\APP\CmsBundle\Entity\Template $template = null)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template
     *
     * @return \APP\CmsBundle\Entity\Template
     */
    public function getTemplate()
    {
        return $this->template;
    }


    /**
     * Set thumb
     *
     * @param \APP\CMSBundle\Entity\File $thumb
     * @return Post
     */
    public function setThumb(\APP\CMSBundle\Entity\File $thumb = null)
    {
        $this->thumb = $thumb;

        return $this;
    }

    /**
     * Get thumb
     *
     * @return \APP\CMSBundle\Entity\File
     */
    public function getThumb()
    {
        return $this->thumb;
    }




    /**
     * Add postBlocs
     *
     * @param \WH\BlogBundle\Entity\PostBloc $postBlocs
     * @return Post
     */
    public function addPostBloc(\WH\BlogBundle\Entity\PostBloc $postBlocs)
    {
        $this->postBlocs[] = $postBlocs;

        $postBlocs->setPost($this);

        return $this;
    }

    /**
     * Remove postBlocs
     *
     * @param \WH\BlogBundle\Entity\PostBloc $postBlocs
     */
    public function removePostBloc(\WH\BlogBundle\Entity\PostBloc $postBlocs)
    {
        $this->postBlocs->removeElement($postBlocs);

        $postBlocs->setPost(null);

    }

    /**
     * Get postBlocs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPostBlocs()
    {
        return $this->postBlocs;
    }

}
