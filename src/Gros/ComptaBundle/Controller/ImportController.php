<?php

namespace Gros\ComptaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gros\ComptaBundle\Entity\Import;
use Gros\ComptaBundle\Form\ImportHandler;

/**
 * Data Import controller.
 *
 * @Route("/import")
 */
class ImportController extends Controller
{
    /**
     * Operation data import for ComptaGros
     *
     * @Route("/", name="import")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        // Uploader
        $import = new Import;
        $form = $this->createFormBuilder($import)
                 ->add('file', 'file', array('label' => 'Your CSV bank file:'))
                 ->getForm();

        $formHandler = new ImportHandler($form, $request, $this->getDoctrine()->getEntityManager());

        if ($formHandler->process()) {
            return $this->redirect($this->generateUrl('import_parsing', array('id' => $import->getId())));
        }

        // Pending imports list
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('GrosComptaBundle:Import')->findAll();

        return $this->render('GrosComptaBundle:Import:index.html.twig', array(
            'form' => $form->createView(),
            'pending_imports' => $entities,
        ));
    }

    /**
     * Data import parsing page
     *
     * @Route("/parsing/{id}", name="import_parsing")
     * @Template()
     */
    public function parsingAction(Request $request)
    {
        $import = new Import;
        $em = $this->getDoctrine()->getManager();
        $import = $em->getRepository('GrosComptaBundle:Import')->find($request->get('id'));

        $parsing = $import->parseLaBanquePostale();

        return $this->render('GrosComptaBundle:Import:parsing.html.twig', array(
            'parsed_lines' => $parsing,
            'lines_count'  => count($parsing),
        ));
    }
}
