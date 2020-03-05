<?php


namespace App\Services;


use App\Entity\Annonce;
use App\Entity\Dealer;
use App\Repository\AnnonceRepository;
use App\Repository\DealerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @property DealerRepository dealerRepo
 * @property AnnonceRepository annonceRepo
 */
class EasyXml
{

    public $JSON_file;
    private $em;
    public $updatedAnnonce = 0;
    public $newedAnnonce = 0;
    /**
     * Permet d'activer le mode debuggage; Affichera les infos de traitements qui ont été faites
     * @var bool
     */
    public $debug;
    /**
     * Permet de choisir le mode de traitement des annonces
     * PAR DEFAULT = 'all'; UNIQUEMENT LES NOUVELLES ANNONCES : 'new'; UNIQUEMENT LES MISES A JOUR : 'update'
     * @var string
     */
    public $only;
    /** Permet de traduire automatiquement les valeurs des annonces
     * @var TranslatorInterface
     */
    private $translator;
    public $wish;
    public $link;

    /**
     * EasyXml constructor.
     * @param AnnonceRepository $annonceRepository
     * @param DealerRepository $dealerRepository
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     */
    public function __construct(AnnonceRepository $annonceRepository, DealerRepository $dealerRepository, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $this->annonceRepo = $annonceRepository;
        $this->dealerRepo = $dealerRepository;
        $this->em = $entityManager;
        $this->translator = $translator;
    }

    /**
     * @param $link
     * @param $debug
     * @param string $only
     * @return string|void
     */
    public function execute($link, $debug, $only = 'all')
    {
        $this->debug = $debug;
        $this->only = $only;
        $this->link = $link;
        $this->readXMLFrom($link);

        $this->manager();
    }

    private function readXMLFrom($link)
    {

        $xmlbase            = file_get_contents($link);
        $json_encoded       = json_encode(simplexml_load_string($xmlbase));
        $this->JSON_file    = json_decode($json_encoded);
    }

    private function manager()
    {
        $lists = $this->JSON_file->listing;


        $countLists = count($lists);

        /**
         * Dûe à la limite de la mémoire on récupère volontairement uniquement : $limitItemPerRequest
         */
        $limitItemPerRequest = $countLists;


       for($i = 0; $i < $limitItemPerRequest; $i++){
           $annonce = $this->annonceRepo->findOneBy(['vehicle_id' => $lists[$i]->vehicle_id]);


           if ($annonce) {
               if($this->only == 'update'){
                   $this->updateAnnonce($annonce, $lists[$i]);


               }
           } else {
               if($this->only == 'new'){
                   $this->newAnnonce($lists[$i]);
                   $this->newedAnnonce = $this->newedAnnonce + 1;


               }
           }
       }

    }

    private function updateAnnonce($annonce, $updateAnnonce)
    {

        $dealer = $this->dealerRepo->findOneBy(['dealer_ref' => str_replace('vobiz_', "", $updateAnnonce->dealer_id)]);


        $annonce->setTitle($updateAnnonce->title);
        $annonce->setSlug(''); //Gérer automatiquement
        $annonce->setDescription($updateAnnonce->description);
        $annonce->setUrl($updateAnnonce->url);
        $annonce->setMake($updateAnnonce->make);
        $annonce->setModel($updateAnnonce->model);
        $annonce->setImage($updateAnnonce->image);
        $annonce->setYear($updateAnnonce->year);
        $annonce->setMileage($updateAnnonce->mileage->value);
        $annonce->setDrivetrain($updateAnnonce->drivetrain);

        if(is_string($updateAnnonce->vehicle_registration_plate))
        {
            $annonce->setVehicleRegistrationPlate($updateAnnonce->vehicle_registration_plate);
        }

        $annonce->setBodyStyle($updateAnnonce->body_style);
        $annonce->setFuelType($updateAnnonce->fuel_type);
        $annonce->setTransmission($this->translator->trans($updateAnnonce->transmission));
        $annonce->setPrice(str_replace(' EUR', "", $updateAnnonce->price));


        if(isset($updateAnnonce->features)){
            if(is_object($updateAnnonce->features)){
                $annonce->setFeatures((array) $updateAnnonce->features);
            }
            else if(is_string($updateAnnonce->features)){ /* Si c'est un string alors on crée une variable de type ARRAY et on push le string dans la variable $re|Array et on le 'set' à l'entity */
                $re = [];
                array_push($re, $updateAnnonce->features);
                $annonce->setFeatures($re);
            }
            else{/* Sinon si c'est un array, on 'set' l'array directement à l'entity */
                $annonce->setFeatures($updateAnnonce->features);

            }
        }

        $dealer->setDealerName($updateAnnonce->dealer_name);
        $dealer->setDealerPhone($updateAnnonce->dealer_phone);
        $dealer->setSlug('');

        $annonce->setDealer($dealer);

        $annonce->setAdress([
            'addr1' => $updateAnnonce->address->component[0],
            'city' => $updateAnnonce->address->component[1],
            'region' => $updateAnnonce->address->component[2],
            'country' => $updateAnnonce->address->component[3],
            'postal_code' => $updateAnnonce->address->component[4]
        ]);

        $annonce->setLatitude($updateAnnonce->latitude);
        $annonce->setLongitude($updateAnnonce->longitude);
        $annonce->setExteriorColor($updateAnnonce->exterior_color);
        $annonce->setStateOfVehicle($updateAnnonce->state_of_vehicle);

        $annonce->setFbPageId($updateAnnonce->fb_page_id);
        $annonce->setDealerCommunicationChannel($updateAnnonce->dealer_communication_channel);
        $annonce->setDealerPrivacyPolicyUrl($updateAnnonce->dealer_privacy_policy_url);

        $this->em->flush();

        $this->updatedAnnonce += 1;
    }

    private function newAnnonce($data)
    {

        $dealer = $this->dealerRepo->findOneBy(['dealer_ref' => str_replace('vobiz_', "", $data->dealer_id)]);
        $this->link = $dealer;
        if(!$dealer){
            $dealer = new Dealer();
            $dealer->setDealerName($data->dealer_name);
            $dealer->setDealerPhone($data->dealer_phone);
            $dealer->setDealerRef(str_replace('vobiz_', "", $data->dealer_id));
            $dealer->setSlug('');

            $this->em->persist($dealer);
            $this->em->flush();
        }

        $annonce = new Annonce();
        $annonce->setVehicleId($data->vehicle_id);
        $annonce->setTitle($data->title);
        $annonce->setSlug(''); //Gérer automatiquement
        $annonce->setDescription($data->description);
        $annonce->setUrl($data->url);
        $annonce->setMake($data->make);
        $annonce->setModel($data->model);
        $annonce->setImage($data->image);
        $annonce->setYear($data->year);
        $annonce->setMileage($data->mileage->value);
        $annonce->setDrivetrain($data->drivetrain);

        if(!is_string($data->vehicle_registration_plate)){
            $annonce->setVehicleRegistrationPlate('none');
        }
        else{
            $annonce->setVehicleRegistrationPlate($data->vehicle_registration_plate);
        }

        $annonce->setBodyStyle($data->body_style);
        $annonce->setFuelType($data->fuel_type);
        $annonce->setTransmission($this->translator->trans($data->transmission));
        $annonce->setPrice(str_replace(' EUR', "", $data->price));


        if(isset($data->features)){
            if(is_object($data->features)){
                $annonce->setFeatures((array) $data->features);
            }
            else if(is_string($data->features)){ /* Si c'est un string alors on crée une variable de type ARRAY et on push le string dans la variable $re|Array et on le 'set' à l'entity */
                $re = [];
                array_push($re, $data->features);
                $annonce->setFeatures($re);
            }
            else{/* Sinon si c'est un array, on 'set' l'array directement à l'entity */
                $annonce->setFeatures($data->features);
                $this->wish = $data->features;
            }
        }

        $annonce->setAdress([
            'addr1' => $data->address->component[0],
            'city' => $data->address->component[1],
            'region' => $data->address->component[2],
            'country' => $data->address->component[3],
            'postal_code' => $data->address->component[4]
        ]);

        if(is_string($data->latitude)){
            $annonce->setLatitude($data->latitude);
        }
        else
        {
            $annonce->setLatitude('');
        }

        $annonce->setLongitude($data->longitude);
        $annonce->setExteriorColor($data->exterior_color);
        $annonce->setStateOfVehicle($data->state_of_vehicle);

        /*Avec la mise en place de l'entité Dealer, on garde malgré tous le dealer_id de l'export dans l'entity Annonce pour le filtre de recherche */

        $annonce->setDealerRef(str_replace('vobiz_', "", $data->dealer_id));

    /* 2.{dealer}  On spécifié ici le 'dealer' auquel on souhaite l'attribué - voir 1.{dealer}*/
        $annonce->setDealer($dealer);

        $annonce->setFbPageId($data->fb_page_id);
        $annonce->setDealerCommunicationChannel($data->dealer_communication_channel);
        $annonce->setDealerPrivacyPolicyUrl($data->dealer_privacy_policy_url);

        $this->em->persist($annonce);
        $this->em->flush();

        $this->newedAnnonce += 1;

    }
}