<?php

namespace App\Controller;

use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/movie', name: 'app_movie')]
    public function index(): Response
    {
        // findAll() = SELECT * FROM movies  -> $movies = $repo->findAll();
        // find() = SELECT * FROM movies WHERE id = 10  -> $movies = $repo->findBy(['id' => '10']);
        // findBy() = SELECT * FROM movies ORDER BY id DESC  -> $movies = $repo->findBy([], ['id' => 'DESC']);
        // findOneBy() = SELECT * FROM movies WHERE id = 10 AND title = 'The Dark Knight' ORDER BY id DESC -> $movies = $repo->findOneBy(['id' => 10, 'title' => 'The Dark Knight' ], ['id' => 'DESC'])
        // count() = SELECT COUNT() FROM movies WHERE id = 11 -> $movies = $repo->count(['id' => 11])

        $repo = $this->em->getRepository(Movie::class);
        $movies = $repo->findOneBy(['id' => 10, 'title' => 'The Dark Knight' ], ['id' => 'DESC']);

        return $this->render('movie/index.html.twig');
    }
    
}
