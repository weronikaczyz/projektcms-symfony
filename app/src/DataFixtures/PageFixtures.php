<?php
/**
 * Page fixtures.
 */
namespace App\DataFixtures;

use App\Entity\Page;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\DataFixtures\UserFixtures;

/**
 * Class PageFixtures
 */
class PageFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
    * Load.
    *
    * @param \Doctrine\Common\Persistence\ObjectManager $manager
    */
    public function loadData(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; ++$i) {
            $page = new Page();
            $page->setTitle($this->faker->sentence);
            $page->setContent($this->faker->paragraph);
            $page->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $page->setUpdatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $page->setAuthor($this->getRandomReference('users'));
            $page->setPublished($this->faker->boolean(50));
            $this->manager->persist($page);
        }

        $manager->flush();
    }

    /**
     * Make sure users are loaded before pages.
     *
     * @return array
     */
    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
