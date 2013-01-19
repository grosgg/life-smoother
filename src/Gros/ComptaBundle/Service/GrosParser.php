<?php

namespace Gros\ComptaBundle\Service;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Bundle\DoctrineBundle\Registry;

class GrosParser
{
    protected $doctrine;
    protected $shops;
    protected $categories;
    protected $defaults;
    protected $processedLineRepository;

    public function __construct(Registry $doctrine, $securityContext)
    {
        $this->doctrine = $doctrine;

        $group = $securityContext->getToken()->getUser()->getGroup()->getId();
        $this->shops = $this->doctrine->getManager()->getRepository('GrosComptaBundle:Shop')->findByGroup($group);
        $this->categories = $this->doctrine->getManager()->getRepository('GrosComptaBundle:Category')->findByGroup($group);
        $this->defaults = $this->doctrine->getManager()->getRepository('GrosComptaBundle:Defaults')->findOneByGroup($group);
        $this->processedLineRepository = $this->doctrine->getManager()->getRepository('GrosComptaBundle:ProcessedLine');

        // TODO: Here only to make less queries for now
        //$this->tomoko = $this->doctrine->getManager()->getRepository('GrosUserBundle:User')->findOneByUsername('tomoko');
        //$this->jeremy = $this->doctrine->getManager()->getRepository('GrosUserBundle:User')->findOneByUsername('jeremy');
    }

    public function parseLaBanquePostale($id, $absolutePath)
    {
        $result = array();
        $row = 0;

        if (($handle = fopen($absolutePath, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $parsedDate = date_parse_from_format('d/m/Y', $data[0]);

                if(checkdate($parsedDate['month'], $parsedDate['day'], $parsedDate['year'])) {
                    // Checking if this line was already processed
                    if (!$this->checkProcessedLine($id, $row)) {
                        $result[$row]['date'] = $parsedDate['year'] . '-' . $parsedDate['month'] . '-' . $parsedDate['day'];
                        $result[$row]['label'] = $data[1];

                        if ($data[2] < 0) {
                            $result[$row]['type'] = 0;
                        } else {
                            $result[$row]['type'] = 1;
                        }
                        $result[$row]['signedAmount'] = floatval(str_replace(',', '.', $data[2]));
                        $result[$row]['absoluteAmount'] = abs($result[$row]['signedAmount']);

                        $result[$row]['parsedLabel'] = $this->parseLabel($data[1]);
                    }
                    $row++;
                }

            }
            fclose($handle);
        }

        //var_dump($result[0]);
        return $result;
    }

    public function parseLabel($data)
    {
        $result = array(
            'guessedCategory' => null,
            'guessedShop'     => null,
            'guessedShopper'  => null,
        );

        // Auto guessing shops
        $result = $this->autoGuessShop($data, $result);

        // Applying default settings
        $result = $this->applyDefaultSettings($result);

        return $result;
    }

    private function autoGuessShop($data, $result)
    {
        foreach ($this->shops as $shop) {
            if (strpos(strtolower($data), strtolower($shop->getName()))) {
                $result['guessedShop'] = $shop->getId();
                $result['guessedCategory'] = $shop->getDefaultCategory()->getId();
            }
        }

        return $result;
    }

    private function applyDefaultSettings($result)
    {
        if ($this->defaults) {
            if (!$result['guessedShop']) {
                $result['guessedShop'] = $this->defaults->getShop()->getId();
            }
            if (!$result['guessedCategory']) {
                $result['guessedCategory'] = $this->defaults->getShop()->getDefaultCategory()->getId();
            }
            if (!$result['guessedShopper']) {
                $result['guessedShopper'] = $this->defaults->getShopper()->getId();
            }
        }

        return $result;
    }

    public function checkProcessedLine($importId, $lineId)
    {
        $result = $this->processedLineRepository->findByBoth($importId, $lineId);

        if (!empty($result)) {
            return true;
        } else {
            return false;
        }
    }

}
