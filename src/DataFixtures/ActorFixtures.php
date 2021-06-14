<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
            ProgramFixtures::class
        ];
    }
    public function load(ObjectManager $manager)
    {
        for($i=0; $i<20;$i++){
            $faker  =  Faker\Factory::create('fr_FR');
            $name = $faker->name;
            $actor = new Actor();
            $actor->setName($name);
            for($j=0; $j<rand(1,4);$j++){
                $actor->addProgram($this->getReference('program_' . rand(0,4)));
            }

            $manager->persist($actor);

        }

        $manager->flush();
    }
}
