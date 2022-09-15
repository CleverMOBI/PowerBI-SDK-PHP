<?php

namespace Tngnt\PBI\API;

use Tngnt\PBI\Client;
use Tngnt\PBI\Response;

class Report
{
    const REPORTS_URL = "https://api.powerbi.com/v1.0/myorg/reports";
    const REPORTS_IN_GROUP_URL = "https://api.powerbi.com/v1.0/myorg/groups/%s/reports";
    const REPORT_URL = "https://api.powerbi.com/v1.0/myorg/reports/%s";
    const REPORT_IN_GROUP_URL = "https://api.powerbi.com/v1.0/myorg/groups/%s/reports/%s";
    const PAGES_URL = 'https://api.powerbi.com/v1.0/myorg/reports/%s/pages';
    const PAGES_IN_GROUP_URL = 'https://api.powerbi.com/v1.0/myorg/groups/%s/reports/%s/pages';
    const GROUP_REPORT_EMBED_URL = "https://api.powerbi.com/v1.0/myorg/groups/%s/reports/%s/GenerateToken";
    const EXPORT_TO_FILE_URL = 'https://api.powerbi.com/v1.0/myorg/reports/%s/ExportTo';
    const EXPORT_TO_FILE_IN_GROUP_URL = 'https://api.powerbi.com/v1.0/myorg/groups/%s/reports/%s/ExportTo';
    const EXPORT_TO_FILE_STATUS_URL = 'https://api.powerbi.com/v1.0/myorg/reports/%s/exports/%s';
    const EXPORT_TO_FILE_STATUS_IN_GROUP_URL = 'https://api.powerbi.com/v1.0/myorg/groups/%s/reports/%s/exports/%s';
    const FILE_OF_EXPORT_TO_FILE_URL = 'https://api.powerbi.com/v1.0/myorg/reports/%s/exports/%s/file';
    const FILE_OF_EXPORT_TO_FILE_IN_GROUP_URL = 'https://api.powerbi.com/v1.0/myorg/groups/%s/reports/%s/exports/%s/file';

    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client The SDK client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string|null $groupId An optional group ID
     *
     * @return Response
     */
    public function getReports($groupId = null)
    {
        $url = $this->getReportsUrl($groupId);

        $response = $this->client->request(Client::METHOD_GET, $url);

        return $this->client->generateResponse($response);
    }

    /**
     * @param string      $reportId Report ID
     * @param string|null $groupId An optional group ID
     *
     * @return Response
     */
    public function getReport($reportId, $groupId = null)
    {
        $url = $this->getReportUrl($reportId, $groupId);

        $response = $this->client->request(Client::METHOD_GET, $url);

        return $this->client->generateResponse($response);
    }

    /**
     * @param string      $reportId The report ID of the report
     * @param string|null $groupId  An optional group ID
     *
     * @return Response
     */
    public function getPages($reportId, $groupId = null)
    {
        $url = $this->getPagesUrl($reportId, $groupId);

        $response = $this->client->request(Client::METHOD_GET, $url);

        return $this->client->generateResponse($response);
    }

    /**
     * @param string      $reportId    The report ID of the report
     * @param string|null $groupId     The group ID of the report
     * @param string|null $accessLevel The access level used for the report
     *
     * @return Response
     */
    public function getReportEmbedToken($reportId, $groupId = null, $accessLevel = 'view')
    {
        $url = sprintf(self::GROUP_REPORT_EMBED_URL, $groupId, $reportId);

        $body = [
            'accessLevel' => $accessLevel,
        ];

        $response = $this->client->request(Client::METHOD_POST, $url, $body);

        return $this->client->generateResponse($response);
    }

    /**
     * @param string $reportId The report ID of the report
     * @param array $body
     * @param string|null $groupId The group ID of the report
     * @return Response
     */
    public function exportToFile($reportId, array $body, $groupId = null)
    {
        $url = $this->getExportToFileUrl($reportId, $groupId);

        $response = $this->client->request(Client::METHOD_POST, $url, $body);

        return $this->client->generateResponse($response);
    }

    /**
     * @param string $reportId The report ID of the report
     * @param string $exportId The export job ID of the report
     * @param string|null $groupId The group ID of the report
     * @return Response
     */
    public function getExportToFileStatus($reportId, $exportId, $groupId = null)
    {
        $url = $this->getExportToFileStatusUrl($reportId, $exportId, $groupId);

        $response = $this->client->request(Client::METHOD_GET, $url);

        return $this->client->generateResponse($response);
    }

    /**
     * @param string $reportId The report ID of the report
     * @param string $exportId The export job ID of the report
     * @param string|null $groupId The group ID of the report
     * @return Response
     */
    public function getFileOfExportToFile($reportId, $exportId, $file, $groupId = null)
    {
        $url = $this->getFileOfExportToFileUrl($reportId, $exportId, $groupId);

        $response = $this->client->requestFile(Client::METHOD_GET, $url, $file);

        return $this->client->generateResponse($response);
    }

    /**
     * @param string|null $groupId An optional group ID
     *
     * @return string
     */
    private function getReportsUrl($groupId = null)
    {
        if (null !== $groupId) {
            return sprintf(self::REPORTS_IN_GROUP_URL, $groupId);
        }

        return self::REPORTS_URL;
    }

    /**
     * @param string|null $groupId An optional group ID
     *
     * @return string
     */
    private function getReportUrl($reportId, $groupId = null)
    {
        if (null !== $groupId) {
            return sprintf(self::REPORT_IN_GROUP_URL, $groupId, $reportId);
        }

        return sprintf(self::REPORT_URL, $reportId);
    }

    /**
     * @param string      $reportId id from report
     * @param string|null $groupId  An optional group ID
     *
     * @return string
     */
    private function getPagesUrl($reportId, $groupId = null)
    {
        if (null !== $groupId) {
            return sprintf(self::PAGES_IN_GROUP_URL, $groupId, $reportId);
        }

        return sprintf(self::PAGES_URL, $reportId);
    }

    /**
     * @param string      $reportId id from report
     * @param string|null $groupId  An optional group ID
     *
     * @return string
     */
    private function getExportToFileUrl($reportId, $groupId)
    {
        if (null !== $groupId) {
            return sprintf(self::EXPORT_TO_FILE_IN_GROUP_URL, $groupId, $reportId);
        }

        return sprintf(self::EXPORT_TO_FILE_URL, $reportId);
    }

    private function getExportToFileStatusUrl($reportId, $exportId, $groupId = null)
    {
        if (null !== $groupId) {
            return sprintf(self::EXPORT_TO_FILE_STATUS_IN_GROUP_URL, $groupId, $reportId, $exportId);
        }

        return sprintf(self::EXPORT_TO_FILE_STATUS_URL, $reportId, $exportId);
    }

    private function getFileOfExportToFileUrl($reportId, $exportId, $groupId = null)
    {
        if (null !== $groupId) {
            return sprintf(self::FILE_OF_EXPORT_TO_FILE_IN_GROUP_URL, $groupId, $reportId, $exportId);
        }

        return sprintf(self::FILE_OF_EXPORT_TO_FILE_URL, $reportId, $exportId);
    }
}
