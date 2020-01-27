<?php
/*****************************************
**		 	Flyff Earthquake v4.0		**
**			  BackendPanel.php			**
**		   Created by Treachery.		**
*****************************************/
require_once("inc/functions.php");

$view = $_GET['view'];

if (in_array($account2,$editor_list)&&$enable_edit)
{
	if ($_GET['sidebar'])
	{
	?>
	<div id="item_title"><b class="title">ITEM PANEL MENU</b></div>
	<div id="item_detail">
		<p class="item_name" style="height: auto;"><img src="images/bullet.jpg" /> <a href="javascript: goPanelPage('add');">เพิ่มไอเท็ม</a></p>
		<p class="item_name" style="height: auto;"><img src="images/bullet.jpg" /> <a href="javascript: goPanelPage('modify');">แก้ไขไอเท็ม</a></p>
		<p class="item_name" style="height: auto;"><img src="images/bullet.jpg" /> <a href="javascript: goPanelPage('<?php echo strtolower($cash_name); ?>');">เพิ่ม <?php echo $cash_name; ?></a></p>
	</div>
	<?php
	}
	else
	{
	?>
	<script type="text/javascript">
		function addcash()
		{
			var account = $("#account").val();
			var cash = $("#cash").val();
			
			$.post("BackendPanel.php?view=<?php echo strtolower($cash_name); ?>",{account: account, cash: cash},function(result){
				$("#list").html(result);
			});
		}
		
		function goViewPage(id)
		{
			goPanelPage('modify&page='+id);
		}
		
		function additem()
		{
			var form = $("#additem").serialize();
			
			$.post("BackendPanel.php?view=add",form,function(result){
				$("#list").html(result);
			});
		}
		
		function edititem()
		{
			var form = $("#edititem").serialize();
			var id = $("#listid").val();
			
			$.post("BackendPanel.php?id="+id,form,function(result){
				$("#list").html(result);
			});
		}
		
		function switchBundle()
		{
			var display = $(".bundle:first").css("display");
			
			if (display=="none")
				$(".bundle").show();
			else
				$(".bundle").css("display","none");
		}
	</script>
	<div id="item_cat"><b class="title">ITEM PANEL > <?php echo ($view=="add") ? "ADD ITEM" : (($view==strtolower($cash_name)) ? "ADD " . strtoupper($cash_name) : "MODIFY ITEMS"); ?></b></div>
	<div id="item_content">
		<?php
		switch($view)
		{
			case "add":
				if (count($_POST))
				{
					$id = getLastID()+1;
					$query = "INSERT [{$mssql_db['character']}].dbo.PREMIUM_SHOP_TBL ";
					$fields = "(id";
					$values = "VALUES ('".$id."'";
					$i=0;
					foreach($_POST as $field => $value)
					{
						if (($field == "itemid" || $field == "name") && trim($value) == "")
							$error = true;
						
						if ($field == "price" || $field == "price_sale" || $field == "itemcount") $value = abs($value);
						if ($field == "desc") $field = "[desc]";
						if ($field == "isbundle" || $field == "forsale")
						{
							if ($value == "on")
								$value = 1;
							else
								$value = 0;
						}
						
						$fields .= ", ".$field;
						$values .= ", '".preg_replace("/'/","\'\'",$value)."'";
					}
					$fields .= ")";
					$values .= ")";
					
					$query .= $fields . $values;
					
					if (!$error)
						$result = mssql_query($query);
					
					if ($result)
						echo "<p style='text-align: center; color: green'>{$_POST['name']} added successfully.</p>";
					else
						echo "<p style='text-align: center; color: red'>Failed to add item.</p>";
				}
				?>
				<style>
					table th {padding: 1px; width: 100px;}
					table td {border: 0;}
					input, select, textarea {padding: 2px; width: 100%;}
					select {width: 103%;}
				</style>
				<p>
					<form action="javascript: additem();" id="additem">
						<table style="width: 350px; margin: auto;">
							<tr>
								<th>รหัสไอเท็ม:</th>
								<td><input name="itemid" maxlength="8" /></td>
							</tr>
							<tr>
								<th>ชื่อไอเท็ม:</th>
								<td><input name="name" maxlength="75" /></td>
							</tr>
							<tr>
								<th>รายละเอียด:</th>
								<td><textarea name="desc"></textarea></td>
							</tr>
							<tr>
								<th>จำนวน:</th>
								<td><input name="itemcount" maxlength="4" /></td>
							</tr>
							<tr>
								<th>ราคาปกติ:</th>
								<td><input name="price" maxlength="8" /></td>
							</tr>
							<tr>
								<th>ราคาขาย:</th>
								<td><input name="price_sale" maxlength="8" value="0" /></td>
							</tr>
							<tr>
								<th>ประเภท:</th>
								<td><select name="category">
								  <option value="A">ไอเท็มสำหรับผู้เล่นใหม่</option>
								  <option value="B">กล่องสุ่ม</option>
								  <option value="C">กล่องสำหรับผู้เล่นใหม่</option>
								  <option value="D">ตีบวก</option>
								  <option value="E">ชุดเกราะ</option>
								  <option value="F">ใบสกอร์ต่าง ๆ</option>
								  <option value="G">อาวุธ</option>
								  <option value="H">น้ำยาต่าง ๆ</option>
								  <option value="I">อาหาร</option>
								  <option value="J">ตัวละคร</option>
								  <option value="K">ไอเท็มจัดเก็บ</option>
								  <option value="L">กิลด์ &amp; ปาร์ตี้</option>
								  <option value="M">บัตร EXP ต่าง ๆ</option>
								  <option value="N">สัตว์เลี้ยง</option>
								  <option value="O">สัตว์บัพ</option>
								  <option value="P">อุปกรณ์สัตว์เลี้ยง</option>
								  <option value="Q">ลูกแก้วสัตว์เลี้ยง</option>
								  <option value="R">อุปกรณ์การบิน</option>
								  <option value="S">ชุดแฟชั่น</option>
								  <option value="T">แฟชั่นแบบชิ้น</option>
								  <option value="U">คลุมและแว่นตา</option>
								  <option value="V">ทรงผม</option>
								  <option value="W">เฟอนิเจอร์</option>
								  <option value="X">อื่น ๆ</option>
								  <option value="Y">แฟชั่นอื่น ๆ</option>
							  </select></td>
							</tr>
							<tr>
								<th>นามสกุลไฟล์:</th>
								<td><input name="image" maxlength="4" value="." /></td>
							</tr>
							<tr>
								<th>เป็นแพ็คเกจมั้ย ?:</th>
								<td style="padding: 2px; border: 1px solid #ccc;"><input type="checkbox" name="isbundle" onclick="switchBundle();" />
							  (ถ้าใช่ให้ทำการกรอกช่องข้างล่าง)</td>
							</tr>
							<tr class="bundle">
								<th>รหัสไอเท็ม 1:</th>
								<td><input name="item1_id" maxlength="8" /></td>
							</tr>
							<tr class="bundle">
								<th>ชื่อไอเท็ม 1:</th>
								<td><input name="item1_name" maxlength="75" /></td>
							</tr>
							<tr class="bundle">
								<th>จำนวนไอเท็ม 1:</th>
								<td><input name="item1_count" maxlength="4" /></td>
							</tr>
							<tr class="bundle">
								<th>รหัสไอเท็ม 2:</th>
								<td><input name="item2_id" maxlength="8" /></td>
							</tr>
							<tr class="bundle">
								<th>ชื่อไอเท็ม 2:</th>
								<td><input name="item2_name" maxlength="75" /></td>
							</tr>
							<tr class="bundle">
								<th>จำนวนไอเท็ม 2:</th>
								<td><input name="item2_count" maxlength="4" /></td>
							</tr>
							<tr class="bundle">
								<th>รหัสไอเท็ม 3:</th>
								<td><input name="item3_id" maxlength="8" /></td>
							</tr>
							<tr class="bundle">
								<th>ชื่อไอเท็ม 3:</th>
								<td><input name="item3_name" maxlength="75" /></td>
							</tr>
							<tr class="bundle">
								<th>จำนวนไอเท็ม 3:</th>
								<td><input name="item3_count" maxlength="4" /></td>
							</tr>
							<tr class="bundle">
								<th>รหัสไอเท็ม 4:</th>
								<td><input name="item4_id" maxlength="8" /></td>
							</tr>
							<tr class="bundle">
								<th>ชื่อไอเท็ม 4:</th>
								<td><input name="item4_name" maxlength="75" /></td>
							</tr>
							<tr class="bundle">
								<th>จำนวนไอเท็ม 4:</th>
								<td><input name="item4_count" maxlength="4" /></td>
							</tr>
							<tr>
								<th>ขายหรือไม่:</th>
								<td style="padding: 2px; border: 1px solid #ccc;"><input type="checkbox" checked name="forsale" />
							  (หากขายให้ติ๊กถูก)</td>
							</tr>
							<tr>
								<td colspan="2" style="border: 0;"><input type="submit" value="เพิ่มไอเท็ม" style="width: auto;" /></td>
							</tr>
						</table>
					</form>
				</p>
				<?php
			break;
			
			case strtolower($cash_name):
				?>
				<p>Please enter a valid account name and a positive integer to add <?php echo $cash_name; ?> to a user's account.<br/>
				<small><i>Negative values will be made positive, and if non-interger values in the <?php echo $cash_name; ?> field will be canceled.</i></small></p>
				<p>
	  <form action="javascript: addcash();">
		<table style="width: 190px; margin: auto;">
							<tr>
								<th style="padding: 1px;">Account:</th>
								<td style="border: 0;"><input id="account" maxlength="16" style="padding: 2px;" /></td>
							</tr>
							<tr>
								<th style="padding: 1px;"><?php echo $cash_name; ?>:</th>
								<td style="border: 0;"><input id="cash" maxlength="10" style="padding: 2px;" /></td>
							</tr>
							<tr>
								<td colspan="2" style="border: 0;"><input type="submit" value="Add <?php echo $cash_name ?>" style="padding: 2px;" /></td>
							</tr>
		  </table>
		</form>
					<?php
					if (count($_POST))
					{
						if (trim($_POST['account']) == "" || trim($_POST['cash']) == "" || !ctype_digit($_POST['cash']))
						{
							$error = "<div style='color: red; text-align: center;'>Failed to add {$cash_name}.</div>";
						}
						else
						{
							$account = clean($_POST['account']);
							$cash = clean(abs($_POST['cash']));
							
							$result = mssql_query("UPDATE [{$mssql_db['account']}].dbo.[ACCOUNT_TBL] SET cash = cash + {$cash} WHERE account = {$account}");
							
							if ($result)
							{
								$cash_result = mssql_query("SELECT TOP 1 {$cash_row} FROM [{$mssql_db['account']}].dbo.[ACCOUNT_TBL] WHERE account = {$account}");
								$cash_array = mssql_fetch_array($cash_result);
								$total_cash = $cash_array[$cash_row];
								$error = "<div style='color: green; text-align: center;'><b>{$cash} {$cash_name}</b> has been added successfully to <b>{$_POST['account']}</b>'s account.<br/><b>Total Cash:</b> {$total_cash}</div>";
							}
							else
								$error = "<div style='color: red; text-align: center;'>Failed to add <b>{$cash} {$cash_name}</b> to <b>{$_POST['account']}</b>'s account.</div>";
						}
						echo $error;
					}
					?>
				</p>
				<?php
			break;
			
			default:
				if ($_GET['id'])
				{
					$id = clean(abs($_GET['id']));
					$result = mssql_query("SELECT * FROM [{$mssql_db['character']}].dbo.PREMIUM_SHOP_TBL WHERE id = {$id}");
					$row = mssql_fetch_array($result);
					if (!mssql_num_rows($result))
					{
						echo "<p style='text-align: center; color: #FF0000; padding-top: 30%; font-weight: bold'>Invalid item ID.</p>";
					}
					else
					if ($_GET['view'] == "delete")
					{
						if (!isset($_GET['confirm']))
							echo "<p style='text-align: center; color: #FF0000; padding-top: 25%; font-weight: bold'>Are you sure you wish to delete {$row['name']}?<br/><input type='button' value='Delete' onclick=\"goPanelPage('delete&id={$row['id']}&confirm');\" style='padding: 2px;' /> <input type='button' value='Cancel' onclick=\"goViewPage({$_SESSION['panelpage']});\" style='padding: 2px;' /></p>";
						else
						{
							$result = mssql_query("DELETE [{$mssql_db['character']}].dbo.PREMIUM_SHOP_TBL WHERE id = {$id}");
							$lastpage = $_SESSION['panelpage'] ? $_SESSION['panelpage'] : 1;
							
							if ($result)
								echo "<p style='text-align: center; color: #FF0000; padding-top: 30%; font-weight: bold'>{$row['name']} deleted.<br/><a href=\"javascript: goViewPage({$lastpage});\">Back</a></p>";
							else
								echo "<p style='text-align: center; color: #FF0000; padding-top: 30%; font-weight: bold'>Failed to delete.<br/><a href=\"javascript: goViewPage({$lastpage});\">Back</a></p>";
						}
					}
					else
					{
						if (count($_POST))
						{
							unset($result);
							if (!$_POST['isbundle']) $_POST['isbundle'] = 0;
							if (!$_POST['forsale']) $_POST['forsale'] = 0;
							
							$update = array();
							
							foreach($_POST as $field => $value)
							{
								if (($field == "itemid" || $field == "name") && trim($value) == "")
									$error = true;
								
								if ($field == "price" || $field == "price_sale" || $field == "itemcount") $value = abs($value);
								
								if ($field == "isbundle" || $field == "forsale")
								{
									if ($value)
										$value = 1;
									else
										$value = 0;
								}
								
								if ($value != $row[$field])
								{
									if (stristr($value,"'") || $field == "desc")
										$value = "'".preg_replace("/'/","''",$value)."'";
									else
										$value = clean($value);
									
									$update[$field] = $value;
								}
							}
							
							$query = "UPDATE [{$mssql_db['character']}].dbo.PREMIUM_SHOP_TBL SET ";
							
							if (empty($update)) $error = true;
							
							if (!$error)
							{
							
								$i=0;
								foreach($update as $row => $value)
								{
									if ($i!=0) $query.=",";
									
									if ($row == "desc") $row = "[desc]";
									
									$query .= " {$row} = {$value}";
									
									$i++;
								}
								
								$query .= " WHERE id = {$id}";
								
								$result = mssql_query($query);
							}
								
							$item_name = $_POST['name'] ? $_POST['name'] : $row['name'];
							
							if ($result)
							{
								$row = $_POST;
								echo "<p style='text-align: center; color: green'>{$item_name} updated successfully.</p>";
							}
							else
								echo "<p style='text-align: center; color: red'>Failed to update {$item_name}.</p>";
						}
					
					foreach ($row as $field => $value)
					{
						$row[$field] = br(preg_replace("/'/","\'",$value));
					}
					?>
					<script type="text/javascript">
					$(document).ready(function() {
					<?php
						foreach($row as $field => $value)
						{
							if ($field=="desc")
							{
							?>$("textarea[name='<?php echo $field; ?>']").val('<?php echo $value; ?>');
							<?php
							}
							else
							if ($field=="category")
							{
							?>$("select[name='<?php echo $field; ?>']").val('<?php echo $value; ?>');
							<?php
							}
							else
							if ($field=="isbundle" || $field=="forsale")
							{
								$onoff = $row[$field] ? "true" : "false"
								
							?>$("input[name='<?php echo $field; ?>']").attr('checked', <?php echo $onoff; ?>);
							<?php
								if ($field=="isbundle" && $onoff=="true")
								{
									echo "$(\".bundle\").show();";
								}
								
								unset($onoff);
							}
							else
							{
							?>$("input[name='<?php echo $field; ?>']").val('<?php echo $value; ?>');
							<?php
							}
						}
					?>
					});
					</script>
					<style>
						table th {padding: 1px; width: 100px;}
						table td {border: 0;}
						input, select, textarea {padding: 2px; width: 100%;}
						select {width: 103%;}
					</style>
					<p>
						<input id="listid" value="<?php echo $id; ?>" type="hidden" />
						<form action="javascript: edititem();" id="edititem">
						  <table style="width: 350px; margin: auto;">
						    <tr>
						      <th>รหัสไอเท็ม:</th>
						      <td><input name="itemid2" maxlength="8" /></td>
					        </tr>
						    <tr>
						      <th>ชื่อไอเท็ม:</th>
						      <td><input name="name2" maxlength="75" /></td>
					        </tr>
						    <tr>
						      <th>รายละเอียด:</th>
						      <td><textarea name="desc2"></textarea></td>
					        </tr>
						    <tr>
						      <th>จำนวน:</th>
						      <td><input name="itemcount2" maxlength="4" /></td>
					        </tr>
						    <tr>
						      <th>ราคาปกติ:</th>
						      <td><input name="price2" maxlength="8" /></td>
					        </tr>
						    <tr>
						      <th>ราคาขาย:</th>
						      <td><input name="price_sale2" maxlength="8" value="0" /></td>
					        </tr>
						    <tr>
						      <th>ประเภท:</th>
						      <td><select name="category2">
						        <option value="A">ไอเท็มสำหรับผู้เล่นใหม่</option>
						        <option value="B">กล่องสุ่ม</option>
						        <option value="C">กล่องสำหรับผู้เล่นใหม่</option>
						        <option value="D">ตีบวก</option>
						        <option value="E">ชุดเกราะ</option>
						        <option value="F">ใบสกอร์ต่าง ๆ</option>
						        <option value="G">อาวุธ</option>
						        <option value="H">น้ำยาต่าง ๆ</option>
						        <option value="I">อาหาร</option>
						        <option value="J">ตัวละคร</option>
						        <option value="K">ไอเท็มจัดเก็บ</option>
						        <option value="L">กิลด์ &amp; ปาร์ตี้</option>
						        <option value="M">บัตร EXP ต่าง ๆ</option>
						        <option value="N">สัตว์เลี้ยง</option>
						        <option value="O">สัตว์บัพ</option>
						        <option value="P">อุปกรณ์สัตว์เลี้ยง</option>
						        <option value="Q">ลูกแก้วสัตว์เลี้ยง</option>
						        <option value="R">อุปกรณ์การบิน</option>
						        <option value="S">ชุดแฟชั่น</option>
						        <option value="T">แฟชั่นแบบชิ้น</option>
						        <option value="U">คลุมและแว่นตา</option>
						        <option value="V">ทรงผม</option>
						        <option value="W">เฟอนิเจอร์</option>
						        <option value="X">อื่น ๆ</option>
						        <option value="Y">แฟชั่นอื่น ๆ</option>
						        </select></td>
					        </tr>
						    <tr>
						      <th>นามสกุลไฟล์:</th>
						      <td><input name="image2" maxlength="4" value="." /></td>
					        </tr>
						    <tr>
						      <th>เป็นแพ็คเกจมั้ย ?:</th>
						      <td style="padding: 2px; border: 1px solid #ccc;"><input type="checkbox" name="isbundle2" onclick="switchBundle();" />
						        (ถ้าใช่ให้ทำการกรอกช่องข้างล่าง)</td>
					        </tr>
						    <tr class="bundle">
						      <th>รหัสไอเท็ม 1:</th>
						      <td><input name="item1_id2" maxlength="8" /></td>
					        </tr>
						    <tr class="bundle">
						      <th>ชื่อไอเท็ม 1:</th>
						      <td><input name="item1_name2" maxlength="75" /></td>
					        </tr>
						    <tr class="bundle">
						      <th>จำนวนไอเท็ม 1:</th>
						      <td><input name="item1_count2" maxlength="4" /></td>
					        </tr>
						    <tr class="bundle">
						      <th>รหัสไอเท็ม 2:</th>
						      <td><input name="item2_id2" maxlength="8" /></td>
					        </tr>
						    <tr class="bundle">
						      <th>ชื่อไอเท็ม 2:</th>
						      <td><input name="item2_name2" maxlength="75" /></td>
					        </tr>
						    <tr class="bundle">
						      <th>จำนวนไอเท็ม 2:</th>
						      <td><input name="item2_count2" maxlength="4" /></td>
					        </tr>
						    <tr class="bundle">
						      <th>รหัสไอเท็ม 3:</th>
						      <td><input name="item3_id2" maxlength="8" /></td>
					        </tr>
						    <tr class="bundle">
						      <th>ชื่อไอเท็ม 3:</th>
						      <td><input name="item3_name2" maxlength="75" /></td>
					        </tr>
						    <tr class="bundle">
						      <th>จำนวนไอเท็ม 3:</th>
						      <td><input name="item3_count2" maxlength="4" /></td>
					        </tr>
						    <tr class="bundle">
						      <th>รหัสไอเท็ม 4:</th>
						      <td><input name="item4_id2" maxlength="8" /></td>
					        </tr>
						    <tr class="bundle">
						      <th>ชื่อไอเท็ม 4:</th>
						      <td><input name="item4_name2" maxlength="75" /></td>
					        </tr>
						    <tr class="bundle">
						      <th>จำนวนไอเท็ม 4:</th>
						      <td><input name="item4_count2" maxlength="4" /></td>
					        </tr>
						    <tr>
						      <th>ขายหรือไม่:</th>
						      <td style="padding: 2px; border: 1px solid #ccc;"><input type="checkbox" checked="checked" name="forsale2" />
						        (หากขายให้ติ๊กถูก)</td>
					        </tr>
						    <tr>
						      <td colspan="2" style="border: 0;"><input type="submit" value="แก้ไขไอเท็ม" style="width: auto;" /></td>
					        </tr>
					      </table>
						</form>
					</p>
					<p style="text-align: right;"><a href="javascript: goViewPage(<?php echo $_SESSION['panelpage'] ? $_SESSION['panelpage'] : 1; ?>);">Back</a></p>
					<?php
					}
				}
				else
				{
					$total_query=mssql_query("SELECT id FROM [{$mssql_db['character']}].dbo.PREMIUM_SHOP_TBL");
					$total_pgs=mssql_num_rows($total_query)/$max_panel_list;
					$total_pages=roundUp($total_pgs);
					if ($total_pages < 1) $total_pages = 1;
					if ($page > $total_pages) $page = $total_pages;
					
					$pageMax = $page * $max_panel_list;
					$listArray = tableArray("SELECT TOP {$pageMax} * FROM [{$mssql_db['character']}].dbo.PREMIUM_SHOP_TBL");
					
					$_SESSION['panelpage'] = $page;
					?>
					<style>
						table.panel-table tr td, table.panel-table tr th, table.panel-table tr th img {height: 35px; padding: 0; margin: 0; overflow: none;}
						table.panel-table td, table.panel-table tr.panel-table-header th {padding: 2px; height: auto;}
						th img {margin-top: 1px;}
						
						#page_nav {width: 500px; height: 15px; padding: 0; text-align: center; margin: 2px auto;}
						#page_nav a {color: #56585d; font-size: 13px; margin: auto 3px; font-weight: bold;}
						#page_nav span {color:#5F87E1; font-size: 13px;}
					</style>

					<table style="width: 100%;" class="panel-table">
						<tr class="panel-table-header">
							<th>รูปภาพ</th>
							<th>ชื่อไอเท็ม</th>
							<th style="width: 275px;">รายละเอียด</th>
							<th>ราคา</th>
							<th>ตัวเลือก</th>
						</tr>
						<?php
						for($i=$pageMax-$max_panel_list;$i<count($listArray);$i++)
						{
						?>
						<tr<?php echo ($i+1==count($listArray)) ? " class='last'" : "" ?>>
							<th><img src="images/items/<?php echo $listArray[$i]['itemid']; echo $listArray[$i]['bundle'] ? "_bundle" : ""; echo $listArray[$i]['image']; ?>" /></th>
							<td><?php echo $listArray[$i]['name']; ?></td>
							<td><?php echo (strlen($listArray[$i]['desc']) > 40) ? substr($listArray[$i]['desc'], 0, 40)."..." : $listArray[$i]['desc']; ?></td>
							<td><?php echo $listArray[$i]['price_sale'] ? $listArray[$i]['price_sale'] : $listArray[$i]['price']; echo " ".$cash_name_min; ?></td>
							<td><a href="javascript: goPanelPage('modify&id=<?php echo $listArray[$i]['id']; ?>');"><img src="images/management_edit.png" /></a> <a href="javascript: goPanelPage('delete&id=<?php echo $listArray[$i]['id']; ?>');"><img src="images/management_delete.png" /></a></td>
						</tr>
						<?php
						}
						?>
					</table>
					<?php
					echo '</div>';
					echo '<p id="page_nav">';
					echo page_list($total_pages, "goViewPage(");
					echo '</p><div>';
				}
		}
		?>
	</div>
	<?php
	}
}
else
{
	include("GetItemList.php");
}
?>
