<?php


namespace App\Services;


use App\Entity\Annonce;
use App\Entity\Dealer;
use App\Repository\AnnonceRepository;
use App\Repository\DealerRepository;
use Doctrine\ORM\EntityManagerInterface;

class EasyXml
{

    public $JSON_file;
    private $em;
    public $updatedAnnonce = 0;
    public $newedAnnonce = 0;
    public $debug;

    public function __construct(AnnonceRepository $annonceRepository, EntityManagerInterface $entityManager)
    {
        $this->repo = $annonceRepository;
        $this->em = $entityManager;

    }

    public function execute($link, $debug)
    {
        $this->debug = $debug;
        $this->readXMLFrom($link);

        if($this->debug)
        {
            return 'Debug est activé';
        }
        return $this->manager($this->repo);
    }

    private function readXMLFrom($link)
    {

        $xmlfile    = file_get_contents($link);
        $json_encoded = json_encode(simplexml_load_string($xmlfile));
        $this->JSON_file = json_decode($json_encoded);
    }

    private function manager(AnnonceRepository $annonceRepository)
    {
        $lists = $this->JSON_file->listing;


        for ($i = 0; $i < count($lists); $i++) {

            $annonce = $this->repo->findOneBy(['vehicle_id' => $lists[$i]->vehicle_id]);

            if ($annonce) {
                $this->updateAnnonce($annonce, $lists[$i]);
            } else {
                $this->newAnnonce($lists[$i]);
            }
        }

    }

    private function updateAnnonce($annonce, $updateAnnonce)
    {


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
        $annonce->setTransmission($updateAnnonce->transmission);
        $annonce->setPrice(str_replace(' EUR', "", $updateAnnonce->price));

        if(isset($updateAnnonce->features->feature)){
            $annonce->setFeatures($updateAnnonce->features->feature);
        }

        $annonce->setDealerId(str_replace('vobiz_', "", $updateAnnonce->dealer_id));
        $annonce->setDealerName($updateAnnonce->dealer_name);
        $annonce->setDealerPhone($updateAnnonce->dealer_phone);

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
        $annonce->setDealerId(str_replace('vobiz_', "", $updateAnnonce->dealer_id));
        $annonce->setDealerName($updateAnnonce->dealer_name);
        $annonce->setDealerPhone($updateAnnonce->dealer_phone);
        $annonce->setFbPageId($updateAnnonce->fb_page_id);
        $annonce->setDealerCommunicationChannel($updateAnnonce->dealer_communication_channel);
        $annonce->setDealerPrivacyPolicyUrl($updateAnnonce->dealer_privacy_policy_url);

        $this->em->flush();

        $this->updatedAnnonce += 1;
    }

    private function newAnnonce($data)
    {

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
        $annonce->setTransmission($data->transmission);
        $annonce->setPrice(str_replace(' EUR', "", $data->price));


        if(isset($data->features->feature)){
            $annonce->setFeatures($data->features->feature);
        }

        $annonce->setAdress([
            'addr1' => $data->address->component[0],
            'city' => $data->address->component[1],
            'region' => $data->address->component[2],
            'country' => $data->address->component[3],
            'postal_code' => $data->address->component[4]
        ]);

        $annonce->setLatitude($data->latitude);
        $annonce->setLongitude($data->longitude);
        $annonce->setExteriorColor($data->exterior_color);
        $annonce->setStateOfVehicle($data->state_of_vehicle);




        $annonce->setDealerId(str_replace('vobiz_', "", $data->dealer_id));
        $annonce->setDealerName($data->dealer_name);
        $annonce->setDealerPhone($data->dealer_phone);
        $annonce->setFbPageId($data->fb_page_id);
        $annonce->setDealerCommunicationChannel($data->dealer_communication_channel);
        $annonce->setDealerPrivacyPolicyUrl($data->dealer_privacy_policy_url);

        $this->em->persist($annonce);
        $this->em->flush();

        $this->newedAnnonce = $this->newedAnnonce + 1;
    }
}