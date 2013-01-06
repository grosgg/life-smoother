<?php

namespace Gros\ComptaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gros\ComptaBundle\Entity\Shopper;
use Gros\ComptaBundle\Form\ShopperType;
use Gros\ComptaBundle\Form\ShopperHandler;

/**
 * Shopper controller.
 *
 * @Route("/shopper")
 */
class ShopperController extends Controller
{
    /**
     * Lists all Shopper entities.
     *
     * @Route("/", name="shopper")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $userGroups = $this->getUser()->getGroups();
        $entities = $em->getRepository('GrosComptaBundle:Shopper')->findByGroup($userGroups[0]);

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Shopper entity.
     *
     * @Route("/{id}/show", name="shopper_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GrosComptaBundle:Shopper')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shopper entity.');
        }

        //ACL or DB Security check
        $grosSecurityService = $this->container->get('gros_compta.security');
        $grosSecurityService->checkGroupAccess($entity);

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a new Shopper entity.
     *
     * @Route("/create", name="shopper_create")
     * @Template("GrosComptaBundle:Shopper:create.html.twig")
     */
    public function createAction(Request $request)
    {
        $shopper  = new Shopper;
        $form = $this->createForm(new ShopperType, $shopper);

        $formHandler = new ShopperHandler($form, $this->get('request'), $this->getDoctrine()->getEntityManager(), $this->getUser());

        if ($formHandler->process()) {
            return $this->redirect($this->generateUrl('shopper_show', array('id' => $shopper->getId())));
        }

        return $this->render('GrosComptaBundle:Shopper:create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Shopper entity.
     *
     * @Route("/{id}/edit", name="shopper_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GrosComptaBundle:Shopper')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shopper entity.');
        }

        //ACL or DB Security check
        $grosSecurityService = $this->container->get('gros_compta.security');
        $grosSecurityService->checkGroupAccess($entity);

        $editForm = $this->createForm(new ShopperType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Shopper entity.
     *
     * @Route("/{id}/update", name="shopper_update")
     * @Method("POST")
     * @Template("GrosComptaBundle:Shopper:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GrosComptaBundle:Shopper')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shopper entity.');
        }

        //ACL or DB Security check
        $grosSecurityService = $this->container->get('gros_compta.security');
        $grosSecurityService->checkGroupAccess($entity);

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ShopperType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('shopper_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Shopper entity.
     *
     * @Route("/{id}/delete", name="shopper_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GrosComptaBundle:Shopper')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Shopper entity.');
            }

            //ACL or DB Security check
            $grosSecurityService = $this->container->get('gros_compta.security');
            $grosSecurityService->checkGroupAccess($entity);

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('shopper'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
