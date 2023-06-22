<?php declare(strict_types=1);
/**
 * Part of Teinte https://github.com/oeuvres/teinte_php
 * Copyright (c) 2022 frederic.glorieux@fictif.org
 * Copyright (c) 2013 frederic.glorieux@fictif.org & LABEX OBVIL
 * Copyright (c) 2012 frederic.glorieux@fictif.org
 * BSD-3-Clause https://opensource.org/licenses/BSD-3-Clause
 */

namespace Oeuvres\Teinte\Format;

use DOMDocument, ErrorException;
use Oeuvres\Kit\{Filesys, Log, Parse, Xt};
use Oeuvres\Xsl\{Xpack};


/**
 * A File format accepting xml manipulation
 */
trait Teiable
{
    /** Store XML as a string, maybe reused */
    protected ?string $tei = null;
    /** DOM Document to process */
    protected ?DOMDocument $teiDoc = null;
    
    /**
     * Return xml state (maybe transformed and diverges from original)
     */
    function tei(): string
    {
        if ($this->tei === null) {
            $this->teiDoc();
            $this->tei = $this->teiDoc->saveXML();
        }
        return $this->tei;
    }

    /**
     * Return dom state
     */
    function teiDoc(): DOMDocument
    {
        if ($this->teiDoc === null || !$this->teiDoc->documentElement) {
            $this->teiMake();
        }
        if ($this->teiDoc !== null && $this->teiDoc->documentElement) {
            return $this->teiDoc;
        }
        // we may have a tei string here ?
        if ($this->tei === null) {
            throw new ErrorException("No tei or teiDoc produced by teiMake()");
        }
        $this->teiDoc = Xt::loadXml($this->tei);
        return $this->teiDoc;
    }

    /**
     * Reset values before loading
     */
    function teiReset(): void
    {
        $this->tei = null;
        $this->teiDoc = null;
        // if file
        if (property_exists($this, 'file')) $this->file = null;
        if (property_exists($this, 'filename')) $this->filename = null;
        if (property_exists($this, 'filemtime')) $this->filemtime = null;
        if (property_exists($this, 'filesize')) $this->filesize = null;
        if (property_exists($this, 'contents')) $this->contents = null;
    }


    /**
     * Make tei, usually on dom
     */
    abstract public function teiMake(?array $pars = null): void;
}