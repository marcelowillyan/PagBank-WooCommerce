<?php

namespace RM_PagSeguro\Object;

class PaymentMethod implements \JsonSerializable
{
    private string $type;
    private int $installments;
    private bool $capture;
    private string $soft_descriptior;
    private Card $card;
    private Boleto $boleto;

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getInstallments(): int
    {
        return $this->installments;
    }

    /**
     * @param int $installments
     */
    public function setInstallments(int $installments): void
    {
        $this->installments = $installments;
    }

    /**
     * @return bool
     */
    public function isCapture(): bool
    {
        return $this->capture;
    }

    /**
     * @param bool $capture
     */
    public function setCapture(bool $capture): void
    {
        $this->capture = $capture;
    }

    /**
     * @return string
     */
    public function getSoftDescriptior(): string
    {
        return $this->soft_descriptior;
    }

    /**
     * @param string $soft_descriptior
     */
    public function setSoftDescriptior(string $soft_descriptior): void
    {
        $this->soft_descriptior = $soft_descriptior;
    }

    /**
     * @return Card
     */
    public function getCard(): Card
    {
        return $this->card;
    }

    /**
     * @param Card $card
     */
    public function setCard(Card $card): void
    {
        $this->card = $card;
    }
    
    /**
     * @return Boleto
     */
    public function getBoleto(): Boleto
    {
        return $this->boleto;
    }
    
    /**
     * @param Boleto $boleto
     */
    public function setBoleto(Boleto $boleto): void
    {
        $this->boleto = $boleto;
    }
    
}