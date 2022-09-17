<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MovieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $movie = new Movie();
        $movie->setTitle('The Dark Knight');
        $movie->setReleaseYear(2008);
        $movie->setImagePath('https://cdn.pixabay.com/photo/2018/03/13/10/36/silhouette-3222158__340.png');
        // add dumy data to movie table 
        $movie->addActor($this->getReference('actor_1'));
        $movie->addActor($this->getReference('actor_2'));
        $manager->persist($movie);

        $movie2 = new Movie();
        $movie2->setTitle('Avengers');
        $movie2->setReleaseYear(2018);
        $movie2->setImagePath('https://cdn.pixabay.com/photo/2017/08/27/23/59/capitanamerica-2688069__340.jpg');
                // add dumy data to movie table 
                $movie2->addActor($this->getReference('actor_3'));
                $movie2->addActor($this->getReference('actor_4'));
        $manager->persist($movie2);

        $manager->flush();
    }
}
