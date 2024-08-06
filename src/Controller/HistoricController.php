<?php

namespace App\Controller;

use App\Entity\Equipment;
use App\Entity\Reparation;
use App\Entity\Utilisation;
use App\Entity\Employee;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

class HistoricController extends AbstractController
{

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/historic/equipmane/{id}', name: 'app_historic')]
    public function index($id): Response
    {

        //$r=$this->maintenanceRepository->joinWithEquipment();
        $eqp = $this->entityManager->getRepository(Equipment::class)->find($id);
        $rptReposotory = $this->entityManager->getRepository(Reparation::class);
        $utlReposotory = $this->entityManager->getRepository(Utilisation::class);


        $rptQuery = $rptReposotory->createQueryBuilder('r');
        $rptQuery->where('r.numeroEqp = :id')
            ->setParameter('id', $id)
            ->orderBy('r.date_entre', 'DESC');;

        $utlQuery = $utlReposotory->createQueryBuilder('u');
        $utlQuery->where('u.numeroEqp = :id')
            ->setParameter('id', $id)
            ->orderBy('u.date_utilisation', 'DESC');


        $rptData = $rptQuery->getQuery()->getResult();
        $utlDdata = $utlQuery->getQuery()->getResult();


        $max = count($rptData) > count($utlDdata) ? count($rptData) : count($utlDdata);
        $newData = $this->createData($rptData, $utlDdata);
        return $this->render(
            'historic/index.html.twig',
            [
                'newData' => $newData,

            ]
        );
    }


    public function createData(array $A1, array $A2): array
    {
        $j = 0;
        $i = 0;
        $newArray = [];
        $continue = true;
        while (($i < count($A1) || $j < count($A2)) && $continue) {


            $emp = $this->entityManager->getRepository(Employee::class)->find($A2[$j]->getIdEmp());
            $nom_prenom = $emp->getName() . ' ' . $emp->getLastname();


            if ($A1[$i]->getDateEntre() < $A2[$j]->getDateUtilisation()) {


                $newArray[] = $this->dataUTL($A2[$j], $nom_prenom);

                $j = $j + 1;
                if ($j == count($A2)) {
                    $continue = false;
                    $A = array_slice($A1, $i);
                    foreach ($A as $a) {
                        $newArray[] = $this->dataRPT($A1[$i]);
                    }
                }
            } else {
                $newArray[] = $this->dataRPT($A1[$i]);
                $i = $i + 1;
                if ($i == count($A1)) {
                    $continue = false;
                    $A = array_slice($A2, $j);
                    foreach ($A as $a) {
                        $newArray[] = $this->dataUTL($A2[$j], $nom_prenom);
                    }
                }
            }
        }

        return $newArray;
    }


    public function dataUTL(Utilisation $U, string $emp): string
    {
        $result = $U->getDateRetourne() !== null ? "et il l'a rendu dans le  ".$U->getDateRetourne()->format('Y-m-d') ." à  ".$U->getDateRetourne()->format('H:i') : "et il ne l'a pas encore rendu";
        return "Utiliser par   " . $emp . "  le   " . $U->getDateUtilisation()->format('Y-m-d'). " à  " .$U->getDateUtilisation()->format('H:I') . "    " . $result;
    }


    public function dataRPT(Reparation $R)
    {
        $result = $R->getDateSortie() !== null ? "et c est revenu le   ".$R->getDateSortie()->format('Y-m-d'). " à  " . $R->getDateEntre()->format('H:i') : "et il n'est pas encore revenu";
        $dsc = $R->getDescription() != '' ? " à cause de ".$R->getDescription() : "";


        
        return "ça va à la réparation   le   " . $R->getDateEntre()->format('Y-m-d') . " à  " . $R->getDateEntre()->format('H:i')."  ". $result;
    }
}
