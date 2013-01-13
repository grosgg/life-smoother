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

        $userGroups = $this->getUser()->getGroups();
        $entities = $em->getRepository('GrosComptaBundle:Operation')->findByGroup($userGroups[0]);
        $grosSecurityService = $this->container->get('gros_compta.security');

        // Checking if the entity can be edited by current user
        foreach ($entities as $key => $entity) {
            if ($grosSecurityService->checkUserAccess('EDIT', $entity, false)) {
                $entities[$key]->canEdit = true;
            } else {
                $entities[$key]->canEdit = false;
            }
        }

        return $this->render('GrosComptaBundle:Operation:index.html.twig', array(
            'entities' => $entities,
        ));
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
     * Creates a new Operation entity.
     *
     * @Route("/create", name="operation_create")
     * @Template("GrosComptaBundle:Operation:create.html.twig")
     */
    public function createAction(Request $request)
    {
        $logger = $this->container->get('gros.logger');
        $grosSecurityService = $this->container->get('gros_compta.security');
        $operation  = new Operation();
        $operationType = new OperationType($this->getUser());

        $formId = $request->get('form_id');
        if ($formId != null) {
            $newFormName = $operationType->getName() . '_' . $formId;
            $operationType->setName($newFormName);
        }

        $form = $this->createForm($operationType, $operation);

        $formHandler = new OperationHandler($form, $request, $this->getDoctrine()->getEntityManager(), $this->getUser(), $logger);

        // Different process depending on form origin
        if($request->isXmlHttpRequest()) {
            if ($formHandler->processAjax()) {
                $grosSecurityService->insertAce($operation);
                die(json_encode(array('status' => true, 'operation' => $operation->getId(), 'form' => $formId)));
            }
        } else {
            if ($formHandler->process()) {
                $grosSecurityService->insertAce($operation);
                return $this->redirect($this->generateUrl('operation_show', array('id' => $operation->getId())));
            }
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

        //ACL or DB Security check
        $grosSecurityService = $this->container->get('gros_compta.security');
        $grosSecurityService->checkUserAccess('EDIT', $entity);

        $editForm = $this->createForm(new OperationType($this->getUser()), $entity);
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

        //ACL or DB Security check
        $grosSecurityService = $this->container->get('gros_compta.security');
        $grosSecurityService->checkUserAccess('EDIT', $entity);

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new OperationType($this->getUser()), $entity);
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

            //ACL or DB Security check
            $grosSecurityService = $this->container->get('gros_compta.security');
            $grosSecurityService->checkUserAccess('DELETE', $entity);

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
