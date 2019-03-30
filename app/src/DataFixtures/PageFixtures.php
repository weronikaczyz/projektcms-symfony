<?php
/**
 * Page fixtures.
 */
namespace App\DataFixtures;

use App\Entity\Page;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Class PageFixtures
 */
class PageFixtures extends Fixture
{
    /**
    * Faker.
    *
    * @var \Faker\Generator
    */
    protected $faker;

    /**
    * Object manager.
    *
    * @var \Doctrine\Common\Persistence\ObjectManager
    */
    protected $manager;

    public function load(ObjectManager $manager)
    {
        $this->faker = Factory::create();
        $this->manager = $manager;

        for ($i = 0; $i < 10; ++$i) {
            $page = new Page();
            $page->setTitle($this->faker->sentence);
            $page->setContent($this->faker->paragraph);
            $page->setPublished($this->faker->boolean(50));
            $this->manager->persist($page);
        }

        $manager->flush();
    }
}
