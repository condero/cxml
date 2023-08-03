<?php

namespace CXml\Models;

use CXml\Models\Responses\ResponseInterface;

class Header
{
    private $userAgent;
    private $fromIdentity;
    private $fromDomain;
    private $toIdentity;
    private $toDomain;
    private $senderIdentity;
    private $senderDomain;
    private $senderSharedSecret;

    public function getUserAgent()
    {
        return $this->userAgent;
    }
           
    public function getFromIdentity()
    {
        return $this->fromIdentity;
    }

    public function getFromDomain()
    {
        return $this->fromDomain;
    }
    
    public function getToIdentity()
    {
        return $this->toIdentity;
    }

    public function getToDomain()
    {
        return $this->toDomain;
    }
    
    public function getSenderIdentity()
    {
        return $this->senderIdentity;
    }

    public function getSenderDomain()
    {
        return $this->senderDomain;
    }

    public function getSenderSharedSecret()
    {
        return $this->senderSharedSecret;
    }
    
    public function setUserAgent($userAgent): self
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    public function setFromIdentity($fromIdentity): self
    {
        $this->fromIdentity = $fromIdentity;
        return $this;
    }

    public function setFromDomain($fromDomain): self
    {
        $this->fromDomain = $fromDomain;
        return $this;
    }
    
    public function setToIdentity($toIdentity): self
    {
        $this->toIdentity = $toIdentity;
        return $this;
    }

    public function setToDomain($toDomain): self
    {
        $this->toDomain = $toDomain;
        return $this;
    }
    
    public function setSenderIdentity($senderIdentity): self
    {
        $this->senderIdentity = $senderIdentity;
        return $this;
    }

    public function setSenderDomain($senderDomain): self
    {
        $this->senderDomain = $senderDomain;
        return $this;
    }
    
    public function setSenderSharedSecret($senderSharedSecret): self
    {
        $this->senderSharedSecret = $senderSharedSecret;
        return $this;
    }

    public function parse(\SimpleXMLElement $headerXml) : void
    {
        $this->senderIdentity = (string)$headerXml->xpath('Sender/Credential/Identity')[0];
        $this->senderSharedSecret = (string)$headerXml->xpath('Sender/Credential/SharedSecret')[0];
    }

    public function render(\SimpleXMLElement $parentNode) : void
    {
        $headerNode = $parentNode->addChild('Header');

        $this->addNode($headerNode, 'From', $this->getFromIdentity() ?? 'Unknown', $this->getFromDomain() ?? '')
            ->addChild('UserAgent', $this->setUserAgent() ?? 'Unknown');
        $this->addNode($headerNode, 'To', $this->getToIdentity() ?? 'Unknown', $this->getToDomain() ?? '')
            ->addChild('UserAgent', $this->setUserAgent() ?? 'Unknown');
        $this->addNode($headerNode, 'Sender', $this->getSenderIdentity() ?? 'Unknown', $this->getSenderDomain() ?? '')
            ->addChild('UserAgent', $this->setUserAgent() ?? 'Unknown');
    }

    private function addNode(\SimpleXMLElement $parentNode, string $nodeName, string $identity, string $doamin) : \SimpleXMLElement
    {
        $node = $parentNode->addChild($nodeName);
        $credentialNode->addAttribute('domain', $domain);
        $credentialNode->addChild('Identity', $identity);

        return $node;
    }
}
