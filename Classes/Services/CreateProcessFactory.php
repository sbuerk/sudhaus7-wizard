<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 project.
 *
 * @author Frank Berger <fberger@sudhaus7.de>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace SUDHAUS7\Sudhaus7Wizard\Services;

use Psr\Log\LoggerInterface;
use SUDHAUS7\Sudhaus7Wizard\CreateProcess;
use SUDHAUS7\Sudhaus7Wizard\Domain\Model\Creator;
use SUDHAUS7\Sudhaus7Wizard\Interfaces\WizardProcessInterface;
use SUDHAUS7\Sudhaus7Wizard\Sources\LocalDatabase;
use SUDHAUS7\Sudhaus7Wizard\Sources\SourceInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @internal
 */
final class CreateProcessFactory
{
    /**
     * @internal
     * @param Creator $creator
     * @param LoggerInterface|null $logger
     * @return CreateProcess
     */
    public static function get(Creator $creator, ?LoggerInterface $logger = null): CreateProcess
    {
        $tsk              = GeneralUtility::makeInstance(CreateProcess::class);
        if ($logger instanceof LoggerInterface) {
            $tsk->setLogger($logger);
        }
        $tsk->setTask($creator);
        $tsk->setTemplateKey($creator->getBase());
        /** @var class-string $processInterface */
        $processInterface              = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['Sudhaus7Wizard']['registeredTemplateExtentions'][ $tsk->getTemplateKey() ];
        /** @var WizardProcessInterface $wizardProcess */
        $wizardProcess = GeneralUtility::makeInstance($processInterface);
        $tsk->setTemplate($wizardProcess);
        $sourceClassName = $creator->getSourceclass();
        if (\class_exists($sourceClassName)) {
            $sourceClass = GeneralUtility::makeInstance(ltrim($sourceClassName, '\\'));
            $tsk->setSource($sourceClass instanceof SourceInterface ? $sourceClass : GeneralUtility::makeInstance(LocalDatabase::class));
            $tsk->getSource()->setCreator($creator);
            $tsk->getSource()->setLogger($logger);
        }
        $pid = $creator->getSourcepid();
        $tsk->setSiteConfig($tsk->getSource()->getSiteConfig($pid));
        return $tsk;
    }
}
