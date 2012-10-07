<?php

namespace Gros\ComptaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gros\ComptaBundle\Entity\Operation;
use Gros\ComptaBundle\Form\OperationType;
use Gros\ComptaBundle\Form\OperationHandler;

/**
 * Operation controller.
 *
 * @Route("/operation")
 */
class OperationController extends Controller
{
    /**
     * Lists all Operation entities.
     *
     * @Route("/", name="operation")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('GrosComptaBundle:Operation')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Operation entity.
     *
     * @Route("/{id}/show", name="operation_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GrosComptaBundle:Operation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Operation entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a new Operation entity.
     *
     * @Route("/create", name="operation_create")
     * @Template("GrosComptaBundle:Operation:create.html.twig")
     */
    public function createAction(Request $request)
    {
        $operation  = new Operation();
        $form = $this->createForm(new OperationType(), $operation);

        $formHandler = new OperationHandler($form, $this->get('request'), $this->getDoctrine()->getEntityManager());

        if ($formHandler->process()) {
            return $this->redirect($this->generateUrl('operation_show', array('id' => $operation->getId())));
        }

        return $this->render('GrosComptaBundle:Operation:create.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * Displays a form to edit an existing Operation entity.
     *
     * @Route("/{id}/edit", name="operation_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GrosComptaBundle:Operation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Operation entity.');
        }

        $editForm = $this->createForm(new OperationType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Operation entity.
     *
     * @Route("/{id}/update", name="operation_update")
     * @Method("POST")
     * @Template("GrosComptaBundle:Operation:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GrosComptaBundle:Operation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Operation entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new OperationType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('operation_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Operation entity.
     *
     * @Route("/{id}/delete", name="operation_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GrosComptaBundle:Operation')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Operation entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('operation'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
