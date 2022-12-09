<?php

namespace App\EventSubscriber;

use App\Entity\{Service, Category, Letter};
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $sluggerInterface;

    public function __construct(SluggerInterface $sluggerInterface)
    {
        $this->sluggerInterface = $sluggerInterface;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            BeforeEntityPersistedEvent::class  => ['setServiceCategorySlug'],
        );
    }

    public function setServiceCategorySlug(BeforeEntityPersistedEvent $event){

        $entity = $event->getEntityInstance();

        if($entity instanceof Service || $entity instanceof Category || $entity instanceof Letter){
            $slug = $this->sluggerInterface->slug($entity->getName());
            $entity->setSlug(strtolower($slug));
        }
        return;

    }

}
