<?php
	session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title>refreshing list</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<style type="text/css">
		#title-container {
			text-align: center;
		}
		.container {
			margin: 5px auto;
		}
		#main-page {
			width: 80%;
		}
		#alert-container {
			width: 80%;
			text-align: center;
			min-height: 60px;
		}
		#top-container {
			overflow-y: auto;
			width: 80%;
			height: 400px;
		}
		#bottom-container {
			width: 80%;
			border: 1px solid grey;
			border-radius: 8px;
			padding: 5px;
			overflow: auto;
		}
		.returned-row-element {
			display: inline-block;
			margin: 5px;
			padding: 5px;
		}
		.returned-project {
			margin-top: 3px;
			  -webkit-box-shadow: 3px 3px 5px 6px #ccc;  
			  -moz-box-shadow:    3px 3px 5px 6px #ccc;  
			  box-shadow:         3px 3px 5px 2px #ccc;  
		}
		.itemName {
			font-size: 1.4rem;
			width: 40%;
		}
		.detail-column {
			text-align: center;
			width: 15%;
			display: inline-block;
			margin: 5px;
			padding: 5px;
		}
		.detail-label {
			font-size: 0.8rem;
			display: block;
			padding: 0;
			margin: 0;
		}
		.itemQty {
			font-size: 1.0rem;
			display: block;
		}
		.remove-item {
			float: right;
			font-size: 1.9rem;
			cursor: pointer;
		}
		#clear-message {
			width: 50%;
			margin: 0 auto !important;
			text-align: center;
		}
		#button-div {
			float: right;
			display: inline-block;
			text-align: center;
			margin: 5px;
		}
		#input-div {
			display: inline-block;
			text-align: center;
			margin: 5px;
		}
	</style>
</head>
<body>

	<div class="container" id="main-page">
		<div class="container" id="title-container">
			<h3>My Sunday Project</h3>
			<p>a list of stuff that's stored in session storage</p>
		</div>
		<div class="container" id="alert-container"></div>
		<div class="container" id="top-container"></div>
		<div class="container" id="bottom-container">
			<form id="project-form">
				<div id="input-div">
					Item Name: <input type="text"  id="itemName" name="itemName">
					Qty: <input type="text" id="itemQty" name="itemQty">
					Price: <input type="text" id="itemPrice" name="itemPrice">
				</div>
				<div id="button-div">
					<button type="button" class="btn btn-primary" id="addItemButton" onclick="addItem()">Add</button>
					<button type="button" class="btn btn-warning" id="addItemButton" onclick="clearItems()">Clear</button>
				</div>
			</form>
		</div>
	</div>
	<script 
		src="https://code.jquery.com/jquery-3.3.1.js"
		integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
		crossorigin="anonymous">
	</script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script type="text/javascript">

		// initial load, get any already stored list items
		// don't need to document-ready-function it
		var itemNumber = 0;

		$.ajax ({
			url: 'ajaxSunday.php',
			type: 'POST',
			datatype: 'text',
			data: {
				key: 'initialValue'},
			success: function(data) {
				itemNumber = data-1;
			},
			error: function(data) {
				console.log('error: '+data);
			}
		});
		
		$.ajax ({
			url: 'ajaxSunday.php',
			type: 'POST',
			datatype: 'text',
			data: {
				key: "initialLoad"
			},
			success: function(data) {
				$('#top-container').html(JSON.parse(data));
			}
		});

		function addItem() {
			// get values from form and send to ajax for processing
			var itemName = $('#itemName').val();
			var itemQty = $('#itemQty').val();
			var itemPrice = $('#itemPrice').val();
			$('#alert-container').html('');
			$.ajax ({
				url: 'ajaxSunday.php',
				type: 'POST',
				datatype: 'json',
				data: {
					key: 'addItem',
					itemName: itemName,
					itemQty: itemQty,
					itemPrice: itemPrice,
					itemNumber: itemNumber},
				success: function(data) {
					$('#top-container').html("");
					$('#top-container').html(JSON.parse(data));
					// console.log(JSON.parse(data));
					$('#itemName').val("");
					$('#itemQty').val("");
					$('#itemPrice').val("");
					showAlert('item added', 'good');
					itemNumber += 1;
				},
				error: function(data) {
					console.log('error: '+data);
				}
			});
		}

		function clearItems() {
			// clear all items
			itemNumber = 0;
			$.ajax ({
				url: 'ajaxSunday.php',
				type: 'POST',
				datatype: 'text',
				data: {
					key: "clearItems"
				},
				success: function(data) {
					$('#top-container').html("");
					showAlert(data, 'good');
				}
			})
		}

		function deleteItem(itemNumber) {
			$.ajax ({
				url: 'ajaxSunday.php',
				type: 'POST',
				datatype: 'text',
				data: {
					key: "deleteItem",
					itemNumber: itemNumber
				},
				success: function(data) {
					$('#top-container').html("");
					$('#top-container').html(JSON.parse(data));
					showAlert('item removed!', 'good');
				},
				error: function(data) {
					console.log('error: '+data);
				}
			})
		}

		function showAlert(text, status) {
			if (status=="bad") {
					$('#alert-container').html('<div id="clear-message" class="alert alert-warning" role="alert">'+text+'</div>');
			} else if (status=="good") {
					$('#alert-container').html('<div id="clear-message" class="alert alert-success" role="alert">'+text+'</div>');
			}
			setTimeout(function() {
  				$('#clear-message').fadeOut('slow');
			}, 2000);
		}
		
	</script>
</body>
</html>