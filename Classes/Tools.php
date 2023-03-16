<?php

/*
 * This file is part of the TYPO3 project.
 * (c) 2022 B-Factor GmbH
 *          Sudhaus7
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 * The TYPO3 project - inspiring people to share!
 * @copyright 2022 B-Factor GmbH https://b-factor.de/
 * @author Frank Berger <fberger@b-factor.de>
 * @author Daniel Simon <dsimon@b-factor.de>
 */

namespace SUDHAUS7\Sudhaus7Wizard;

class Tools
{

    /**
     * @return mixed
     */
    public static function getRegisteredExtentions()
    {
        return $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['Sudhaus7Wizard']['registeredExtentions'];
    }

    /**
     * @return array
     */
    public static function getCreatorConfig()
    {
        return [
            'flex' => $GLOBALS['TCA']['tx_sudhaus7wizard_domain_model_creator']['columns']['flexinfo']['config']['ds'],
            'types' => $GLOBALS['TCA']['tx_sudhaus7wizard_domain_model_creator']['types'],
        ];
    }

    /**
     * @param $class
     */
    public static function registerExtention($class)
    {
        //$extention,$description,$sourcepid,$flex,$addfields='')
        //{

        if (!in_array(WizardInterface::class, class_implements($class))) {
            return;
        }

        $config = $class::getConfig();

        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['Sudhaus7Wizard']['registeredExtentions'][$config['extention']] = $class;
        if (is_array($GLOBALS['TCA']['tx_sudhaus7wizard_domain_model_creator']) && !isset($GLOBALS['TCA']['tx_sudhaus7wizard_domain_model_creator']['columns']['flexinfo']['config']['ds'][$config['extention']])) {
            $GLOBALS['TCA']['tx_sudhaus7wizard_domain_model_creator']['columns']['base']['config']['items'][] = [
                $config['description'],
                $config['extention'],
            ];
            $GLOBALS['TCA']['tx_sudhaus7wizard_domain_model_creator']['columns']['flexinfo']['config']['ds'][$config['extention']] = $config['flexinfo'];
            $GLOBALS['TCA']['tx_sudhaus7wizard_domain_model_creator']['types'][$config['extention']] = [
                'showitem' => '
                status,base,sourceclass,sourcepid,projektname,longname,shortname,domainname,contact,email,--div--;Benutzer,reduser,redpass,redemail,--div--;Template Konfigurationen,flexinfo
            ' . $config['addfields'],
            ];
            $GLOBALS['TCA']['tx_sudhaus7wizard_domain_model_creator']['columns']['sourcepid']['config']['default'] = $config['sourcepid'];
        }
    }
}
