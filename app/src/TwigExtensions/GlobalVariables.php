<?php

namespace App\TwigExtensions;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use App\Repository\SettingRepository;
use App\Repository\PageRepository;

class GlobalVariables extends AbstractExtension implements GlobalsInterface
{
    /**
     * Setting repository.
     *
     * @var \App\Repository\SettingRepository $settingRepository
     */
    private $settingRepository;

    /**
    * Page repository.
    *
    * @var \App\Repository\PageRepository $pageRepository
    */
    private $pageRepository;

    public function __construct(SettingRepository $settingRepository, PageRepository $pageRepository)
    {
        $this->settingRepository = $settingRepository;
        $this->pageRepository = $pageRepository;
    }

    public function getGlobals()
    {
        $pageTitle = $this->settingRepository->getPageTitle();
        $publishedPages = $this->pageRepository->queryAllByPublished();

        return [
            'pageTitle' => $pageTitle,
            'mainMenuItems' => $publishedPages
        ];
    }
}
