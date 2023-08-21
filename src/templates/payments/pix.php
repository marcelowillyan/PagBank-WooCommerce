<?php
/** @var \RM_PagBank\Connect\Gateway $this */

use RM_PagBank\Connect;

$expiry = (int)$this->get_option('pix_expiry_minutes');
switch ($expiry){
    case $expiry <= 60:
        $text = sprintf(__('Você terá %d minutos para pagar com seu código PIX.', Connect::DOMAIN), $expiry);
        break;
    case 1440:
        $text = __('Você terá 24 horas para pagar com seu código PIX.', Connect::DOMAIN);
        break;
    case $expiry % 1440 === 0:
        $expiry = $expiry / 1440;
        $text = sprintf(__('Você terá %d dias para usar seu código PIX.', Connect::DOMAIN), $expiry);
        break;
    default:
        $text = '';
        break;
}
?>
<p class="instructions">
    <?php echo $this->get_option('pix_instructions'); ?>
    <br/>
    <?php echo $text; ?>
</p>