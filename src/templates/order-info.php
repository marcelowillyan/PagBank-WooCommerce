<?php
/** @var WC_ORDER $order */

use RM_PagBank\Helpers\Functions;

$charge_id = $order->get_meta('pagbank_charge_id');
?>
<p class="form-field form-field-wide">
    <img src="<?php echo plugins_url('public/images/pagbank.svg', WC_PAGSEGURO_CONNECT_PLUGIN_FILE)?>" style="width: 100px; height: auto; margin-right: 10px; float: left;" alt="PagBank Logo"/>
	<?php if($order->get_meta('pagbank_is_sandbox') == 1):?>
		<p class="sandbox">Ambiente de Testes</p>
	<?php endif;?>

    <?php if($order->get_meta('pagbank_payment_method') === 'boleto'):?>
        <p class="form-field form-field-wide ps-pagbank-info">
                <span class="dashicons dashicons-download small-text"></span><a href="<?php echo $order->get_meta('pagbank_boleto_pdf')?>" title="Baixar Boleto em PDF">Baixar Boleto</a>
                <span class="dashicons dashicons-format-image small-text"></span><a href="<?php echo $order->get_meta('pagbank_boleto_png')?>" title="Ver imagem do boleto">Ver Boleto</a>
        </p>
    <?php endif;?>

    <?php if($order->get_meta('pagbank_payment_method') === 'pix'):?>
        <p class="form-field form-field-wide ps-pagbank-info">
            <a href="<?php echo $order->get_meta('pagbank_pix_qrcode')?>" title="Segure Ctrl ou Cmd para abrir a imagem em outra aba.">Ver QrCode Pix</a><span class="dashicons dashicons-external"></span>
        </p>
    <?php endif;?>

	<?php if($order->get_meta('pagbank_payment_method') === 'credit_card'):?>
		<p class="form-field form-field-wide ps-pagbank-info">
			<?php if($order->get_meta('pagbank_card_installments')):?>
				<?php echo __('Cartão de Crédito em', \RM_PagBank\Connect::DOMAIN);?> <?php echo $order->get_meta('pagbank_card_installments');?>x
			<?php endif;?>
			<?php if($order->get_meta('_pagbank_card_brand')):
				$brand_url = Functions::get_cc_flag_url($order->get_meta('_pagbank_card_brand'));
				$brand = mb_strtoupper($order->get_meta('_pagbank_card_brand')) . ' - ';
				if ($brand_url) {
					$brand = '<img src="' . $brand_url . '" style="width: 30px; height: auto; margin-right: 10px; float: left;" alt="' . $brand . '"/>';
				}
				?>
				<br/><?php echo esc_attr($order->get_meta('_pagbank_card_first_digits') . 'xx xxxx' . $order->get_meta('_pagbank_card_last_digits')) . $brand;?>
				<br/>Titular: <?php echo esc_attr($order->get_meta('_pagbank_card_holder_name'));?>
			<?php endif;?>
		</p>
	<?php endif;?>

	<?php if($charge_id):
		$transaction = str_replace('CHAR_', '', $charge_id);
		$link = 'https://minhaconta.pagseguro.uol.com.br/transacao/detalhes/' . $transaction;
		if ($order->get_meta('pagbank_is_sandbox') == 1) {
			$link = 'javascript:alert(\'Pedidos feitos em ambiente de testes não estão disponíveis no painel do PagBank.\');';
		}
		?>
		<p class="form-field form-field-wide ps-pagbank-info">
			<a href="<?php echo $link?>" title="Segure Ctrl ou Cmd para abrir em outra aba.">Ver no PagBank</a><span class="dashicons dashicons-external"></span>
		</p>
	<?php endif;?>
</p>

