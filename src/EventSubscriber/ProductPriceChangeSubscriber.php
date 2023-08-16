<?php

namespace App\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use App\Entity\Product;
use App\Entity\PriceHistory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Events;

class ProductPriceChangeSubscriber implements EventSubscriber
{
    private EntityManagerInterface $entityManager;
    private array $priceHistoryQueue = [];

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::preUpdate,
            Events::postFlush,
        ];
    }

    public function preUpdate(PreUpdateEventArgs $event)
    {
        $product = $event->getObject();
        if (!$product instanceof Product) {
            return;
        }
        if ($event->hasChangedField('price')) {
            $priceHistory = new PriceHistory();
            $priceHistory->setDateUpdate(new \DateTime());
            $priceHistory->setPrice($event->getNewValue('price'));
            $priceHistory->setProduct($product);

            $this->priceHistoryQueue[] = $priceHistory;
        }
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        if (!empty($this->priceHistoryQueue)) {
            foreach ($this->priceHistoryQueue as $priceHistory) {
                $this->entityManager->persist($priceHistory);
            }
            $this->priceHistoryQueue = [];
            $this->entityManager->flush();
        }
    }
}
