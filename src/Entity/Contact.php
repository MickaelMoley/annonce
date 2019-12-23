<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;


class Contact
{
    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/[0-9]{10}/"
     * )
     */

    public $phone;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Email()
     */

    public $email;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(min=10)
     */

    public $message;


    /**
     * @var string|null
     * @Assert\NotBlank()
     */

    public $vehicleId;

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     * @return Contact
     */
    public function setPhone(?string $phone): Contact
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return Contact
     */
    public function setEmail(?string $email): Contact
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     * @return Contact
     */
    public function setMessage(?string $message): Contact
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVehicleId(): ?string
    {
        return $this->vehicleId;
    }

    /**
     * @param string|null $vehicleId
     * @return Contact
     */
    public function setVehicleId(?string $vehicleId): Contact
    {
        $this->vehicleId = $vehicleId;
        return $this;
    }
}
