<?php

/*
 * Slim Auth Implementation Example
 *
 * @link      http://github.com/jeremykendall/slim-auth-impl Canonical source repo
 * @copyright Copyright (c) 2013-2015 Jeremy Kendall (http://about.me/jeremykendall)
 * @license   http://github.com/jeremykendall/slim-auth-impl/blob/master/LICENSE MIT
 */
use Zend\Permissions\Acl\Acl as ZendAcl;
/**
 * ACL for Slim Auth Implementation Example.
 */
class Acl extends ZendAcl
{
    protected $defaultPrivilege = array('GET');
    public function __construct()
    {
        // APPLICATION ROLES
        $this->addRole('guest');
        // member role "extends" guest, meaning the member role will get all of
        // the guest role permissions by default
        $this->addRole('admin');
        // APPLICATION RESOURCES
        // Application resources == Slim route patterns
        $this->addResource('/');
        $this->addResource('/about');
        $this->addResource('/blog');
        $this->addResource('/blog/{id}');
        $this->addResource('/signup');
        $this->addResource('/calendar');
        $this->addResource('/crew');
        $this->addResource('/testimonials');
        $this->addResource('/faq');
        $this->addResource('/login');
        $this->addResource('/logout');
        $this->addResource('/admin');

        // APPLICATION PERMISSIONS
        // Now we allow or deny a role's access to resources.
        // The third argument is 'privilege'. In Slim Auth privilege == HTTP method
        $this->allow('guest', '/', $this->defaultPrivilege);
        $this->allow('guest', '/login', array('GET', 'POST'));
        $this->deny('guest', '/logout');
        $this->deny('guest', '/admin');

        // This allows admin access to everything
        $this->allow('admin');
    }
}
