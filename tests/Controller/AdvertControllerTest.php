<?php

namespace App\Test\Controller;

use App\Entity\Advert;
use App\Entity\Category;
use App\Repository\AdvertRepository;
use App\Repository\CategoryRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdvertControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private AdvertRepository $repository;
    private CategoryRepository $categoryRepository;
    private string $path = '/admin/advert/';

//    private EntityManagerInterface $manager;
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Advert::class);
        $this->categoryRepository = static::getContainer()->get('doctrine')->getRepository(Category::class);

//        foreach ($this->repository->findAll() as $object) {
//            $this->manager->remove($object);
//        }
    }

    public function testIndex(): void
    {
        $this->client->request('GET', $this->path);
        $this->client->followRedirect();

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Advert index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testShow(): void
    {
        $advert = $this->repository->findBy([], ['id' => 'ASC']);

        $this->client->followRedirects();

        $path = $this->path . $advert[0]->getId();
        var_dump($path);
        $this->client->request('GET', $path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Advert');
    }


//    public function testChangetate(): void
//    {
//        $fixture = new Advert();
//        $fixture->setTitle('My Title');
//        $fixture->setContent('My Title');
//        $fixture->setAuthor('My Title');
//        $fixture->setEmail('My Title');
//        $fixture->setPrice('My Title');
//        $fixture->setState('My Title');
//        $fixture->setCreatedAt('My Title');
//        $fixture->setPublishedAt('My Title');
//        $fixture->setCategory('My Title');
//
//        $this->manager->persist($fixture);
//        $this->manager->flush();
//
//        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));
//
//        $this->client->submitForm('Update', [
//            'advert[title]' => 'Something New',
//            'advert[content]' => 'Something New',
//            'advert[author]' => 'Something New',
//            'advert[email]' => 'Something New',
//            'advert[price]' => 'Something New',
//            'advert[state]' => 'Something New',
//            'advert[createdAt]' => 'Something New',
//            'advert[publishedAt]' => 'Something New',
//            'advert[category]' => 'Something New',
//        ]);
//
//        self::assertResponseRedirects('/advert/');
//
//        $fixture = $this->repository->findAll();
//
//        self::assertSame('Something New', $fixture[0]->getTitle());
//        self::assertSame('Something New', $fixture[0]->getContent());
//        self::assertSame('Something New', $fixture[0]->getAuthor());
//        self::assertSame('Something New', $fixture[0]->getEmail());
//        self::assertSame('Something New', $fixture[0]->getPrice());
//        self::assertSame('Something New', $fixture[0]->getState());
//        self::assertSame('Something New', $fixture[0]->getCreatedAt());
//        self::assertSame('Something New', $fixture[0]->getPublishedAt());
//        self::assertSame('Something New', $fixture[0]->getCategory());
//    }
}
