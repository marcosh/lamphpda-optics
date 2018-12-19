<?php

declare(strict_types=1);

namespace Marcosh\OphpticsSpec;

use Marcosh\Ophptics\BaseLens;

class Street
{
    /**
     * @var int
     */
    private $number;

    /**
     * @var string
     */
    private $name;

    public function __construct(int $number, string $name)
    {
        $this->number = $number;
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        return new self(
            $this->number,
            $name
        );
    }
}

$streetNameLens = new BaseLens(
    function (Street $street): string {
        return $street->getName();
    },
    function (Street $street, string $name): Street {
        return $street->setName($name);
    }
);

describe('StreetNameLens', function () use ($streetNameLens) {
    $street = new Street(221, 'Baker Street');

    it('gets the correct name', function () use ($streetNameLens, $street) {
        expect($streetNameLens->get($street))->toBe('Baker Street');
    });

    it('sets the name correctly', function () use ($streetNameLens, $street) {
        expect($streetNameLens->set($street, 'Downing Street'))->toEqual(new Street(221, 'Downing Street'));
    });

    it('does not modify the original data structure', function () use ($streetNameLens, $street) {
        $oldStreet = clone $street;

        $newStreet = $streetNameLens->set($street, 'Downing Street');

        expect($street)->toEqual($oldStreet);
        expect($newStreet)->not->toEqual($street);
    });

    it('modifies the internal value via a callable', function () use ($streetNameLens, $street) {
        $oldStreet = clone $street;

        $newStreet = new Street(221, 'BAKER STREET');

        expect($streetNameLens->modify($street, 'strtoupper'))->toEqual($newStreet);
        expect($street)->toEqual($oldStreet);
    });
});

class Address
{
    /**
     * @var string
     */
    private $city;

    /**
     * @var Street
     */
    private $street;

    public function __construct(string $city, Street $street)
    {
        $this->city = $city;
        $this->street =$street;
    }

    public function getStreet(): Street
    {
        return $this->street;
    }

    public function setStreet(Street $street): self
    {
        return new self(
            $this->city,
            $street
        );
    }
}

$addressStreetLens = new BaseLens(
    function (Address $address): Street {
        return $address->getStreet();
    },
    function (Address $address, Street $street): Address {
        return $address->setStreet($street);
    }
);

describe('AddressStreetLens', function () use ($addressStreetLens) {
    $street = new Street(221, 'Baker Street');
    $address = new Address('London', $street);

    it('gets the correct street', function () use ($addressStreetLens, $street, $address) {
        expect($addressStreetLens->get($address))->toBe($street);
    });

    it('sets the street correctly', function () use ($addressStreetLens, $address) {
        $newStreet = new Street(221, 'Downing Street');
        $newAddress = new Address('London', $newStreet);

        expect($addressStreetLens->set($address, $newStreet))->toEqual($newAddress);
    });

    it('does not modify the original data structure', function () use ($addressStreetLens, $address) {
        $oldAddress = clone $address;

        $newStreet = new Street(221, 'Downing Street');
        $newAddress = $addressStreetLens->set($address, $newStreet);

        expect($address)->toEqual($oldAddress);
        expect($newAddress)->not->toEqual($address);
    });

    it('modifies the internal value via a callable', function () use ($addressStreetLens, $address) {
        $oldAddress = clone $address;

        $newStreet = new Street(221, 'Downing Street');
        $newAddress = new Address('London', $newStreet);

        $f = function (Street $street) use ($newStreet) {
            return $newStreet;
        };

        expect($addressStreetLens->modify($address, $f))->toEqual($newAddress);
        expect($address)->toEqual($oldAddress);
    });
});

describe('Composing StreetNameLens with AddressStreetLens', function () use ($streetNameLens, $addressStreetLens) {
    $street = new Street(221, 'Baker Street');
    $address = new Address('London', $street);

    it('gets the correct street name', function () use ($streetNameLens, $addressStreetLens, $address) {
        expect($addressStreetLens->compose($streetNameLens)->get($address))->toBe('Baker Street');
    });

    it('sets the correct street name', function () use ($streetNameLens, $addressStreetLens, $address) {
        $newStreet = new Street(221, 'Downing Street');
        $newAddress = new Address('London', $newStreet);

        expect($addressStreetLens->compose($streetNameLens)->set($address, 'Downing Street'))->toEqual($newAddress);
    });

    it('does not modify the original data structure', function () use ($streetNameLens, $addressStreetLens, $address) {
        $oldAddress = clone $address;

        $newAddress = $addressStreetLens->compose($streetNameLens)->set($address, 'Downing Street');

        expect($address)->toEqual($oldAddress);
        expect($newAddress)->not->toEqual($address);
    });

    it('modifies the internal value via a callable', function () use ($streetNameLens, $addressStreetLens, $address) {
        $oldAddress = clone $address;

        $newStreet = new Street(221, 'BAKER STREET');
        $newAddress = new Address('London', $newStreet);

        expect($addressStreetLens->compose($streetNameLens)->modify($address, 'strtoupper'))->toEqual($newAddress);
        expect($address)->toEqual($oldAddress);
    });
});
