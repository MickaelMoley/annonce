<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnnonceRepository")
 */
class Annonce
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $vehicle_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $make;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $model;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $image = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * @ORM\Column(type="json")
     */
    private $mileage = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $drivetrain;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $vehicle_registration_plate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $body_style;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fuel_type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $transmission;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="array")
     */
    private $features = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $latitude;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $longitude;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $exterior_color;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $state_of_vehicle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fb_page_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dealer_communication_channel;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dealer_privacy_policy_url;

    /**
     * @ORM\Column(type="integer")
     */
    private $dealer_id;

    /**
     * @ORM\Column(type="array")
     */
    private $adress = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dealerName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dealerPhone;

    public function getVehicleId(): ?string
    {
        return $this->vehicle_id;
    }

    public function setVehicleId(string $vehicule_id): self
    {
        $this->vehicle_id = $vehicule_id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getMake(): ?string
    {
        return $this->make;
    }

    public function setMake(string $make): self
    {
        $this->make = $make;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(?string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getImage(): ?array
    {
        return $this->image;
    }

    public function setImage(?array $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getMileage(): ?array
    {
        return $this->mileage;
    }

    public function setMileage(array $mileage): self
    {
        $this->mileage = $mileage;

        return $this;
    }

    public function getDrivetrain(): ?string
    {
        return $this->drivetrain;
    }

    public function setDrivetrain(string $drivetrain): self
    {
        $this->drivetrain = $drivetrain;

        return $this;
    }

    public function getVehicleRegistrationPlate(): ?string
    {
        return $this->vehicle_registration_plate;
    }

    public function setVehicleRegistrationPlate(string $vehicle_registration_plate): self
    {
        $this->vehicle_registration_plate = $vehicle_registration_plate;

        return $this;
    }

    public function getBodyStyle(): ?string
    {
        return $this->body_style;
    }

    public function setBodyStyle(string $body_style): self
    {
        $this->body_style = $body_style;

        return $this;
    }

    public function getFuelType(): ?string
    {
        return $this->fuel_type;
    }

    public function setFuelType(string $fuel�_type): self
    {
        $this->fuel_type = $fuel�_type;

        return $this;
    }

    public function getTransmission(): ?string
    {
        return $this->transmission;
    }

    public function setTransmission(string $transmission): self
    {
        $this->transmission = $transmission;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getFeatures(): ?array
    {
        return $this->features;
    }

    public function setFeatures(array $features): self
    {
        $this->features = $features;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getExteriorColor(): ?string
    {
        return $this->exterior_color;
    }

    public function setExteriorColor(string $exterior_color): self
    {
        $this->exterior_color = $exterior_color;

        return $this;
    }

    public function getStateOfVehicle(): ?string
    {
        return $this->state_of_vehicle;
    }

    public function setStateOfVehicle(string $state_of_vehicle): self
    {
        $this->state_of_vehicle = $state_of_vehicle;

        return $this;
    }

    public function getFbPageId(): ?string
    {
        return $this->fb_page_id;
    }

    public function setFbPageId(string $fb_page_id): self
    {
        $this->fb_page_id = $fb_page_id;

        return $this;
    }

    public function getDealerCommunicationChannel(): ?string
    {
        return $this->dealer_communication_channel;
    }

    public function setDealerCommunicationChannel(string $dealer_communication_channel): self
    {
        $this->dealer_communication_channel = $dealer_communication_channel;

        return $this;
    }

    public function getDealerPrivacyPolicyUrl(): ?string
    {
        return $this->dealer_privacy_policy_url;
    }

    public function setDealerPrivacyPolicyUrl(string $dealer_privacy_policy_url): self
    {
        $this->dealer_privacy_policy_url = $dealer_privacy_policy_url;

        return $this;
    }

    public function getDealerId(): ?int
    {
        return $this->dealer_id;
    }

    public function setDealerId(int $dealer_id): self
    {
        $this->dealer_id = $dealer_id;

        return $this;
    }

    public function getAdress(): ?array
    {
        return $this->adress;
    }

    public function setAdress(array $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = strtolower(str_replace(" ", "-", $this->vehicle_id . " " . $this->title));

        return $this;
    }

    public function getDealerName(): ?string
    {
        return $this->dealerName;
    }

    public function setDealerName(string $dealerName): self
    {
        $this->dealerName = $dealerName;

        return $this;
    }

    public function getDealerPhone(): ?string
    {
        return $this->dealerPhone;
    }

    public function setDealerPhone(string $dealerPhone): self
    {
        $this->dealerPhone = $dealerPhone;

        return $this;
    }

    public function __toString()
    {
        return ' ';
    }

    public function returnMake()
    {

        return $this->getMake();
    }
    public function returnModel()
    {
        return $this->getModel();
    }


}
