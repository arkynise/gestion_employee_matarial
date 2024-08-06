<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Entity\Equipment;
use App\Repository\ReparationRepository;
use App\Repository\UtilisationRepository;
use App\Entity\Reparation;
use App\Entity\Utilisation;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Service\NotefecationService;

class EquipmentController extends AbstractController
{

    private $entityManager;
    private $notefy;
    private $maintenanceRepository;
    private $UtilisationRepository;


    public function __construct(EntityManagerInterface $entityManager, NotefecationService $notefy, ReparationRepository $maintenanceRepository,UtilisationRepository $UtilisationRepository)
    {
        $this->entityManager = $entityManager;
        $this->notefy = $notefy;
        $this->maintenanceRepository = $maintenanceRepository;
        $this->UtilisationRepository=$UtilisationRepository;
    }

    #[Route('/equipment', name: 'app_dashboard_equipment')]
    public function equipment(): Response
    {

        $Eqp = $this->entityManager->getRepository(Equipment::class)->findBy([], ['date_ajoute' => 'DESC']);

        return $this->render(
            'equipment/equipment.html.twig',
            [
                'Equipments' => $Eqp,
            ]
        );
    }


    #[Route('/equipment/ajoute', name: 'add_equipment')]
    public function AddEquipment(): Response
    {
        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
            // User has the ROLE_ADMIN role
            return $this->redirectToRoute('equipment');
        }

        return $this->render('equipment/addEquipment.html.twig');
    }



    #[Route('/equipment/ajoute/sauvegarder', name: 'save_equipment')]
    public function saveEquipment(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
            // User has the ROLE_ADMIN role
            return $this->redirectToRoute('equipment');
        }
        $Eqp = new Equipment();
        $Eqp->setNom($request->get('mark'));
        $Eqp->setModel($request->get('model'));
        $Eqp->setDescription($request->get('description'));
        $Eqp->setType($request->get('type'));

        $Eqp->setDateAjoute(new \DateTime());
        $imageFile = $request->files->get('image');
        if ($imageFile) {
            $imageName = $imageFile->getClientOriginalName();
            $originalFilename = "/images/equipment/" . $imageName;
            $targetDirectory = "../public/images/equipment/";
            try {
                $imageFile->move(
                    $targetDirectory,
                    $originalFilename
                );
                $Eqp->setImage($imageName);
            } catch (FileException $e) {
                // Handle file exception, e.g., log error, return error message

                $Eqp->setImage('default_image.jpg');
            }
        } else {
            $Eqp->setImage('default_image.jpg');
        }



        $entityManager->persist($Eqp);
        $entityManager->flush();

        $user = $this->getUser();
        $in_line = ' ' . $request->get('mark') . ' ' . $request->get('model');
        $this->notefy->notefy($user->getUserIdentifier(), 'material', $in_line, 'insert');
        return $this->redirectToRoute('equipment');
    }

    #[Route('/equipment/delete/{id}', name: 'delete_equipment')]
    public function deleteEntity(int $id, EntityManagerInterface $entityManager): Response
    {

        $entity = $entityManager->getRepository(Equipment::class)->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Entity not found');
        }

        $entityManager->remove($entity);
        $entityManager->flush();
        $user = $this->getUser();
        $in_line = ' ' . $entity->getNom() . ' ' . $entity->getModel();
        $this->notefy->notefy($user->getUserIdentifier(), 'material', $in_line, 'delete');
        // Optional: Add a flash message for user feedback
        $this->addFlash('success', 'Entity deleted successfully');

        return $this->redirectToRoute('equipment'); // Redirect to a route after deletion
    }




    #[Route('/equipment/search', name: 'search_equipment')]
    public function search(Request $request, EntityManagerInterface $entityManager): Response
    {
        $query = $entityManager->createQuery(
            'SELECT e FROM App\Entity\Equipment e
             WHERE e.nom LIKE :searchInput OR
              e.model LIKE :searchInput OR
                e.type=:typefield'
        );
        $searchInput = $request->get('search_field');
        $query->setParameter('searchInput', $searchInput . '%');
        $query->setParameter('typefield', $searchInput);
        $Eqp = $query->getResult();
        $length = count($Eqp);
        return $this->render('equipment/equipment.html.twig', [
            'Equipments' => $Eqp,
            'count' => $length
        ]);
    }











    #[Route('/equipment/update/{id}', name: 'update_equipment')]
    public function updateEquipment(int $id, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_SUPER_ADMIN') && !$this->isGranted('ROLE_ADMIN')) {
            // User has the ROLE_ADMIN role
            return $this->redirectToRoute('equipment');
        }
        $equipmentRepository = $this->entityManager->getRepository(Equipment::class);
        $Emp = $this->entityManager->getRepository(Employee::class)->findAll();
        $equipment = $equipmentRepository->find($id);


        return $this->render('equipment/updateEquipment.html.twig', [
            'eqp' => $equipment,
            'Emps' => $Emp
        ]);
    }


    #[Route('/equipment/saveupdate/{id}', name: 'save_update_equipment')]
    public function saveUpdateEquipment(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {


        if (!($this->isGranted('ROLE_SUPER_ADMIN') && !$this->isGranted('ROLE_ADMIN'))) {
            // User has the ROLE_ADMIN role
            return $this->redirectToRoute('equipment');
        }
        $Eqp = $entityManager->getRepository(Equipment::class)->find($id);
        $Eqp->setNom($request->get('mark'));
        $Eqp->setModel($request->get('model'));
        $Eqp->setDescription($request->get('description'));
        $Eqp->setType($request->get('type'));
        $Originam_state = $Eqp->getState();
        $Eqp->setState($request->get('state'));
        $imageFile = $request->files->get('image');

        if ($imageFile) {
            $imageName = $imageFile->getClientOriginalName();
            $originalFilename = "/images/equipment/" . $imageName;
            $targetDirectory = "../public/images/equipment/";
            try {
                $imageFile->move(
                    $targetDirectory,
                    $originalFilename
                );
                $Eqp->setImage($imageName);
            } catch (FileException $e) {
            }
        } else {
            $Eqp->setImage($request->get('original_image'));
        }


        if ($request->get('state') == 'utiliser') {
            $user = $entityManager->getRepository(Employee::class)->find($request->get('user'));
            if ($Originam_state == 'disponible') {


                $this->save_utilisation($Eqp, $user, $entityManager);
            }
            if ($Originam_state == 'mantonance') {
                $this->update_mantonance($id);
                $this->save_utilisation($Eqp, $user, $entityManager);
            }
        }
        if ($request->get('state') == 'mantonance') {

            if ($Originam_state == 'disponible') {
            $this->save_mantonance($Eqp, $request->get('mnt_desc'), $entityManager);
            }
            if ($Originam_state == 'utiliser') {
                $this->update_utilisation($id);
                $this->save_mantonance($Eqp, $request->get('mnt_desc'), $entityManager);
            }
        }
        if ($request->get('state') == 'disponible' && $Originam_state == 'mantonance') {


            $this->update_mantonance($id);
        }
        if ($request->get('state') == 'disponible' && $Originam_state == 'utiliser') {


            $this->update_utilisation($id);
        }

        $entityManager->flush();


        $user = $this->getUser();
        $in_line = ' ' . $request->get('mark') . ' ' . $request->get('model');
        $this->notefy->notefy($user->getUserIdentifier(), 'material', $in_line, 'update');
        return $this->redirectToRoute('equipment');
        return $this->redirectToRoute('equipment');
    }



    public function save_utilisation(Equipment $eqp, Employee $user, EntityManagerInterface $entityManager): void
    {
        $utiliser = new Utilisation();

        $utiliser->setIdEmp($user);
        $utiliser->setNumeroEqp($eqp);
        $utiliser->setDateUtilisation(new \DateTime());
        $entityManager->persist($utiliser);
        $entityManager->flush();
    }

    public function save_mantonance(Equipment $eqp, string $mnt, EntityManagerInterface $entityManager): void
    {
        $Reparation = new Reparation();


        $Reparation->setNumeroEqp($eqp);
        $Reparation->setDateEntre(new \DateTime());
        $Reparation->setDescription($mnt);
        $entityManager->persist($Reparation);
        $entityManager->flush();
    }
    public function update_mantonance($numeroqp): void
    {
        $result = $this->maintenanceRepository->findMaxIdByNumereqp($numeroqp);

        if ($result) {
            $id = $result->getId();
            $Reparation = $this->entityManager->getRepository(Reparation::class)->find($id);
            $Reparation->setDateSortie(new \DateTime());
            $this->entityManager->flush();
        }
    }
    public function update_utilisation($numeroqp): void
    {
        $result = $this->UtilisationRepository->findMaxIdByNumereqp($numeroqp);

        if ($result) {
            $id = $result->getId();
            $Reparation = $this->entityManager->getRepository(Utilisation::class)->find($id);
            $Reparation->setDateRetourne(new \DateTime());
            $this->entityManager->flush();
        }
    }
}
