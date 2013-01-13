<?php

namespace Gros\ComptaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gros\ComptaBundle\Entity\Shop;
use Gros\ComptaBundle\Form\ShopType;
use Gros\ComptaBundle\Form\ShopHandler;

/**
 * Shop controller.
 *
 * @Route("/shop")
 */
class ShopController extends Controller
{
    /**
     * Lists all Shop entities.
     *
     * @Route("/", name="shop")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $userGroups = $this->getUser()->getGroups();
        $entities = $em->getRepository('GrosComptaBundle:Shop')->findByGroup($userGroups[0]);

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Shop entity.
     *
     * @Route("/{id}/show", name="shop_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GrosComptaBundle:Shop')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop entity.');
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
     * Creates a new Shop entity.
     *
     * @Route("/create", name="shop_create")
     * @Template("GrosComptaBundle:Shop:create.html.twig")
     */
    public function createAction(Request $request)
    {
        $shop  = new Shop;
        $form = $this->createForm(new ShopType($this->getUser()), $shop);

        $formHandler = new ShopHandler($form, $this->get('request'), $this->getDoctrine()->getEntityManager(), $this->getUser());

        if ($formHandler->process()) {
            return $this->redirect($this->generateUrl('shop_show', array('id' => $shop->getId())));
        }

        return $this->render('GrosComptaBundle:Shop:create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Shop entity.
     *
     * @Route("/{id}/edit", name="shop_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GrosComptaBundle:Shop')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop entity.');
        }

        //ACL or DB Security check
        $grosSecurityService = $this->container->get('gros_compta.security');
        $grosSecurityService->checkGroupAccess($entity);

        $editForm = $this->createForm(new ShopType($this->getUser()), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Shop entity.
     *
     * @Route("/{id}/update", name="shop_update")
     * @Method("POST")
     * @Template("GrosComptaBundle:Shop:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GrosComptaBundle:Shop')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Shop entity.');
        }

        //ACL or DB Security check
        $grosSecurityService = $this->container->get('gros_compta.security');
        $grosSecurityService->checkGroupAccess($entity);

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ShopType($this->getUser()), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('shop_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Shop entity.
     *
     * @Route("/{id}/delete", name="shop_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GrosComptaBundle:Shop')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Shop entity.');
            }

            //ACL or DB Security check
            $grosSecurityService = $this->container->get('gros_compta.security');
            $grosSecurityService->checkGroupAccess($entity);

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('shop'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
