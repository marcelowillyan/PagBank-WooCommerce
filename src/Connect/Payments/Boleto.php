<?php

namespace RM_PagBank\Connect\Payments;

use RM_PagBank\Helpers\Params;
use RM_PagBank\Object\Address;
use RM_PagBank\Object\Amount;
use RM_PagBank\Object\Boleto as BoletoObj;
use RM_PagBank\Object\Charge;
use RM_PagBank\Object\Holder;
use RM_PagBank\Object\InstructionLines;
use RM_PagBank\Object\PaymentMethod;
use WC_Data_Exception;
use WC_Order;

class Boleto extends Common
{
    public string $code = 'boleto';


	/**
	 * Prepare order params for Boleto
	 *
	 * @return array
	 * @throws WC_Data_Exception
	 */
    public function prepare(): array
    {
        $return = $this->getDefaultParameters();

        $charge = new Charge();
        $charge->setReferenceId($this->order->get_id());

        $amount = new Amount();
        $orderTotal = $this->order->get_total();

        if ($discountConfig = Params::getConfig('boleto_discount', 0)){
            $discount = floatval(Params::getDiscountValue($discountConfig, $orderTotal));
            $this->order->set_discount_total(floatval($this->order->get_discount_total()) + $discount);
            $this->order->set_total($this->order->get_total() - $discount);
            $orderTotal = $orderTotal - $discount;
        }

        $amount->setValue(Params::convertToCents($orderTotal));
        $charge->setAmount($amount);

        $paymentMethod = new PaymentMethod();
        $paymentMethod->setType('BOLETO');
        $boleto = new BoletoObj();
        $boleto->setDueDate(date('Y-m-d', strtotime('+' . Params::getConfig('boleto_expiry_days', 7) . 'day')));
        $instruction_lines = new InstructionLines();
        $instruction_lines->setLine1(Params::getConfig('boleto_line_1', 'Não aceitar após vencimento'));
        $instruction_lines->setLine2(Params::getConfig('boleto_line_2', 'Obrigado por sua compra.'));
        $boleto->setInstructionLines($instruction_lines);
        $holder = new Holder();
        $holder->setName($this->order->get_billing_first_name() . ' ' . $this->order->get_billing_last_name());
        $holder->setTaxId(Params::removeNonNumeric($this->order->get_meta('_billing_cpf')));
        $holder->setEmail($this->order->get_billing_email());

        $holderAddress = new Address();
        $holderAddress->setCountry('BRA');
        $holderAddress->setCity($this->order->get_billing_city());
        $holderAddress->setPostalCode(Params::removeNonNumeric($this->order->get_billing_postcode()));
        $holderAddress->setLocality($this->order->get_meta('_billing_neighborhood'));
        $holderAddress->setStreet($this->order->get_billing_address_1());
        $holderAddress->setNumber($this->order->get_meta('_billing_number'));
        $holderAddress->setRegionCode($this->order->get_billing_state());

        if($this->order->get_meta('_billing_complement'))
            $holderAddress->setComplement($this->order->get_meta('_billing_complement'));
        $holder->setAddress($holderAddress);
        $boleto->setHolder($holder);
        $paymentMethod->setType('BOLETO');
        $paymentMethod->setBoleto($boleto);
        $charge->setPaymentMethod($paymentMethod);

        $charges = ['charges' => [$charge]];

        return array_merge($return, $charges);

    }

	/**
	 * Set some variables and requires the template with boleto instructions for the success page
	 * @param $order_id
	 *
	 * @return void
	 * @noinspection SpellCheckingInspection
	 */
	public function getThankyouInstructions($order_id){
        $boleto_barcode = get_post_meta($order_id, 'pagbank_boleto_barcode', true);
        $boleto_barcode_formatted = get_post_meta($order_id, 'pagbank_boleto_barcode_formatted', true);
        $boleto_due_date = get_post_meta($order_id, 'pagbank_boleto_due_date', true);
        $boleto_pdf = get_post_meta($order_id, 'pagbank_boleto_pdf', true);
        $boleto_png = get_post_meta($order_id, 'pagbank_boleto_png', true);
        require_once dirname(__FILE__) . '/../../templates/boleto-instructions.php';
    }

}
