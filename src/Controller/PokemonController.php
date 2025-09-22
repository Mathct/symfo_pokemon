<?php

namespace App\Controller;

use App\Entity\Pokemon;
use App\Form\PokemonType;
use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PokemonController extends AbstractController
{
    #[Route('/pokemon', name:'pokemons')]
    public function index(PokemonRepository $PokemonRepo): Response
    {

        $pokemons = $PokemonRepo->findAll();

        return $this->render('pokemon/index.html.twig', [
            'pokemons' => $pokemons
        ]);
    }

    #[Route('/pokemon/new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        // je crée une instance de Pokemon
        $pokemon = new Pokemon();

        // permet de créer le form a partir du Type (les inputs) et de l'instance(les verifs des champs)
        $formPokemon = $this->createForm(PokemonType::class, $pokemon);

        //recupere la requete en post ou get de ce formulaire (et seulement ce formulaire)
        $formPokemon->handleRequest($request);

        if($formPokemon->isSubmitted() && $formPokemon->isValid()){
            
            $em->persist($pokemon);  //equivalent du prepare
            $em->flush(); //equivalent du execute

            // revenir sur la page des pokemon (la methode qui a le name 'pokemons')
            return $this->redirectToRoute('pokemons');
        }

        return $this->render('pokemon/new.html.twig', [
            'formPokemon' => $formPokemon
        ]);
    
    }


}
