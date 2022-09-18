<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieFormType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    private $em;
    private $movieRepository;
    public function __construct(EntityManagerInterface $em, MovieRepository $movieRepository) 
    {
        $this->em = $em;
        $this->movieRepository = $movieRepository;
    }

    #[Route('/movie', name: 'movies')]
    public function index(): Response
    {
        $movies = $this->movieRepository->findAll();

        return $this->render('movie/index.html.twig', [
            'movies' => $movies
        ]);
    }
        // findAll() = SELECT * FROM movies  -> $movies = $repo->findAll();
        // find() = SELECT * FROM movies WHERE id = 10  -> $movies = $repo->findBy(['id' => '10']);
        // findBy() = SELECT * FROM movies ORDER BY id DESC  -> $movies = $repo->findBy([], ['id' => 'DESC']);
        // findOneBy() = SELECT * FROM movies WHERE id = 10 AND title = 'The Dark Knight' ORDER BY id DESC -> $movies = $repo->findOneBy(['id' => 10, 'title' => 'The Dark Knight' ], ['id' => 'DESC'])
        // count() = SELECT COUNT() FROM movies WHERE id = 11 -> $movies = $repo->count(['id' => 11])

        // $repo = $this->em->getRepository(Movie::class);
        // $movies = $repo->findOneBy(['id' => 10, 'title' => 'The Dark Knight' ], ['id' => 'DESC']);

        // return $this->render('movie/index.html.twig');

    #[Route('/movies/create', name: 'create_movie')]
    public function create(Request $request): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieFormType::class, $movie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $newMovie = $form->getData();

            dd($newMovie);
            exit;
        }

        return $this->render('/movie/create.html.twig', [
            'form' => 
        $form->createView()]);
    }

    #[Route('/movies/{id}', methods: ['GET'], name: 'show_movie')]
    public function show($id): Response
    {
        $movie = $this->movieRepository->find($id);

        return $this->render('movie/show.html.twig', [
            'movie' => $movie
        ]);
    }
    
}
