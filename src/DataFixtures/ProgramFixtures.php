<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    const PROGRAMS = [
        'Walking dead' => [
            'summary' => 'Des zombies envahissent la terre',
            'category' => 'category_0',
        ],
        'Fear the walking dead' => [
            'summary' => 'Un spin-off de Walking Dead se déroulant à Los Angeles',
            'category' => 'category_0',
        ],
        'Supernatural' => [
            'summary' => 'Deux frères suivent les traces de leur père en tant que "chasseurs", combattant des êtres surnaturels maléfiques de toutes sortes',
            'category' => 'category_4',
        ],
        'WandaVision' => [
            'summary' => 'Combinant des éléments de sitcom traditionnelle à ceux de l\'Univers Marvel. Wanda Maximoff et Vision sont des super-héros vivant dans une banlieue idéalisée mais commençant à soupçonner que tout n\'est peut-être pas ce qu\'il paraît être.',
            'category' => 'category_6',
        ],
        'Lucifer' => [
            'summary' => 'Lucifer Morningstar en a assez d\'être serviteur dévoué en Enfer et décide de passer du temps sur Terre afin de mieux comprendre l\'humanité. Il s\'installe à Los Angeles, la cité des anges.',
            'category' => 'category_5',
        ],
    ];
    public function load(ObjectManager $manager)
    {
        $i = 0;
        foreach (self::PROGRAMS as $title => $data) {
            $program = new Program();
            $program->setTitle($title);
            $program->setSummary($data['summary']);
            $program->setCategory($this->getReference($data['category']));
            $manager->persist($program);
            $this->addReference('program_'.$i, $program);
            $i++;
        }

        $manager->flush();
    
    }
    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
          CategoryFixtures::class,
        ];
    }
}
