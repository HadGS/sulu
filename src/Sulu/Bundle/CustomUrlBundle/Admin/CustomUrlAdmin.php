<?php

/*
 * This file is part of Sulu.
 *
 * (c) Sulu GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Bundle\CustomUrlBundle\Admin;

use Sulu\Bundle\AdminBundle\Admin\Admin;
use Sulu\Bundle\PageBundle\Admin\PageAdmin;
use Sulu\Component\Security\Authorization\PermissionTypes;
use Sulu\Component\Webspace\Manager\WebspaceManagerInterface;
use Sulu\Component\Webspace\Webspace;

/**
 * Includes custom-url-bundle into sulu admin.
 */
class CustomUrlAdmin extends Admin
{
    /**
     * Returns security context for custom-urls in given webspace.
     *
     * @param string $webspaceKey
     *
     * @return string
     */
    public static function getCustomUrlSecurityContext($webspaceKey)
    {
        return sprintf('%s%s.%s', PageAdmin::SECURITY_CONTEXT_PREFIX, $webspaceKey, 'custom-urls');
    }

    /**
     * @var WebspaceManagerInterface
     */
    private $webspaceManager;

    public function __construct(WebspaceManagerInterface $webspaceManager)
    {
        $this->webspaceManager = $webspaceManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getSecurityContexts()
    {
        $webspaceContexts = [];
        /* @var Webspace $webspace */
        foreach ($this->webspaceManager->getWebspaceCollection() as $webspace) {
            $securityContextKey = self::getCustomUrlSecurityContext($webspace->getKey());
            $webspaceContexts[$securityContextKey] = $this->getSecurityContextPermissions();
        }

        return [
             'Sulu' => [
                 'Webspaces' => $webspaceContexts,
             ],
         ];
    }

    public function getSecurityContextsWithPlaceholder()
    {
        return [
            'Sulu' => [
                'Webspaces' => [
                    self::getCustomUrlSecurityContext('#webspace#') => $this->getSecurityContextPermissions(),
                ],
            ],
        ];
    }

    private function getSecurityContextPermissions()
    {
        return [
            PermissionTypes::VIEW,
            PermissionTypes::ADD,
            PermissionTypes::EDIT,
            PermissionTypes::DELETE,
        ];
    }
}
