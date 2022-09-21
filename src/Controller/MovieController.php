<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieFormType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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
            $imagePath = $form->get('imagePath')->getData();

            if($imagePath) {
                $newFileName = uniqid() . '.' . $imagePath->guessExtension();

                try {
                    $imagePath->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads',
                        $newFileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }
                $newMovie->setImagePath('/uploads/' . $newFileName);
            }
            $this->em->persist($newMovie);
            $this->em->flush();

            return $this->redirectToRoute('movies');


        }

        return $this->render('/movie/create.html.twig', [
            'form' => 
        $form->createView()]);
    }


    #[Route('/movies/delete/{id}', methods: ['GET', 'DELETE'] ,name: 'delete_movie')]
    public function delete($id, Request $request): Response
    {
        $movie = $this->movieRepository->find($id);
        $this->em->remove($movie);

        $this->em->flush();

        return $this->redirectToRoute('movies');

    }

    #[Route('/movies/edit/{id}', name: 'edit_movie')]
    public function edit($id, Request $request): Response
    {
        $movie = $this->movieRepository->find($id);
        $form = $this->createForm(MovieFormType::class, $movie);

        $form->handleRequest($request);
        $imagePath = $form->get('imagePath')->getData();

        if ($form->isSubmitted() && $form->isValid()){
            if ($imagePath){
                if ($movie->getImagePath() !== null){
                    if (file_exists(
                        $this->getParameter('kernel.project_dir') . $movie->getImagePath()
                    )){
                        $this->getParameter('kernel.project_dir') . $movie->getImagePath();
                    }
                        $newFileName = uniqid() . '.' . $imagePath->guessExtension();

                        try {
                            $imagePath->move(
                                $this->getParameter('kernel.project_dir') . '/public/uploads',
                                $newFileName
                            );
                        } catch (FileException $e) {
                            return new Response($e->getMessage());
                        }

                        $movie->setImagePath('/uploads/' . $newFileName);
                        $this->em->flush();

                        return $this->redirectToRoute('movies');
                    
                }
            }else{
                $movie->setTitle($form->get('title')->getData());
                $movie->setReleaseYear($form->get('releaseYear')->getData());
                $movie->setDescription($form->get('description')->getData());

                $this->em->flush();
                return $this->redirectToRoute('movies');
            }
        }

        return $this->render('movie/edit.html.twig', [
            'movie' => $movie,
            'form' => $form->createView()
        ]);
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
