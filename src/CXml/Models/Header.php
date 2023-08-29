<?php

namespace CXml\Models;

use CXml\Models\Responses\ResponseInterface;

class Header
{
    private $senderIdentity;
    private $senderSharedSecret;
    private $userAgent;
    private $fromIdentity;
    private $fromDomain;
    private $toIdentity;
    private $toDomain;
    private $senderDomain;

    public function getSenderIdentity()
    {
        return $this->senderIdentity;
    }

    public function setSenderIdentity($senderIdentity): self
    {
        $this->senderIdentity = $senderIdentity;
        return $this;
    }

    public function getSenderSharedSecret()
    {
        return $this->senderSharedSecret;
    }

    public function setSenderSharedSecret($senderSharedSecret): self
    {
        $this->senderSharedSecret = $senderSharedSecret;
        return $this;
    }

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

    public function getSenderDomain()
    {
        return $this->senderDomain;
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

    public function setSenderDomain($senderDomain): self
    {
        $this->senderDomain = $senderDomain;
        return $this;
    }

    public function parse(\SimpleXMLElement $headerXml) : void
    {
        $this->senderIdentity = (string)$headerXml->xpath('Sender/Credential/Identity')[0];
        $this->senderSharedSecret = (string)$headerXml->xpath('Sender/Credential/SharedSecret')[0];
        $this->senderDomain = (string)$headerXml->xpath('Sender/Credential')[0]['domain'];

        $this->fromIdentity = (string)$headerXml->xpath('From/Credential/Identity')[0];
        $this->fromDomain = (string)$headerXml->xpath('From/Credential')[0]['domain'];

        $networkIdCredential = $headerXml->xpath("//To/Credential[@domain='networkid']");
        $this->toIdentity = (string)$networkIdCredential[0]->Identity;
        $this->toDomain = "networkid";
    }

    public function render(\SimpleXMLElement $parentNode) : void
    {
        $headerNode = $parentNode->addChild('Header');

        $this->addNode($headerNode, 'From', $this->getFromIdentity() ?? 'Unknown', $this->getFromDomain() ?? '');
        $this->addNode($headerNode, 'To', $this->getToIdentity() ?? 'Unknown', $this->getToDomain() ?? '');
        $this->addNode($headerNode, 'Sender', $this->getSenderIdentity() ?? 'Unknown', $this->getSenderDomain() ?? '')
            ->addChild('UserAgent', $this->getUserAgent() ?? 'Unknown');
    }

    private function addNode(\SimpleXMLElement $parentNode, string $nodeName, string $identity, string $domain) : \SimpleXMLElement
    {
        $node = $parentNode->addChild($nodeName);

        $credentialNode = $node->addChild('Credential');
        $credentialNode->addAttribute('domain', $domain);
        $credentialNode->addChild('Identity', $identity);

        return $node;
    }
}
