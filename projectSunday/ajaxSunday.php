<?
session_start();

if ($_POST['itemNumber'] == 0) {
	// new array!
	$_SESSION['tasklist'] = array();
	$data = [];
} else {
	$data = $_SESSION['taskList'];
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {

		if ($_POST['key'] == "addItem") {
			try {	
				$totalCost = $_POST['itemPrice'] * $_POST['itemQty'];
				$newTask = "<div class='returned-project' id='{$_POST['itemNumber']}'>
								<div class='returned-row-element itemName'>{$_POST['itemName']}</div>
								<div class='detail-column'>
									<div class='detail-label'>QTY</div>
									<div class='itemQty'>{$_POST['itemQty']}</div>
								</div>
								<div class='detail-column'>
									<div class='detail-label'>EACH</div>
									<div class='itemPrice'>$ {$_POST['itemPrice']}</div>
								</div>
								<div class='detail-column'>
									<div class='detail-label'>TOTAL</div>
									<div class='itemPrice'>$ {$totalCost}</div>
								</div>
								<div class='returned-row-element remove-item' onclick='deleteItem({$_POST['itemNumber']})'>&#9760;</div>
							</div>";
				array_push($data, $newTask);
				$_SESSION['taskList'] = $data;
				echo json_encode($data);
			} catch (PDOException $e) {
                exit($e->getMessage());
              }
		}

		if ($_POST['key'] == "clearItems") {
			$_SESSION['taskList'] = '';
			echo 'list items cleared!';
		}

		if ($_POST['key'] == "initialLoad") {
			if(!empty($_SESSION['taskList'])) {
			echo json_encode($_SESSION['taskList']);
			}
		}

		if ($_POST['key'] == "deleteItem") {
			try {
				// instead of deleting the item, which is EASY, javascript requires arrays to be in numeric order, no skips, so
				// need to just delete the data and rerender the results with the empty data
				$itemNumber = $_POST['itemNumber'];
				// set value of array index to ''
				$_SESSION['taskList'][$_POST['itemNumber']] = "";
				echo json_encode($_SESSION['taskList']);
			} catch (PDOException $e) {
				exit($e->getMessage());
			}
		}

		if ($_POST['key'] == "initialValue") {
			if (isset($_SESSION['taskList'])) {
				echo count($_SESSION['taskList']);
			}
		}
	}
?>