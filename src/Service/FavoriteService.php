<?php

namespace App\Service;

use App\Repository\MovieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class FavoriteService
{
    private $movie;
    private $flash;
    private $request;

    public function __construct(MovieRepository $movieRepository, FlashBagInterface $flashBagInterface, RequestStack $requestStack)
    {
        $this->movie = $movieRepository;
        $this->flash = $flashBagInterface;
        $this->request = $requestStack;
    }

    public function add($id)
    {
        $movieToAdd = $this->movie->find($id);
        $session = $this->request->getSession();
        $moviesFavorites = $session->get('attributes-favoris');
        $moviesFavorites[$id]= $movieToAdd;
        $session->set('attributes-favoris', $moviesFavorites);

        $this->flash->add(
            'success',
            $movieToAdd->getTitle() .' a été ajouté à la liste de favoris.'
        );
    }

    public function delete($id)
    {
        $session = $this->request->getSession();
        $moviesFavorites = $session->get('attributes-favoris');
        $movieTitle = $moviesFavorites[$id]->getTitle();
        unset($moviesFavorites[$id]);
        $session->set('attributes-favoris', $moviesFavorites);

        $this->flash->add(
            'danger',
            $movieTitle .' a été retiré de la liste de favoris.'
        );
    }

    public function empty()
    {
        $session = $this->request->getSession();
        $session->set('attributes-favoris', []);
        // $session->remove("attributes-favoris");

        $this->flash->add(
            'warning',
            'La liste de vos favoris a été vidée.'
        );
    }

    public function toggle($id)
    {
        $session = $this->request->getSession();
        $moviesFavorites = $session->get('attributes-favoris');

        // if (isset($moviesFavorites) && array_key_exists($id, $moviesFavorites))
        if (isset($moviesFavorites[$id]))
        {
            $movieTitle = $moviesFavorites[$id]->getTitle();
            unset($moviesFavorites[$id]);
    
                $this->flash->add(
                    'danger',
                    $movieTitle .' a été retiré de la liste de favoris.'
                );
        } else {
            $movieToAdd = $this->movie->find($id);
            $moviesFavorites[$id]= $movieToAdd;
            
            $this->flash->add(
                'success',
                $movieToAdd->getTitle() .' a été ajouté à la liste de favoris.'
            );
        };
        
        $session->set('attributes-favoris', $moviesFavorites);
    }
}

