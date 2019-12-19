<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Annonce;
use App\Form\SearchForm;
use App\Repository\AnnonceRepository;
use App\Services\EasyXml;
use Doctrine\ORM\EntityManagerInterface;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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


        $data = new SearchData();
        $data->current_page = $request->get('page', 1);
        $annonces = $annonceRepository->findSearch($data);

        return $this->render('front/index.html.twig', [
            'annonces' => $annonces
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
        [$minYear, $maxYear] = $annonceRepository->findMinMaxYear($data);
        $makes = $annonceRepository->findMake();
        $models = $annonceRepository->findModel();

        $form = $this->createForm(SearchForm::class, $data, ['makes' => $makes, 'models' => $models]);

        $form->handleRequest($request);


        $annonces = $annonceRepository->findSearch($data);


        return $this->render('front/recherche.twig', [
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

    /**
     * @Route("/annonces/{slug}", name="annonce")
     * @param Annonce $annonce
     * @return Response
     */
    public function showAnnonce(Annonce $annonce): Response
    {

        return $this->render('front/annonce.html.twig', [
            'annonce' => $annonce
        ]);
    }

    /**
     * @Route("/deposer-une-annonce", name="deposer", methods={"POST", "GET"})
     * @return Response
     */
    public function deposer(): Response
    {

        return $this->render("front/deposer.html.twig");
    }

    /**
     * @Route("/receipt", name="demande", methods={"POST"})
     * @param Request $request
     * @param Swift_Mailer $mailer
     * @return JsonResponse
     */
    public function contact(Request $request, Swift_Mailer $mailer)
    {

        if($request->isXmlHttpRequest()){

            $name   =   $request->get('name');
            $email  =   $request->get('email');
            $tel    =   $request->get('tel');

            $message    =   (new Swift_Message('Nouvelle demande : Déposer une annonce'))
                ->setFrom('contact@site.fr')
                ->setTo('jeremy@vroomiz.fr')
                ->setBody(
                    $this->renderView('email/email.html.twig', [
                        'name'  => $name,
                        'email' => $email,
                        'tel'  => $tel
                    ]),
                    'text/html'
                );
            $mailer->send($message);

            $this->addFlash('success', 'La demande de contact a bien été envoyée !');

            return new JsonResponse([
                'receipt' => true,
                'info' => [
                    'name'  => $name,
                    'email' => $email,
                    'tel'   => $tel]
            ])
            ;
        }
    }

    /**
     * Récupère la liste des annonces depuis un lien : default -> Vers Vroomiz
     * @Route("/fetch", name="fetch")
     */
    public function fetchXML(EasyXml $myXml)
    {
        $myXml->execute('https://www.vroomiz.fr/export/facebook/facebook.xml');

        return $this->render('front/fetch.html.twig');
    }
}
