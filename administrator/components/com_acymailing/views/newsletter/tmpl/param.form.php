<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.9.0
 * @author	acyba.com
 * @copyright	(C) 2009-2015 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><?php
if(acymailing_isAllowed($this->config->get('acl_newsletters_lists','all')) || acymailing_isAllowed($this->config->get('acl_newsletters_attachments','all')) || acymailing_isAllowed($this->config->get('acl_newsletters_sender_informations','all')) || acymailing_isAllowed($this->config->get('acl_newsletters_meta_data','all'))){ ?>
	 <div id="newsletterparams">

	<?php echo $this->tabs->startPane( 'mail_tab');

		if(!acymailing_isAllowed($this->config->get('acl_newsletters_lists','all')) || $this->type == 'joomlanotification'){
			$doc = JFactory::getDocument();
			$doc->addStyleDeclaration(" .mail_receivers_acl{display:none;} ");
			echo '<div class="mail_receivers_acl">';
		} else{
			echo $this->tabs->startPanel(JText::_( 'LISTS' ), 'mail_receivers');
		} ?>
		<br style="font-size:1px"/>
		<?php if(empty($this->lists)){
				echo JText::_('LIST_CREATE');
			}else{
				echo JText::_('LIST_RECEIVERS');
				if(count($this->lists) > 10){
					?>
					<script language="javascript" type="text/javascript">
					<!--
						function acymailing_searchAList(){
							var filter = document.getElementById("acymailing_searchList").value.toLowerCase();
							for(i=0 ; i<<?php echo count($this->lists); ?> ; i++){
								itemName = document.getElementById("listName_"+i).innerHTML.toLowerCase();
								itemId = document.getElementById("listId_"+i).innerHTML;
								if(document.getElementById(itemId+"listmail1").checked || itemName.indexOf(filter)>-1){
									document.getElementById("acylist_"+i).style.display = "table-row";
								}else{
									document.getElementById("acylist_"+i).style.display = "none";
								}
							}
						}
					//-->
					</script>
					<?php
					echo '<div style="margin-bottom:10px;"><input onkeyup="acymailing_searchAList();" type="text" style="width: 200px;max-width:100%;margin-bottom:5px;" placeholder="'.JText::_('ACY_SEARCH').'" id="acymailing_searchList"></div>';
				}
		?>
		<table id="receiverstable" class="adminlist table table-striped table-hover" cellpadding="1" width="100%">
			<thead>
				<tr>
					<th class="title">
						<?php echo JText::_('LIST_NAME'); ?>
					</th>
					<th class="title">
						<?php echo JText::_('LIST_RECEIVE'); ?>
					</th>
				</tr>
			</thead>
			<tbody>
		<?php
				$k = 0;
				$filter_list = JRequest::getInt( 'filter_list');
				if(empty($filter_list)) $filter_list=JRequest::getInt('listid');
				$i = 0;
				$selectedLists = explode(',',JRequest::getString('listids'));

				$orderedList = array();
				$listsPerCategory = array();
				$languages = array();
				foreach($this->lists as $row){
					$orderedList[$row->category][$row->listid] = $row;
					$listsPerCategory[$row->category][$row->listid] = $row->listid;
					if(count($this->lists) < 4) continue;

					$languages['all'][$row->listid] = $row->listid;
					if($row->languages == 'all') continue;
					$lang = explode(',',trim($row->languages,','));
					foreach($lang as $oneLang){
						$languages[strtolower($oneLang)][$row->listid] = $row->listid;
					}
				}
				ksort($orderedList);
				$allCats = array_keys($orderedList);
				$this->lists = array();
				foreach($orderedList as $oneCategory){
					$this->lists = array_merge($this->lists, $oneCategory);
				}

				foreach($this->lists as $row){
					if(empty($row->category)) $row->category = JText::_('ACY_NO_CATEGORY');
					if(count($allCats) > 1 && (empty($currentCatgeory) || $row->category != $currentCatgeory)){
						$currentCatgeory = $row->category; ?>
						<tr class="<?php echo "row$k"; ?>">
							<td colspan="2">
								<a href="#" onclick="checkCats('<?php echo htmlspecialchars(str_replace("'", "\'", $row->category == JText::_('ACY_NO_CATEGORY') ? -1 : $row->category), ENT_QUOTES, "UTF-8"); ?>');"><strong><?php echo htmlspecialchars($row->category, ENT_QUOTES, "UTF-8"); ?></strong></a>
							</td>
						</tr>
				<?php }

				$checked = (bool) ($row->mailid || (empty($row->mailid) && empty($this->mail->mailid) && $filter_list == $row->listid) || (empty($this->mail->mailid) && count($this->lists) == 1) || (in_array($row->listid,$selectedLists)));
				$classList = $checked? 'acy_list_checked' : 'acy_list_unchecked';
		?>
				<tr id="acylist_<?php echo $i; ?>" class="<?php echo "row$k $classList"; ?>">
					<td style="display:none;" id="listId_<?php echo $i; ?>"><?php echo $row->listid;?></td>
					<td style="display:none;" id="listName_<?php echo $i; ?>"><?php echo $row->name;?></td>
					<td>
						<?php
							echo '<div class="roundsubscrib rounddisp" style="background-color:'.$row->color.'"></div>';
							$text = '<b>'.JText::_('ACY_ID').' : </b>'.$row->listid;
							$text .= '<br />'.$row->description;
							echo acymailing_tooltip($text, $row->name, 'tooltip.png', $row->name);
						?>
					</td>
					<td align="center" nowrap="nowrap" style="text-align:center">
						<?php echo JHTML::_('acyselect.booleanlist', "data[listmail][".$row->listid."]" , '',$checked,JText::_('JOOMEXT_YES'),JText::_('JOOMEXT_NO'),$row->listid.'listmail'); ?>
					</td>
				</tr>
		<?php
						$k = 1-$k;
						$i++;
					}
				if(count($this->lists)>3){
			?>
			<tr><td/><td align="center" nowrap="nowrap" style="text-align:center">
						<script language="javascript" type="text/javascript">
						<!--
							var selectedLists = new Array();

							<?php
							foreach($languages as $val => $listids){
								echo "selectedLists['$val'] = new Array('".implode("','",$listids)."'); ";
							}
							?>

							function updateStatus(selection){
								for(var i=0; i < selectedLists['all'].length; i++){
									if(searchParent(window.document.getElementById(selectedLists['all'][i]+'listmail0'), 'tr').style.display == 'none') continue;
									<?php if(ACYMAILING_J30) echo 'jQuery("input[id="+selectedLists["all"][i]+"listmail0]").next().click();'; ?>
									window.document.getElementById(selectedLists['all'][i]+'listmail0').checked = true;
								}
								if(!selectedLists[selection]) return;
								for(var i=0; i < selectedLists[selection].length; i++){
									if(searchParent(window.document.getElementById(selectedLists[selection][i]+'listmail0'), 'tr').style.display == 'none') continue;
									<?php if(ACYMAILING_J30) echo 'jQuery("input[id="+selectedLists[selection][i]+"listmail1]").next().click();'; ?>
									window.document.getElementById(selectedLists[selection][i]+'listmail1').checked = true;
								}
							}
						-->
						</script>
						<?php
						$selectList = array();
						$selectList[] = JHTML::_('select.option', 'none',JText::_('ACY_NONE'));
						foreach($languages as $oneLang => $values){
							if($oneLang == 'all') continue;
							$selectList[] = JHTML::_('select.option', $oneLang,ucfirst($oneLang));
						}
						$selectList[] = JHTML::_('select.option', 'all',JText::_('ACY_ALL'));
						echo JHTML::_('acyselect.radiolist', $selectList, "selectlists" , 'onclick="updateStatus(this.value);"', 'value', 'text');
						?>
					</td></tr>
			<?php } ?>
			</tbody>
		</table>

		<script language="javascript" type="text/javascript">
		<!--
			function searchParent(elem, tag){
				tag = tag.toUpperCase();
				do {
					if (elem.nodeName === tag) {
						return elem;
					}
				} while (elem = elem.parentNode);
				return null;
			}

			var listsCats = new Array();

			<?php
			foreach($listsPerCategory as $val => $listids){
				if(empty($val)) $val = '-1';
				echo "listsCats['".str_replace("'", "\'", $val)."'] = new Array('".implode("','",$listids)."'); ";
			}
			?>
			function checkCats(selection){
				if(!listsCats[selection]) return;
				var unselect = true;
				for(var i=0; i < listsCats[selection].length; i++){
					if(searchParent(window.document.getElementById(listsCats[selection][i]+'listmail0'), 'tr').style.display == 'none') continue;
					if(window.document.getElementById(listsCats[selection][i]+'listmail1').checked == true) continue;
					unselect = false;
					break;
				}
				for(var i=0; i < listsCats[selection].length; i++){
					if(searchParent(window.document.getElementById(listsCats[selection][i]+'listmail0'), 'tr').style.display == 'none') continue;
					if(unselect){
						<?php if(ACYMAILING_J30) echo 'jQuery("input[id="+listsCats[selection][i]+"listmail0]").next().click();'; ?>
						window.document.getElementById(listsCats[selection][i]+'listmail0').checked = true;
					}else{
						<?php if(ACYMAILING_J30) echo 'jQuery("input[id="+listsCats[selection][i]+"listmail1]").next().click();'; ?>
						window.document.getElementById(listsCats[selection][i]+'listmail1').checked = true;
					}
				}
			}
		-->
		</script>

		<?php if(acymailing_level(2) && acymailing_isAllowed($this->config->get('acl_lists_filter','all'))) include_once(dirname(__FILE__).DS.'filters.php');
		}
		if(!acymailing_isAllowed($this->config->get('acl_newsletters_lists','all')) || $this->type == 'joomlanotification') echo '</div>';
		else echo $this->tabs->endPanel();

	 	if(acymailing_isAllowed($this->config->get('acl_newsletters_attachments','all'))){
		 	echo $this->tabs->startPanel(JText::_( 'ATTACHMENTS' ), 'mail_attachments');?>
			<br style="font-size:1px"/>
			<?php if(!empty($this->mail->attach)){?>
			<fieldset class="adminform" id="attachmentfieldset">
			<legend><?php echo JText::_( 'ATTACHED_FILES' ); ?></legend>
				<?php
						foreach($this->mail->attach as $idAttach => $oneAttach){
							$idDiv = 'attach_'.$idAttach;
							echo '<div id="'.$idDiv.'">'.$oneAttach->filename.' ('.(round($oneAttach->size/1000,1)).' Ko)';
							echo $this->toggleClass->delete($idDiv,$this->mail->mailid.'_'.$idAttach,'mail');
					echo '</div>';
						}
			?>
			</fieldset>
			<?php } ?>
			<div id="loadfile">
				<input type="file" style="width:auto;" name="attachments[]" />
			</div>
			<a href="javascript:void(0);" onclick='addFileLoader()'><?php echo JText::_('ADD_ATTACHMENT'); ?></a>
				<?php echo JText::sprintf('MAX_UPLOAD',$this->values->maxupload);?>
			<?php echo $this->tabs->endPanel();
 		}

		if(!acymailing_isAllowed($this->config->get('acl_newsletters_sender_informations','all'))){
			$doc = JFactory::getDocument();
			$doc->addStyleDeclaration(" .mail_sender_acl{display:none;} ");
			echo '<div id="mail_sender_acl" style="display:none" >';
		} else{
			echo $this->tabs->startPanel(JText::_( 'SENDER_INFORMATIONS' ), 'mail_sender');
		}?>
		<br style="font-size:1px"/>
		<table width="100%" class="paramlist admintable" id="senderinformationfieldset">
			<tr>
					<td class="paramlist_key">
						<label for="fromname"><?php echo JText::_( 'FROM_NAME' ); ?></label>
					</td>
					<td class="paramlist_value">
						<input placeholder="<?php echo JText::_( 'USE_DEFAULT_VALUE' ); ?>" class="inputbox" id="fromname" type="text" name="data[mail][fromname]" style="width:200px" value="<?php echo $this->escape(@$this->mail->fromname); ?>" />
					</td>
				</tr>
			<tr>
					<td class="paramlist_key">
						<label for="fromemail"><?php echo JText::_( 'FROM_ADDRESS' ); ?></label>
					</td>
					<td class="paramlist_value">
						<input onchange="validateEmail(this.value, '<?php echo addslashes(JText::_('FROM_ADDRESS')); ?>')" placeholder="<?php echo JText::_( 'USE_DEFAULT_VALUE' ); ?>" class="inputbox" id="fromemail" type="text" name="data[mail][fromemail]" style="width:200px" value="<?php echo $this->escape(@$this->mail->fromemail); ?>" />
					</td>
				</tr>
				<tr>
				<td class="paramlist_key">
					<label for="replyname"><?php echo JText::_( 'REPLYTO_NAME' ); ?></label>
					</td>
					<td class="paramlist_value">
						<input placeholder="<?php echo JText::_( 'USE_DEFAULT_VALUE' ); ?>" class="inputbox" id="replyname" type="text" name="data[mail][replyname]" style="width:200px" value="<?php echo $this->escape(@$this->mail->replyname); ?>" />
					</td>
				</tr>
				<tr>
				<td class="paramlist_key">
					<label for="replyemail"><?php echo JText::_( 'REPLYTO_ADDRESS' ); ?></label>
					</td>
					<td class="paramlist_value">
						<input onchange="validateEmail(this.value, '<?php echo addslashes(JText::_('REPLYTO_ADDRESS')); ?>')" placeholder="<?php echo JText::_( 'USE_DEFAULT_VALUE' ); ?>" class="inputbox" id="replyemail" type="text" name="data[mail][replyemail]" style="width:200px" value="<?php echo $this->escape(@$this->mail->replyemail); ?>" />
					</td>
			</tr>
		</table>
<?php
		echo acymailing_getFunctionsEmailCheck();

		if(!acymailing_isAllowed($this->config->get('acl_newsletters_sender_informations','all'))) echo '</div>';
		else echo $this->tabs->endPanel();

		if($this->type == 'joomlanotification'){
			$doc = JFactory::getDocument();
			$doc->addStyleDeclaration(" .mail_metadata_jnotif{display:none;} ");
			echo '<div class="mail_metadata_jnotif">';
		} else{
			if(acymailing_isAllowed($this->config->get('acl_newsletters_meta_data','all'))){
				echo $this->tabs->startPanel(JText::_( 'META_DATA' ), 'mail_metadata');?>
				<br style="font-size:1px"/>
				<table width="100%" class="paramlist admintable" id="metadatatable">
					<tr>
							<td class="paramlist_key">
								<label for="metakey"><?php echo JText::_( 'META_KEYWORDS' ); ?></label>
							</td>
							<td class="paramlist_value">
								<textarea id="metakey" name="data[mail][metakey]" rows="5" cols="30" ><?php echo @$this->mail->metakey; ?></textarea>
							</td>
						</tr>
					<tr>
							<td class="paramlist_key">
								<label for="metadesc"><?php echo JText::_( 'META_DESC' ); ?></label>
							</td>
							<td class="paramlist_value">
								<textarea id="metadesc" name="data[mail][metadesc]" rows="5" cols="30" ><?php echo @$this->mail->metadesc; ?></textarea>
							</td>
						</tr>
				</table>
				<?php
				echo $this->tabs->endPanel();
			}
		}
		if($this->type == 'joomlanotification') echo '</div>';
		if(acymailing_level(3) && acymailing_isAllowed($this->config->get('acl_newsletters_inbox_actions','all'))) include(dirname(__FILE__).DS.'inboxactions.php');
	echo $this->tabs->endPane(); ?>
	</div>
<?php } ?>
