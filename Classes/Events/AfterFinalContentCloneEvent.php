<?php

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

namespace SUDHAUS7\Sudhaus7Wizard\Events;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use SUDHAUS7\Sudhaus7Wizard\CreateProcess;
use SUDHAUS7\Sudhaus7Wizard\Interfaces\WizardEventInterface;
use SUDHAUS7\Sudhaus7Wizard\Traits\EventTrait;

class AfterFinalContentCloneEvent implements LoggerAwareInterface, WizardEventInterface
{
    use LoggerAwareTrait;
    use EventTrait;
    public function __construct(CreateProcess $create_process)
    {
        $this->create_process = $create_process;
        $this->logger = $create_process->getLogger();
    }

    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }
}
