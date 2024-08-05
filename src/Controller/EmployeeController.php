<?php

namespace App\Controller;



use App\Entity\Equipment;
use App\Entity\Employee;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DateTime\DateTime;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\FileUploader;
use App\Service\NotefecationService;

class EmployeeController extends AbstractController
{

    private $entityManager;
    private $fileUploader;
    private $notefy;
    

    public function __construct(EntityManagerInterface $entityManager, FileUploader $fileUploader,NotefecationService $notefy)
    {
        $this->entityManager = $entityManager;
        $this->fileUploader = $fileUploader;
        $this->notefy=$notefy;
    }






    #[Route('/employee', name: 'app_dashboard_employee')]
    public function employee(): Response
    {
        $Emp = $this->entityManager->getRepository(Employee::class)->findBy([], ['date_de_creetion' => 'DESC']);
        return $this->render('employee/employee.html.twig', [
            'Emps' => $Emp
        ]);
    }
    #[Route('/employee/ajoute', name: 'ajoute_employee')]
    public function index(): Response
    {
        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
            // User has the ROLE_ADMIN role
            return $this->redirectToRoute('employee');
        }
        return $this->render('employee/addemployee.html.twig');
    }



    #[Route('/employee/update/{id}', name: 'ajoute_employee')]
    public function display_update(int $id, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
            // User has the ROLE_ADMIN role
            return $this->redirectToRoute('employee');
        }
        $entity = $entityManager->getRepository(Employee::class)->find($id);
        return $this->render('employee/updateemployee.html.twig', [
            'emp' => $entity
        ]);
    }




    #[Route('/employee/update/save/{id}', name: 'ajoute_employee')]
    public function save_update(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
            // User has the ROLE_ADMIN role
            return $this->redirectToRoute('employee');
        }
        $Emp = $entityManager->getRepository(Employee::class)->find($id);
        $Emp->setName($request->get('nom'));
        $Emp->setLastname($request->get('prenom'));
        if (!$request->get('ddn')) {
            $Emp->setBirthday(\DateTime::createFromFormat('Y-m-d', '0000-00-00'));
        } else {
            $Emp->setBirthday(\DateTime::createFromFormat('Y-m-d', $request->get('ddn')));
        }

        $Emp->setTelephone($request->get('telephone'));

        
        $Emp->setSalaire(floatval($request->get('salaire')));
        $Emp->setEmail($request->get('email'));

        $dateTime = new \DateTime();
        $profileImage = $request->files->get('image_uploader');
       
        if ($profileImage) {
            $fileName = "I-" . $dateTime->format('YmdHis') . ".jpg";
            $this->fileUploader->upload($profileImage, "/images/employees/profile_photo/", $fileName);
            $Emp->setProfileImage($fileName);
        }
        
        $naissance = $request->files->get('naissance');
        if ($naissance) {
            $fileName = "N-" . $dateTime->format('YmdHis') . ".pdf";
            $this->fileUploader->upload($naissance, "/images/employees/act_de_naissance/", $fileName);
            $Emp->setBirthCerteficat($fileName);
        }
        $residence = $request->files->get('residence');
        if ($residence) {
            $fileName = "R-" . $dateTime->format('YmdHis') . ".pdf";
            $this->fileUploader->upload($residence, "/images/employees/residence/", $fileName);
            $Emp->setResidence($fileName);
        }


        $entityManager->flush();

        $user=$this->getUser();
        $in_line=' '.$request->get('nom').' '.$request->get('prenom');
        $this->notefy->notefy($user->getUserIdentifier(),'employee',$in_line,'update');
        return $this->redirectToRoute('employee');
    }


    #[Route('/employee/sauvegarder', name: 'save_employee')]
    public function save(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
            // User has the ROLE_ADMIN role
            return $this->redirectToRoute('employee');
        }
        $Emp = new employee();
        $Emp->setName($request->get('nom'));
        $Emp->setLastname($request->get('prenom'));
        if (!$request->get('ddn')) {
            $Emp->setBirthday(\DateTime::createFromFormat('Y-m-d', '0000-00-00'));
        } else {
            $Emp->setBirthday(\DateTime::createFromFormat('Y-m-d', $request->get('ddn')));
        }

        $Emp->setTelephone($request->get('telephone'));

        $Emp->setDateDeCreetion(new \DateTime());
        $Emp->setSalaire(floatval($request->get('salaire')));
        $Emp->setEmail($request->get('email'));

        $dateTime = new \DateTime();
        $profileImage = $request->files->get('image_uploader');
        $fileName="default_profile_image.jpg";
        if ($profileImage) {
            $fileName = "I-" . $dateTime->format('YmdHis') . ".jpg";
            $this->fileUploader->upload($profileImage, "/images/employees/profile_photo/", $fileName);
            
        }
        $Emp->setProfileImage($fileName);
        $naissance = $request->files->get('naissance');
        if ($naissance) {
            $fileName = "N-" . $dateTime->format('YmdHis') . ".pdf";
            $this->fileUploader->upload($naissance, "/images/employees/act_de_naissance/", $fileName);
            $Emp->setBirthCerteficat($fileName);
        }
        $residence = $request->files->get('residence');
        if ($residence) {
            $fileName = "R-" . $dateTime->format('YmdHis') . ".pdf";
            $this->fileUploader->upload($residence, "/images/employees/residence/", $fileName);
            $Emp->setResidence($fileName);
        }

        $entityManager->persist($Emp);
        $entityManager->flush();

        $user=$this->getUser();
        $in_line=' '.$request->get('nom').' '.$request->get('prenom');
        $this->notefy->notefy($user->getUserIdentifier(),'employee',$in_line,'insert');
        return $this->redirectToRoute('employee');
    }




    #[Route('/employee/search', name: 'search_employee')]
    public function search(Request $request, EntityManagerInterface $entityManager): Response
    {
        $query = $entityManager->createQuery(
            'SELECT u FROM App\Entity\Employee u
             WHERE u.name LIKE :searchInput OR
              u.lastname LIKE :searchInput OR
              u.telephone LIKE :searchInput '
        );
        $searchInput = $request->get('search_field');
        $query->setParameter('searchInput', $searchInput . '%');
        $emp = $query->getResult();
        $length = count($emp);
        return $this->render('employee/employee.html.twig', [
            'Emps' => $emp,
            'count' => $length
        ]);
    }


    #[Route('/employee/delete/{id}', name: 'delete_employee')]
    public function deleteEntity(int $id, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
            // User has the ROLE_ADMIN role
            return $this->redirectToRoute('employee');
        }
        $entity = $entityManager->getRepository(Employee::class)->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Entity not found');
        }

        $entityManager->remove($entity);
        $entityManager->flush();
        $user=$this->getUser();
        $in_line=' '.$entity->getName().' '.$entity->getLastname();
        $this->notefy->notefy($user->getUserIdentifier(),'employee',$in_line,'delete');

        // Optional: Add a flash message for user feedback
        $this->addFlash('success', 'Entity deleted successfully');

        return $this->redirectToRoute('employee'); // Redirect to a route after deletion
    }


    #[Route('/generatePdf/{id}', name: 'print_action')]

    public function generatePdf(Pdf $pdf, int $id): Response
    {
        // Render the HTML template and capture the specific section
        $eemployeetRepository = $this->entityManager->getRepository(Employee::class);
        $employee = $eemployeetRepository->find($id);
        $html = $this->renderView('employee/pdf_template.html.twig', [
            'emp' => $employee
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
