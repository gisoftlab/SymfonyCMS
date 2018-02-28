<?php

namespace Web\ProductBundle\Twig;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Knp\Component\Pager\Paginator;


class ProductExtension extends \Twig_Extension
{

    protected $doctrine;
    protected $paginator;
    protected $container;

    public function __construct(ContainerInterface $container)
    {

        $this->container = $container;
    }


    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('checkCommentIsUser', array($this, 'checkCommentIsUser')),
        );
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('mostVotedComment', array($this, 'mostVotedCommentFilter')),
            new \Twig_SimpleFilter('injectTags', array($this, 'injectTagsFilter')),
            new \Twig_SimpleFilter('showFirstImgFromPost', array($this, 'showFirstImgFromPostFilter')),
        );
    }

    /**
     * @param $comment
     * @param $user
     * @return string
     */
    public function checkCommentIsUser($comment, $user)
    {

        if (!$comment) {
            return "";
        }

        if (!$user) {
            return "";
        }

        if ($comment->getUser()->getId() == $user->getId()) {
            return "markComment";
        }

    }

    /**
     * @param $post
     * @param bool $max
     * @return bool
     */
    public function mostVotedCommentFilter($post, $max = false)
    {

        $commentsOut = array();

        if (!$post) {
            return false;
        }

        $comments = $this->doctrine->getRepository('AppBlogBundle:Comment')->retrivateSorted($post);

        $postID = $post->getId();
        $commentMAX = $this->doctrine->getRepository('AppBlogBundle:Comment')->retrivateVotedComments($postID);


        $maxCommentId = null;
        if ($commentMAX) {
            $maxCommentId = $commentMAX->getId();
        }

//        if($max){
//            return $commentMAX;
//        }else{
        if ($comments) {
            $commentsOut[] = $commentMAX;
            foreach ($comments as $key => $comment) {
                if ($comment->getid() != $maxCommentId) {
                    $commentsOut[] = $comment;
                }
            }
        }

        $request = $this->container->get("request");

        $page = $request->get("page") ? $request->get("page") : 1;
        $limit = $this->container->getParameter('paginator.limit.per.comment');

        //$paginator = self::$context->get('knp_paginator'); //(1)
        // uses event subscribers to paginate $target
        $slice = $this->paginator->paginate($commentsOut, $page, $limit); //(3)

        return $slice;
//        }              
    }

    /**
     * @param $post
     * @return string
     */
    public function showFirstImgFromPostFilter($post)
    {
        $posStart = strpos($post, "<img");
        $post = substr($post, $posStart);
        $posEnd = strpos($post, "/>");
        $post = substr($post, $posStart - 3, $posEnd + 2);

        return $post;


        //   $posStart = stripos($post, "<p><img");
        //      $post = substr($post, $posStart);
        //    $posEnd = stripos($post, "/></p>");
        //     $post = substr($post, $posStart+1,$posEnd);
        //    return $post;

        //     return $commentsOut;

    }

    /**
     * @param $name
     * @param $htmlTags
     * @return string
     */
    public function injectTagsFilter($name, $htmlTags)
    {

        $htmlTagsArr = array();
        $htmlTagsArr = explode(",", $htmlTags);
        $nameOut = "";

        $randno = $htmlTagsArr[array_rand($htmlTagsArr)];

        if ($randno != "none") {
            $nameOut = "<".$randno.">".$name."</".$randno.">";
        } else {
            $nameOut = $name;
        }

        return $nameOut;
    }

    public function getName()
    {
        return 'product_extension';
    }

}
