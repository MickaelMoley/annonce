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

    public function __construct(AnnonceRepository $annonceRepository)
    {
        $this->annonceRepository = $annonceRepository;
    }



    /**
     * @Route("/", name="home")
     */
    public function index(Request $request)
    {


        $data = new SearchData();
        $data->current_page = $request->get('page', 1);
        $annonces = $this->annonceRepository->findSearch($data);

        return $this->render('front/index.html.twig', [
            'annonces' => $annonces
        ]);
    }

    /**
     * @Route("/annonces", name="annonces")
     */

    public function annonces(Request $request)
    {

        $data = new SearchData();
        $data->current_page = $request->get('page', 1);
        $form = $this->createForm(SearchForm::class, $data);

        $form->handleRequest($request);

        $annonces = $this->annonceRepository->findSearch($data);


        return $this->render('front/recherche.twig', [
            'annonces' => $annonces,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/annonces/{slug}", name="annonce")
     */
    public function showAnnonce(Annonce $annonce): Response
    {

        return $this->render('front/annonce.twig', [
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
     * @Route("/fetch", name="fetch")
     */
    public function fetchXML(EasyXml $myXml)
    {
        $myXml->execute('https://www.vroomiz.fr/export/facebook/facebook.xml');

        return $this->render('front/fetch.html.twig');
    }
}
