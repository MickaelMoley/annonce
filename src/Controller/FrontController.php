<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Annonce;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Form\SearchForm;
use App\Repository\AnnonceRepository;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{

    /**
     * @Route("/", name="home")
     * @param Request $request
     * @param AnnonceRepository $annonceRepository
     * @return Response
     */
    public function index(Request $request, AnnonceRepository $annonceRepository): Response
    {
        $makes = $annonceRepository->findMake();
        $models = $annonceRepository->findModel();

        $data = new SearchData();
        $data->current_page = $request->get('page', 1); /* Pour la gestion de la pagination*/
        $data->limitPerPage = 4; /* Nombre d'annonces à afficher sur la page d'accueil*/
        $annonces = $annonceRepository->findSearch($data);

        return $this->render("front/index.html.twig", [
            'annonces' => $annonces,
            'makes' => $makes,
            'models' => $models
        ]);
    }

    /**
     * @Route("/annonces", name="annonces")
     * @param Request $request
     * @param AnnonceRepository $annonceRepository
     * @return Response
     */

    public function annonces(Request $request, AnnonceRepository $annonceRepository)
    {

        $data = new SearchData();
        $data->current_page = $request->get('page', 1);

        /**
         * Construire le filtre par rapport à la base de donnée
         */
        [$minPrice, $maxPrice] = $annonceRepository->findMinMaxPrice($data);
        [$minKilometer, $maxKilometer] = $annonceRepository->findMinMaxKilometer($data);
        [$minYear, $maxYear] = $annonceRepository->findMinMaxYear($data);
        $makes = $annonceRepository->findMake();
        $models = $annonceRepository->findModel();
        $bodyStyle = $annonceRepository->findBodyStyle();
        $fuelTypes = $annonceRepository->findCarburant();

        $transmission = $annonceRepository->findTransmission();

        $form = $this->createForm(SearchForm::class, $data, [
            'makes' => $makes,
            'models' => $models,
            'bodyStyle' => $bodyStyle,
            'fuelType' => $fuelTypes,
            'transmission' => $transmission
        ])
        ;

        $form->handleRequest($request);


        $annonces = $annonceRepository->findSearch($data);

        return $this->render('front/recherche.twig', [
            'annonces' => $annonces,
            'form' => $form->createView(),
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'minKilometer' => $minKilometer,
            'maxKilometer' => $maxKilometer,
            'minYear' => $minYear,
            'maxYear' => $maxYear,
            'makes' => $makes,
            'models' => $models,
            'fuelType' => $fuelTypes
        ]);
    }

    /**
     * Affiche une annonce et envoie un message en cas de submission du formulaire
     * @Route("/annonces/{slug}", name="annonce")
     * @param Annonce $annonce
     * @return Response
     */
    public function showAnnonce(Annonce $annonce, \Swift_Mailer $mailer, Request $request, AnnonceRepository $annonceRepository): Response
    {
        $contact = new Contact();
        $contact->setVehicleId($annonce->getVehicleId());
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $contact = $form->getData();

            $message = (new Swift_Message("Demande de contact n° réf véhicule : " . $annonce->getVehicleId()))
                ->setFrom('contact@site.fr')
                ->setTo('jeremy@vroomiz.fr')
                ->setBody(
                    $this->renderView('email/email.html.twig', [
                        'email' => $contact->email,
                        'tel' => $contact->phone,
                        'ref' => $contact->vehicleId,
                        'message' => $contact->message
                    ]),
                    'text/html'
                );
            $mailer->send($message);

            $this->addFlash('success', 'La demande de contact a bien été envoyée !');
        }
        $data = new SearchData();
        $data->limitPerPage = 4;

        $annonces = $annonceRepository->findSearch($data);

        return $this->render('front/annonce.html.twig', [
            'annonce' => $annonce,
            'annonces' => $annonces,
            'contact' => $form->createView(),
        ]);
    }

    /**
     * @Route("/deposer-une-annonce", name="deposer", methods={"POST", "GET"})
     * @param Request $request
     * @param Swift_Mailer $mailer
     * @return Response
     */
    public function deposerAnnonce(Request $request, \Swift_Mailer $mailer): Response
    {
        if ($request->isXmlHttpRequest()) {

            $name = $request->get('name');
            $email = $request->get('email');
            $tel = $request->get('tel');

            $ref = $request->get('ref') || null;
            $message = $request->get('message') || null;

            $message = (new Swift_Message($ref = null ? "Déposer une annonce" : "Demande de contact"))
                ->setFrom('contact@site.fr')
                ->setTo('jeremy@vroomiz.fr')
                ->setBody(
                    $this->renderView('email/email.html.twig', [
                        'name' => $name,
                        'email' => $email,
                        'tel' => $tel,
                        'ref' => $ref,
                        'message' => $message
                    ]),
                    'text/html'
                );
            $mailer->send($message);
        }

        return $this->render("front/deposer.html.twig");
    }


    /**
     * @Route("/dealer", name="dealer_store")
     * @param Request $request
     * @param AnnonceRepository $annonceRepository
     * @return Response
     */

    public function dealer(Request $request, AnnonceRepository $annonceRepository)
    {

        $data = new SearchData();
        $data->current_page = $request->get('page', 1);
        $data->dealer_id = $request->get('dealer_id');

        if (empty($data->dealer_id)) {
            $this->addFlash('danger',"Une erreur s'est produite. Veuillez réessayer.");
            return $this->redirectToRoute('annonces');
        }

        /**
         * Construire le filtre par rapport à la base de donnée
         */
        [$minPrice, $maxPrice] = $annonceRepository->findMinMaxPrice($data);
        [$minYear, $maxYear] = $annonceRepository->findMinMaxYear($data);
        $makes = $annonceRepository->findMake($data->dealer_id);
        $models = $annonceRepository->findModel($data->dealer_id);
        $bodyStyle = $annonceRepository->findBodyStyle();

        $fuelTypes = $annonceRepository->findCarburant();
        $transmission = $annonceRepository->findTransmission();





        $form = $this->createForm(SearchForm::class, $data, [
            'makes' => $makes,
            'models' => $models,
            'bodyStyle' => $bodyStyle,
            'fuelType' => $fuelTypes,
            'transmission' => $transmission
        ])
        ;

        $form->handleRequest($request);

        $annonces = $annonceRepository->findSearch($data);


        return $this->render('front/dealer.twig', [
            'annonces' => $annonces,
            'form' => $form->createView(),
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'minYear' => $minYear,
            'maxYear' => $maxYear,
            'makes' => $makes,
            'models' => $models
        ]);
    }


}
