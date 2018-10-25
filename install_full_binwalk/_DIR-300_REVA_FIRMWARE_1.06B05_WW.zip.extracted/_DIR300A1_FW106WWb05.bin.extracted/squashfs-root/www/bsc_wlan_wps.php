		<div class="box">
			<h2><?=$m_title_wps?></h2>
			<table cellpadding="1" cellspacing="1" border="0" width="525">
			<tr>
				<?=$td1?><?=$m_enable?></td>
				<?=$td2?><?=$symbol?>
				<input type=checkbox name=wps_enable <?if($cfg_wps_enable =="1"){echo " checked";}?> onclick="enable_disable_wps()">
				</td>
			</tr>
			<tr>
				<?=$td1?><?=$m_current_pin?></td>
				<?=$td2?><?=$symbol?><b><?=$cfg_wps_pin?></b>
				</td>
			</tr>
			<tr>
				<?=$td1?></td>
				<?=$td2?>
				<input type=button id="bt_gen_pin" value="<?=$m_gen_pin?>" onclick="gen_pin()">
				&nbsp;
				<input type=button id="bt_reset_pin" value="<?=$m_reset_pin?>" onclick="reset_pin()">
				</td>
			</tr>
			<tr>
				<?=$td1?><?=$m_wps_status?></td>
				<?=$td2?><?=$symbol?>
					<?
						if($cfg_wps_enable =="1")	{$wps_en_dis = $m_enabled." / ";}
						else						{$wps_en_dis = $m_disabled." / ";}
						if ($cfg_wps_state=="1")	{echo $wps_en_dis.$m_configured;}
						else						{echo $wps_en_dis.$m_unconfigured;}
					?>
				</td>
			</tr>
			<tr>
				<?=$td1?><?=$m_wps_locksecurity?></td>
				<?=$td2?><?=$symbol?>
				<input type=checkbox name="wps_locksecurity" <?if($cfg_wps_locksecurity =="1"){echo " checked";}?>>
				</td>
			</tr>
			<tr>
				<?=$td1?></td>
				<?=$td2?>
				<input type=button id="bt_reset_wps" value="<?=$m_reset_wps?>" onclick="reset_wps()">
				</td>
			</tr>
			<tr>
				<?=$td1?></td>
				<?=$td2?>
				<input type=button id="bt_do_wps" value="<?=$m_wps_wizard?>" onclick="add_dev()">
				</td>
			</tr>
			</table>
		</div>
