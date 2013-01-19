<?php

namespace Gros\ComptaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gros\ComptaBundle\Entity\Defaults;
use Gros\ComptaBundle\Form\DefaultsType;
use Gros\ComptaBundle\Form\DefaultsHandler;

/**
 * Settings controller.
 *
 * @Route("/settings")
 */
class SettingsController extends Controller
{
    /**
     * Default settings screen for compta
     *
     * @Route("/", name="settings_defaults")
     * @Template("GrosComptaBundle:Settings:defaults.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $entity = $em->getRepository('GrosComptaBundle:Defaults')->findOneByGroup($user->getGroup());
        $entityType = new DefaultsType($user);

        if (!$entity) {
            //throw $this->createNotFoundException('Unable to find Defaults entity.');
            $entity = new Defaults();
        }

        $form = $this->createForm($entityType, $entity);

        $formHandler = new DefaultsHandler($form, $this->get('request'), $this->getDoctrine()->getEntityManager(), $user);

        if ($formHandler->process()) {
        }

        $editForm = $this->createForm($entityType, $entity);

        return array(
            'edit_form'   => $editForm->createView(),
        );
    }
}
