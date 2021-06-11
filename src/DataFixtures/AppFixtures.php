<?php

namespace App\DataFixtures;

use App\Entity\Discount;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        foreach (self::getProductsData() as $productData) {
            $product = new Product();
            $product->setName($productData['Name']);
            $product->setDescription($productData['Description']);
            $product->setCategory($productData['Category']);
            $product->setPrice($productData['Price']);

            $manager->persist($product);
        }

        foreach (self::getDiscountsData() as $discountData) {
            $discount = new Discount();
            $discount->setDiscountCode($discountData['DiscountCode']);
            $discount->setDiscount($discountData['Discount']);
            $discount->setUseCount($discountData['UseCount']);

            $manager->persist($discount);
        }

        $manager->flush();
    }

    private static function getProductsData()
    {
        /* ELECTRONICS */
        yield [
            'Name' => 'Ecran 24 pouces Akus',
            'Description' => '',
            'Price' => 150,
            'Category' => 'Informatique'
        ];
        yield [
            'Name' => 'Souris Sans Fil Grogitech',
            'Description' => '',
            'Price' => 125,
            'Category' => 'Informatique'
        ];
        yield [
            'Name' => 'Clavier AZERTY UltraX',
            'Description' => '',
            'Price' => 130,
            'Category' => 'Informatique'
        ];

        /* FURNITURE */
        yield [
            'Name' => 'Table en bois Ikeo',
            'Description' => 'Matière: Bois // Dimensions: 250 x 150 x 100',
            'Price' => '150',
            'Category' => 'Meuble'
        ];
        yield [
            'Name' => 'Tabouret en fer Confortsama',
            'Description' => 'Matière: Fer // Dimensions: 30 x 30 x 75',
            'Price' => '50',
            'Category' => 'Meuble',
        ];
        yield [
            'Name' => 'Boite en plastique Dut',
            'Description' => 'Matière: Plastique // Dimensions: 60 x 30 x 30',
            'Price' => '40',
            'Category' => 'Meuble'
        ];

        /* CLOTHING */
        yield [
            'Name' => 'T-Shirt AB/CD',
            'Description' => 'Matière: Coton',
            'Price' => '30',
            'Category' => 'Vetement',
        ];
        yield [
            'Name' => 'Pantalon d hokage',
            'Description' => 'Matière: Toile',
            'Price' => '50',
            'Category' => 'Vetement',
        ];
        yield [
            'Name' => 'Pull-over Plastica',
            'Description' => 'Matière: Laine',
            'Price' => '45',
            'Category' => 'Vetement',
        ];
    }

    private static function getDiscountsData()
    {
        /* DISCOUNT */
        yield [
            'DiscountCode' => 'CODE25',
            'Discount' => 25,
            'UseCount' => 10
        ];
        yield [
            'DiscountCode' => 'CODE50',
            'Discount' => 50,
            'UseCount' => 10
        ];
        yield [
            'DiscountCode' => 'CODE75',
            'Discount' => 75,
            'UseCount' => 10
        ];
        yield [
            'DiscountCode' => 'CODE100',
            'Discount' => 100,
            'UseCount' => 10
        ];
        
    }
}
