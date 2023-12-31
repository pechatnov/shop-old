<?

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main,
	Bitrix\Main\Localization\Loc,
	Bitrix\Main\Page\Asset;

Asset::getInstance()->addJs("/bitrix/components/bitrix/sale.order.payment.change/templates/.default/script.js");
Asset::getInstance()->addCss("/bitrix/components/bitrix/sale.order.payment.change/templates/.default/style.css");
$this->addExternalCss("/bitrix/css/main/bootstrap.css");
CJSCore::Init(array('clipboard', 'fx'));

Loc::loadMessages(__FILE__);
?>
<div class="personal_area">
	<?include($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/inc/templ/menu_lk.php');?>
	<div class="cont_lk">
		<div class="list_orders">
			<?
			if (!empty($arResult['ERRORS']['FATAL']))
			{
				foreach($arResult['ERRORS']['FATAL'] as $error)
				{
					ShowError($error);
				}
				$component = $this->__component;
				if ($arParams['AUTH_FORM_IN_TEMPLATE'] && isset($arResult['ERRORS']['FATAL'][$component::E_NOT_AUTHORIZED]))
				{
					$APPLICATION->AuthForm('', false, false, 'N', false);
				}

			}
			else
			{
				if (!empty($arResult['ERRORS']['NONFATAL']))
				{
					foreach($arResult['ERRORS']['NONFATAL'] as $error)
					{
						ShowError($error);
					}
				}
				if (!count($arResult['ORDERS']))
				{
					if ($_REQUEST["filter_history"] == 'Y')
					{
						if ($_REQUEST["show_canceled"] == 'Y')
						{
							?>
							<h3><?= Loc::getMessage('SPOL_TPL_EMPTY_CANCELED_ORDER')?></h3>
							<?
						}
						else
						{
							?>
							<h3><?= Loc::getMessage('SPOL_TPL_EMPTY_HISTORY_ORDER_LIST')?></h3>
							<?
						}
					}
					else
					{
						?>
						<h3><?= Loc::getMessage('SPOL_TPL_EMPTY_ORDER_LIST')?></h3>
						<?
					}
				}
				?>
				<?
				$nothing = !isset($_REQUEST["filter_history"]) && !isset($_REQUEST["show_all"]);
				$clearFromLink = array("filter_history","filter_status","show_all", "show_canceled");

				if ($nothing || $_REQUEST["filter_history"] == 'N')
				{
					?>
					<div class="top_links">
						<a  href="<?=$APPLICATION->GetCurPageParam("filter_history=Y", $clearFromLink, false)?>">
							<?echo Loc::getMessage("SPOL_TPL_VIEW_ORDERS_HISTORY")?>
						</a>
					</div>
					<?
				}
				if ($_REQUEST["filter_history"] == 'Y')
				{
					?>
					<div class="top_links">
						<a class="top_links" href="<?=$APPLICATION->GetCurPageParam("", $clearFromLink, false)?>">
							<?echo Loc::getMessage("SPOL_TPL_CUR_ORDERS")?>
						</a>
					</div>
					<?
					if ($_REQUEST["show_canceled"] == 'Y')
					{
						?>
						<div class="top_links">
							<a class="top_links" href="<?=$APPLICATION->GetCurPageParam("filter_history=Y", $clearFromLink, false)?>">
								<?echo Loc::getMessage("SPOL_TPL_VIEW_ORDERS_HISTORY")?>
							</a>
						</div>
						<?
					}
					else
					{
						?>
						<div class="top_links">
							<a class="top_links" href="<?=$APPLICATION->GetCurPageParam("filter_history=Y&show_canceled=Y", $clearFromLink, false)?>">
								<?echo Loc::getMessage("SPOL_TPL_VIEW_ORDERS_CANCELED")?>
							</a>
						</div>
						<?
					}
				}
				?>
				<?
				if (!count($arResult['ORDERS']))
				{
					?>
					<div class="top_links">
						<a href="<?=htmlspecialcharsbx($arParams['PATH_TO_CATALOG'])?>">
							<?=Loc::getMessage('SPOL_TPL_LINK_TO_CATALOG')?>
						</a>
					</div>
					<?
				}

				if ($_REQUEST["filter_history"] !== 'Y')
				{
					$paymentChangeData = array();
					$orderHeaderStatus = null;

					foreach ($arResult['ORDERS'] as $key => $order)
					{
						if ($orderHeaderStatus !== $order['ORDER']['STATUS_ID'] && $arResult['SORT_TYPE'] == 'STATUS')
						{
							$orderHeaderStatus = $order['ORDER']['STATUS_ID'];

							?>
							<h2 style="text-align: left">
								<?= Loc::getMessage('SPOL_TPL_ORDER_IN_STATUSES') ?> &laquo;<?=htmlspecialcharsbx($arResult['INFO']['STATUS'][$orderHeaderStatus]['NAME'])?>&raquo;
							</h2>
							<?
						}
						?>
					<div class="block_lk">
						<div class="title">
							<?=Loc::getMessage('SPOL_TPL_ORDER')?>
							<?=Loc::getMessage('SPOL_TPL_NUMBER_SIGN').$order['ORDER']['ACCOUNT_NUMBER']?>
							<?=Loc::getMessage('SPOL_TPL_FROM_DATE')?>
							<?=$order['ORDER']['DATE_INSERT']->format($arParams['ACTIVE_DATE_FORMAT'])?>,
							<?=count($order['BASKET_ITEMS']);?>
							<?
							$count = count($order['BASKET_ITEMS']) % 10;
							if ($count == '1')
							{
								echo Loc::getMessage('SPOL_TPL_GOOD');
							}
							elseif ($count >= '2' && $count <= '4')
							{
								echo Loc::getMessage('SPOL_TPL_TWO_GOODS');
							}
							else
							{
								echo Loc::getMessage('SPOL_TPL_GOODS');
							}
							?>
							<?=Loc::getMessage('SPOL_TPL_SUMOF')?>
							<?=$order['ORDER']['FORMATED_PRICE']?>
						</div>
						<div class="item">
							<div class="title"><?=Loc::getMessage('SPOL_TPL_PAYMENT')?></div>
							<?
							$showDelimeter = false;
							foreach ($order['PAYMENT'] as $payment)
							{
								?>
								<p style="display: none">
								<?
								if ($order['ORDER']['LOCK_CHANGE_PAYSYSTEM'] !== 'Y')
								{
									$paymentChangeData[$payment['ACCOUNT_NUMBER']] = array(
										"order" => htmlspecialcharsbx($order['ORDER']['ACCOUNT_NUMBER']),
										"payment" => htmlspecialcharsbx($payment['ACCOUNT_NUMBER']),
										"allow_inner" => $arParams['ALLOW_INNER'],
										"refresh_prices" => $arParams['REFRESH_PRICES'],
										"path_to_payment" => $arParams['PATH_TO_PAYMENT'],
										"only_inner_full" => $arParams['ONLY_INNER_FULL']
									);
								}
								?>
								<?
								if (!$showDelimeter)
								{
									$showDelimeter = true;
								}
								?>

								<?
								$paymentSubTitle = Loc::getMessage('SPOL_TPL_BILL')." ".Loc::getMessage('SPOL_TPL_NUMBER_SIGN').htmlspecialcharsbx($payment['ACCOUNT_NUMBER']);
								if(isset($payment['DATE_BILL']))
								{
									$paymentSubTitle .= " ".Loc::getMessage('SPOL_TPL_FROM_DATE')." ".$payment['DATE_BILL']->format($arParams['ACTIVE_DATE_FORMAT']);
								}
								$paymentSubTitle .=",";
								echo $paymentSubTitle;
								?>
								<?=$payment['PAY_SYSTEM_NAME']?>
								<?
								if ($payment['PAID'] === 'Y')
								{
									?>
									<span><?=Loc::getMessage('SPOL_TPL_PAID')?></span>
									<?
								}
								elseif ($order['ORDER']['IS_ALLOW_PAY'] == 'N')
								{
									?>
									<span><?=Loc::getMessage('SPOL_TPL_RESTRICTED_PAID')?></span>
									<?
								}
								else
								{
									?>
									<span><?=Loc::getMessage('SPOL_TPL_NOTPAID')?></span>
									<?
								}
								?>
								</p>

								<p>
								<?=Loc::getMessage('SPOL_TPL_SUM_TO_PAID')?>: <?=$payment['FORMATED_SUM']?>
								<?
								if (!empty($payment['CHECK_DATA']))
								{
									$listCheckLinks = "";
									foreach ($payment['CHECK_DATA'] as $checkInfo)
									{
										$title = Loc::getMessage('SPOL_CHECK_NUM', array('#CHECK_NUMBER#' => $checkInfo['ID']))." - ". htmlspecialcharsbx($checkInfo['TYPE_NAME']);
										if (strlen($checkInfo['LINK']))
										{
											$link = $checkInfo['LINK'];
											$listCheckLinks .= "<div><a href='$link' target='_blank'>$title</a></div>";
										}
									}
									if (strlen($listCheckLinks) > 0)
									{
										?>
										<div class="sale-order-list-payment-check">
											<div class="sale-order-list-payment-check-left"><?= Loc::getMessage('SPOL_CHECK_TITLE')?>:</div>
											<div class="sale-order-list-payment-check-left">
												<?=$listCheckLinks?>
											</div>
										</div>
										<?
									}
								}
								if ($order['ORDER']['IS_ALLOW_PAY'] == 'N' && $payment['PAID'] !== 'Y')
								{
									?>
									<span><?=Loc::getMessage('SOPL_TPL_RESTRICTED_PAID_MESSAGE')?></span>
									<?
								}
								?>
								</p>
								<?
							}
							if (!empty($order['SHIPMENT']))
							{
								?>
								<div class="title"><?=Loc::getMessage('SPOL_TPL_DELIVERY')?></div>
								<?
							}
							$showDelimeter = false;
							foreach ($order['SHIPMENT'] as $shipment)
							{
								if (empty($shipment))
								{
									continue;
								}
								?>
								<?
								if (!$showDelimeter)
								{
									$showDelimeter = true;
								}
								?>
								<p style="display: none">
									<?=Loc::getMessage('SPOL_TPL_LOAD')?>
									<?
									$shipmentSubTitle = Loc::getMessage('SPOL_TPL_NUMBER_SIGN').htmlspecialcharsbx($shipment['ACCOUNT_NUMBER']);
									if ($shipment['DATE_DEDUCTED'])
									{
										$shipmentSubTitle .= " ".Loc::getMessage('SPOL_TPL_FROM_DATE')." ".$shipment['DATE_DEDUCTED']->format($arParams['ACTIVE_DATE_FORMAT']);
									}

									if ($shipment['FORMATED_DELIVERY_PRICE'])
									{
										$shipmentSubTitle .= ", ".Loc::getMessage('SPOL_TPL_DELIVERY_COST')." ".$shipment['FORMATED_DELIVERY_PRICE'];
									}
									echo $shipmentSubTitle;
									?>
									<?
									if ($shipment['DEDUCTED'] == 'Y')
									{
										?>
										<?=Loc::getMessage('SPOL_TPL_LOADED');?>
										<?
									}
									else
									{
										?>
										<span><?=Loc::getMessage('SPOL_TPL_NOTLOADED');?></span>
										<?
									}
									?>
								</p>

								<p style="display: none">
									<?=Loc::getMessage('SPOL_ORDER_SHIPMENT_STATUS');?>: <?=htmlspecialcharsbx($shipment['DELIVERY_STATUS_NAME'])?>
								</p>

								<?
								if (!empty($shipment['DELIVERY_ID']))
								{
									?>
									<p>
										<?=Loc::getMessage('SPOL_TPL_DELIVERY_SERVICE')?>: <?=$arResult['INFO']['DELIVERY'][$shipment['DELIVERY_ID']]['NAME']?>
									</p>
									<?
								}

								if (!empty($shipment['TRACKING_NUMBER']))
								{
									?>
									<p>
										<?=Loc::getMessage('SPOL_TPL_POSTID')?>: <?=htmlspecialcharsbx($shipment['TRACKING_NUMBER'])?>
									</p>
									<?
								}
								?>
								<?
								if (strlen($shipment['TRACKING_URL']) > 0)
								{
									?>
									<a target="_blank" href="<?=$shipment['TRACKING_URL']?>">
										<?=Loc::getMessage('SPOL_TPL_CHECK_POSTID')?>
									</a>
									<?
								}
								?>
								<?
							}
							?>
						</div>
						<div class="more_detail">
							<div class="more">
								<a href="<?=htmlspecialcharsbx($order["ORDER"]["URL_TO_DETAIL"])?>"><?=Loc::getMessage('SPOL_TPL_MORE_ON_ORDER')?></a>
							</div>
							<div class="repeat_cancel">
								<a href="<?=htmlspecialcharsbx($order["ORDER"]["URL_TO_COPY"])?>"><?=Loc::getMessage('SPOL_TPL_REPEAT_ORDER')?></a>
								<a href="<?=htmlspecialcharsbx($order["ORDER"]["URL_TO_CANCEL"])?>"><?=Loc::getMessage('SPOL_TPL_CANCEL_ORDER')?></a>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
						<?
					}
				}
				else
				{

					$orderHeaderStatus = null;
					if ($_REQUEST["show_canceled"] === 'Y' && count($arResult['ORDERS']))
					{
						?>
						<h2 style="text-align: left">
							<?= Loc::getMessage('SPOL_TPL_ORDERS_CANCELED_HEADER') ?>
						</h2>
						<?
					}

					foreach ($arResult['ORDERS'] as $key => $order)
					{
						if ($orderHeaderStatus !== $order['ORDER']['STATUS_ID'] && $_REQUEST["show_canceled"] !== 'Y')
						{
							$orderHeaderStatus = $order['ORDER']['STATUS_ID'];
							?>
							<h2 style="text-align: left">
								<?= Loc::getMessage('SPOL_TPL_ORDER_IN_STATUSES') ?> &laquo;<?=htmlspecialcharsbx($arResult['INFO']['STATUS'][$orderHeaderStatus]['NAME'])?>&raquo;
							</h2>
							<?
						}
						?>
						<div class="block_lk">
							<div class="title">
								<?= Loc::getMessage('SPOL_TPL_ORDER') ?>
								<?= Loc::getMessage('SPOL_TPL_NUMBER_SIGN') ?>
								<?= htmlspecialcharsbx($order['ORDER']['ACCOUNT_NUMBER'])?>
								<?= Loc::getMessage('SPOL_TPL_FROM_DATE') ?>
								<?= $order['ORDER']['DATE_INSERT'] ?>,
								<?= count($order['BASKET_ITEMS']); ?>
								<?
								$count = substr(count($order['BASKET_ITEMS']), -1);
								if ($count == '1')
								{
									echo Loc::getMessage('SPOL_TPL_GOOD');
								}
								elseif ($count >= '2' || $count <= '4')
								{
									echo Loc::getMessage('SPOL_TPL_TWO_GOODS');
								}
								else
								{
									echo Loc::getMessage('SPOL_TPL_GOODS');
								}
								?>
								<?= Loc::getMessage('SPOL_TPL_SUMOF') ?>
								<?= $order['ORDER']['FORMATED_PRICE'] ?>
							</div>
							<div class="item">
								<?
								if ($_REQUEST["show_canceled"] !== 'Y')
								{
									?>
									<?= Loc::getMessage('SPOL_TPL_ORDER_FINISHED')?>
									<?
								}
								else
								{
									?>
									<?= Loc::getMessage('SPOL_TPL_ORDER_CANCELED')?>
									<?
								}
								?>
								<?= $order['ORDER']['DATE_STATUS_FORMATED'] ?>
							</div>
							<div class="more_detail">
								<div class="more">
									<a href="<?=htmlspecialcharsbx($order["ORDER"]["URL_TO_DETAIL"])?>"><?=Loc::getMessage('SPOL_TPL_MORE_ON_ORDER')?></a>
								</div>
								<div class="repeat_cancel">
									<a href="<?=htmlspecialcharsbx($order["ORDER"]["URL_TO_COPY"])?>"><?=Loc::getMessage('SPOL_TPL_REPEAT_ORDER')?></a>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
						<?
					}
				}

				echo $arResult["NAV_STRING"];

				if ($_REQUEST["filter_history"] !== 'Y')
				{
					$javascriptParams = array(
						"url" => CUtil::JSEscape($this->__component->GetPath().'/ajax.php'),
						"templateFolder" => CUtil::JSEscape($templateFolder),
						"templateName" => $this->__component->GetTemplateName(),
						"paymentList" => $paymentChangeData
					);
					$javascriptParams = CUtil::PhpToJSObject($javascriptParams);
					?>
					<script>
						BX.Sale.PersonalOrderComponent.PersonalOrderList.init(<?=$javascriptParams?>);
					</script>
					<?
				}
			}
			?>
		</div>
	</div>
	<div class="clearfix"></div>
</div>
