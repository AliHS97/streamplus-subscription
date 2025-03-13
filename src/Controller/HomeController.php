<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Request $request): Response
    {
        $status = $request->query->get('success');

        $routeParams = [];
        if ($status) {
            $routeParams['success'] = $status;
        }

        return $this->redirectToRoute('subscription-user-information', $routeParams);
    }
}
