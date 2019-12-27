<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Repository\AnnonceRepository;
use App\Services\EasyXml;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/fetchModelbyMake", name="api_fetchModelByMake")
     */
    public function index(Request $request, AnnonceRepository $annonceRepository)
    {


        if($request->isXmlHttpRequest())
        {
            $data = $request->get('make');
            $annonce = null;
            if(!empty($data)){
                $annonce = $annonceRepository->findModelByMake($data);
            }
            else{
                $annonce = $annonceRepository->findModel();

            }

            return $this->json($annonce);
        }


        return $this->render('api/index.html.twig', [
            'annonce' => $annonce
        ]);
    }

    /**
     * Récupère la liste des annonces depuis un lien : default -> URL "https://www.vroomiz.fr/export/facebook/facebook.xml"
     * @Route("/fetch", name="fetch")
     * @param EasyXml $myXml
     * @return Response
     */
    public function fetchXML(EasyXml $myXml)
    {
        $myXml->execute('uploads/facebook.xml', false);

        return $this->render('front/fetch.html.twig', [
            'fetcher' => $myXml
        ]);
    }

}
