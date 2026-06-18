<?php

declare(strict_types=1);

namespace Devsk\DsNotifier\Form\FormDataProvider;

use Devsk\DsNotifier\UserFunction\FormEngine\Tca;
use TYPO3\CMS\Backend\Form\FormDataProviderInterface;

/**
 * Form Data Provider that injects the dynamically generated flex form DS configuration
 * for the ds_notifier notification record into processedTca before TcaFlexPrepare runs.
 */
class NotifierFlexFormDataProvider implements FormDataProviderInterface
{
    public function addData(array $result): array
    {
        if ($result['tableName'] !== 'tx_dsnotifier_domain_model_notification') {
            return $result;
        }

        if (!isset($result['processedTca']['columns']['configuration'])) {
            return $result;
        }

        $flexConfig = Tca::flexFormTcaConfiguration();

        // Update processedTca so getDataStructureIdentifier() finds the event-specific ds key.
        // The actual DS file is provided by NotifierFlexFormListener via PSR-14 events.
        $result['processedTca']['columns']['configuration']['config'] = array_merge(
            $result['processedTca']['columns']['configuration']['config'] ?? [],
            $flexConfig['config']
        );

        return $result;
    }
}

