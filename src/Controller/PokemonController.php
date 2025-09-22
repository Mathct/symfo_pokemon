<?php

namespace App\Controller;

use App\Entity\Pokemon;
use App\Form\PokemonType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PokemonController extends AbstractController
{
    #[Route('/pokemon')]
    public function index(): Response
    {
        return $this->render('pokemon/index.html.twig', [
            
        ]);
    }

    #[Route('/pokemon/new')]
    public function new(): Response
    {
        //$pokemon = new Pokemon();

        $formPokemon = $this->createForm(PokemonType::class);

        return $this->render('pokemon/new.html.twig', [
            'formPokemon' => $formPokemon
        ]);
    
    }


}
