<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;

class ArticleController extends FOSRestController
{
    // …

    /**
     * @Rest\Post(
     *    path = "/articles",
     *    name = "app_article_create"
     * )
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("article", converter="fos_rest.request_body")
     */
    public function createAction(Article $article)
    {
        $em = $this->getDoctrine()->getManager();

        $em->persist($article);
        $em->flush();

        return $this->view($article, Response::HTTP_CREATED,
          ['Location' => $this->generateUrl(
            'app_article_show',
            ['id' => $article->getId(), UrlGeneratorInterface::ABSOLUTE_URL])
          ]
        );
    }

    /**
     * @Get(
     *     path = "/articles/{id}",
     *     name = "app_article_show",
     *     requirements = {"id"="\d+"}
     * )
     * @View
     */
    public function showAction(Article $article)
    {
        return $article;
    }

    /**
     * @Rest\Get("/articles", name="app_article_list")
     * @View
     */
    public function listAction()
    {
        $articles = $this->getDoctrine()->getRepository('AppBundle:Article')->findAll();

        return $articles;
    }
}
