<?php

namespace App\Controller;

use App\Dto\CombinationPositionDto;
use App\Service\CombinationPdfService;
use chillerlan\QRCode\QRCode;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CombinationPdfController extends AbstractController
{
    public function __construct(
        private Pdf                   $knpSnappyPdf,
        private CombinationPdfService $combinationPdfService
    )
    {
    }

    #[Route('/combination/lock/{lock}.pdf', name: 'app_combination_pdf')]
    public function index(int $lock): PdfResponse
    {
        $combinations = $this->combinationPdfService->getCombinationsForLock($lock);

        if (null === $combinations) {
            throw new NotFoundHttpException();
        }

        $html = $this->renderView('combination_pdf/combinations-pdf.html.twig', array(
            'combinations' => array_map(fn($combination) => new CombinationPositionDto($combination, (new QRCode())->render($combination->getLock() . $combination->getLockColumn())), $combinations),
        ));

        return new PdfResponse(
            $this->knpSnappyPdf
                ->setOption('enable-local-file-access', true)
                ->setOption('margin-top', 0)
                ->setOption('margin-left', 0)
                ->setOption('margin-right', 0)
                ->setOption('margin-bottom', 0)
                ->getOutputFromHtml($html),
            'combinations.pdf',
            'application/pdf',
            ResponseHeaderBag::DISPOSITION_INLINE
        );
    }
}
