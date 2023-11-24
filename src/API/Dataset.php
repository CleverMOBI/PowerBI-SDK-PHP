<?php

namespace Tngnt\PBI\API;

use Tngnt\PBI\Client;
use Tngnt\PBI\Model\Dataset as DatasetModel;
use Tngnt\PBI\Response;

/**
 * Class Dataset.
 */
class Dataset
{
    const DATASET_URL = 'https://api.powerbi.com/v1.0/myorg/datasets';
    const GROUP_DATASET_URL = 'https://api.powerbi.com/v1.0/myorg/groups/%s/datasets';
    const REFRESH_DATASET_URL = 'https://api.powerbi.com/v1.0/myorg/datasets/%s/refreshes';
    const GROUP_REFRESH_DATASET_URL = 'https://api.powerbi.com/v1.0/myorg/groups/%s/datasets/%s/refreshes';
    const REFRESH_EXECUTION_DATASET_URL = 'https://api.powerbi.com/v1.0/myorg/datasets/%s/refreshes/%s';
    const GROUP_REFRESH_EXECUTION_DATASET_URL = 'https://api.powerbi.com/v1.0/myorg/groups/%s/datasets/%s/refreshes/%s';

    /**
     * The SDK client
     *
     * @var Client
     */
    private $client;

    /**
     * Dataset constructor.
     *
     * @param Client $client The SDK client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Retrieves the datasets from the PowerBI API
     *
     * @param string|null $groupId An optional group ID
     *
     * @return Response
     */
    public function getDatasets($groupId = null)
    {
        $url = $this->getUrl($groupId);

        $response = $this->client->request(Client::METHOD_GET, $url);

        return $this->client->generateResponse($response);
    }

    /**
     * Get refresh history of the dataset from the PowerBI API
     *
     * @param string      $datasetId An dataset ID
     * @param string|null $groupId   An optional group ID
     * @param int|null    $top       The requested number of entries in the refresh history. If not provided, the default is the last available 60 entries.
     *
     * @return Response
     */
    public function getRefreshHistory($datasetId, $groupId = null, ?int $top = null)
    {
        $url = $this->getRefreshUrl($groupId, $datasetId);
        if ($top) {
            $url .= '?$top='.$top;
        }
        $response = $this->client->request(Client::METHOD_GET, $url);

        return $this->client->generateResponse($response);
    }

    /**
     * Get refresh execution of the dataset from the PowerBI API
     *
     * @param string      $datasetId An dataset ID
     * @param string      $refreshId A refresh ID (for example, from getRefreshHistory)
     * @param string|null $groupId   An optional group ID
     *
     * @return Response
     */
    public function getRefreshExecution($datasetId, $refreshId, $groupId = null)
    {
        $url = $this->getRefreshExecutionUrl($groupId, $datasetId, $refreshId);
        $response = $this->client->request(Client::METHOD_GET, $url);

        return $this->client->generateResponse($response);
    }



    /**
     * Refresh the dataset from the PowerBI API
     *
     * @param string      $datasetId An dataset ID
     * @param string|null $groupId   An optional group ID
     * @param bool|null   $notify    set if user recibe notify mail
     *
     * @return Response
     */
    public function refreshDataset($datasetId, $groupId = null, $notify = true)
    {
        $url = $this->getRefreshUrl($groupId, $datasetId);
        if ($notify) {
            $response = $this->client->request(Client::METHOD_POST, $url, ['notifyOption' => 'MailOnFailure']);
        } else {
            $response = $this->client->request(Client::METHOD_POST, $url);
        }

        return $this->client->generateResponse($response);
    }

    /**
     * Create a new dataset on PowerBI.
     *
     * @param DatasetModel $dataset The dataset model
     * @param string|null  $groupId An optional group ID
     *
     * @return Response
     */
    public function createDataset(DatasetModel $dataset, $groupId = null)
    {
        $url = $this->getUrl($groupId);

        $response = $this->client->request(client::METHOD_POST, $url, $dataset);

        return $this->client->generateResponse($response);
    }

    /**
     * Helper function to format the request URL.
     *
     * @param string|null $groupId An optional group ID
     *
     * @return string
     */
    private function getUrl($groupId)
    {
        if ($groupId) {
            return sprintf(self::GROUP_DATASET_URL, $groupId);
        }

        return self::DATASET_URL;
    }

    /**
     * Helper function to format the request URL.
     *
     * @param string      $datasetId id from dataset
     * @param string|null $groupId   An optional group ID
     *
     * @return string
     */
    private function getRefreshUrl($groupId, $datasetId)
    {
        if ($groupId) {
            return sprintf(self::GROUP_REFRESH_DATASET_URL, $groupId, $datasetId);
        }

        return sprintf(self::REFRESH_DATASET_URL, $datasetId);
    }

    private function getRefreshExecutionUrl($groupId, $datasetId, $refreshId)
    {
        if ($groupId) {
            return sprintf(self::GROUP_REFRESH_EXECUTION_DATASET_URL, $groupId, $datasetId, $refreshId);
        }

        return sprintf(self::REFRESH_EXECUTION_DATASET_URL, $datasetId, $refreshId);
    }

}
