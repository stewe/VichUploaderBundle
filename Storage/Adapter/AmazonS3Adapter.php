<?php
namespace Vich\UploaderBundle\Storage\Adapter;

use Vich\UploaderBundle\Storage\Adapter\CDNAdapterInterface;

/**
 * Adapter for Amazon S3 CDN
 *
 * @author Stefano Sala <stefano.sala@spa.it>
 */
class AmazonS3Adapter implements CDNAdapterInterface
{
    /**
     * @var AmazonS3
     */
    protected $service;

    /**
     * @var string
     */
    protected $bucket;

    /**
     * Constructor.
     * 
     * @param AmazonS3 $service
     * @param string $bucket
     * @param string $directory optional directory to store files
     */
    public function __construct(\AmazonS3 $service, $bucket)
    {
        $this->service = $service;
        $this->bucket = $bucket;
    }

    /**
     * {@inheritDoc}
     */
    public function put($filePath, $filename)
    {
        $response = $this->service->create_object(
            $this->bucket,
            $filename,
            array('fileUpload' => $filePath)
        );

        if (!$response->isOK()) {
            throw new \RuntimeException(sprintf('Could not write the \'%s\' file.', $filename));
        }

        return intval($response->header["x-aws-requestheaders"]["Content-Length"]);
    }

    /**
     * {@inheritDoc}
     */
    public function getAbsoluteUri($filename)
    {
        if (!$this->exists($filename)) {
            throw new Exception\FileNotFound($filename);
        }

        $response = $this->service
            ->get_object($this->bucket, $filename);

        return $response->header['_info']['url'];
    }

    /**
     * {@inheritDoc}
     */
    public function remove($filename)
    {
        if (!$this->exists($filename)) {
            throw new Exception\FileNotFound($filename);
        }

        $response = $this->service->delete_object(
            $this->bucket,
            $filename
        );

        if (!$response->isOK()) {
            throw new \RuntimeException(sprintf(
                'Could not delete the "%s" file.',
                $filename
            ));
        }
    }

    /**
     * Method to find if a file exists in current bucket
     * 
     * @param string $filename
     * 
     * @return boolean
     */
    public function exists($filename)
    {
        return $this->service->if_object_exists(
            $this->bucket,
            $filename
        );
    }
}