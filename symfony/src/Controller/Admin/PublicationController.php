<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Common\DoctrineListRepresentationFactory;
use App\Entity\Publication;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\View\ViewHandlerInterface;
use HandcraftedInTheAlps\RestRoutingBundle\Controller\Annotations\RouteResource;
use HandcraftedInTheAlps\RestRoutingBundle\Routing\ClassResourceInterface;
use Sulu\Bundle\MediaBundle\Media\Manager\MediaManagerInterface;
use Sulu\Component\Rest\AbstractRestController;
use Sulu\Component\Security\SecuredControllerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @RouteResource("publication")
 */
class PublicationController extends AbstractRestController implements ClassResourceInterface, SecuredControllerInterface
{
    private DoctrineListRepresentationFactory $doctrineListRepresentationFactory;
    private EntityManagerInterface $entityManager;
    private MediaManagerInterface $mediaManager;

    public function __construct(
        DoctrineListRepresentationFactory $doctrineListRepresentationFactory,
        EntityManagerInterface $entityManager,
        MediaManagerInterface $mediaManager,
        ViewHandlerInterface $viewHandler,
        ?TokenStorageInterface $tokenStorage = null
    ) {
        $this->doctrineListRepresentationFactory = $doctrineListRepresentationFactory;
        $this->entityManager = $entityManager;
        $this->mediaManager = $mediaManager;

        parent::__construct($viewHandler, $tokenStorage);
    }

    public function cgetAction(): Response
    {
        $listRepresentation = $this->doctrineListRepresentationFactory->createDoctrineListRepresentation(
            Publication::RESOURCE_KEY
        );

        return $this->handleView($this->view($listRepresentation));
    }

    public function getAction(int $id): Response
    {
        $publication = $this->entityManager->getRepository(Publication::class)->find($id);
        if (!$publication) {
            throw new NotFoundHttpException();
        }

        return $this->handleView($this->view($publication));
    }

    public function putAction(Request $request, int $id): Response
    {
        $publication = $this->entityManager->getRepository(Publication::class)->find($id);
        if (!$publication) {
            throw new NotFoundHttpException();
        }

        $this->mapDataToEntity($request->request->all(), $publication);
        $this->entityManager->flush();

        return $this->handleView($this->view($publication));
    }

    public function postAction(Request $request): Response
    {
        $publication = new Publication();

        $this->mapDataToEntity($request->request->all(), $publication);
        $this->entityManager->persist($publication);
        $this->entityManager->flush();

        return $this->handleView($this->view($publication, 201));
    }

    public function deleteAction(int $id): Response
    {
        /** @var Publication $publication */
        $publication = $this->entityManager->getReference(Publication::class, $id);
        $this->entityManager->remove($publication);
        $this->entityManager->flush();

        return $this->handleView($this->view(null, 204));
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function mapDataToEntity(array $data, Publication $entity): void
    {
        $entity->setTitle($data['title']);
        $entity->setContentBlocks($data['contentBlocks']);

        /***
        // @TODO:: API output.
        foreach ($entity->getContentBlocks() as  $contentBlock) {
            // Serialize data based on type.
            $type = $contentBlock['type'];
        }**/
    }

    public function getSecurityContext(): string
    {
        return Publication::SECURITY_CONTEXT;
    }
}
