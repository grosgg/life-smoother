<?php

namespace Gros\ComptaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gros\ComptaBundle\Entity\Rule;
use Gros\ComptaBundle\Form\RuleType;
use Gros\ComptaBundle\Form\RuleHandler;

/**
 * Rule controller.
 *
 * @Route("/rule")
 */
class RuleController extends Controller
{
    /**
     * Lists all Rule entities.
     *
     * @Route("/", name="rule")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $userGroup = $this->getUser()->getGroup();
        $entities = $em->getRepository('GrosComptaBundle:Rule')->findByGroup($userGroup);
        $grosSecurityService = $this->container->get('gros_compta.security');

        // Checking if the entity can be edited by current user
        foreach ($entities as $key => $entity) {
            if ($grosSecurityService->checkUserAccess('EDIT', $entity, false)) {
                $entities[$key]->canEdit = true;
            } else {
                $entities[$key]->canEdit = false;
            }
        }

        return $this->render('GrosComptaBundle:Rule:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a Rule entity.
     *
     * @Route("/{id}/show", name="rule_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GrosComptaBundle:Rule')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rule entity.');
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
     * Creates a new Rule entity.
     *
     * @Route("/create", name="rule_create")
     * @Template("GrosComptaBundle:Rule:create.html.twig")
     */
    public function createAction(Request $request)
    {
        $logger = $this->container->get('gros.logger');
        $grosSecurityService = $this->container->get('gros_compta.security');
        $rule = new Rule();
        $rule->setGroup($this->getUser()->getGroup());
        $ruleType = new RuleType($this->getUser());

        $formId = $request->get('form_id');
        if ($formId != null) {
            $newFormName = $ruleType->getName() . '_' . $formId;
            $ruleType->setName($newFormName);
        }

        $form = $this->createForm($ruleType, $rule);

        $formHandler = new RuleHandler($form, $request, $this->getDoctrine()->getEntityManager(), $this->getUser(), $logger);

        // Different process depending on form origin
        if($request->isXmlHttpRequest()) {
            if ($formHandler->processAjax()) {
                $grosSecurityService->insertAce($rule);
                die(json_encode(array('status' => true, 'rule' => $rule->getId(), 'form' => $formId)));
            }
        } else {
            if ($formHandler->process()) {
                $grosSecurityService->insertAce($rule);
                return $this->redirect($this->generateUrl('rule_show', array('id' => $rule->getId())));
            }
        }

        return $this->render('GrosComptaBundle:Rule:create.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * Displays a form to edit an existing Rule entity.
     *
     * @Route("/{id}/edit", name="rule_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GrosComptaBundle:Rule')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rule entity.');
        }

        //ACL or DB Security check
        $grosSecurityService = $this->container->get('gros_compta.security');
        $grosSecurityService->checkUserAccess('EDIT', $entity);

        $editForm = $this->createForm(new RuleType($this->getUser()), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Rule entity.
     *
     * @Route("/{id}/update", name="rule_update")
     * @Method("POST")
     * @Template("GrosComptaBundle:Rule:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GrosComptaBundle:Rule')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rule entity.');
        }

        //ACL or DB Security check
        $grosSecurityService = $this->container->get('gros_compta.security');
        $grosSecurityService->checkUserAccess('EDIT', $entity);

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new RuleType($this->getUser()), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('rule_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Rule entity.
     *
     * @Route("/{id}/delete", name="rule_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GrosComptaBundle:Rule')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Rule entity.');
            }

            //ACL or DB Security check
            $grosSecurityService = $this->container->get('gros_compta.security');
            $grosSecurityService->checkUserAccess('DELETE', $entity);

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('rule'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

}
