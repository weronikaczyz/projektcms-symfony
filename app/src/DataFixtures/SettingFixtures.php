<?php

namespace App\DataFixtures;

use App\Entity\Setting;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\DataFixtures\PageFixtures;
use App\Repository\PageRepository;

class SettingFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Page repository.
     *
     * @var \App\Repository\PageRepository $pageRepository
     */
    private $pageRepository;

    /**
     * Constructor.
     *
     * @param \App\Repository\PageRepository $pageRepository Page repository.
     */
    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    /**
    * Load.
    *
    * @param \Doctrine\Common\Persistence\ObjectManager $manager
    */
    public function loadData(ObjectManager $manager): void
    {
        $firstPublishedPage = $this->pageRepository->queryOneByPublished();
        $homePageSetting = new Setting();
        $homePageSetting->setName('homepage');
        $homePageSetting->setValue($firstPublishedPage->getId());
        $homePageSetting->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
        $homePageSetting->setUpdatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
        $this->manager->persist($homePageSetting);

        $titleSetting = new Setting();
        $titleSetting->setName('title');
        $titleSetting->setValue($this->faker->sentence);
        $titleSetting->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
        $titleSetting->setUpdatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
        $this->manager->persist($titleSetting);

        $manager->flush();
    }

    /**
    * Make sure pages are loaded before settings.
    *
    * @return array
    */
    public function getDependencies()
    {
        return array(
            PageFixtures::class,
        );
    }
}
