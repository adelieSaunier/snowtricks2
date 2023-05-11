<?php

namespace App\Controller;

use App\Entity\Tricks;
use App\Repository\CategoriesRepository;
use App\Repository\TricksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    private const TRICKS_LIMIT = 10;

    #[Route('/', name: 'app_main')]
    public function index(CategoriesRepository $categoriesRepository, TricksRepository $tricks, Session $session): Response
    {

        $session->set('tricks_offset', self::TRICKS_LIMIT);
        
        $showLoadMoretricksButton = true;

        if($session->get('tricks_offset') >= $tricks->count([])) {
            $showLoadMoretricksButton = false;
        }

        return $this->render('main/index.html.twig', [
            'categories' => $categoriesRepository->findall(),
            'tricks' => $tricks->findBy([], null, self::TRICKS_LIMIT, null),
            'showLoadMore' => $showLoadMoretricksButton,
        ]);
    }

    #[Route('/loadmore_tricks', name: 'loadmore_tricks', methods: ['POST'])]
    public function loadMoreTricks(TricksRepository $trickRepository, Session $session): JsonResponse
    {
        $offset = $session->get('tricks_offset');
        $session->set('tricks_offset', $offset + self::TRICKS_LIMIT);
        $showLoadMoreTricksButton = true;
        $tricks = $trickRepository->findBy([], null, self::TRICKS_LIMIT, $offset);

        dump($session->get('tricks_offset'), $trickRepository->count([]));

        if($session->get('tricks_offset') >= $trickRepository->count([])) {
            $showLoadMoreTricksButton = false;
        }

        $jsonContent = [];
        
        array_push($jsonContent, ['showLoadMore' => $showLoadMoreTricksButton]);

        foreach($tricks as $trick) {
            
            array_push($jsonContent, [
                'id' => $trick->getId(),
                'name' => $trick->getName(),
                'slug' => $trick->getSlug(),
                'mainimage' => $trick->getMainimage(),
            ]);
        }

        return new JsonResponse(json_encode($jsonContent));
    }


}
