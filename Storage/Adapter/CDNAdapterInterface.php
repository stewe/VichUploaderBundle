<?php

namespace Vich\UploaderBundle\Storage\Adapter;

/**
 * CDNAdapterInterface
 *
 * @author ftassi
 */
interface CDNAdapterInterface
{
    /**
     * Uploads $filePath to CDN
     *
     * @param string $filePath
     * @param string $filename
     */
    public function put($filePath, $filename);

    /**
     * Returns the public uri of a resource
     *
     * @param string $filename
     *
     * @return string
     */
    public function getAbsoluteUri($filename);

    /**
     * Delete $filename
     *
     * @param string $filename
     */
    public function remove($filename);
}