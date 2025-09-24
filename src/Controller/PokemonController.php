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

#[Route('/pokemon')]
final class PokemonController extends AbstractController
{
    // Afficher tous les pokemons
    #[Route('/', name:'pokemons', methods:['GET'])]
    public function index(PokemonRepository $PokemonRepo): Response
    {

        $pokemons = $PokemonRepo->findAll();

        return $this->render('pokemon/index.html.twig', [
            'pokemons' => $pokemons
        ]);
    }

    //Afficher un pokemon
    #[Route('/show/{id}', name:'pokemon', methods:['GET'])]
    public function show(PokemonRepository $PokemonRepo, int $id): Response
    {
        
        $pokemon = $PokemonRepo->findOneBy(["id" => $id]);

        return $this->render('pokemon/show.html.twig', [
            'pokemon' => $pokemon
        ]);
    }

    // Creer un pokemon
    #[Route('/new', name:'pokemon_new', methods:['GET', 'POST'])]
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

            // revenir sur la page des pokemon (la route qui a le name 'pokemons')
            return $this->redirectToRoute('pokemons');
        }

        return $this->render('pokemon/new.html.twig', [
            'formPokemon' => $formPokemon
        ]);
    
    }

    // Supprimer un pokemon
    #[Route('/delete/{id}', name:'pokemon_delete', methods:['POST'])]
    public function delete(int $id, Request $request, Pokemon $pokemon, EntityManagerInterface $em): Response //Pokemon $pokemon permet de se passer de $pokemon = $PokemonRepo->findOneBy(["id" => $id]);
    {
        if($this->isCsrfTokenValid('delete'.$id, $request->request->get('_token')))
        {
            $em->remove($pokemon);
            $em->flush();
            $this->addFlash('success', "votre pokemon a été supprimé");
            return $this->redirectToRoute('pokemons');
        }

        else {
            $this->addFlash('error', "echec de la suppression");
            return $this->redirectToRoute('pokemons');
        }
      
    }


    // Editer un pokemon
    #[Route('/{id}/edit', name:'pokemon_edit', methods:['GET', 'POST'])]
    public function edit(int $id, Request $request, Pokemon $pokemon, EntityManagerInterface $em): Response //Pokemon $pokemon permet de se passer de $pokemon = $PokemonRepo->findOneBy(["id" => $id]);
    {
        $formPokemon = $this->createForm(PokemonType::class, $pokemon);

        //recupere la requete en post ou get de ce formulaire (et seulement ce formulaire)
        $formPokemon->handleRequest($request);

        if($formPokemon->isSubmitted() && $formPokemon->isValid()){
              
            $em->flush(); 
            $this->addFlash('success', "votre pokemon a été modifié");
            // revenir sur la page des pokemon (la route qui a le name 'pokemons')
            return $this->redirectToRoute('pokemons');
        }

         return $this->render('pokemon/edit.html.twig', [
            'formPokemon' => $formPokemon
        ]);
    }


}
