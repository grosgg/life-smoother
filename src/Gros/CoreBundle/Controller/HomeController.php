<?php

namespace Gros\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Home controller.
 *
 * @Route("/home")
 */
class HomeController extends Controller
{
    /**
     * Home screen for website
     *
     * @Route("/", name="home")
     * @Template()
     */
    public function indexAction()
    {
        $group = $this->getUser()->getGroup()->getId();
        $em = $this->getDoctrine()->getManager();

        // Achievements
        $progress = 0;
        $progressTotal = 7;
        $achievements = array(
            'shoppers'   => array('label' => 'Create a shopper', 'done' => false),
            'categories' => array('label' => 'Add a category', 'done' => false),
            'shops'      => array('label' => 'Add a shop', 'done' => false),
            'defaults'   => array('label' => 'Save defaults settings', 'done' => false),
            'rules'      => array('label' => 'Create a parsing rule', 'done' => false),
            'imports'    => array('label' => 'Upload a CSV banking file', 'done' => false),
            'operations' => array('label' => 'Insert an operation', 'done' => false),
        );

        $shoppers = $em->getRepository('GrosComptaBundle:Shopper')->findBy(array('group' => $group));
        if (!empty($shoppers)) {
            $progress += 1;
            $achievements['shoppers']['done'] = true;
        }

        $categories = $em->getRepository('GrosComptaBundle:Category')->findBy(array('group' => $group));
        if (!empty($categories)) {
            $progress += 1;
            $achievements['categories']['done'] = true;
        }

        $shops = $em->getRepository('GrosComptaBundle:Shop')->findBy(array('group' => $group));
        if (!empty($shops)) {
            $progress += 1;
            $achievements['shops']['done'] = true;
        }

        $defaults = $em->getRepository('GrosComptaBundle:Defaults')->findBy(array('group' => $group));
        if (!empty($defaults)) {
            $progress += 1;
            $achievements['defaults']['done'] = true;
        }

        $rules = $em->getRepository('GrosComptaBundle:Rule')->findBy(array('group' => $group));
        if (!empty($rules)) {
            $progress += 1;
            $achievements['rules']['done'] = true;
        }

        $imports = $em->getRepository('GrosComptaBundle:Import')->findBy(array('group' => $group), array('id' => 'DESC'), 4);
        if (!empty($imports)) {
            $progress += 1;
            $achievements['imports']['done'] = true;
        }

        $operations = $em->getRepository('GrosComptaBundle:Operation')->findBy(array('group' => $group), array('id' => 'DESC'), 6);
        if (!empty($operations)) {
            $progress += 1;
            $achievements['operations']['done'] = true;
        }

        // Savings and expenses
        $savingsAndExpenses = array(
            'month' => array(0 => 0, 1 => 0),
            'year' => array(0 => 0, 1 => 0),
        );

        $sumMonth = $em->getRepository('GrosComptaBundle:Operation')->sumByType(date('Y-m-01'), date('Y-m-30'), $group);
        $sumYear = $em->getRepository('GrosComptaBundle:Operation')->sumByType(date('2012-01-01'), date('Y-12-31'), $group);

        for ($i=0; $i<=1; $i++) {
            if (isset($sumMonth[$i])) {
                $savingsAndExpenses['month'][$sumMonth[$i]['type']] = $sumMonth[$i]['sumamount'];
            }
            if (isset($sumYear[$i])) {
                $savingsAndExpenses['year'][$sumYear[$i]['type']] = $sumYear[$i]['sumamount'];
            }
        }

        return $this->render('GrosCoreBundle:Home:index.html.twig', array(
            'operations' => $operations,
            'imports' => $imports,
            'progress' => round($progress / $progressTotal * 100),
            'achievements' => $achievements,
            'savings' => $savingsAndExpenses,
        ));
    }
}
