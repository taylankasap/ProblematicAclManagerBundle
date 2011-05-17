<?php

namespace Problematic\AclManagerBundle\Acl;

use Symfony\Component\Security\Acl\Dbal\MutableAclProvider;
use Symfony\Component\Security\Acl\Domain\Acl;
use Symfony\Component\Security\Acl\Model\SecurityIdentityInterface;
use Symfony\Component\Security\Core\SecurityContext;

class AclManager extends AbstractAclManager {
    protected $permissionContextCollection = array();
    
    public function __construct(SecurityContext $securityContext, MutableAclProvider $aclProvider) {
        parent::__construct($securityContext, $aclProvider);
    }
    
    public function processPermissions($reset = false) {
        foreach ($this->permissionContextCollection as $permissionContext) {
            $this->applyPermission($permissionContext);
        }
        $this->updateAcl();
        
        if ($reset) {
            $this->permissionContextCollection = array();
        }
        
        return $this;
    }
    
    public function createPermissionContext($type, $securityIdentity, $mask, $granting = true) {
        return $this->doCreatePermissionContext($type, $securityIdentity, $mask, $granting);
    }
    public function addPermissionContext(PermissionContextInterface $permissionContext, $key = null) {
        if (null === $key) {
            $this->permissionContextCollection[] = $permissionContext;
        } else {
            $this->permissionContextCollection[$key] = $permissionContext;
        }
    }
    
    public function createSecurityIdentity($identity) {
        return $this->doCreateSecurityIdentity($identity);
    }
    
    public function loadAcl($entity) {
        if (null === $entity || !is_object($entity)) {
            throw new Exception("Provide a valid entity context before trying to load an ACL");
        }
        $this->acl = $this->doLoadAcl($entity);
        
        return $this;
    }
    
    public function updateAcl() {
        $this->doUpdateAcl();
        
        return $this;
    }
    
    public function applyPermission(PermissionContextInterface $permissionContext) {
        $this->doApplyPermission($permissionContext);
        
        return $this;
    }
    
    public function installDefaults() {
        $this->doInstallDefaults();
        
        return $this;
    }
}

?>
