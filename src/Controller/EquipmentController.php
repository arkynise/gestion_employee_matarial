<?php

namespace App\Controller;

use App\Entity\Employee;
use Knp\Snappy\Pdf;
use App\Entity\Equipment;
use App\Entity\TypesEquipment;
use App\Entity\Mantonance;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class EquipmentController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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


        return $this->redirectToRoute('equipment');
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








    #[Route('/equipment/printDisplayEquipment/{id}', name: 'print_equipment')]
    public function printDisplayEquipment(int $id, EntityManagerInterface $entityManager): Response
    {
        $equipmentRepository = $this->entityManager->getRepository(Equipment::class);
        $equipment = $equipmentRepository->find($id);


        return $this->render('equipment/displayPDFEquipment.html.twig', [
            'eqp' => $equipment,

        ]);
    }






    #[Route('/equipment/update/{id}', name: 'update_equipment')]
    public function updateEquipment(int $id, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
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


        if (!($this->isGranted('ROLE_SUPER_ADMIN') || $this->isGranted('ROLE_SUPER_ADMIN'))) {
            // User has the ROLE_ADMIN role
            return $this->redirectToRoute('equipment');
        }
        $Eqp = $entityManager->getRepository(Equipment::class)->find($id);
        $Eqp->setNom($request->get('mark'));
        $Eqp->setModel($request->get('model'));
        $Eqp->setDescription($request->get('description'));
        $Eqp->setType($request->get('type'));
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

        $entityManager->flush();

        return $this->redirectToRoute('equipment');
    }














    #[Route('/generatePdf/{id}', name: 'print_action')]

    public function generatePdf(Pdf $pdf, int $id): Response
    {
        // Render the HTML template and capture the specific section
        $equipmentRepository = $this->entityManager->getRepository(Equipment::class);
        $equipment = $equipmentRepository->find($id);
        $html = $this->renderView('equipment/finalformat.html.twig', [
            'eqp' => $equipment
        ]);

        // Generate PDF from the rendered HTML
        $pdfContent = $pdf->getOutputFromHtml($html);

        // Return the PDF response
        return new Response(
            $pdfContent,
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="file.pdf"',
            ]
        );
    }
}
