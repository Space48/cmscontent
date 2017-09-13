<?php
/**
 * ${FILE_NAME}
 *
 * @Date        09/2017
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * @author      @diazwatson
 */

declare(strict_types=1);

namespace Space48\CmsContent\Model;

interface CmsInterface
{

    /**
     * Import CMS Content
     *
     * @param array $fixtures
     *
     * @return void
     */
    public function import(array $fixtures);

    /**
     * Export CMS Content
     *
     * @return void
     */
    public function export();

    /**
     * Delete CMS Content
     *
     * @return void
     */
    public function delete();

}
