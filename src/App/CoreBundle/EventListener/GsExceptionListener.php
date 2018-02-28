<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\CoreBundle\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Firewall\ExceptionListener as BaseExceptionListener;

class GsExceptionListener extends BaseExceptionListener {

    /**
     * setTargetPath
     * @param Request $request
     */
    protected function setTargetPath(Request $request) {

        // Do not save target path for XHR and non-GET requests
        // You can add any more logic here you want
        if ($request->isXmlHttpRequest() || 'GET' !== $request->getMethod()) {
            return;
        }

        $request->getSession()->set('_security.target_path', $request->getUri());
    }

}
