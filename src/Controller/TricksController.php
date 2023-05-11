<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Tricks;
use App\Entity\Images;
use App\Entity\Videos;
use App\Form\CommentsFormType;
use App\Form\TricksFormType;
use App\Repository\CommentsRepository;
use App\Repository\TricksRepository;
use App\Service\PictureService;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;



#[Route('/figures', name: 'figures_')]
class TricksController extends AbstractController
{
    private const COMMENT_LIMIT = 8;


    #[Route('/ajout', name: 'add')]
    public function add(
        Request $request, 
        EntityManagerInterface $em, 
        SluggerInterface $slugger,
        PictureService $pictureService
        ): Response
    {
        if (!$this->getUser()) {
            $this->addFlash('success', 'Pour ajouter une figure vous devez vous connecter');
            return $this->redirectToRoute('app_main');
        }

        $trick = new Tricks(); // nvelle figure

        //création formulaire 
        $form = $this->createForm(TricksFormType::class, $trick);
        // envoi de la requete du form
        $form->handleRequest($request);
        // verifie si form  soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {

            //on recup l'image à la une 
            $mainimage = $form->get('mainimage')->getData();
            // On définit le dossier de destination
            $foldermain = 'main';

            // On appelle le service d'ajout
            $fichiermain = $pictureService->add($mainimage, $foldermain, 300, 300);

            // on envoie le nom de l'image en bdd
            $trick->setMainimage($fichiermain);

            
            // GESTION PLUSIEURS IMAGES
            // On récupère les images du formulaire
            $images = $form->get('images')->getData();
            foreach($images as $image){
                // On définit le dossier de destination
                $folder = 'tricks';
                // On appelle le service d'ajout
                $fichier = $pictureService->add($image, $folder, 300, 300);
                $img = new Images();
                $img->setName($fichier);
                $trick->addImage($img);
            }
            //création du slug 
            $slug = $slugger->slug($trick->getName());
            $trick->setSlug($slug);

            //stockage des infos
            $em->persist($trick);
            $em->flush();
            //message succes
            $this->addFlash('success', 'Figure ajoutée avec succès');
            //redirection
            return $this->redirectToRoute('app_main');
        }

        return $this->render('user/figures/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/modification/{id}', name: 'edit')]
    public function edit(
        Tricks $trick,
        Request $request, 
        EntityManagerInterface $em, 
        SluggerInterface $slugger,
        PictureService $pictureService
    ): Response
    {
        //Verif si utilisateur connecté
        if (!$this->getUser()) {
            $this->addFlash('success', 'Pour modifier une figure vous devez vous connecter');
            return $this->redirectToRoute('app_main');
        }

        //creation formulaire 
        $form = $this->createForm(TricksFormType::class, $trick);
        // envoi de la requete du form
        $form->handleRequest($request);
        // verifie si form  soumis et valide
        if($form->isSubmitted() && $form->isValid())
        {
            
            //on recup l'image à la une 
            $mainimage = $form->get('mainimage')->getData();

            if($mainimage != null){
                // On définit le dossier de destination
                $foldermain = 'main';
                // On appelle le service d'ajout
                $fichiermain = $pictureService->add($mainimage, $foldermain, 300, 300);
                // on envoie le nom de l'image en bdd
                $trick->setMainimage($fichiermain);
                //dd($trick);
            }
            
            // On récupère les images
            $images = $form->get('images')->getData();

            foreach($images as $image){
                // On définit le dossier de destination
                $folder = 'tricks';

                // On appelle le service d'ajout
                $fichier = $pictureService->add($image, $folder, 300, 300);

                $img = new Images();
                $img->setName($fichier);
                $trick->addImage($img);
            }
            //création du slug 
            $slug = $slugger->slug($trick->getName());
            $trick->setSlug($slug);

            //stockage des infos
            $em->persist($trick);
            $em->flush();

            //message succes
            $this->addFlash('success', 'Figure modifiée avec succès');
            //redirection
            return $this->redirectToRoute('app_main');
        }

        return $this->render('user/figures/edit.html.twig', [
            'form' => $form->createView(),
            'trick' => $trick
        ]);
    }

    #[Route('/suppression/{id}', name: 'delete')]
    public function delete(Tricks $trick, TricksRepository $tricksRepository): Response
    {
        //suppression de la figure 
        $tricksRepository->remove($trick, true);
        $this->addFlash('success', '<p><strong>' . $trick->getName() . '</strong> a été supprimé </p>');
        return $this->redirectToRoute('app_main');
    }

    #[Route('/suppression/image/{id}', name: 'delete_image', methods: ['DELETE'])]
    public function deleteImage(Images $image, Request $request, EntityManagerInterface $em, PictureService $pictureService): JsonResponse
    {
        // On récupère le contenu de la requête
        $data = json_decode($request->getContent(), true);

        if($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])){
            // Le token csrf est valide
            // On récupère le nom de l'image
            $nom = $image->getName();

            if($pictureService->delete($nom, 'tricks', 300, 300)){
                // On supprime l'image de la base de données
                $em->remove($image);
                $em->flush();

                return new JsonResponse(['success' => true], 200);
            }
            // La suppression a échoué
            return new JsonResponse(['error' => 'Erreur de suppression'], 400);
        }

        return new JsonResponse(['error' => 'Token invalide'], 400);
    }

    #[Route('/suppression/video/{id}', name: 'delete_video', methods: ['DELETE'])]
    public function deleteVideo(Videos $video, Request $request, EntityManagerInterface $em): JsonResponse
    {
        // On récupère le contenu de la requête
        $data = json_decode($request->getContent(), true);

        if($this->isCsrfTokenValid('delete' . $video->getId(), $data['_token'])){
            // Le token csrf est valide
            if($video->getId()){
                // On supprime la video de la bdd
                $em->remove($video);
                $em->flush();

                return new JsonResponse(['success' => true], 200);
            }
            // La suppression a échoué
            return new JsonResponse(['error' => 'Erreur de suppression'], 400);
        }

        return new JsonResponse(['error' => 'Token invalide'], 400);
    }

    #[Route('/{slug}', name: 'details')]
    public function details(Tricks $trick, CommentsRepository $commentsRepository, Request $request, EntityManagerInterface $em, Session $session): Response
    {
        $session->set('commentary_offset', self::COMMENT_LIMIT);
        $showLoadMoreComments = true;

        if ($session->get('commentary_offset') >= $trick->getComments()->count()) {
            $showLoadMoreComments = false;
        }
        // Verifier si l'utilisateur est connecté
        // Si il est connecté envoi de la figure avec formulaire de commentaire
        if ($this->getUser()) {

            // Nous créons l'instance de "Commentaires"
            $commentaire = new Comments();
            // Création du formulaire en utilisant "CommentsFormType" et on lui passe l'instance
            $commentform = $this->createForm(CommentsFormType::class, $commentaire);
            // Nous récupérons les données
            $commentform->handleRequest($request);
            if ($commentform->isSubmitted() && $commentform->isValid()) {

                $commentaire->setUser($this->getUser());
                $commentaire->setTrick($trick);
                //stockage des infos
                $em->persist($commentaire);
                $em->flush();
                //message succes
                $this->addFlash('success', 'Commentaire ajouté avec succès');
                //redirection
                return $this->redirectToRoute('figures_details', ['slug' => $trick->getSlug()]);
            }

            return $this->render('tricks/details.html.twig', [
                'trick' => $trick,
                'comments' => $commentsRepository->findBy(['trick' => $trick], ['created_at' => 'DESC'], self::COMMENT_LIMIT, null),
                'showLoadMore' => $showLoadMoreComments,
                'commentform' => $commentform->createView()
            ]);

        } else {

            return $this->render('tricks/details.html.twig', [
                'trick' => $trick,
                'comments' => $commentsRepository->findBy(['trick' => $trick], ['created_at' => 'DESC'], self::COMMENT_LIMIT, null),
                'showLoadMore' => $showLoadMoreComments
            ]);

        }
    }

    #[Route('/trick/loadmore_comments/{id}', name: 'loadmore_comments', methods: ['POST'])]
    public function loadMoreComments(CommentsRepository $commentsRepository, Tricks $trick, Session $session): JsonResponse
    {
        $offset = $session->get('commentary_offset');
        $session->set('commentary_offset', $session->get('commentary_offset') + self::COMMENT_LIMIT);
        $jsonContent = [];

        $showLoadMoreComments = true;

        if ($session->get('commentary_offset') >= $trick->getComments()->count()) {
            $showLoadMoreComments = false;
        }
        dump($session->get('commentary_offset'), $trick->getComments()->count(), $showLoadMoreComments);

        $jsonContent['showLoadMore'] = $showLoadMoreComments;

        $comments = $commentsRepository->findBy(['trick' => $trick], ['created_at' => 'DESC'], self::COMMENT_LIMIT, $offset);
        $payload = [];
        foreach ($comments as $comment) {
            array_push($payload, [
                'id' => $comment->getId(),
                'trickId' => $comment->getTrick()->getId(),
                'user' => ['username' => $comment->getUser()->getNickname()], //'avatar' => $comment->getUser()->getAvatar()],
                'content' => $comment->getContent(),
                'createdAt' => $comment->getCreatedAt(),
            ]);
        }

        $jsonContent['payload'] = $payload;
        //dump(json_encode($jsonContent));

        return new JsonResponse(json_encode($jsonContent));
    }

    
}
