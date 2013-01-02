<?php

namespace Gros\ComptaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gros\ComptaBundle\Entity\Import;
use Gros\ComptaBundle\Form\ImportHandler;
use Gros\ComptaBundle\Entity\Operation;
use Gros\ComptaBundle\Form\OperationType;

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
        $em = $this->getDoctrine()->getManager();
        $operationRepository = $em->getRepository('GrosComptaBundle:Operation');

        $import = $em->getRepository('GrosComptaBundle:Import')->find($request->get('id'));
        $shops = $em->getRepository('GrosComptaBundle:Shop')->findAll();
        $categories = $em->getRepository('GrosComptaBundle:Category')->findAll();
        $users = $em->getRepository('GrosUserBundle:User')->findAll();

        $grosParserService = $this->container->get('gros_compta.parser');
        $parsing = $import->parseLaBanquePostale($grosParserService);

        $operation = new Operation;
        $operationForm = new OperationType();
        $operationFormDefaultName = $operationForm->getName();
        $forms = array();
        $parsingTotalCount = count($parsing);

        for ($i=0; $i<$parsingTotalCount; $i++) {
            // Need to define what shop to use for duplicate checking
            if (isset($parsing[$i]['parsedLabel']['guessedShop'])) {
                $guessedShop = $parsing[$i]['parsedLabel']['guessedShop'];
            } else {
                $guessedShop = $shops[0]->getId();
            }

            // When a duplicate is found, we don't display the line again
            if ($operationRepository->checkDuplicate($parsing[$i]['absoluteAmount'], $guessedShop, $parsing[$i]['date'])) {
                unset ($parsing[$i]);
            } else {
                $operationForm->setName($operationFormDefaultName . '_' . $i);
                $form = $this->createForm($operationForm, $operation);
                $forms[$i] = $form->createView();
            }
        }

        return $this->render('GrosComptaBundle:Import:parsing.html.twig', array(
            'parsed_lines' => $parsing,
            'lines_count'  => count($parsing),
            'forms'        => $forms,
            'shops'        => $shops,
            'categories'   => $categories,
            'users'        => $users,
            'importId'     => $import->getId(),
        ));
    }
}
