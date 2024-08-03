<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use TCPDF;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Equipment;
use Doctrine\ORM\EntityManagerInterface;

class PdfgeneraterController extends AbstractController
{


    private $dompdf;

    public function __construct()
    {
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', false);
        $options->set('isRemoteEnabled', true);

        $this->dompdf = new Dompdf($options);
    }

    public function generatePdfusingdompdf(string $html): string
    {
        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper('A4', 'portrait');
        $this->dompdf->render();

        return $this->dompdf->output();
    }


    public function generatePdfusingtcpdf($html): string
    {
        // Create new PDF document
        $pdf = new TCPDF();

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Basic Test');
        $pdf->SetSubject('PDF Generation');

        // Set margins
        $pdf->SetMargins(15, 27, 15);
        $pdf->SetHeaderMargin(10);
        $pdf->SetFooterMargin(10);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 10);

        // Add a page
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('helvetica', '', 12);

        // Basic HTML content
       
        $pdf->writeHTML($html, true, false, true, false, '');

        // Output PDF
        return $pdf->Output('', 'S'); // Return PDF as string
    }





    #[Route('/pdfgenerater/equipment/{id}', name: 'app_pdfgenerater_equipment')]
    public function index(int $id,EntityManagerInterface $entityManager): Response
    {

        $equipmentRepository=$entityManager->getRepository(Equipment::class);
        $equipment = $equipmentRepository->find($id);
        $html = $this->renderView('equipment/finalformat.html.twig', [
            'eqp'=>$equipment
        ]);
        $pdfContent=$this->generatePdfusingdompdf($html);
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
