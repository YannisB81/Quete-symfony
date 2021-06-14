<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for($i=0; $i < 50; $i++){
            $episode = new Episode();
            $faker = Faker\Factory::create('fr_FR');
            $episode->setNumber($faker->numberBetween(1,30));
            $episode->setTitle($faker->text($maxNbChars = rand(20,50)) );
            $episode->setSynopsis($faker->text);
            $episode->setSeason($this->getReference('season_' . rand(0,19)));
            $manager->persist($episode);

        }

        $manager->flush();
    }
    public function getDependencies()
    {
        return [SeasonFixtures::class];
    }
}
