<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<title>Export Code Search</title>
		 <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- Bootstrap -->
		<link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" media="screen" />
		<link href="<?php echo base_url(); ?>assets/css/export_code_search.css" rel="stylesheet" media="screen" />
		<link href="<?php echo base_url(); ?>assets/css/bootstrap-responsive.css" rel="stylesheet">
	</head>
	<body>
		<div class="container">
			<div class="row">
				<!-- Countries table left hand side -->
				<div class="span4">
					<table border="0" cellspacing="5" cellpadding="5">
						<tr><th>Flag</th><th>Code</th><th>Name</th><th>Currency</th></tr>
						<?php foreach($countries as $row): ?>
						<tr>
							<td>
								<img src="<?php echo base_url().'assets/img/flags_iso/32/'.strtolower($row->code).'.png'; ?>" />
							</td>
							<td><?php echo $row->code; ?></td>
							<td><?php echo $row->name; ?></td>
							<td></td>
						</tr>
						<?php endforeach; ?>
					</table>
				</div>

				<!-- Currency table right hand side -->
				<div class="span3 offset3">
					<table>
						<tr><th>Code</th><th>Country</th><th>Description</th></tr>
						<?php foreach($currencies as $row): ?>
						<tr data-id="<?php echo $row->id; ?>">
							<td><?php echo $row->code; ?></td>
							<td><?php echo $row->country_name; ?></td>
							<td><?php echo $row->description; ?></td>
						</tr>
						<?php endforeach; ?>
					</table>
				</div>
				
			</div>
		</div>
		<script src="http://code.jquery.com/jquery.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
	</body>
</html>	