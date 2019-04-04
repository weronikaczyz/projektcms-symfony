<?php
/**
 * Page fixtures.
 */
namespace App\DataFixtures;

use App\Entity\Page;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class PageFixtures
 */
class PageFixtures extends AbstractBaseFixtures
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
            $page->setPublished($this->faker->boolean(50));
            $this->manager->persist($page);
        }

        $manager->flush();
    }
}
